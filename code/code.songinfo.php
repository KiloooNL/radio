<?php

// The class handling song info
include_once('classes/class.song.php');

$songID = Def('songID');

// The song object specified by the songID
$song = Song::getSong($songID);