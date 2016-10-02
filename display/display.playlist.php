<?php require_once('display.header.php'); ?>
		
			<!-- BEGIN:SEARCH -->
			<div id="search">
			<form method="get" action="playlist.php" name="searchParameters">
			Search: <?php InputText('search', $search, '',20); ?>
			by: <?php InputCombo('fields', $search_fields, "0", "All,Title,Artist,Album,Genre", '0,t,a,album,g', ""); ?>
			<input type="submit" value="Go" name="B1" />
			Display <?php InputCombo('limit', $limit, 25, '5,10,25,50,100', "", "document.forms.searchParameters.submit();"); ?> results

				<hr/>
				Search by Artist:<br />
				<table>
					<tbody>
						<tr>
							<td>
								<input type="submit" name="character" class="characterButton" value="All" onclick="document.forms.searchParameters.search.value=''"/>
							</td>
							<td>
								<input <?php echo "0 - 9" == $character? "id='activeCharacter'" : "";?> type="submit" name="character" class="characterButton"value='0 - 9'/>
							</td>

							<?php
							for($charVal = ord('A');$charVal <= ord('Z'); $charVal++) {
								$c = chr($charVal);
								echo "<td>";
								echo "<input ".($character == $c? "id='activeCharacter'" : "")." type='submit' name='character' class='characterButton' value='$c' onclick='document.forms.searchParameters.search.value=\"\"' />";
								echo "</td>";
							}
							?>
						</tr>
					</tbody>
				</table>
				</form>
				<br />
			</div>
			<!-- END:SEARCH -->

			<!-- BEGIN:PLAYLIST -->
			<div id="playlist">
				<div id="playlist_wrapper">
					<table  cellpadding="0" cellspacing="0">
						<thead>
							<tr>
								<th colspan="3" align="left">
									Playlist results
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
						<tr>
							<td colspan="6" id="td-playlist-paging">
							<?php if(is_array($playlistSongs) && (count($playlistSongs)>0)) { ?>
								<?php if($start > 0) { 
								echo $prevlnk; 
								} ?>
								&nbsp;[ Showing <?php echo "$first to $last of $cnt"; ?> ] &nbsp;
								<?php 
								// FIX THIS!!!
								if(($start+$limit) < $cnt) { 
								echo $nextlnk; 
								} ?>
							<?php }?>
							</td>
						</tr>
						<?php
						 if(is_array($playlistSongs) || (count($playlistSongs)>0))
						  foreach ($playlistSongs as $key => $playlistSong): ?>
							<tr>
								<td>
									<?php echo $key+1; ?>
								</td>
								<td>
									<?php if(!empty($playlistSong->picture)) : ?>
									<a href="javascript:request(<?php echo $playlistSong->ID; ?>);">
										<img id="rpPicture<?php echo $key; ?>" style="display: none;" onload="showPicture(this, <?php echo SHOW_PICTURES; ?>)" width="60" height="60" src="<?php echo $playlistSong->picture; ?>" alt="Buy CD!" title="Click to view CD info" />
									</a>
									<?php endif; ?>
								</td>
								<td>
									<?php 
									# if (strpos($playlistSong->artist_title,'???') !== false) {
									# $playlistSong->artist_title = 'this is a broken track123';
									# } ?>
									<?php echo $playlistSong->artist_title; ?>
									<?php if ($playlistSong->isRequested): ?>
									~requested~
									<?php endif; ?>
								</td>
								<td align="center">
									<?php if (ALLOW_REQUESTS) : ?>
									<a href="javascript:request(<?php echo $playlistSong->ID; ?>);">
										<img src="images/request.png" alt="Request this track now!" title="Request this track now!"/>
									</a>
									<?php endif; ?>
									<?php /*<a href="<?php echo $playlistSong->buycd; ?>" target="_blank">
										<img src="images/buy.png" alt="Buy this CD or Track now!" title="Buy this CD or Track now!"/>
									</a>*/ ?>
									<a href="<?php echo $playlistSong->website; ?>" target="_blank">
										<img src="images/home.png" alt="Artist homepage" title="Artist homepage" />
									</a>
									<a href="javascript:songinfo(<?php echo $playlistSong->ID; ?>)">
										<img src="images/info.png" alt="Track information" title="Track information" />
									</a>
								</td>
								<td>
									<?php echo $playlistSong->album; ?>
								</td>
								<td align="right">
									<?php echo $playlistSong->durationDisplay; ?>
								</td>
							</tr>
						<?php endforeach; ?>
							<tr>
								<td colspan="6" id="td-playlist-paging">
								<?php if(!is_array($playlistSongs) || (count($playlistSongs)==0)) { ?>
								 	No matches found. Please try another search.
								<?php } else { ?>
									<?php if($start > 0) { echo $prevlnk; } ?>
									&nbsp; [ Showing <?php echo "$first to $last of $cnt"; ?> ] &nbsp;
									<?php
									if(($start+$limit) < $cnt) { 
									echo $nextlnk; 
									} ?>
								<?php } ?>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<!-- END:PLAYLIST -->

		
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

			// Rounds the Coming Up Corners
			$('#coming-up dl').corner();

			// Round the Dedication Corners
			$('#dedication dl').corner('tl tr').corner();

			// Round the Top Request Corners
			$('#top_requests dl').corner();

			// Rounds the Playlist and search boxes
			$('#playlist_wrapper, #search').corner();
			// Style odd and even rows in Playlist Table (Cross-browser compatible)
			$('#playlist table tbody tr:nth-child(odd)').addClass('playlist_odd');
			$('#playlist table tbody tr:nth-child(even)').addClass('playlist_even');

		});
		//]]>

		</script>

	</body>
</html>