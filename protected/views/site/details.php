<?php
include_once 'jsincludes.php';
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->getBaseUrl().'/assets/1d6decab/js/modal.popup.js');

$data['clapData'] = $clapData;
$data['commentsData'] = $commentsData;
$this->renderPartial('site/_details', $clapData);
$this->renderPartial('site/_comments', $commentsData);

?>
  <script type="text/javascript">
  $(document).ready(function()
  {
    //-----------  popup for user actions -----------------
        //Change these values to style your modal popup
    var clapId = <?php echo $clapData['id'];?>;
    var sourcelike = "http://www.clapd.com/useractions/getusers?clapId=" + clapId + "&type=1";
    var sourceuseful = "http://www.clapd.com/useractions/getusers?clapId=" + clapId + "&type=2";
    var sourcesave = "http://www.clapd.com/useractions/getusers?clapId=" + clapId + "&type=3";
    var width = 190;
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
 
    //This method initialises the modal popup
    $(".modallike").click(function() {
 
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
            sourcelike,
            loadingImage );
 
    }); 

    $(".modaluseful").click(function() {
 
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
            sourceuseful,
            loadingImage );
 
    }); 

    $(".modalsave").click(function() {
 
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
            sourcesave,
            loadingImage );
 
    }); 
 
    //This method hides the popup when the escape key is pressed
    $(document).keyup(function(e) {
        if (e.keyCode == 27) {
            closePopup(fadeOutTime);
        }
    });
  });
  </script>
