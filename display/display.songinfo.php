<?php header('Content-type: text/html; charset=ISO-8859-1');  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title><?php echo STATION_NAME; ?> - Track Information</title>
		<link rel="shortcut icon" href="favicon.ico" />
		<!-- Styling of the samPHPweb pages -->
		<link rel="stylesheet" type="text/css" href="styles/style.css" />
		<!-- Songinfo Page specific styles -->
		<link rel="stylesheet" type="text/css" href="styles/songinfo.css" />

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
		<!-- JQuery library to do cool Javascript stuff -->
		<script type="text/javascript" src="js/jquery-1.4.2.js"></script>
		<!-- JQuery plugin for Cross-Browser compatible rounding of corners -->
		<script type="text/javascript" src="js/jquery.corner.js"></script>
	</head>

	<body>

		<!-- BEGIN:PAGE -->
		<div id="page">
			<div id="pictureAndLinks">
				<?php if(SHOW_PICTURES && !empty($song->picture)) : ?>  <a href="<?php echo $song->buycd; ?>" target="_blank"><img class="picture" id="picture" onload="showPicture(this, <?php echo SHOW_PICTURES; ?>)" src="<?php echo $song->picture; ?>" alt="Buy CD!" border=0 /></a><?php endif; ?>
			</div>
			<h1>Track Information</h1>

			<dl>
				<dt>Title</dt>
				<dd><?php echo $song->title; ?></dd>
				<dt>Artist</dt>
				<dd><a href="playlist.php?search=<?php echo $song->artist; ?>" title="Click to search for <?php echo $song->artist; ?>" target="_blank"><?php echo $song->artist; ?></a></dd>
				<dt>Album</dt>
				<dd><a href="playlist.php?search=<?php echo $song->album; ?>" title="Click to search for <?php echo $song->album; ?>" target="_blank"><?php echo $song->album; ?></a></dd>
				<dt>Duration</dt>
				<dd><?php echo $song->durationDisplay; ?></dd>
				<dt>Year</dt>
				<dd><?php echo $song->albumyear; ?></dd>
				<dt>Genre</dt>
				<dd><?php echo $song->genre; ?></dd>
				<dt>Links</dt>
				<dd>
				  <a href="<?php echo $song->website; ?>" target="_blank"><img class="request" src="images/home.png" alt="More artist details" title="More artist details" border="0" /></a>
				  <a href="<?php echo $song->buycd; ?>" target="_blank"><img class="buy" src="images/buy.png" alt="Buy this CD or Track now!" title="Buy this CD or Track now!" border="0" /></a>
				  <?php if (ALLOW_REQUESTS) : ?>
				  	<a href="javascript:request(<?php echo $song->ID; ?>);"><img class="request" src="images/request.png" alt="Request this track now!" title="Request this track now!" border="0" /></a>
				  <?php endif; ?>
				</dd>
				<?php if (!empty($song->lyrics)) : ?>
					<dt>Lyrics</dt>
					<dd class="broad"><pre><?php echo $song->lyrics; ?></pre></dd>
				<?php endif; ?>
				<?php if (!empty($song->info)) : ?>
					<dt>Information</dt>
					<dd class="broad"><?php echo $song->info; ?></dd>
				<?php endif; ?>
			</dl>

			<div class="spacer"></div>

			<?php require_once('display.footer.php'); ?>

		</div>
		<!-- END:PAGE -->

	</body>
</html>

