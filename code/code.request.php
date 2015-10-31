<?php

$songID = Def('songID');

settype($songID, 'integer'); //Make sure songID is an integer to avoid SQL injection
if (empty($songID)) {
	throw new Exception('Song ID must be valid');
}

$requestID = Def('requestID', 0);
if ($requestID == 0) { //This is a new request for a song
	$host = $_SERVER["REMOTE_ADDR"];

	$request = "GET /req/?songID=$songID&host=" . urlencode($host) . " HTTP\1.0\r\n\r\n";

	$xmldata = "";
	$fd = @fsockopen(SAM_HOST, SAM_PORT, $errno, $errstr, 30);

	if (!empty($fd)) {
		fputs($fd, $request);
		$line = "";
		while (!($line == "\r\n")) {
			$line = fgets($fd, 128);
		}// strip out the header
		while ($buffer = fgets($fd, 4096)) {
			$xmldata .= $buffer;
		}
		fclose($fd);
	} else {
		throw new Exception('Unable to connect to ' . SAM_HOST . ':' . SAM_PORT . ". Station might be offline.<br>The error returned was $errstr ($errno).");
	}

	if (empty($xmldata)) {
		throw new Exception('Invalid data returned!');
	}

	//#################################
	//	Initialize data
	//#################################
	$tree = XML2Array($xmldata);
	$request = Keys2Lower($tree["REQUEST"]);

	$code = $request["status"]["code"];
	$message = $request["status"]["message"];
	$requestID = $request["status"]["requestid"];

	if (empty($code)) {
		throw new Exception('Invalid data returned!');
	}

	if ($code != 200) {
		throw new Exception($message);
	}
} else { //If a request was already made, allow dedication to the request
	$data = array();
	$data['msg'] = strip_tags(Def('rmessage'));
	$data['name'] = strip_tags(Def('rname'));

	$db = Database::getInstance();
	$db->update('requestlist', $data, array('ID = ?' => $requestID, 'songID = ?' => $songID));
}

// Retrieve details of the requested song from the database.
if ($requestID > 0) {
	require_once('classes/class.song.php');
	$song = Song::getRequestedSong($requestID);
}