<?php

class Song {

	private static $songs = array();
	private static $playlistSongCount;

	public $ID;
	public $songtype;
	public $date_played;
	public $duration;
	public $durationDisplay;
	public $artist;
	public $title;
	public $artist_title;
	public $album;
	public $albumyear;
	public $genre;
	public $website;
	public $buycd;
	public $lyrics;
	public $info;
	public $picture;
	public $haspicture = false;
	public $requestID;
	public $listeners;
	public $cnt;
	public $isDedication = false;
	public $isRequested;
	public $dedicationName;
	public $dedicationMessage;

	public static function getComingSongs() {
		$db = Database::getInstance();

		// Return tracks in the queuelist table
		$select = $db->select()
					 ->from(array('s' => 'songlist'),
							array('*'))
					 ->join(array('q' => 'queuelist'),
							'q.songID = s.ID',
							array('requestID'))
					 ->where('s.songtype = ?', 'S') //Only return song of type S
					 ->order('q.sortID ASC')
					 ->limit(COMING_UP_COUNT);

		$songs = array();
		try {
			$songs = $db->fetchAll($select);
		} catch (Zend_Db_Adapter_Exception $ex) {
			echo "Please verify database settings.<br />";
			exit;
		}

		$comingSongs = array();
		foreach ($songs as $songKey => $song) {
			$comingSongs[$songKey] = new self();
			$comingSongs[$songKey]->setValues($song);
		}
		return $comingSongs;
	}

	public static function getCurrentSong() {
		if (count(self::$songs) == 0) {
			self::getRecentSongs();
		}

		reset(self::$songs);
		return current(self::$songs);
	}

	public static function getRecentSongs() {
		if (count(self::$songs) == 0) {
			$db = Database::getInstance();

			// Return songs from the history table;
			$select = $db->select()
						 ->from(array('s' => 'songlist'),
								array('*'))
						 ->join(array('h' => 'historylist'),
								'h.songID = s.ID',
								array('listeners', 'requestID', 'starttime' => 'date_played'))
						 ->joinLeft(array('r' => 'requestlist'),
								'r.id = h.requestID',
								array('dedicationName' => 'name',
									  'dedicationMessage' => 'msg'))
						 ->where('s.songtype = ?', 'S') //Only return song of type S
						 ->order('h.date_played DESC')
						 ->limit(HISTORY_COUNT + 1);

			$songs = array();
			try {
				$songs = $db->fetchAll($select);
			} catch (Zend_Db_Adapter_Exception $ex) {
				echo "Please verify database settings.<br />";
				exit;
			}

			foreach ($songs as $songKey => $song) {
				self::$songs[$songKey] = new self();
				self::$songs[$songKey]->setValues($song);
			}
		}
		return array_slice(self::$songs, 1, HISTORY_COUNT);
	}

	public static function getTopRequestedSongs() {
		$db = Database::getInstance();

		try {
			$sub_select = $db->select()
				 ->from(array('r' => 'requestlist'),
						array('cnt' => 'count(songID)'))
				 ->where('s.ID = r.songID')
				 ->where('r.code = 200')
				 ->group(array('r.songID'));

			$select = $db->select()
						 ->distinct(true)
						 ->from(array('s' => 'songlist'),
								array('ID', 'songtype', 'date_played', 'duration',
										'artist', 'title', 'album', 'albumyear',
										'genre', 'website', 'buycd', 'picture',
										'cnt' => new Zend_Db_Expr('('.$sub_select->__toString().')')))
						 ->join(array('r' => 'requestlist'),
								'r.songID = s.ID',
								array())
						 ->where('r.code = 200');

			switch (get_class($db)) {
				case 'Zend_Db_Adapter_Pdo_Mssql':
				case 'Zend_Db_Adapter_Sqlsrv':
					$select->where('DATEDIFF(day, r.t_stamp, GETDATE()) <= ?', REQUEST_DAYS);
					break;
				case 'ZendX_Db_Adapter_Firebird':
					$select->where('DATEDIFF(day, r.t_stamp, CURRENT_DATE) <= ?', REQUEST_DAYS);
					break;
				case 'Zend_Db_Adapter_Mysqli' :
					$select->where('DATEDIFF(CURRENT_DATE, r.t_stamp) <= ?', REQUEST_DAYS);
					break;
				case 'Zend_Db_Adapter_Pdo_Pgsql':
				default:
					$select->where('(CURRENT_DATE - CAST(r.t_stamp AS DATE)) <= ?', REQUEST_DAYS);
			}

			$select->order('cnt DESC')
				   ->limit(TOP_REQUEST_COUNT);

			$songs = $db->fetchAll($select);
		} catch (Zend_Db_Adapter_Exception $ex) {
			echo "Please verify database settings.<br />";
			exit;
		}

		$topRequestedSongs = array();
		foreach ($songs as $songKey => $song) {
			$topRequestedSongs[$songKey] = new self();
			$topRequestedSongs[$songKey]->setValues($song);
		}
		return $topRequestedSongs;
	}

