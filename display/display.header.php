<?php header('Content-type: text/html; charset=ISO-8859-1');  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>DJ Kilooo'z Radio<?php  if(isset($currentSong->artist && $currentSong->title)) { echo ' | '.$currentSong->artist." - ".$currentSong->title;  } ?></title>
		<meta charset="UTF-8">
		<link rel="shortcut icon" href="/favicon.png" />
		<!-- General styles of the samPHPweb pages -->
		<link rel="stylesheet" type="text/css" href="styles/style.css" />
		<link href="/radio/menu_style.css" rel="stylesheet" type="text/css" media="screen" />

		<!-- Common Javascript functions -->
		<script type="text/javascript" src="js/common.js"></script>
		<?php if (ALLOW_REQUESTS) : ?>
		<!-- Javascript for request and songinfo actions -->
		<script type="text/javascript">
			/**
			 * Open a popup window to send a song request to SAM
			 */
			function request(songID) {
				<?php if(PRIVATE_REQUESTS): ?>
					requestPrivate(songID);
				<?php else: ?>
					var samhost = "<? echo SAM_HOST; ?>";
					var samport = "<? echo SAM_PORT; ?>";
					requestAudioRealm(songID, samhost, samport);
				<?php endif; ?>
			}
		</script>
		<?php endif; ?>
		<!-- AddThis javascript -->
		<script type="text/javascript" src="js/addthis.js"></script>
		<!-- JQuery library to do cool Javascript stuff -->
		<script type="text/javascript" src="js/jquery-1.4.2.js"></script>
		<!-- JQuery plugin for Cross-Browser compatible rounding of corners -->
		<script type="text/javascript" src="js/jquery.corner.js"></script>
	</head>

	<body>
		<!-- BEGIN:PAGE -->
		<div id="page">
			<div id="logo">
				<a href="./">
					<img src="<?php echo STATION_LOGO; ?>"/>
					<?php //echo STATION_NAME; ?>
				</a>
				<?php 
				/*********************************************************
				************* RE ENABLED THE MENU ITEM HERE *************/
				/*******************************************************/
				$current_menu_item=1;
				//include("../../menu.php"); 
				?>
			</div>

			<!-- ERROR_MESSAGE -->
			<?php if(isset($err_message) && is_array($err_message) && count($err_message)>0) : ?>
			<div id="err_message" class="error">
				<?php foreach($err_message as $errmsg) { echo '<div>'.$errmsg.'</div>'; } ?>
			</div>
			<?php endif; ?>

			<div id="navigation">
				<dl>
					<dt>Menu</dt>
					<!--<dd><a href="javascript:player(<php echo STATION_ID; >)" title="Click to Listen"><img src="images/menu/speaker.png" /> Listen</a></dd>-->
					<dd><a href="playing.php" title="Now playing"><img src="images/menu/play.png" /> Now playing</a></dd>
					<dd><a href="playlist.php" title="Playlist &amp; Requests"><img src="images/menu/tb-file-list.png" />&nbsp;&nbsp;Playlist<?php if (ALLOW_REQUESTS) : ?> &amp; Requests<?php endif; ?></a></dd>
					<?php
						$station_email = constant('STATION_EMAIL');
						if (!empty($station_email) && !is_null($station_email)) :
					?>
							<dd><a href="mailto:<?php echo $station_email ?>" title="Email us!"><img src="images/menu/email.png" /> Email us!</a></dd>
					<?php endif; ?>
				</dl>
			</div>
			<?php if (ALLOW_REQUESTS && SHOW_TOP_REQUESTS && is_array($topRequestedSongs) && count($topRequestedSongs) > 0): ?>
			<div id="top_requests">
				<dl>
					<dt>Top Requested</dt>
					<?php
						  $counter = 1;
						  foreach ($topRequestedSongs as $topRequestedSong): ?>
						<dd>
							<a href="javascript:songinfo(<?php echo $topRequestedSong->ID; ?>)" title="<?php echo $topRequestedSong->artist_title; ?>">
								<?php echo $counter++;?>. <?php echo $topRequestedSong->title; ?>
								<?php if(!empty($topRequestedSong->artist)) : ?><br />&nbsp;&nbsp;&nbsp;&nbsp;by  <?php echo $topRequestedSong->artist; ?><?php endif; ?>
								(<?php echo $topRequestedSong->cnt; ?>)
							</a>
						</dd>
					<?php endforeach; ?>
				</dl>
			</div>
			<?php endif; ?>
			
			<?php if(SHOW_TOP_TRACKS && is_array($topPlayedSongs) && count($topPlayedSongs) > 0): ?>
			<div id="top_requests">
				<dl>
					<dt>Most Played Tracks</dt>
					<?php
						$counter = 1;
						foreach ($topPlayedSongs as $topPlayedSong): ?>
					<dd>							
						<a href="javascript:songinfo(<?php echo $topPlayedSong->ID; ?>)" title="<?php echo $topPlayedSong->artist_title; ?>">
						<?php echo $counter++;?>. <?php echo $topPlayedSong->title; ?>
						<?php if(!empty($topPlayedSong->artist)) : ?><br />&nbsp;&nbsp;&nbsp;&nbsp;by  <?php echo $topPlayedSong->artist; ?><?php endif; ?>
						(<?php echo $topPlayedSong->count_played; ?>)
						</a>
					</dd>
					<?php endforeach; ?>
				</dl>
			</div>
			
			<?php endif; ?>
			
			<div id="partner-links">
			<!--<a href="http://audiorealm.com" title="AudioRealm Network Station" target="_blank"> <img src="/radio/web/images/AudioRealmBadge.png" title="AudioRealm Network Station" border="0" /> </a>
				<br /><br />
				<a href="http://spacial.com/sam-broadcaster" title="Powered by SAM Broadcaster" target="_blank"> <img src="/radio/web/images/SAM-BC-BUTTON.gif" title="Powered by SAM Broadcaster" border="0" /> </a>
				<br /><br />-->
			</div>