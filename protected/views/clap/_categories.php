

<?php 
$boxcount=0;
foreach ($categoriesData as $rootCatId => $rootCatData) {
	if ($boxcount == 0) {
		echo '<div class="prevbox"></div>';
	}
	echo '<div class="roundbox">';
	printf("<b>%s</b><br>", $rootCatData['catText']);
	$categoryChildren = $rootCatData['children'];
	foreach ($categoryChildren as $index => $categoryChild) {
		// $postCategoryUrl = "/claps?r=clap/post&catid=" . $categoryChild['catid'];
        $postCategoryUrl = $this->createUrl('clap/post', array('catid' => $categoryChild['catid']));
		if (isset($_GET['question']) && ($_GET['question'] == '1')) {
			$postCategoryUrl .= '&question=1';
		}
		echo "- <a href='$postCategoryUrl'>" . $categoryChild['catText'] . "</a><br>";
		
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
