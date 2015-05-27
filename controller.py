import json
import urllib.request
import time
#import serial

global data,ser,base_url


def main_loop():
	global data,ser,base_url
	#data retrieving
	while data=="null":
		fetch_url(base_url+"waiting")
		time.sleep(2)
	#dictate ingredients
	for i in data["ingredients"]:
		serial.write(i+"|"+str(data["ingredients"][i]))
		wait_answer()
	#update db
	fetch_url(base_url+"completed/"+str(data["id"]))
	#reset position
	serial.write("x|x")
	wait_answer()
def fetch_url(url):
	global data
	page=urllib.request.urlopen(url)
	j=page.read().decode("utf-8")
	data=json.loads(j)
def wait_answer():
	global ser
	v=None
	while not v=="1":
		v=ser.readline().strip()
		time.sleep(0.5)

data="null"
base_url="http://robot.app/orders/"
#ser=serial.Serial(port="dev/tty0",baudrate=9600)
main_loop()
