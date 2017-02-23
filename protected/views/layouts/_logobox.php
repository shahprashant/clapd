<?php 
	$taglines = array("Be Positive<br>B+ :-)", 
					"Spread<br>the<br>Goodness", 
					"Experience<br>Educate<br>Enrich",
					"Let the<br>positive times<br>flow",
					"The glass is<br>half full",
			);
	$tagline = $taglines[rand(0,count($taglines)-1)];
	
?>
<div class="roundbox greenbox" id="box5">
<br>
<div class="logobox"><a href='<?php echo Yii::app()->request->getBaseUrl(true);?>'><img src='/images/Clapd_logo_250x250.png' width=150></a></div>
<br clear='all'>
<?php echo "<div class='taglinebox'>$tagline</div>"; ?>
<div class="logoboxbottom">
<div class='bottomsubbox4'><img src='/images/flip.png' onmouseover='flipLogoBox("box5");'></img></div>
</div>


</div>