	public static function getPlaylistSongs($search_words, $sort_letter, $start, $limit, $search_fields) {
		//Set some bounds
		if ($start <= 0) {
			$start = 0;
		}

		if ($limit <= 5) {
			$limit = 5;
		}
		
		//Set search filters
		$doSearchTitle = (!$search_fields || $search_fields == 't');
		$doSearchArtist = (!$search_fields || $search_fields == 'a');
		$doSearchGenre = (!$search_fields || $search_fields == 'g');
		$doSearchAlbum = !$search_fields;

		$db = Database::getInstance();

		$select_where = $db->select();
		$select_where->where('songtype = ?', 'S')
					 ->where('status = ?', 0);
					 
		/* ORIGINAL CODE
		
		if (is_array($search_words)) {
			reset($search_words);
			while (list($key, $val) = each($search_words)) {
				$val = "%$val%";
				
				if($doSearchTitle)
					$whereFields[] = $db->quoteInto('(title like ?)', $val);
				if($doSearchArtist)
					$whereFields[] = $db->quoteInto('(artist like ?)', $val);
				if($doSearchAlbum)
					$whereFields[] = $db->quoteInto('(album like ?)', $val);
				
				$orWhere[] = implode(' OR ', $whereFields);
			}

			$select_where->where(implode(' OR ', $orWhere));
		}
		*/
		
		if (is_array($search_words)) {
			reset($search_words);
			while (list($key, $val) = each($search_words)) {
			
				// $val = search string
				// Check if string contains one word, or multiple.
				// If only one word in the string is present, use % for wildcard around $val to help expand results.
				$val = "$val%";
				if (preg_match("/ /", $val)) {
					$val = "%$val%";
				} else {
					$val = "%$val%";
				}
				
				if($doSearchTitle)	
					$whereFields[] = $db->quoteInto('(title like ?)', $val);
				if($doSearchArtist)
					$whereFields[] = $db->quoteInto('(artist like ?)', $val);
				if($doSearchAlbum)
					$whereFields[] = $db->quoteInto('(album like ?)', $val);
				if($doSearchGenre)
					$whereFields[] = $db->quoteInto('(genre like ?)', $val);
					
				$orWhere[] = implode(' OR ', $whereFields);
			}

			$select_where->where(implode(' OR ', $orWhere));
		}

		if ($sort_letter == '0') {
			$select_where->where($db->quoteInto('NOT((artist>=?)', 'A') . ' AND ' . $db->quoteInto('(artist<?))', 'ZZZZZZZZZZZ'));
		} elseif ($sort_letter != '') {
			$nextletter = chr(ord($sort_letter) + 1);
			$select_where->where($db->quoteInto('(artist>=?)', $sort_letter) . ' AND ' . $db->quoteInto('(artist<?)', $nextletter));
		}

		//Calculate total
		$total_select = clone $select_where;
		$total_select->from('songlist',
							array('cnt' => 'count(*)'));

		try {
			$row = $db->fetchRow($total_select);
		} catch (Zend_Db_Adapter_Exception $ex) {
			echo "Please verify database settings.<br />";
			exit;
		}

		self::$playlistSongCount = $row['CNT'];

		//Now grab a section of that
		$playlist_select = $select_where;
		$playlist_select->from('songlist')
						->order(array('artist ASC', 'title ASC'))
						->limit($limit, $start);

		try {
			$rows = $db->fetchAll($playlist_select);

			$songs = array();
			foreach ($rows as $key => $row) {
				$songs[$key] = new self();
				$songs[$key]->setValues($row);
			}
		} catch (Zend_Db_Adapter_Exception $ex) {
			echo "Please verify database settings.<br />";
			exit;
		}
		return $songs;
	}

	public static function checkSongChange($songID) {
		$db = Database::getInstance();

		try {
			$select = $db->select()
						 ->from(array('s' => 'historylist'),
								array('songID'))
						 ->order('date_played DESC')
						 ->limit(1);

			$song = $db->fetchRow($select);
			if ($song["SONGID"] <> $songID) {
				return true;
			} else {
				return false;
			}
		} catch (Zend_Db_Adapter_Exception $ex) {
			echo "<!-- Please verify database settings. -->";
			exit;
		}
	}

	public static function getPlaylistSongCount() {
		return self::$playlistSongCount;
	}

	public static function getSong($songID) {
		$db = Database::getInstance();

		try {
			$select = $db->select()
						 ->from(array('s' => 'songlist'))
						 ->where('ID = ?', $songID);
			$row = $db->fetchRow($select);

			if (!is_null($row)) {
				$song = new self();
				$song->setValues($row);
			}
		} catch (Zend_Db_Adapter_Exception $ex) {
			echo "Please verify database settings.<br />";
			exit;
		}
		return $song;
	}

	public static function getRequestedSong($requestID) {
		$db = Database::getInstance();
		try {
			$select = $db->select()
						 ->from(array('s' => 'songlist'),
								array('s.*',
									  'songID' => 's.ID',
									  'requestID' => 'r.ID',))
							->join(array('r' => 'requestlist'),
										 's.ID = r.songID',
										 array('dedicationName' => 'name',
											   'dedicationMessage' => 'msg'))
							->where('r.ID = ?', $requestID);

			$row = $db->fetchRow($select);
			if (!is_null($row)) {
				$song = new self();
				$song->setValues($row);
			}
		} catch (Zend_Db_Adapter_Exception $ex) {
			echo "Please verify database settings.<br />";
			exit;
		}
		return $song;
	}

	public function setValues($songinfo) {
		$properties = get_class_vars(get_class($this));
		$methods = get_class_methods($this);
		unset($methods[array_search('setValues', $methods)]);

		//use set_xxx methods to set object properties
		foreach ($methods as $method) {
			preg_match("/^set_(.*)/", $method, $matches);
			if (count($matches) > 0) {
				$this->$method($songinfo);
			}
		}

		//Now set the remaining properties that matches they key of the songinfo array
		foreach ($properties as $propertyKey => $propertyValue) {
			$propertyKeyUpper = strtoupper($propertyKey);
			if (isset($songinfo[$propertyKeyUpper])
					&& !is_null($songinfo[$propertyKeyUpper])
					&& is_null($this->$propertyKey)) {
				$this->$propertyKey = $songinfo[$propertyKeyUpper];
			}
		}
		return $this;
	}

	protected function set_date_played($song) {
		if ($song['DATE_PLAYED'] instanceof DateTime) {
			$date_played = $song['DATE_PLAYED']->format('Y-m-d H:i:s');
		} else {
			$date_played = $song['DATE_PLAYED'];
		}

		$this->date_played = $date_played;
	}

	protected function set_buycd($song) {
		if (empty($song['BUYCD'])) {
			$data = 'http://audiorealm.com/findcd.html?artist=#artist#&title=#title#&album=#album#';
			$buycd = FillData($song, $data);
		} else {
			$buycd = $song['BUYCD'];
		}

		$this->buycd = $buycd;
	}

	protected function set_website($song) {
		$website = null;
		//Make a link to search for the artist homepage
		if (empty($song['WEBSITE'])) {
			$data = 'http://audiorealm.com/findwebsite.html?artist=#artist#&title=#title#&album=#album#';
			$website = FillData($song, $data);
		} else {
			$website = trim($song['WEBSITE']);
			preg_match('/^((http[s]?):\/\/)?/', $website, $matches);
			if (empty($matches[0])) {
				$website = 'http://' . $website;
			}
		}
		$this->website = $website;
	}

	protected function set_picture($song) {
		$picture = PICTURE_URL_NA;
		if (!empty($song['PICTURE'])) {
			$picture = PICTURE_URL . $song['PICTURE'];
			$this->haspicture = true;
		}

		$this->picture = $picture;
	}

	protected function set_artist_title($song) {
		//Make Artist+Tile combination
		if (!empty($song['ARTIST']) && !empty($song['TITLE'])) {
			$artist_title = $song['ARTIST'] . ' - ' . $song['TITLE'];
		} elseif (!empty($song['TITLE'])) {
			$artist_title = $song['TITLE'];
		} else {
			//If both Artist and Title is empty, use filename
			$path_parts = pathinfo($song['FILENAME']);
			$artist_title = $path_parts['filename']; //Requires PHP 5.2.0
		}

		$this->artist_title = $artist_title;
	}

	protected function set_album($song) {
		$this->album = $song['ALBUM'];
	}

	protected function set_durationDisplay($song) {
		$dur = $song["DURATION"];

		if (is_numeric($dur) && $dur > 0) {
			$ss = round($song["DURATION"] / 1000);
			$mm = (int) ($ss / 60);
			$ss = ($ss % 60);
			if ($ss < 10) {
				$ss = "0$ss";
			} $durDisplay = "$mm:$ss";
		} else {
			$durDisplay = "";
		}
		$this->durationDisplay = $durDisplay;
	}

	protected function set_isDedication($song) {
		$isDedication = false;
		if (isset($song['REQUESTID']) && $song['REQUESTID'] > 0) {
			if (!empty($song['DEDICATIONNAME'])) {
				$isDedication = true;
				$this->dedicationName = $song['DEDICATIONNAME'];
				$this->dedicationMessage = $song['DEDICATIONMESSAGE'];
			}
		}
		$this->isDedication = $isDedication;
	}

	protected function set_isRequested($song) {
		$isRequested = false;
		if (isset($song['REQUESTID']) && $song['REQUESTID'] > 0) {
			$this->requestID = $song['REQUESTID'];
			$isRequested = true;
		}
		$this->isRequested = $isRequested;
	}

}