# zoox-test

# HOW TO MAKE THE INSTALATION

C:\webserver\xampp-php7.4.8\apache\conf\extra\httpd-vhosts.conf

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

C:\Windows\System32\drivers\etc\hosts

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

# NOTE:
