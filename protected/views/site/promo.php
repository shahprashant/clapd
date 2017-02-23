
<?php
include_once 'jsincludes.php';
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->getBaseUrl().'/assets/1d6decab/js/modal.popup.js');

	$boxidcount = 6;
	echo "<div class='prevbox'></div>";
	foreach ($clapsPromos as $clapPromo) {
		$boxid = "box" . $boxidcount;
		$promoid = "promo" . $boxidcount;
		printf("<div class='roundbox' id='%s'><div class='promosubbox' id='%s' style='display:none;'><br>%s</div></div>",$boxid, $promoid, $clapPromo);
		$boxidcount++;
	}	
	echo "<div class='nextbox'></div>";
?>

<script type="text/javascript">
  	$(document).ready(function()
  	{

    <?php if (isset($_GET['accountsuccess']) && ($_GET['accountsuccess'] == '1')) { ?>
        //-----------  popup for account creation -----------------
        //Change these values to style your modal popup
        var source = "http://www.clapd.com/site/message?accountsuccess=1";
        var width = 500;
        var align = "center";
        var top = 100;
        var padding = 10;
        var backgroundColor = "#FFFFFF";
        var borderColor = "#000000";
        var borderWeight = 4;
        var borderRadius = 5;
        var fadeOutTime = 300;
        var disableColor = "#666666";
        var disableOpacity = 40;
        // var loadingImage = "relative_path_to_file/loading.gif";
        var loadingImage = '';
     
        // successful account creation
        modalPopup( align,
            top,
            width,
            padding,
            disableColor,
            disableOpacity,
            backgroundColor,
            borderColor,
            borderWeight,
            borderRadius,
            fadeOutTime,
            source,
            loadingImage );

        //This method hides the popup when the escape key is pressed
        $(document).keyup(function(e) {
            if (e.keyCode == 27) {
                closePopup(fadeOutTime);
            }
        });
    <?php } ?>

  	  	var textDur = 2000;

  	  	/* $("#promo6").fadeIn(400).delay(textDur).fadeOut('slow', function() {
  	  		$("#promo7").fadeIn(400).delay(textDur).fadeOut('slow', function() {
  	  			$("#promo8").fadeIn(400).delay(textDur).fadeOut('slow', function() {
  	  				$("#promo9").fadeIn(400).delay(textDur).fadeOut('slow', function() {
  	  					$("#promo10").fadeIn(400).delay(textDur).fadeOut('slow');
  	  				})
  	  			})
  	  		})
  	  	})  */
        /*
  	  	$("#promo6").fadeIn('slow', function() {
            delay(textDur);
  	  	    $("#promo7").fadeIn('slow', function() {
                delay(textDur);
  	  	        $("#promo8").fadeIn('slow', function() {
                })
            })
        }) */
        setTimeout(function() { $("#promo6").fadeIn('slow'); },1000);
        setTimeout(function() { $("#promo7").fadeIn('slow'); },3000);
        setTimeout(function() { $("#promo8").fadeIn('slow'); },5000);
        setTimeout(function() { $("#promo9").fadeIn('slow'); },7000);
        setTimeout(function() { $("#promo10").fadeIn('slow'); },9000);
  	})
</script>
