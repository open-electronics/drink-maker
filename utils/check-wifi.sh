#!bin/bash
while true; do
    sudo iwlist wlan0 scan
    sleep 15
done