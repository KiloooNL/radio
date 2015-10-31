<?php
$songID = (int)$_GET["songID"];

// The class handling song info
include_once('classes/class.song.php');

// The song object specified by the songID
$songChanged = Song::checkSongChange($songID);