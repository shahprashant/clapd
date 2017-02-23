<?php 
	printf("<div class='detailsclosebox' id='detailsclosebox%s'></div>", $boxrow);
?>
<br clear="all">
<div class="detailsrightbox">
	<?php 
		if (!isset($likes)) {
			$likes = 0;
		}
		if (!isset($usefuls)) {
			$usefuls = 0;
		}
		if (!isset($saves)) {
			$saves = 0;
		}
		$useractionUrl = $this->createUrl("useractions/save");
		echo "<div class='likebox'>Rating</div><div class='likenumbox'>: $rating</div><br clear='all'>";
		echo "<div class='likebox'><a href='javascript:void(0)' onclick='saveLike(\"$id\",\"$useractionUrl\");'>Like</a></div><div class='likenumbox'>: (". $likes . ")</div><br clear='all'>";
		echo "<div class='likebox'><a href='javascript:void(0)' onclick='saveUseful(\"$id\",\"$useractionUrl\");'>Useful</a></div><div class='likenumbox'>: (". $usefuls . ")</div><br clear='all'>";
		echo "<div class='likebox'><a href='javascript:void(0)' onclick='saveFavorite(\"$id\",\"$useractionUrl\");'>Save</a></div><div class='likenumbox'>: (" . $saves . ")</div><br clear='all'>";
	?>
</div>
<div class="detailsmaincontent">
	<?php
	echo "<img src='/images/pps150.jpg'  width=100 align='left'>";
	echo "<font color=blue><a href='". Yii::app()->request->getBaseUrl() . "/" . $username . "'>" . $name . '</a></font><br>';
	echo "<b>$title</b><br>";
	echo "$clap<br>";
	?>
</div>
