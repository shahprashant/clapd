<?php

include_once 'jsincludes.php';
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->getBaseUrl().'/assets/1d6decab/js/modal.popup.js');

/* Yii::app()->clientScript->registerCssFile("http://fonts.googleapis.com/css?family=Michroma"); */

$boxcount=0;
$boxidcount=6;

include_once "_indexclapboxes.php";
?>

  <script type="text/javascript">
  $(document).ready(function()
  {

<?php if ($this->page == "Claps") { ?>
    var processing = false;
	function getMoreClapRows() {
		var nextClapRow=$(".dummybox:last").attr("nextClapRow");
		var nextBoxIdCount=$(".dummybox:last").attr("nextBoxIdCount");
		var clapsUrl = '<?php echo $this->createUrl("site/getmoreclaprows"); ?>';
		var getMoreClapRowsUrl = clapsUrl + "?nextClapRow=" + nextClapRow + "&nextBoxIdCount=" + nextBoxIdCount;
		if (nextClapRow > 0) {
			$.get(getMoreClapRowsUrl, function(data) {
			if (data != "") {
				$(".dummybox:last").after(data); 
			}
            processing = false;
			})
		}

	}

	$(window).scroll(function(){
		if ($(window).scrollTop() >= $(document).height() - $(window).height() - 50 ){
             if (!(processing)) {
                 processing = true;
			     getMoreClapRows(); 
             }
		}
	});

	var notificationsCountUrl = '<?php echo $this->createUrl("useractions/getnotificationscount"); ?>';
 /*
    $.get(notificationsCountUrl, function(data) {
	    	// Notifications Count
	    	var notificationsHtml = "Notifications: " + data;
	    	$("#notificationbox").html(notificationsHtml);
	    	
    }); */

<?php } // end of if this->page  ?>

  });
  </script>


