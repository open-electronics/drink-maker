import json
import urllib.request
import time
import serial

global data,ser,base_url


def main_loop():
	global data,ser,base_url
	#data retrieving
	while data=="null":
		fetch_url(base_url+"waiting")
		time.sleep(2)
	#dictate ingredients
	for i in data["ingredients"]:
		ser.write("*-"+i+str(data["ingredients"][i])+"\n")
		wait_answer()
	#update db
	fetch_url(base_url+"completed/"+str(data["id"]))
	#reset position
	ser.writeline("00\n")
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
ser=serial.Serial(port="dev/ttyS0",baudrate=9600)
ser.writeline("GoHome\n")
while True:
	main_loop()
