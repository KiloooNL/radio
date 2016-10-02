<?php

// The class handling song info
require_once('classes/class.song.php');

// The currently Playing song object
$currentSong = Song::getCurrentSong();

if (!$currentSong instanceof Song) {
	$err_message[] = 'No track info retrieved, is SAM currently playing?';
}

// An array of song objects with songs that played recently
$recentSongs = Song::getRecentSongs();

// An array of song objects with songs that are in the queue to be played
$comingSongs = Song::getComingSongs();

if (ALLOW_REQUESTS) {
	// An array of song objects with the top requested songs
	$topRequestedSongs = Song::getTopRequestedSongs();
	$topPlayedSongs    = Song::getTopPlayedSongs();
}