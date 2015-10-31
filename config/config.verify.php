<?php

$err_message = array();
## ======================================== ##
$station_email = constant('STATION_EMAIL');
if ($station_email == 'mail@mailserver.com') {
	$err_message[] = 'Email address not updated. Please change contact email or alternatively leave it blank to disable the email link.';
}

$station_id = constant('STATION_ID');
if (!is_numeric($station_id)) {
	throw new Exception('STATION_ID: Your station id must be numeric, "' . $station_id . '" given.');
}

if ($station_id <= 0) {
	$err_message[] = 'Player listen link will not work as stationID is not provided.';
}

## ======================================== ##
$allow_requests = constant('ALLOW_REQUESTS');
if (!is_bool($allow_requests)) {
	throw new Exception('ALLOW_REQUESTS: Incorrect option, only true or false allowed, "' . $allow_requests . '" given.');
}

## ======================================== ##
if ($allow_requests) {
	$sam_host = constant('SAM_HOST');
	if (empty($sam_host) || is_null($sam_host) || 'your.sam.host' == $sam_host) {
		$err_message[] = 'SAM_HOST: Requests will not work as SAM host is not specified. Please check configuration.';
	}

	if (privateIP($sam_host)) {
		//$err_message[] = 'SAM_HOST: Warning - using local or private network IP range ("' . $sam_host . '") - only do this if SAM is on the same network as this webserver.';
	}

	## ======================================== ##
	$sam_port = constant('SAM_PORT');
	if (!is_numeric($sam_port)) {
		throw new Exception('SAM_PORT: The specified SAM port must be numeric, "' . $sam_port . '" given.');
	}

	## ======================================== ##
	$private_requests = constant('PRIVATE_REQUESTS');
	if (!is_bool($private_requests)) {
		throw new Exception('PRIVATE_REQUESTS: Incorrect option, only true or false allowed, "' . $private_requests . '" given.');
	}

	## ======================================== ##
	$show_top_requests = constant('SHOW_TOP_REQUESTS');
	if (!is_bool($show_top_requests)) {
		throw new Exception('SHOW_TOP_REQUESTS: Incorrect option, only true or false allowed, "' . $show_top_requests . '" given.');
	}

	## ======================================== ##
	$top_request_count = constant('TOP_REQUEST_COUNT');
	if (!is_numeric($top_request_count)) {
		throw new Exception('TOP_REQUEST_COUNT: Incorrect option, only numeric values allowed, "' . $top_request_count . '" given.');
	}

	## ======================================== ##
	$request_days = constant('REQUEST_DAYS');
	if (!is_numeric($request_days)) {
		throw new Exception('REQUEST_DAYS: Incorrect option, only numeric values allowed, "' . $request_days . '" given.');
	}
}

## ======================================== ##
$showpictures = constant('SHOW_PICTURES');
if (!is_bool($showpictures)) {
	throw new Exception('SHOW_PICTURES: Incorrect option, only true or false allowed, "' . $showpictures . '" given.');
}

if ($showpictures) {
	//Only check paths if relative path
	$picurl = constant('PICTURE_URL');
	if (strpos($picurl, "://") === false) {
		if (!is_dir($picurl)) {
			$err_message[] = 'PICTURE_URL: Warning - picture directory does not exist on this webserver. (' . $picurl . ')';
		} else {
			$defpic = constant('PICTURE_URL_NA');
			if (!empty($defpic) && $defpic != $picurl) {
				if (!file_exists($defpic)) {
					$err_message[] = 'PICTURE_URL_NA: Default picture not found. (' . $picurl . ')';
				}
			}
		}
	}
}

## ======================================== ##
$history_count = constant('HISTORY_COUNT');
if (!is_numeric($history_count)) {
	throw new Exception('HISTORY_COUNT: Incorrect option, only numeric values allowed, "' . $history_count . '" given.');
}

## ======================================== ##
$coming_up_count = constant('COMING_UP_COUNT');
if (!is_numeric($coming_up_count)) {
	throw new Exception('COMING_COUNT: Incorrect option, only numeric values allowed, "' . $coming_up_count . '" given.');
}
## ======================================== ##
## ======================================== ##
$checkInterval = constant('CHECK_INTERVAL');
if ($checkInterval > 0) {
	if ($checkInterval < 10000)
		$err_message[] = 'Check interval below recommended minimum of 10 seconds.';
	if ($checkInterval > 180000)
		$err_message[] = 'Check interval above recommended maximum of 3 minutes.';
}

## ======================================== ##

/**
 *
 * Check if the specified IP is in the private IP range.
 * @param string $ip
 * @return boolean
 */
function privateIP($ip) {
	if ((($ip >= "10.0.0.0") && ($ip <= "10.255.255.255")) ||
			(($ip >= "192.168.0.0") && ($ip <= "192.168.255.255")) ||
			(($ip >= "172.16.0.0") && ($ip <= "172.31.255.255"))) {
		return true;
	}
	return false;
}