## Drink Maker
DrinkMaker is the software for an automated robot bartender!
It features a web application capable of taking orders from multiple users, handling queues and sending notifications.
The WebApp also is capable of handling stock quantities in each bottle and offering users only the drink that are currently preparable with the actual quantities in each bottle.
The owner can manage the bahaviour of the machine using a dedicated control panel.
It is powered by the Lumen-Laravel framework, while the UI is realized with MaterializeCSS.

## HardWare
The whole DrinkMaker environment is intended to run on a RaspberryPi 2.0 with a RandA board and its dedicated stepper motor controller board.

## Controller
The DrinkMaker environment also has a python controller that handles the realization of all the drinks, by fetching data from the WebApp and sending instructions, via I2C, to the RandA board.
The board is powered by an ATMega 328 microcontroller programmed to control full RGB lighting along with the mechanics of the machine.

## Updates
The machine also has an automated updates system based on Git.
Everytime it starts up, it'll check for any differences with the remore GitHub repository, and if any changes are found, it will fetch and apply them in real time.

## Setup
You can clone this folder in your /home/pi folder and run the install.sh script with Super User privileges.
```
cd /home/pi/drink-maker/utils
sudo bash install.sh
```
This will take some time and it will install all the dependencies necessary to make the machine work.
If you prefer a faster setup you can download the .img file and write it to an SD card with at least 8GB of free space.
Don't forget to later expand your RaspBian filesystem to fit all your SD card space!

##Read more
You can read more about this on the [OpenElectronics website](http://www.open-electronics.org/the-drink-maker-open-sourcing-your-cocktail/)

##Where to Buy
You can buy the hardware needed to realize the Drink Maker machine on [Futura Elettronica website](https://www.futurashop.it/drinkmaker-con-5-dosatori-in-kit-7800-drinkmaker?filter_name=drink%20maker)

##Feature requests and Bugs
You can send us feature requests and bugs via the "Issues" section of our GitHub repository!

### License

The Articles you can find on Open Electonics are licensed under a Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License. Where not specified differently, design files and source code are instead provided according to a Creative Commons Attribution-ShareAlike 4.0 Unported License


## Laravel and Lumen
Documentation for the framework can be found on the [Lumen website](http://lumen.laravel.com/docs).
If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.
