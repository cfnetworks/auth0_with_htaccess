<?php

class config
{
    /**
     * Retrieves the configuration settings.
     *
     * @return mixed The configuration settings.
     */
    public static function get()
    {
        return array(

            // auth0 config
            'domain' 		=> "xxx.eu.auth0.com",
            'clientId' 		=> "<client-id>",
            'clientSecret'	=> "<client-secret>",
            'cookieSecret' 	=> "<cookie-secret>",
            'baseUrl' 		=> 'https://www.mydomain.com/auth.php',
            'callbackUrl' 	=> 'https://www.mydomain.com/auth.php',
            'loginUrl' 		=> 'https://www.mydomain.com/auth.php?login',
            'logoutUrl' 	=> 'https://www.mydomain.com/auth.php?logout',
        
            // auth files authentication used by htaccess
            // note: this files/directories must be writeable by the webserver
            'authFile'          =>   '/var/www/myproject/cache/htauth',
            'authTimestampFile' =>   '/var/www/myproject/cache/htauth_timestamp',

            // application base url
            'appBaseUrl' => '/',
        
            // salt for auth files
            // to gain more security, you can change this salt
            'salt' => '<my-salt>',
        );
    }
}