#!/bin/bash
target='/etc/wpa_supplicant/wpa_supplicant.conf'
echo "ctrl_interface=DIR=/var/run/wpa_supplicant GROUP=netdev " > "${target}"
echo "update_config=1" >> "${target}"
echo "network={" >> "${target}"
echo "  ssid=\""$1"\"" >> "${target}"
echo "  psk=\""$2"\"" >> "${target}"
echo "}" >> "${target}"

sudo ifdown wlan0
sudo ifup wlan0
echo "1"