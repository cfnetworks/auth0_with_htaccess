<?php

// be strict
declare(strict_types=1);

// require auth0 vendor files
require_once('../../vendor/autoload.php');

// require config
require_once('config.php');

/**
 * Class auth0Handler - Handles authentication using Auth0 service.
 */
class auth0Handler
{
	protected $auth;
	protected $config;
    protected $salt;
	protected $authFile;
	protected $authTimestampFile;

	/**
	 * Constructor for the Auth0 class.
	 *
	 * @return void
	 */
	public function __construct()
	{
		// init config
		$this->config = config::get();

		// init auth0
		$this->initAuth0();
	}

	/**
	 * Returns the configuration array for the Auth0 class.
	 *
	 * @return array The configuration array.
	 */
	public function getConfig(): array
	{
		// return config
		return $this->config;
	}

	/**
	 * Logs the user in.
	 *
	 * @return void
	 */
	public function login(): void
	{
        if($this->isLoggedin()) {
			// redirect to baseUrl
			header("Location: " . $this->config['baseUrl']);
			exit;
        }

		// clear cookies and auth files
		$this->clearCookiesAndAuthFiles();

		// clear auth0 session
		$this->auth->clear();

		// redirect to auth0 login page
		header("Location: " . $this->auth->login($this->config['callbackUrl']));
		exit;
	}

	/**
	 * Logs out the current user by clearing their session data and local auth files.
	 *
	 * @return void
	 */
	public function logout(): void
	{
		// clear cookies and auth files
		$this->clearCookiesAndAuthFiles();

		// redirect to auth0 logout page which then redirects to baseUrl
		header("Location: ".$this->auth->logout($this->config['baseUrl']));
		exit;
	}

	/**
	 * Handles the callback from Auth0 after authentication.
	 *
	 * @return void
	 */
	public function handleCallback(): void
	{
		// handle callback by auth0
		$this->auth->exchange($this->config['callbackUrl']);

		// we should be authenticated now, get credentials
		$session = $this->auth->getCredentials();

		// handle credentials
        if($session && $session->user['email_verified'] === true) {

            // TODO: change to writing separate files in distinct directory
            // so multiple accounts could log in parallel

			// create unique hash used in cookie and local file for comparison in .htaccess
			$authValue = md5($this->config['salt'].time().rand()); // hash to identify via session cookie

            // create expiration date in readable format in .htaccess
            $expirationStr = date('YmdHis', $session->user['exp']); // Format: // 20231201181846

			// write hash to whitelist file
			if(!file_put_contents($this->config['authFile'], $authValue)) {
				throw new Exception('error adding ip to whitelist');
			}

			// write timestamp to whitelist file
			if(!file_put_contents($this->config['authTimestampFile'], $expirationStr)) {
				throw new Exception('error adding timestamp to whitelist');
			}

			// set cookie with content of htauth file
			if(!setcookie('htauth', $authValue)) {
				throw new Exception('error setting htauth cookie');
			}

			// redirect to baseUrl
			header("Location: " . $this->config['baseUrl']);
			exit;
		}
		else {
            throw new Exception('login was not possible. is email address verified? or service unavailable?');		
		}
	}

	/**
	 * Returns an array containing details of the authenticated user.
	 *
	 * @return array An array containing details of the authenticated user.
	 */
	public function getUserDetails(): array
	{
		// get user details
		if(!$userDetails = $this->auth->getCredentials()->user) {
			throw new Exception('could not get user details. are you logged in?');
		}

		// return user details
		return $userDetails;
	}

	/**
	 * Checks if the user is currently logged in.
	 *
	 * @return bool Returns true if the user is logged in, false otherwise.
	 */
	public function isLoggedin(): bool
	{
        // manual check of cookie and local files
        // because .htaccess skips this url

		// get cookie and htauth file content
		$authCookie = $_COOKIE['htauth'] ?? '';
		$htauth = file_get_contents($this->config['authFile']);
		$authTimestamp = file_get_contents($this->config['authTimestampFile']);

		// validate cookie and htauth file content
		if($authCookie != $htauth || $authTimestamp < date('YmdHis')) {
			$this->clearCookiesAndAuthFiles();
			return false;
		}

        // get current credentials from auth0
		$session = $this->auth->getCredentials();

        // logged in
        if($session && $session->user['email_verified'] === true) {            
            return true;			
		}
        // logged in but not verified => Exception
		elseif($session && $session->user['email_verified'] !== true) {
			throw new Exception('email address is not verified');
		}
        // not logged in
		else {
			return false;
		}
	}

	/**
	 * Clears the cookies and authentication files used by the Auth0 class.
	 *
	 * @return void
	 */
	protected function clearCookiesAndAuthFiles(): void
	{
		// reset cookie
		if(!setcookie('htauth', '')) {
			throw new Exception('could not set cookie htauth');
		}

		// remove ip address from whitelist
		if(!file_put_contents($this->config['authFile'], '-')) {
			throw new Exception('error on writing auth file');
		}

		// remove timestamp address from whitelist
		if(!file_put_contents($this->config['authTimestampFile'], '-')) {
			throw new Exception('error on writing auth timestamp file');
		}
	}
	
	/**
	 * Initializes the Auth0 authentication system.
	 *
	 * @return void
	 */
	protected function initAuth0(): void
	{
		// init auth0 model
		$this->auth = new \Auth0\SDK\Auth0([
			'domain' => $this->config['domain'],
			'clientId' => $this->config['clientId'],
			'clientSecret' => $this->config['clientSecret'],
			'cookieSecret' => $this->config['cookieSecret'],
		]);
	}
}