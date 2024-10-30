#!/bin/bash

# dnf installs - essentials

sudo dnf install nginx php-fpm php-mysqlnd -y
sudo systemctl enable --now nginx php-fpm



