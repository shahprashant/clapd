

<?php 
$boxcount=0;
foreach ($categoriesData as $rootCatId => $rootCatData) {
	if ($boxcount == 0) {
		echo '<div class="prevbox"></div>';
	}
	echo '<div class="roundbox">';
	printf("<b><a href='/claps?catid=%s&rootcat=1'>%s</a></b><br>", $rootCatId, $rootCatData['catText']);
	$categoryChildren = $rootCatData['children'];
	foreach ($categoryChildren as $index => $categoryChild) {
		//printf("- <a href='/claps?catid=%s'>%s</a><br>", $categoryChild['catid'], $categoryChild['catText']);
		printf("- <a href='%s'>%s</a><br>", getCategoryUrl($categoryChild['catid'], $categoryChild['catText']), $categoryChild['catText']);
	}
	echo '</div>';
	$boxcount++;
	if ($boxcount >= 5) {
		echo '<br clear="all">';
		$boxcount=0;
	}

} 
if (($boxcount > 0) && ($boxcount < 5)) {
	while ($boxcount < 5) {
		echo '<div class="roundbox">&nbsp;</div>';
		$boxcount++;
	}
}
	
?>
<br clear="all">
