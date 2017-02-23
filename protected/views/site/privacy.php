<?php

include_once 'jsincludes.php';
foreach ($privacyArray as $subject => $description) {	
	echo "<div class='prevbox'></div>";

	printf("<div class='roundbox'><div class='promosubbox'><br>%s</div></div>",$subject);
	printf("<div class='combobox' style='display:block'><div class='promosubboxleft'><br>%s</div></div>",$description);
	echo "<div class='nextbox'></div>";
	echo "<br clear='all'>";
}

?>
