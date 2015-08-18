import json
import urllib.request
import time
import serial
import math
import os
from subprocess import *

global data,ser,base_url


def main_loop():
	global data,base_url
	#data retrieving
	while data==None:
		data = fetch_url("orders/waiting")
		if data=="shutdown":
			shutdown()
		time.sleep(2)
	
	#tell the machine that we have a new drink with stuff
	write_data("NewDrink|"+data["start"]+"|"+data["timeout"]+"|"+data["lights"])
	if wait_answer("2") == "2": #activate, expect 2 as a "timed out" signal
		data = fetch_url("orders/timedout")
	else:
		data = fetch_url("orders/activated")
		prepare_drink()
	data=None
	
def prepare_drink():
	#dictate ingredients
	total_parts=0;
	for ingredient in data["ingredients"]:
		total_parts=total_parts+ ingredient["needed"]
	
	for ingredient in data["ingredients"]:
		ingredient_volume= (ingredient["needed"]/total_parts)*data["volume"]
		ingredient_volume= round_to_multiple(ingredient_volume)
		parts= ingredient_volume/2
		write_data(str(ingredient["position"])+ "|"+str(parts))
		wait_answer()
	#update db
	data = fetch_url("orders/completed")
	#reset position
	write_data("0|0")
	wait_answer()

def round_to_multiple(number,multiple=2):
	if math.floor(number)%multiple==0:
		return int(math-floor(number))
	else :
		return int((math.floor(number/multiple))*multiple)

def shutdown():
    os.system("sudo shutdown -h now")
time.sleep(5)
	
def fetch_url(url):
	global data
	url= base_url+url
	try:
		page=urllib.request.urlopen(url)
		j=page.read().decode("utf-8")
		if j=="shutdown":
			return "shutdown"
		return json.loads(j)
	except:
		return None
def wait_answer(answer="1"):
	global ser
	v=None
	while not (v=="1" or v==answer):
		v=ser.readline().decode("UTF-8").strip()
		print("In:"+v)
		time.sleep(0.2)
	return v
def write_data(data):
	global ser
	print("Out:"+data)
	ser.write(bytes("!"+data+'\n','UTF-8'))
def connect_wifi(ssid,password):
	os.system("sudo bash /var/www/drink-maker/utils/connect.sh "+ssid + " " + password)

subprocess.call(["ArduLoad", "/var/www/drink-maker/utils/drink-maker.cpp.hex"])
time.sleep(3)
base_url="http://drink/"
ser=serial.Serial(port="/dev/ttyS0",baudrate=9600)
write_data("GoHome")
wait_answer()
while True:
	data = fetch_data("settings/wifiData")
	if data != None:
		connect_wifi(data["ssid"],data["password"])
	data=None
	main_loop()
	
