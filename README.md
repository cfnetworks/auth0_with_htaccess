# Use auth0 with .htaccess
Implement auth0.com authentication with .htaccess

## What does it do?
Basically this project uses the capability of auth0.com and combines it with .htaccess. This means your login status gets verified by the .htaccess file and does not have to be authenticated every time in your code base. This means you can protect your application with auth0 without changing the code of it.

## How does it work?
* When you login successfully with auth0 an encrypted hash will be created and saved in a cookie and also gets written into a local file on your webserver
* Also the expiration date will be written in a separate local file on your webserver
* .htaccess loads the content of the encrypted hash file and the expiration date into local variables
* .htaccess then compares the sent cookie value with the variable containing the encrypted hash; If unequal, redirect to Login / unauthorized page
* .htaccess then compares the expiration date from the variable with the current date; If expiration date is older than current date, redirect to Login / unauthorized page

## Make the example work

