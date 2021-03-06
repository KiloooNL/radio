<?php

try {
	// Get the configuration
	require_once('../config/config.php');
	
	// Get the code for this page
	require_once('../code/code.songinfo.php');
	
	// Get the display for this page
	require_once('../display/display.songinfo.php');

} catch (Exception $ex) {
	// The error page will be displayed if anything goes wrong above
	$message = $ex->getMessage();
	require_once('../display/display.error.php');
}