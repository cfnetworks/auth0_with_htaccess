# Use auth0 with .htaccess
Implement auth0.com authentication with .htaccess

## What does it do?
Essentially, this project leverages the capabilities of auth0.com and integrates them with .htaccess. Consequently, your login status is verified by the .htaccess file, eliminating the need for authentication checks in your codebase each time. This enables you to secure your application with Auth0 without the need to modify its code.

This project is intended as a prototype and should not be used on production systems without thorough code adaptation and comprehensive security assessments.

## How does it work?
* When you successfully log in with Auth0, an encrypted hash will be generated and stored in a cookie. Simultaneously, it is written into a local file on your webserver.
* Additionally, the expiration date is recorded in a separate local file on your webserver.
* .htaccess loads the contents of the encrypted hash file and the expiration date into local variables.
* Subsequently, .htaccess compares the sent cookie value with the variable containing the encrypted hash. If they are not equal, a redirect to the Login/Unauthorized page occurs.
* Furthermore, .htaccess compares the expiration date from the variable with the current date. If the expiration date is earlier than the current date, a redirect to the Login/Unauthorized page is triggered.

## Make the example work

