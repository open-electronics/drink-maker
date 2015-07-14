#!/bin/bash
#Extract all installation files (dir RandAinstall) and set-up
#Author: daniele Denaro
# set on trace
set -v

#redirect stdout and stderr to output and log file
exec &> >(tee RandAinstall.log)

#extract directory RandAinstall (where installation files are contained) 
#from archive
tar -xzf RandAinstall.tar.gz -C /
#end extract from archive

#modify files
#required files : 
#/RandAinstall/profile, /RandAinstall/addtorclocal.txt, /RandAinstall/cmdline.txt  

#save present files for backup
sudo cp ./.profile  ./.profile-old
sudo cp /etc/rc.local /etc/rc-old.local
sudo cp /etc/inittab /etc/inittab-old
sudo cp /boot/cmdline.txt /boot/cmdline-old.txt
sudo chmod 555 ./.profile-old
sudo chmod 555 /etc/rc-old.local
sudo chmod 555 /etc/inittab-old
sudo chmod 555 /boot/cmdline-old.txt

#modify files

#add command list to .profile. 
#Save to RandAInstall/newprofile and copy to .profile 
cat  ./.profile /home/pi/RandAinstall/profile  > /home/pi/RandAinstall/newprofile
cp /home/pi/RandAinstall/newprofile ./.profile
sudo chmod 755 .profile
#add RandA startup command to rc.local (system file that system run on startup)
#Very important! 
#Button ON/OFF enable.
#Start: remote Arduino IDE server, WEB server and Arduino command detecting
#But attention! This script uses command and script downloaded below
sudo chmod 777 /etc/rc.local
sudo sed -e 's/^exit 0/#RandA local/' /etc/rc.local | cat - /home/pi/RandAinstall/etc/addtorclocal.txt > /home/pi/RandAinstall/etc/rc.local
sudo cp /home/pi/RandAinstall/etc/rc.local /etc/rc.local
sudo chmod 755 /etc/rc.local
#Comment ttyAMA0 (that is dev/ttyS0 and dev/Arduino) for serial port communication
#not terminal
sudo chmod 777 /etc/inittab
sudo sed -e '/ttyAMA0/s/^T0:23:respawn:/#T0:23:respawn:/' /etc/inittab > /home/pi/RandAinstall/etc/inittab
sudo cp /home/pi/RandAinstall/etc/inittab /etc/inittab
sudo chmod 755 /etc/inittab
#copy script for variables setting (JAVA_HOME). Edit this file if java link is changed
sudo cp /home/pi/RandAinstall/etc/setvar.sh  /etc/profile.d/setvar.sh
sudo chmod 777 /etc/profile.d/setvar.sh
source /etc/profile.d/setvar.sh
#
#sudo chmod 777 /boot/cmdline.txt
sudo mv /home/pi/RandAinstall/boot/cmdline.txt /boot/cmdline.txt
sudo chmod 755 /boot/cmdline.txt

#install commands and system files
#new commands to bin path; and commands or scripts for button ON/OFF 
sudo mv /home/pi/RandAinstall/bin/ /home/pi/bin/
sudo mv /home/pi/RandAinstall/etc/ButtonOff.sh /etc/ButtonOff.sh
sudo mv /home/pi/RandAinstall/etc/PowerOff /etc/PowerOff
sudo mv /home/pi/RandAinstall/etc/ups-monitor /etc/init.d/ups-monitor

#move directories
#repository for compiled Arduino sketches (locally compiled)
sudo mv /home/pi/RandAinstall/ArduinoUpload/ /home/ArduinoUpload/
#repository for compiled Arduino sketches (remotelly compiled)
sudo mv /home/pi/RandAinstall/RArduinoUpload/ /home/RArduinoUpload/
#repository for local sketches sources
sudo mv /home/pi/RandAinstall/sketchbook/ /home/pi/sketchbook/
#repository for c-workspace and java-workspace (containing command and examples)
sudo mv /home/pi/RandAinstall/workspace/ /home/pi/workspace/

#move utilities and packages
mv /home/pi/RandAinstall/RandAutil/ /home/pi/RandAutil/

#enable I2C
sudo /home/pi/RandAutil/enable-i2c.sh

#install programs

#wiringPi. Copy archive and install.
tar -xzf /home/pi/RandAutil/packages/wiringPi-5edd177.tar.gz -C /home/pi/
dir="$PWD"
cd /home/pi/wiringPi-5edd177
./build
cd "$dir"
#pi4j. Install pi4j archive (java version of wiringPi) (from package)
sudo dpkg -i /home/pi/RandAutil/packages/pi4j-1.0-SNAPSHOT.deb
#java mail. Copy java library for java-mail
sudo mv  /home/pi/RandAutil/packages/javamail-1.4.7/  /opt/javamail-1.4.7/
#install Arduino IDE (local IDE)
#sudo apt-get -y install arduino
source /home/pi/RandAutil/packages/arduino/ArduInst.sh
# modified lib for Arduino IDE and link to rxtxSerial lib (after Arduino installation)
sudo cp  /home/pi/RandAinstall/pde.jar /usr/share/arduino/lib/pde.jar 
sudo mv  /home/pi/RandAinstall/RAComm/  /usr/share/arduino/libraries/RAComm/ 
sudo ln -s -f /usr/lib/jni/librxtxSerial.so /usr/lib/librxtxSerial.so
