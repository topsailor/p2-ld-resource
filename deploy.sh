#!/bin/bash

# root folder 
sudo cp download.php index.php /usr/share/hanflix/
sudo chown -R nginx:nginx /usr/share/hanflix
sudo chmod -R 755 /usr/share/hanflix

# nginx conf
sudo cp hanflix_web.conf /etc/nginx/conf.d/


sudo systemctl restart nginx php-fpm

