#!/bin/bash
#Author: Marco Bellan

sudo echo "i2c-dev" >> /etc/modules
sudo echo "i2c-bcm2708" >> /etc/modules

sudo echo "dtparam=i2c_arm=on" >> /boot/config.txt
sudo echo "dtparam=i2c1=on" >> /boot/config.txt