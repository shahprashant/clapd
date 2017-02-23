<?php
include_once 'jsincludes.php';
$boxcount = 0;

foreach ($clapHighlights as $highlight) {	
	if ($boxcount == 0) {
		echo "<div class='prevbox'></div>";
	}

	printf("<div class='roundbox'><div class='promosubbox'><br>%s</div></div>",$highlight);
	$boxcount++;
	if ($boxcount >= 5) {
		echo "<div class='nextbox'></div>";
		echo "<br clear='all'>";
		$boxcount=0;
	}
}

?>
