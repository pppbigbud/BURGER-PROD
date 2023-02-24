#!/bin/bash

# Créer le fichier .htaccess dans le dossier public de votre application Symfony
cat <<EOF > ./public/.htaccess
RewriteEngine On
RewriteRule ^(.*)$ index.php [QSA,L]
EOF