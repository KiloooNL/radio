<?php header('Content-type: text/html; charset=ISO-8859-1');  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>Request Successfully Processed</title>
		<link rel="shortcut icon" href="favicon.ico" />
		<!-- Styling of the samPHPweb pages -->
		<link rel="stylesheet" type="text/css" href="styles/style.css" />
		<!-- Request specific styling -->
		<link rel="stylesheet" type="text/css" href="styles/request.css" />
		<!-- Common Javascript functions -->
		<script type="text/javascript" src="js/common.js"></script>
		<!-- JQuery library to do cool Javascript stuff -->
		<script type="text/javascript" src="js/jquery-1.4.2.js"></script>
		<!-- JQuery plugin for Cross-Browser compatible rounding of corners -->
		<script type="text/javascript" src="js/jquery.corner.js"></script>
	</head>

	<body>

		<!-- BEGIN:PAGE -->
		<div id="page">

			<h2 class="success">Request successful and should play shortly.</h2>

			<div id="pictureAndLinks">
				<?php if(SHOW_PICTURES && !empty($song->picture)) : ?>  <a href="<?php echo $song->buycd; ?>" target="_blank"><img class="picture" onload="showPicture(this, true)" src="<?php echo $song->picture; ?>" alt="Buy CD!" border=0 /></a><?php endif; ?>
			</div>
			<h1>Track Information</h1>

			<dl>
				<dt>Title</dt>
				<dd><?php echo $song->title; ?></dd>
				<?php if(!empty($song->artist)) : ?>
					<dt>Artist</dt>
					<dd><?php echo $song->artist; ?></dd>
				<?php endif; ?>
				<?php if(!empty($song->album)) : ?>
					<dt>Album</dt>
					<dd><?php echo $song->album; ?></dd>
				<?php endif; ?>
				<dt>Links</dt>
				<dd>
				  <a href="<?php echo $song->website; ?>" target="_blank"><img class="request" src="images/home.png" alt="More artist details" title="More artist details" border="0" /></a>
				  <a href="<?php echo $song->buycd; ?>" target="_blank"><img class="buy" src="images/buy.png" alt="Buy this CD or Track now!" title="Buy this CD or Track now!" border="0" /></a>
				</dd>

				<dt>Dedications</dt>
				<dd class="broad">
					<?php
					if ($song->isDedication):
						$rmessage = stripslashes($_REQUEST['rmessage']);
						$dedicationMessage = Text2Html(trim($rmessage));
						$dedicationName = stripslashes($_REQUEST['rname']);
					?>
					<div id="dedicationMessage">"<?php echo $dedicationMessage; ?>"</div>
					<div id="dedicationName">Dedicated by <?php echo $dedicationName; ?></div>

					<?php else: ?>
						<form method="post">
						<?php InputHidden("requestID", $song->requestID, 0); ?>
						<?php InputHidden("songID", $song->ID, 0); ?>
							Your name:<br/>
							<input type="text" name="rname" size="30"/><br/>
							Your dedication message:<br/>
							<textarea rows="4" name="rmessage" cols="24"></textarea><br/>
							<input type="submit" value="Dedicate it!" name="B1" />
						</form>
						<br />
						<br />
						Note: Your dedication will show up on the "Now playing" page of the website as soon as your requested track is played. The DJ might also read your dedication over the air.
					<?php endif; ?>
				</dd>
			</dl>

			<div class="spacer"></div>
			<?php require_once('display.footer.php'); ?>
		</div>
		<!-- END:PAGE -->

	</body>
</html>