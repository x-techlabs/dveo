#!/bin/bash
# Enable swap, run composer update, disable swap. That's it.

if [ ! -f /swapfile ]; then
    sudo dd if=/dev/zero of=/swapfile bs=1024 count=1024m
    sudo mkswap /swapfile
fi

sudo swapon /swapfile
composer update
sudo swapoff /swapfile