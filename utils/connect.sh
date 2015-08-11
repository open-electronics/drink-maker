#!/bin/bash
target='/etc/wpa_supplicant/wpa_supplicant.conf'
backup='/var/www/drink-maker/runtime'

#Backup old connection file
sudo cp "${target}" "${backup}"

#Write new one
echo "ctrl_interface=DIR=/var/run/wpa_supplicant GROUP=netdev " > "${target}"
echo "update_config=1" >> "${target}"
echo "network={" >> "${target}"
echo "  ssid=\""$1"\"" >> "${target}"
echo "  psk=\""$2"\"" >> "${target}"
echo "}" >> "${target}"

#Connect interfaces
sudo ifdown wlan0
sudo ifup wlan0

#Wait for it to connect
sleep 1

#Check if it has connected
OUTPUT="$(iwgetid | grep 'wlan0' | grep -oP '"\K[^"\047]+(?=["\047])')"
if [[ "$OUTPUT" == "$1" ]]
then #it has connected
	echo "1" #everything went well
else #it hasn't
	sudo cp "${backup}" "${target}" #put in the old wpa_supplicant
	#Connect interfaces
	sudo ifdown wlan0
	sudo ifup wlan0
	echo "0" #it didn't work
fi

#Delete the runtime file
sudo rm "${backup}"
echo "1"