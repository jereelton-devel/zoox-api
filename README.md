# zoox-challege

# How do make the install and test

# Requirements
- PHP 7.4
- XAMPP v3.2.4
- MongoDB 4.4.1
- Compass 1.22.1
- Composer dependencies

> NOTE: This project and app was tested on Windows environment

> NOTE: Use URL-Encode to send a request for api zoox-api: urlencode (PHP)

> NOTE: This project is part of the zoox project and must be used together with the <h1>zoox-test</h1>
> https://github.com/jereelton-devel/zoox-test/tree/devel 

# Configure Apache Server - XAMPP
C:\webserver\xampp-php7.4.8\apache\conf\extra\httpd-vhosts.conf

- API
<pre>
<VirtualHost *:80>
    ServerAdmin webmaster@email.com.br
    DocumentRoot "C:/webserver/xampp-php7.4.8/htdocs/zoox-api"
    ServerName zoox.api.local
    ErrorLog "logs/zoox.api.local.local-error.log"
    CustomLog "logs/zoox.api.local.local-access.log" common
    <Directory "C:/webserver/xampp-php7.4.8/htdocs/zoox-api">
        Require all granted

        RewriteEngine On

        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteRule ^ index.php [QSA,L]
		
		Header set Access-Control-Allow-Origin '*'
		
    </Directory>
</VirtualHost>
</pre>

- APP with permission
<pre>
<VirtualHost *:80>
    ServerAdmin webmaster@email.com.br
    DocumentRoot "C:/webserver/xampp-php7.4.8/htdocs/zoox-test"
    ServerName zoox.test.local
    ErrorLog "logs/zoox.test.local.local-error.log"
    CustomLog "logs/zoox.test.local.local-access.log" common
    <Directory "C:/webserver/xampp-php7.4.8/htdocs/zoox-test">
        Require all granted

        RewriteEngine On

        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteRule ^ index.php [QSA,L]
		
		Header set Access-Control-Allow-Origin '*'
		
    </Directory>
</VirtualHost>
</pre>

- APP without permission
<pre>
<VirtualHost *:80>
    ServerAdmin webmaster@email.com.br
    DocumentRoot "C:/webserver/xampp-php7.4.8/htdocs/zoox-test"
    ServerName zoox.denied.local
    ErrorLog "logs/zoox.denied.local.local-error.log"
    CustomLog "logs/zoox.denied.local.local-access.log" common
    <Directory "C:/webserver/xampp-php7.4.8/htdocs/zoox-test">
        Require all granted

        RewriteEngine On

        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteRule ^ index.php [QSA,L]
		
		Header set Access-Control-Allow-Origin '*'
		
    </Directory>
</VirtualHost>
</pre>

# Configure hosts file

C:\Windows\System32\drivers\etc\hosts

<pre>
# Copyright (c) 1993-2009 Microsoft Corp.
#
# This is a sample HOSTS file used by Microsoft TCP/IP for Windows.
#
# This file contains the mappings of IP addresses to host names. Each
# entry should be kept on an individual line. The IP address should
# be placed in the first column followed by the corresponding host name.
# The IP address and the host name should be separated by at least one
# space.
#
# Additionally, comments (such as these) may be inserted on individual
# lines or following the machine name denoted by a '#' symbol.
#
# For example:
#
#      102.54.94.97     rhino.acme.com          # source server
#       38.25.63.10     x.acme.com              # x client host

# localhost name resolution is handled within DNS itself.
#	127.0.0.1       localhost
#	::1             localhost
	127.0.0.1       localhost
	127.0.0.1       zoox.api.local
	127.0.0.1       zoox.test.local
	127.0.0.1       zoox.denied.local
</pre>

# Restart your webserver
- XAMPP::Controll (by example)

# Import the MongoDB databases that are placed on zoox-api/db in this project
- zoox_mongodb_collection_apikey.json
- zoox_mongodb_collection_auth.json
- zoox_mongodb_collection_cidade.json
- zoox_mongodb_collection_estado.json
- zoox_mongodb_collection_log.json

# IMPORTANT:
>Put the files files zoox-test on your localhost root path, so access the app by http://zoox.test.local/zoox-dash.php.

>The API should be defined with the correct local dns name for the correct funcionally this test, by example: http://zoox.api.local

> You can too put the files in the localhost (http://localhost/zoox-test) webserver, but will be needed register the application into database correctly.

# Requests

> NOTE !<br />
(:col) = collection and (:data) = data

- Data Search:
> <strong>GET</strong><br />
> zoox.api.local/action/search/:col/:data/:type
> <br /><strong>Sample:</strong><br />
> http://zoox.api.local/action/search/cidade/SP/asc

- Data List
> <strong>GET</strong><br />
> zoox.api.local/action/list/:col
> <br /><strong>Sample:</strong><br />
> http://zoox.api.local/action/list/cidade

- Data Specific List
> <strong>GET</strong><br />
> zoox.api.local/action/listone/:col/:data
> <br /><strong>Sample:</strong><br />
> http://zoox.api.local/action/listone/cidade/1

- Data Ordered List
> <strong>GET</strong><br />
> zoox.api.local/action/listorder/:col/:data
> <br /><strong>Sample:</strong><br />
> http://zoox.api.local/action/listorder/cidade/nome

- Data Insert
> <strong>POST</strong><br />
> zoox.api.local/action/insert/:col/:data1/:data2
> <br /><strong>Sample:</strong><br />
> http://zoox.api.local/action/insert/cidade/Alphaville/SP

- Data Update
> <strong>POST</strong><br />
> zoox.api.local/action/update/:col/:data/:data1/:data2
> <br /><strong>Sample:</strong><br />
> http://zoox.api.local/action/update/cidade/5/Ubatuba/RJ

- Data Delete
> <strong>POST</strong><br />
> zoox.api.local/action/delete/:col/:data
> <br /><strong>Sample:</strong><br />
> http://zoox.api.local/action/delete/cidade/9
>

# Authentication
The app authentication is make in the collection database zoox_mongodb_collection_auth, so you should be register your app in the collection database, example:
* {id: 1, app: zoox.test.local}
* {id: 2, app: zoox.api.local}

# Authorization
After app authentication the api request your token to check your identiry correctly in the collection zoox_mongodb_collection_apikey, so you need register or create a token of access this api, example:
* {id: 1, app: "zoox.test.local", token: "MTIzNDU2Nzg5MA==", timelife: 1}
* {id: 2, app: "zoox.test.local", token: "MTIzNDU2Nzg5MA==", timelife: 1}

On both cases you should be send the token in the request header with name <strong>x-Api-key</strong> and client name <strong>x-Client-Origin</strong>.