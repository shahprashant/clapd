<?php
$boxcount=0;
$clapsData = $data['clapsData'];
foreach ($clapsData as $index => $clapsRow) {

	$title = $clapsRow['title'];
	$clap = $clapsRow['clap'];
	$category = $clapsRow['category'];	
	$author = $clapsRow['name'];
?>	
<div class="roundbox">
<?php echo $author . '<br>' . $title . '<br>' . $clap;
$boxcount++;
if ($boxcount == 4) {
	echo "<div class='roundbox'>Restaurants</div>";
	$boxcount++;
}
?>
</div>
<?php 
	if ($boxcount >= 5) {
		echo "<br clear='all'>";
		$boxcount=0;
	}
}
?>

<br clear="all">
<div class="roundbox">&nbsp;</div>
<div class="roundbox">&nbsp;</div>
<div class="roundbox">&nbsp;</div>
<div class="roundbox">&nbsp;</div>
<div class="roundbox">&nbsp;</div>
<br clear="all">
<div class="roundbox">&nbsp;</div>
<div class="roundbox">&nbsp;</div>
<div class="roundbox">&nbsp;</div>
<div class="roundbox">&nbsp;</div>
<div class="roundbox">&nbsp;</div>
<br clear="all">
