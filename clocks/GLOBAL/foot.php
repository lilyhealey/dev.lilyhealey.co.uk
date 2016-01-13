<?php if ($pageName && !$stripped) { ?>
	                            
	<div style='position: fixed; z-index: 1; top: 50%; left: 50%; width: 200px; height: 200px; margin: -100px 0 0 -100px;'>
		<a href="index.html">                                
			<canvas datasrc='JS/ModernArtClock.pde' width='200' height='200'></canvas>
		</a>
	</div>

<?php

/*
	// if ($pageName == "library") {

		echo "<div style='position: fixed; z-index: 1; top: 50%; left: 50%; width: 
		360px; height: 360px; margin: -180px 0 0 -180px;'>
		<a href='index.html' title='D'><img src='MEDIA/Delaunay-8.gif' 
		style='width: 360px;' alt='Delaunay triangulation' title='/' /></a></li>";
		echo "</div><br />";
*/

		// LOGOS, DISCLAIMER, DATE
		
		echo "\n<div class='helvetica'>";
		echo "\n";		
		echo "\n CURRENTLY ". date("dmy H:i:s");
		echo "\n</div>";
		
	//	}
	?>


<?php } ?>

	</body>
</html>
