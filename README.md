

init project
Requirments:

- Activation du mod rewrite de apache2
- composer
- bower

VHOST example:
--------

<VirtualHost *:80>
 ServerName montchat
    DocumentRoot "/var/www/html/montchat/www"
        <Directory "/var/www/html/montchat/www">
            Options Indexes FollowSymLinks MultiViews
            AllowOverride None
            Order allow,deny
            allow from all

            <IfModule mod_rewrite.c>
                RewriteEngine On

                    RewriteCond %{REQUEST_METHOD} OPTIONS
                    RewriteRule ^(.*)$ $1 [R=200,L]

                RewriteCond %{REQUEST_FILENAME} !-f
                RewriteRule ^(.*)$ /app.php [QSA,L]
          </IfModule>
        </Directory>
</VirtualHost>


----------
lancer:

-   composer dump-autoload  -o
-   bower install

Les Routes:

-   /tchat
-   /login
-   /logout

