<?php
	/* This file gets system stats by trying to visit the sites listed below, and displays
	   whether or not they are online. Feel free to edit this for your own use. 
	   - KiloooNL */
	   
	   
		function Visit($url){
			// $page = $url; will not work because it will echo the code and therefor display the page we are requesting.
			$page = array();
			$agent = "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)";
		    $ch = curl_init();
			curl_setopt ($ch, CURLOPT_URL,$url );
			curl_setopt($ch, CURLOPT_USERAGENT, $agent);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch,CURLOPT_VERBOSE,false);
			curl_setopt($ch, CURLOPT_TIMEOUT, 5);
			curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch,CURLOPT_SSLVERSION,3);
			curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, FALSE);
			$page=curl_exec($ch);
			// echo curl_error($ch);
			$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);
			
			/* Results */
			$sec = $httpcode;
			
			if($page == NULL){ $page = "offline.php"; } // $page would be NULL if there was no reply from the server which indicates it is offline (or timed out)
			else if($page == "File not found") { $page = "playing.php"; }
			else { $page = "Unknown (Error 1: NO PAGE SPECIFIED!)"; }
			
			$msg = "<!--"."\n"."Kilooo'z PHP getRequest v1.2"."\n"."Recieved answer in: $sec seconds"."\n"."Page displayed: $page"."\n"."-->"."\n";
			echo $msg;
			   if($httpcode>=200 && $httpcode<300) return true;
			   else return false;
		}
	   
		/* Usually it is:
		if (Visit("http://192.168.1.146:8000")) {
				//Results();
				require_once("playing.php");
		} 
		However due to the fact I can't always run
		ShoutCAST! it is currently this, instead: */
		if (Visit("http://192.168.1.146")){
				//Results();
				require_once("playing.php");
		} 
		else {
				//Results();
				require_once("offline.php");
		} 
?>