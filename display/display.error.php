<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title><?php echo STATION_NAME; ?> - Error</title>
		<link rel="shortcut icon" href="favicon.ico" />
		<!-- General styles of the samPHPweb pages -->
		<link rel="stylesheet" type="text/css" href="styles/style.css" />

		<!-- JQuery library to do cool Javascript stuff -->
		<script type="text/javascript" src="js/jquery-1.4.2.js"></script>
		<!-- JQuery plugin for Cross-Browser compatible rounding of corners -->
		<script type="text/javascript" src="js/jquery.corner.js"></script>
		<!-- Javascript using JQuery to round some of the HTML items on the page -->
		<script type="text/javascript">
			//<![CDATA[
			// Make sure the DOM is ready
			$(document).ready(function() {
				// Rounding of corners (Cross-browser compatible)
				// See http://jquery.malsup.com/corner/ for different Styles.
				$('#page').corner();
			});
			//]]>

		</script>
	</head>

	<body>

		<!-- BEGIN:PAGE -->
		<div id="page">


			<!-- BEGIN:LOGO -->
			<div id="logo">
				<a href="./">
					<img src="<?php echo STATION_LOGO; ?>"/>
					<?php echo STATION_NAME; ?>
				</a>
			</div>
			<!-- END:LOGO -->


			<div id="content" class="error">

				<h1>Oops! Something went wrong...</h1>
				<h2><?php echo $message; ?></h2>
			</div>


			<?php require_once('display.footer.php'); ?>

		</div>
		<!-- END:PAGE -->

	</body>
</html>

