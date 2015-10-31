<?php

/* This script will check if a new song started playing and then return javascript code that will refresh the current page */
try {
	// Get the configuration
	require_once('../config/config.php');

	// Get the code for this page
	require_once('../code/code.songcheck.php');

	// Get the display for this page
	require_once('../display/display.songcheck.php');

} catch (Exception $ex) {
	// The error page will be displayed if anything goes wrong above
	$message = $ex->getMessage();
	require_once('../display/display.error.php');
}