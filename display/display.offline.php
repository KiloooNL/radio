<?php require_once('display.header.php'); ?>

		<?php if ($currentSong instanceof Song) : ?>

			<!-- BEGIN:CURRENTLY PLAYING -->
			<div id="currently_playing_wrapper">
				<div id="currently_playing">
					<table cellpadding="0" cellspacing="0">
						<thead>
							<tr>
								<th align="left" style="width: 170px;">
								</th>
								<th align="center">
								</th>
								<th align="left">
								</th>
								<th align="right" style="width: 50px;">
								</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td align="center">
								</td>
								<td colspan="2">
									<span id="currently-playing-title">DJ Kilooo'z Radio is offline or no DJ is currently broadcasting. <br />Contact the <a href="mailto:sktfreak-klz-@hotmail.com">system admin</a> or ask a DJ to broadcast</span>
									<br />
								</td>
								<td align="right">
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<!-- END:CURRENTLY PLAYING -->
		<?php endif; ?>


			<?php if(is_array($comingSongs) && count($comingSongs)>0) : ?>
			<!-- BEGIN:COMING UP -->
			<div id="coming-up_wrapper">
				<div id="coming-up">
					<table cellpadding="0" cellspacing="0">
						<thead>
							<tr>
								<th colspan="1" align="left">
									Coming up
								</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<?php
										$counter = 1;
										$doCounter = count($comingSongs) > 1;
										foreach ($comingSongs as $comingSong): ?>
										<div>
											<?php if($doCounter) : ?><span class="comingIndex"><?php echo $counter++;?></span><?php endif; ?>
											<a href="javascript:songinfo(<?php echo $comingSong->ID; ?>)" title="<?php echo $comingSong->artist_title; ?>">
												<?php if(!empty($comingSong->artist)) : ?><?php echo $comingSong->artist; ?><?php endif; ?> - <?php echo $comingSong->title; ?>
											</a>
										<?php if($comingSong->isRequested): ?>
											~requested~
										<?php endif; ?>
										</div>
									<?php endforeach; ?>
									<hr style="width:100%;border:none;"/>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<!-- END:COMING UP -->
			<?php endif; ?>


			<?php if(is_array($recentSongs) && count($recentSongs)>0) : ?>
			<!-- BEGIN:RECENTLY PLAYED -->
			<div id="recently_played_wrapper">
				<div id="recently_played">
					<table cellpadding="0" cellspacing="0">
						<thead>
							<tr>
								<th colspan="2" align="left">
									Recently Played
								</th>
								<th class="links" align="center">
									Links
								</th>
								<th align="left">
									Album
								</th>
								<th align="right">
									Time
								</th>
							</tr>
						</thead>
						<tbody>
						<?php foreach ($recentSongs as $key => $recentSong): ?>
							<tr>
								<td>
									<?php if(SHOW_PICTURES && !empty($recentSong->picture)) : ?>
									<a href="<?php echo $recentSong->buycd; ?>" target="_blank">
										<img class="rpPicture" id="rpPicture<?php echo $key; ?>" onload="showPicture(this, <?php echo SHOW_PICTURES; ?>)" width="60" height="60" src="<?php echo $recentSong->picture; ?>" alt="Buy CD!" title="Buy CD!" />
									</a>
									<?php endif; ?>
								</td>

								<td>
									<?php echo $recentSong->artist_title; ?>
									<?php if ($recentSong->isRequested): ?>
									~requested~
									<?php endif; ?>
									<br />
									<br /><font size="1">Year: <span id="currently-playing-artist"><?php if(empty($recentSong->albumyear)) { echo "Unknown"; } else echo $recentSong->albumyear; ?></span></font>
									<br /><font size="1">Genre: <span id="currently-playing-artist"><?php if(empty($recentSong->genre)) { echo "Other"; } else echo $recentSong->genre; ?></span></font>
								</td>
								<td align="center">
									<a href="<?php echo $recentSong->buycd; ?>" target="_blank">
										<img src="images/buy.png" alt="Buy this CD now!" title="Buy this CD now!" />
									</a>
									<a href="<?php echo $recentSong->website; ?>" target="_blank">
										<img src="images/home.png" alt="Artist homepage" title="Artist homepage" />
									</a>
									<a href="javascript:songinfo(<?php echo $recentSong->ID; ?>)">
										<img src="images/info.png" alt="Track information" title="Track information" />
									</a>
								</td>

								<td>
									<?php echo $recentSong->album; ?>
								</td>
								<td align="right">
									<?php echo $recentSong->durationDisplay; ?>
								</td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
			<!-- END:RECENTLY PLAYED -->
			<?php endif; ?>


		<?php require_once('display.footer.php'); ?>


		</div>
		<!-- END:PAGE -->

		<!-- JQuery to round corners some of the HTML items on the page -->
		<script type="text/javascript">
		//<![CDATA[
		// Make sure the DOM is ready
		$(document).ready(function() {
			// Rounding of corners (Cross-browser compatible)
			// See http://jquery.malsup.com/corner/ for different Styles.

			// Rounds the page corners
			$('#page').corner();

			// Rounds the Navigation Menu Corners
			$('#navigation dl').corner();

			// Rounds the Currently Playing Table Corners
			$('#currently_playing').corner();

			// Rounds the Coming Up Corners
			$('#coming-up').corner();

			// Rounds the Recently Played Table Corners
			$('#recently_played').corner();
			// Style odd and even rows in Currently Playing Table (Cross-browser compatible)
			$('#recently_played table tbody tr:nth-child(odd)').addClass('recently_played_odd');
			$('#recently_played table tbody tr:nth-child(even)').addClass('recently_played_even');

			// Round the Dedication Corners
			$('#dedication dl').corner('tl tr').corner();

			// Round the Top Request Corners
			$('#top_requests dl').corner();
		});
		//]]>
		</script>
	</body>
</html>

