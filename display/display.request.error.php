<?php header('Content-type: text/html; charset=ISO-8859-1');  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>Request Failed</title>
		<link rel="shortcut icon" href="favicon.ico" />
		<!-- General styles of the samPHPweb pages -->
		<link rel="stylesheet" type="text/css" href="styles/style.css" />
		<!-- Request Error page specific styles -->
		<link rel="stylesheet" type="text/css" href="styles/request.error.css" />
	</head>

	<body>

		<!-- BEGIN:PAGE -->
		<div id="page">
			<h1>Request</h1>
			<h2>Your request failed:</h2>
			<h2 class="error"><?php echo $message; ?></h2>

			<?php require_once('display.footer.php'); ?>

		</div>
		<!-- END:PAGE -->

	</body>
</html>

