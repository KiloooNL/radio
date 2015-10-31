<?php

// Define path to application directory
defined('APP_PATH') || define('APP_PATH', realpath(dirname(__FILE__) . '/../'));
// Ensure common is on include_path
set_include_path(implode(PATH_SEPARATOR, array(realpath(APP_PATH . '/library'), '.')));
// Make a shortcut to DIRECTORY_SEPARATOR
define('DS', DIRECTORY_SEPARATOR);

/*
SET TIMEZONE
	date_default_timezone_set('America/New_York')
	If using >= PHP 5.3 uncomment date_default_timezone_set below and set correct timezone
	For correct options see: http://www.php.net/manual/en/timezones.php
*/
date_default_timezone_set('Australia/Adelaide');

## ======================================== ##
## Station general details					##
## ======================================== ##
define('STATION_NAME', 'KiloooNL\'z Radio Station');
define('STATION_EMAIL', ''); //Leave blank if you do not wish to publish your email address!
define('STATION_LOGO', 'images/logo.png');

/*
STATION_ID
	Your SpacialNet station ID.
	Log into your SpacialNet broadcaster account and go to "My Stations" to get this ID.
	This is required to make the listen links on the website to work.
	Follow these steps to set up your station: http://support.spacialaudio.com/wiki/Listing_on_Audiorealm
*/
define('STATION_ID', 0);


## ============================================================ ##
## General options												##
## ============================================================ ##


/*
ALLOW_REQUESTS
	If true, requests are allowed to be made to SAM.
	If false, all request related links will be hidden.
 */
define('ALLOW_REQUESTS', true);

/*
PRIVATE_REQUESTS
	If true, your own web server will handle song requests.
	If false, AudioRealm.com will handle the request.
 */
define('PRIVATE_REQUESTS', true);

/*
SHOW_TOP_REQUESTS
	If true, display the top requests
 */
define('SHOW_TOP_REQUESTS',	true);

/*
TOP_COUNT
	The number of top requests to display
 */
define('TOP_REQUEST_COUNT',	5);

/*
REQUEST_DAYS
	Show the top requests for the last xx days
 */
define('REQUEST_DAYS', 10);

/*
SAM_HOST
	The internet IP address of the machine SAM is running on.
	DO NOT use a local IP address like 127.0.0.1 or 192.x.x.x (UNLESS your webserver is on the same local network as SAM)
	Not sure what your IP address is?
	Try http://www.ipchicken.com or http://www.whatismyip.com
	If your IP address changes regularly, rather use DNS to make the "name" static.
	See http://www.no-ip.com and http://www.dyndns.com/
*/
define('SAM_HOST', '127.0.0.1');

/*
SAM_PORT
	The port SAM handles HTTP requests on. Usually 1221.
	If you are behind a router you may need to implement "port forwarding" to make SAM visible.
	For more information on port forwarding see:
	  http://en.wikipedia.org/wiki/Port_forwarding
	  http://portforward.com/
 */
define('SAM_PORT', 1221);

/*
SHOW_PICTURES
	Must we show album cover pictures in now playing section?
	For this to work you must
	a) Associate the album cover pictures with the tracks using the Song Information Editor in SAM.
	   The Amazon lookup really makes this an easy process.
	b) Upload these album pictures to your webserver.
	   By default SAM stores the pictures in the directory specified in SAM->Config->General under "Local Picture Directory"
	c) SAM can upload album cover pictures using FTP. See
	  * SAM->Config->HTML Ouput to set up FTP details
	  * and SAM->Menu->General->HTML Output->Upload all pictures
 */
define('SHOW_PICTURES', true);

/*
PICTURE_URL
	Location where all your album pictures are stored.
	Example Relative path: pictures/
	Example Absolute path: http://your.webserver.com/pictures/
 */
define('PICTURE_URL', 'pictures/');

/*
PICTURE_NA
	Use this picture if the song has no picture.
	To disable the use of a default picture set value to empty string.
 */
define('PICTURE_URL_NA', PICTURE_URL.'na.png');

/*
HISTORY_COUNT
	The number of history items to display on the playing page
 */
define('HISTORY_COUNT',	5);

/*
COMING_UP_COUNT
	The number of coming soon songs to display.
	Set to zero to disable coming up section.
 */
define('COMING_UP_COUNT', 2);

/*
CHECK_INTERVAL
	How regularly do you want to check for a song change event in order to refresh the "Now playing" page data.
	Set to zero to disable checks for song change.
	Default settings is every 30 seconds.
	We do not recommend a setting lower than 10 seconds (10000) or higher than 3 minutes (180000).
*/
define('CHECK_INTERVAL', 30000);

## ============================================================ ##

// The Singleton Database class
require_once('Common/Database.php');

// Create a connection to the database from config file
$db = Database::getInstance(APP_PATH.DS.'config'.DS.'dbconfig.xml.php');

// Verify the config settings above
require_once('config.verify.php');