import json
import urllib.request
import time
import serial
import math
import os
import signal
import subprocess

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
		fetch_url("orders/timedout")
	else:
		fetch_url("orders/activated")
		prepare_drink(data)
	data=None
	
def prepare_drink(info):
	print(info)
	#dictate ingredients
	total_parts=0;
	for ingredient in info["ingredients"]:
		total_parts=total_parts+ int(ingredient["needed"])
	
	for ingredient in info["ingredients"]:
		ingredient_volume= (int(ingredient["needed"])/total_parts)*int(info["volume"])
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
		return int(math.floor(number))
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
                try:
                        v=ser.readline().decode("UTF-8").strip()
                        print("In:"+v)
                except:
                        print("Error")
                        time.sleep(0.1)
	return v
def write_data(data):
	global ser
	print("Out:"+data)
	success = False
	while not success:
                try:
                        ser.write(bytes("!"+data+'\n','UTF-8'))
                        success= True
                except:
                        print("Error")
                        success = False
                        
def connect_wifi(ssid,password):
	os.system("sudo bash /var/www/drink-maker/utils/connect.sh "+ssid + " " + password)

def load_sketch():
	count = 0
	success = False
	
	while success == False:
		p = subprocess.Popen(["bash","/home/pi/bin/ArduLoad","/var/www/drink-maker/utils/drink_maker.cpp.hex"], shell = False)
		while p.poll() == None and count < 20:
			count = count + 1
			time.sleep(1)
		if p.poll() == None:
			try:
				p.terminate()
			except:
				print("Error")
		else:
			success = True

load_sketch()

time.sleep(3)
base_url="http://127.0.0.1/"
connected = False
while not connected:
        try:
                ser=serial.Serial(port="/dev/ttyS0",baudrate=9600)
                connected= True
        except:
                connected= False
                time.sleep(0.5)
                
ser.flushInput()
ser.flushOutput()
write_data("GoHome")
wait_answer()
while True:
	data = fetch_url("settings/wifiData")
	if data != None:
		connect_wifi(data["ssid"],data["password"])
	data=None
	main_loop()
	
