<?php
include_once 'jsincludes.php';

$boxcount = 0;

foreach ($tagsArray as $tagRow) {	
	if ($boxcount == 0) {
		echo "<div class='prevbox'></div>";
	}

    $tagUrl = Yii::app()->request->getBaseUrl() . "/" . $this->user['username'] . "?tag=" . str_replace("#","",$tagRow['tag']);
	printf("<div class='roundbox'><div class='promosubbox'><br><br><br><a href='%s'>%s</a><br>(%s)</div></div>", $tagUrl, $tagRow['tag'], $tagRow['numClaps']);
	$boxcount++;
	if ($boxcount >= 5) {
		echo "<div class='nextbox'></div>";
		echo "<br clear='all'>";
		$boxcount=0;
	}
}

while ($boxcount > 0 && $boxcount < 5) {
	echo '<div class="roundbox">&nbsp;</div>' . "\n";
	$boxcount++;
}

?>
