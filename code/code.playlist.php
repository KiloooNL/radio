<?php

// The class handling song info
include_once('classes/class.song.php');

if (ALLOW_REQUESTS) {
	// An array of song objects with the top requested songs
	$topRequestedSongs = Song::getTopRequestedSongs();
}

$start = Def('start', 0);	// Where the playlist must start
$limit = Def('limit', 25);	// How many items will be displayed
$search = Def('search');	// The search string
$character = Def('character'); // The letter to sort the playlist by
$search_fields = Def('fields'); // The fields to search in
if ("All" == $character) {
	unset($character);
}


//########## BUILD SEARCH STRING ################
$search_words = '';
if ($search <> '') {
	$search_words = array();
	$search = stripslashes($search);
	$quote = explode('"', $search);
	reset($quote);
	while(list($qkey, $qval) = each($quote)) {
		if($qkey & 1) {
			// Exact match
			$val = trim($qval);
			if (!empty($val)) {
				$search_words[] = $val;
			}
		}
		else {
		/* ---------------------
		This section fixes the 'bug' of searching inside every ID3 tag
		for requested song (string). And instead does the above (code).
		
		
		TODO:
		Change support from exact to partial but NOT like this:
		$whereFields[] = $db->quoteInto('(title like ?)', $val);
		
		*/
			$val = trim($qval);
			if (!empty($val)) {
				$search_words[] = $val;
			} else {
				$search_words[] = $val;
			}
			/* List of words
			$temp = explode(' ', $qval);
			reset($temp);
			while (list($qkey, $qval) = each($temp)) {
				$val = trim($qval);
				if (!empty($val)) {
					$search_words[] = $val;
				}
			}*/
		}
	}
}

// An array of song objects matching the search criteria

$playlistSongs = Song::getPlaylistSongs($search_words, $character, $start, $limit, $search_fields);
$cnt = Song::getPlaylistSongCount();
//########## =================== ################
$first = $start + 1;
$last = min($cnt, $start + $limit);

// Create the previous and next links based on the result
if ($cnt > 0) {
	$searchstr = urlencode($search);
	$q1;
	$q2;
	$prev = max(0, $start - $limit);
	
	if ($start > 0) {
		$prevlnk = "<a href='?start=$prev&limit={$limit}&character=$character&search=$searchstr&fields=$search_fields'>&lt;&lt; Previous</a>";
	}

	$tmp = ($start + $limit);
	if ($tmp < $cnt) {
		$nextlnk = "<a href='?start=$tmp&limit={$limit}&character=$character&search=$searchstr&fields=$search_fields'>Next &gt;&gt;</a>";
	}
}