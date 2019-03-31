# templogger.py
# @author: Dillon John
# Periodically collects temperature data from a DS18b20 temp sensor
# and stores it in a database

# Download the helper library from https://www.twilio.com/docs/python/install
from twilio.rest import Client

# Import Libraries
import mysql.connector as mariadb
import os
import glob
import time

#Initialize the GPIO pins
os.system('modprobe w1-gpio') # Turns the GPIO module on
os.system('modprobe w1-therm') # Turns the Temperature module on

# Assign the sensor(s) unique ID to a short variable
# at this time, there's only one sensor
# changes would need to be made to this program to accommodate multiples
sensor = '28-0517608a27ff'

# Set limits for the high and low end of the desired temperature range
# If the temperature reading falls outside the specified range, a
# notification should be sent to the user
highLimit = 72.0
lowLimit = 68.0

# Finds the correct device file that holds the sensor data
base_dir = '/sys/bus/w1/devices/'
device_folder = glob.glob(base_dir + sensor)[0]
device_file = device_folder + '/w1_slave'

# This function reads the sensor's raw data
def read_temp_raw():
	f = open(device_file, 'r')
	lines = f.readlines()
	f.close()
	return lines

# This function converts the raw data from the sensor into degrees Celsius
def degC():
	lines = read_temp_raw() # read the sensor 'device file'

	# While the first line does not contain 'YES', wait for 0.2s
	# and then read the 'device file' again.
	while lines[0].strip()[-3:] != 'YES':
		time.sleep(0.2)
		lines = read_temp_raw()

	# Look for the position of the '=' in the 2nd line of
	# the 'device file'
	equals_pos = lines[1].find('t=')

	# if the '=' is found, convert the rest of the line after the '='
	# into deg C and then deg F
	if equals_pos != -1:
		temp_string = lines[1][equals_pos+2:]
		temp_c  = float(temp_string) / 1000.0
		print temp_c
		return temp_c

# This function converts the temperature from Celsius to Fahrenheit
def degF(tempC):
	temp_f = tempC * 9.0 / 5.0 + 32.0
	print temp_f
	return temp_f

# Twilio setup
# Your Account Sid and Auth token from twilio.com/console
# Danger! This is insecure. See http://til.io/secure
account_sid = '********************************'
auth_token = '*********************************'
client = Client(account_sid, auth_token)

# Establish a database connection
db = mariadb.connect(
	host = "localhost",
	user = "******",
	passwd = "********",
	database = "temps"
	)

# Create the handler/cursor
mycursor = db.cursor()

# Create a variable to store the sleep period, in seconds
sleepFor = 15 # 300 sec = 5 min, 120 = 2 min, etc.

# Create the INSERT statement for the db
sql = "INSERT INTO TEMPS (fTemp, cTemp) VALUES( %s, %s)"


# This function periodically writes the temperature data to the database
# It will continue to do so until the program is stopped
while True:
	# Retrieve current temperature data
	tempCelsius = degC()
	tempFahrenheit = degF(tempCelsius)

	if tempFahrenheit > highLimit:
		difference = tempFahrenheit - highLimit
		# Send this message if the temperature gets too high
		tooHotMessage = client.messages \
		    .create(
		    body = 'The current fermentation temperature is ' + str(tempFahrenheit) + '. ' + str(difference) + ' degrees above the set limit of ' + str(highLimit),
		    from_='+18622800237',
		    to='+18622006269'
		    )
		print(tooHotMessage.sid)

	if tempFahrenheit < lowLimit:
		difference = lowLimit - tempFahrenheit
		# Send this messsage if the temperature gets too low.
		tooColdMessage = client.messages \
			.create(
			body = 'The current fermentation temperature is ' + str(tempFahrenheit) + '. ' +  str(difference) + ' below the set limit of ' + str(lowLimit),
		    from_='+18622800237',
		    to='+18622006269'
		    )
		print(tooColdMessage.sid)

	# Create the INSERT statement for the db using the current variables
	val = (tempFahrenheit, tempCelsius)

	# Execute the statement
	mycursor.execute(sql, val)

	# Commit the changes
	db.commit()

	# Sleep for the period assigned above
	time.sleep(sleepFor)
