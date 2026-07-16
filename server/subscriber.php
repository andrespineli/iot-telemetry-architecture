<?php

require_once('DB.php');

define('BROKER', 'localhost');
define('PORT', 1883);
define('CLIENT_ID', "PHP");

$client = new Mosquitto\Client(CLIENT_ID);
$client->onConnect('connect');
$client->onDisconnect('disconnect');
$client->onSubscribe('subscribe');
$client->onMessage('message');
$client->connect(BROKER, PORT, 60);
$client->subscribe('raspbroker', 1); 

$client->loopForever();

function connect($r) {
	echo "Received response code {$r}\n";
}

function subscribe() {
	echo "Subscribed to a topic\n";
}

function message($message) {
	printf($message->payload . "\n");   
	$db = new DB();
	$db->storeTemperature($message->payload);
	$temp = substr($message->payload, -5);	 
	if (substr($message->payload, 0, 2) == "A0") {
		$source = 'Arduino';		
	} elseif (substr($message->payload, 0, 2) == "N0") {
		$source = 'NodeMCU';
	}
	
	shell_exec('python /var/www/html/lcd.py ' . 'TEMP:' . $temp . '*C SENSOR:' . $source);
	shell_exec('sudo service phpsub restart');
}

function disconnect() {
	echo "Disconnected cleanly\n";
}
