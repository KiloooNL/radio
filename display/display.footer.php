		<!-- BEGIN:FOOTER -->
		<div id="footer">
			<!-- Please leave at a minimum one link back to AudioRealm.com or SpacialAudio.com if you appreciate our hard work! - The SpacialAudio Team -->
			<p>
				<?php
				// Use this if you want to display people tuned into the station.
				
				if ($currentSong->listeners == 1) {
					echo "
					<div id=\"listener_count\">There is currently $currentSong->listeners person tuned into DJ Kilooo'z Radio. Tune in now 
						<a href=\"http://$ip:8000/listen.pls?sid=1\"><img src='/images/radio/foobar.png' width='28' height='28' title='Foobar' alt='Foobar' /></a>
						<a href=\"/radio/playlists/RadioStation_wmp.m3u\"><img src='/images/radio/wmp.png' width='28' height='28' title='Windows Media Player' alt='Windows Media Player' /></a>
						<a href=\"/radio/playlists/RadioStation_wmp.m3u\"><img src='/images/radio/winamp.png' width='28' height='28' title='Winamp' alt='Winamp' /></a>
					</div>";
				} if ($currentSong->listeners >= 2) { 
					echo "
					<div id=\"listener_count\">There is currently $currentSong->listeners people tuned into DJ Kilooo'z Radio. Tune in now 
						<a href=\"http://$ip:8000/listen.pls?sid=1\"><img src='/images/radio/foobar.png' width='28' height='28' title='Foobar' alt='Foobar' /></a>
						<a href=\"/radio/playlists/RadioStation_wmp.m3u\"><img src='/images/radio/wmp.png' width='28' height='28' title='Windows Media Player' alt='Windows Media Player' /></a>
						<a href=\"/radio/playlists/RadioStation_wmp.m3u\"><img src='/images/radio/winamp.png' width='28' height='28' title='Winamp' alt='Winamp' /></a>
					</div>";
				} if($currentSong->listeners == 0) {
					echo "
					<div id=\"listener_count\">
						There is currently no-one tuned into DJ Kilooo'z Radio. Tune in now
						<a href=\"http://$ip:8000/listen.pls?sid=1\"><img src='/images/radio/foobar.png' width='28' height='28' title='Foobar' alt='Foobar' /></a>
						<a href=\"/radio/playlists/RadioStation_wmp.m3u\"><img src='/images/radio/wmp.png' width='28' height='28' title='Windows Media Player' alt='Windows Media Player' /></a>
						<a href=\"/radio/playlists/RadioStation_wmp.m3u\"><img src='/images/radio/winamp.png' width='28' height='28' title='Winamp' alt='Winamp' /></a>
					</div>
					
					";
				}
				?>
			<br>
				This station is part of the <a href="http://audiorealm.com">AudioRealm</a> and <a href="http://www.spacialnet.com">SpacialNet</a> networks.<br />
				Website & design by <a href="http://kilooo.dyndns.org/">KiloooNL</a>. &copy; Copyright 2009 - <?php echo date('Y',time()); ?>. 
			</p>
		</div>
		<!-- END:FOOTER -->