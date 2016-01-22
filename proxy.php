<?php
/**
 * Author: Trimbitas Sorin
 * Email: trimbitassorin@hotmail.com
 */

/**
 * Docs:
 * 	github for the mqtt class
 * 	http://blog.telenor.io/2015/10/15/lora-aws.html
 *  http://docs.aws.amazon.com/iot/latest/developerguide/protocols.html
 *
 */

// Validate parameters
if ( ! isset($_GET['topic']) OR ! isset($_GET['msg']))
{
	die('You need to set "topic" and "msg" as GET parameters!');
}
$topic = trim($_GET['topic']);
$msg = trim($_GET['msg']);
if ( ! $topic)
{
	die('Invalid topic!');
}

// All is ok .. load the library and initialize it
require_once(__DIR__.'/lib/mqtt.lib.php');

$config = [
	'aws_iot_host'          	=> 'A323VJS9G76W0W.iot.eu-west-1.amazonaws.com',
	'aws_iot_port'				=> 8883,
	'aws_iot_cafile'        	=> __DIR__.'/certs/VeriSign-Class-3-Public-Primary-Certification-Authority-G5.pem',
	'aws_iot_crtfile'       	=> __DIR__.'/certs/7e54b41b6a-certificate.pem.crt',
	'aws_iot_private_keyfile'	=> __DIR__.'/certs/7e54b41b6a-private.pem.key',
];

// Init MQTT Library
$mqtt = new libMQTT\client($config['aws_iot_host'], $config['aws_iot_port'], 'php_proxy');
$mqtt->setClientCert($config['aws_iot_crtfile'], $config['aws_iot_private_keyfile']);
$mqtt->setCAFile($config['aws_iot_cafile']);
$mqtt->setCryptoProtocol('ssl');
$mqtt->setVerbose(1);


// Try to connect
if ( ! $mqtt->connect())
{
	die('0'.'');
}

// We are connected :)
// Publish the GET parameters
// Third parameter is QOS = Quality of Service, check this for more information http://docs.aws.amazon.com/iot/latest/developerguide/protocols.html
$reply = $mqtt->publish($topic, $msg, 0);

// Close the connection
$mqtt->close();

if ($reply)
{
	// Publish action succeded! :)
	echo 1;
	die();
}

// Sadly, publish action failed :(
echo 0;
die();