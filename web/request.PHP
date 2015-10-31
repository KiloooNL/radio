<?php

try {
	// Get the configuration
	require_once('../config/config.php');

	// Get the code for this page
	require_once('../code/code.request.php');

	// Get the display for this page
	require_once('../display/display.request.php');

} catch (Exception $ex) {
	// The error page will be displayed if anything goes wrong above
	$message = $ex->getMessage();
	require_once('../display/display.request.error.php');
}