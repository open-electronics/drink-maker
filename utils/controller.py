import json
import urllib.request
import time
import serial

global data,ser,base_url


def main_loop():
	global data,base_url
	#data retrieving
	while data=="null":
		fetch_url(base_url+"waiting")
		time.sleep(2)
	#dictate ingredients
	for i in data["ingredients"]:
		write_data("*-"+i+str(data["ingredients"][i]))
		wait_answer()
	#update db
	fetch_url(base_url+"completed/"+str(data["id"]))
	#reset position
	write_data("*-00")
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
		v=ser.readline().decode("UTF-8").strip()
		time.sleep(0.2)
def write_data(data):
	global ser
	ser.write(bytes(data+'\n','UTF-8'))

data="null"
base_url="http://192.168.0.199/orders/"
ser=serial.Serial(port="/dev/ttyS0",baudrate=9600)
write_data("GoHome")
wait_answer()
while True:
	main_loop()
