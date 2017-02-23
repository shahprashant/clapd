<script type='text/javascript'>
// get the connected facebook user id
var connectedFbUserId = '<?php echo $fbUserId; ?>';
</script>
<?php
include_once 'jsincludes.php';
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->getBaseUrl().'/assets/1d6decab/js/facebookpost.js');
//Yii::app()->clientScript->registerCssFile("http://fonts.googleapis.com/css?family=Gudea");
//Yii::app()->clientScript->registerCssFile("http://fonts.googleapis.com/css?family=Michroma");

$this->breadcrumbs=array(
		'Claps'=>array('index'),
		'Create',
);


if (isset($_GET['catid'])) {
	echo $this->renderPartial('clap/_form', array('model'=>$model, 'catText' => $catText, 'additionalFBPermissionUrl' => $additionalFBPermissionUrl));
} else {
	echo $this->renderPartial('clap/_categories', array('model'=>$model, 'categoriesData' => $categoriesData));
}
?>

<script type='text/javascript'>
        $(document).ready(function() {
            // display num of characters
            $('#titletext').keyup(function() {
                var len = this.value.length;
                if (len >= 50) {
                    this.value = this.value.substring(0, 50);
                }
                $('#charLeft').text(50 - len);
            });

            var titlehelp = "Enter a short title which is less than 50 characters. The entire title will be displayed in the Clap box.";

            var claphelp = "Use following notations to improve your Clap:<br><br><b>+CheerWords: </b> Use to highlight the most positive parts of the your experience (+LotOfParking , +GreatActing, etc)<br><b>#TagWords: </b> Use to group your claps across categories (#kidfriendly, #romantic) <br><b>^Reference URLs:</b> Any additional links that you want to provide in your clap (^http:/www.blah.com)";

            var ratingshelp = "Currently Privacy is Public by default. Other options will be added later.<br><br>Posting to Facebook feature is pending approval from facebook.";

            $('#titletext').focus(function() {
                $('#helpbox').text(titlehelp);
            });
            $('#titletext').focusout(function() {
                $('#helpbox').text("");
            });
            $('#claptext').focus(function() {
                $('#helpbox').html(claphelp);
            });
            $('#claptext').focusout(function() {
                $('#helpbox').html("");
            });
            $('#ratingsbox').click(function() {
                $('#helpbox').html(ratingshelp);
            });
            $('#ratingsbox').focusout(function() {
                $('#helpbox').html("");
            });
        });
</script>
