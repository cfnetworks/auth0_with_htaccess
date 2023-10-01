# Use auth0 with .htaccess
Implement auth0.com authentication with .htaccess

## What does it do?
Essentially, this project leverages the capabilities of auth0.com and integrates them with .htaccess. Consequently, your login status is verified by the .htaccess file, eliminating the need for authentication checks in your codebase each time. This enables you to secure your application with Auth0 without the need to modify its code.

This project is intended as a prototype and should not be used on production systems without thorough code adaptation and comprehensive security assessments.

## How does it work?
* When you successfully log in with auth0, an encrypted hash will be generated and stored in a cookie. Simultaneously, it is written into a local file on your webserver.
* Additionally, the expiration date is recorded in a separate local file on your webserver.
* .htaccess loads the contents of the encrypted hash file and the expiration date into local variables.
* Subsequently, .htaccess compares the sent cookie value with the variable containing the encrypted hash. If they are not equal, a redirect to the Login/Unauthorized page occurs.
* Furthermore, .htaccess compares the expiration date from the variable with the current date. If the expiration date is earlier than the current date, a redirect to the Login/Unauthorized page is triggered.

## Make the example work

* copy files from repository to your application
	- composer.json one level above your document root
	- cache folder: must be writeable
	- config folder: contains the configuration with sensible data
	- public folder: should be copied to you document root of your application
	
* Use `composer install` to download auth0 dependencies
* auth0.com: Create Regular Web Application 
* Add auth0-Domain, Client ID, Client Secret to /config/config.php
* Add baseUrl, callbackUrl, loginUrl, logoutUrl to auth0 and /config/config.php
* Add local path to htauth and htauth_timestamp files  (cache folder) to /config/config.php
* Add appBaseUrl to /config/config.php (the relative url to your application)
* Add salt to config/config.php - should be a random secret key which will be used as salt for hashing
* Edit .htaccess
	- change the path of your htauth and htauth_timestamp files
	- change RewriteCond %{REQUEST_URI} to the relative location of auth.php (2x)
	- change the FQDN to auth.php (2x)

## Author

Author website: https://www.cfnetworks.at

## License

MIT License

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.



