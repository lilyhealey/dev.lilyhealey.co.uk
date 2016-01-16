<!DOCTYPE html>
<html>
	<head>
		<title>video</title>
		<style>
			html, body {
				margin: 0px;
			}
			#player, #page {
				position: fixed;
				width: 100%;
				height: 100%;	
			}
			#player {
				z-index: -99;
			}
			#page {
				z-index: 100;
			}
		</style>
		<script>
			var tag = document.createElement('script');
			tag.src = "http://www.youtube.com/player_api";
			var firstScriptTag = document.getElementsByTagName('script')[0];
			firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

			// 3. This function creates an <iframe> (and YouTube player)
			//    after the API code downloads.
			var player;
			function onYouTubePlayerAPIReady() {
				player = new YT.Player('player', {
				playerVars: {'modestbranding': 1, 'autoplay': 1, 'controls': 0,'autohide':1,'showinfo':0},
				// videoId: '8xIXAy8GvN8', // eugene onegin
				videoId: 'QcIy9NiNbmo', // bad blood
				events: {
					'onReady': onPlayerReady,
					'onStateChange': onPlayerStateChange
					}
				});
			}

			// 4. The API will call this function when the video player is ready.
			function onPlayerReady(event) {
				event.target.mute();
			}
			
			// this doesn't work
			var playerDOM = document.getElementById("player");
			function onPlayerStateChange(event) {
				if(!event.data)
					playerDOM.style.display = "none";
			}
		</script>
	<head>
	<body>
		<div id='player' style="position: fixed; z-index: -99; width: 100%; height: 100%"></div>
		<div id='page'></div>
	</body>
</html>