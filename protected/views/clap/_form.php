<?php 
	$placeholders = array(
				'8' => "Name of Movie",
				'9' => "Name of TV Show",
				'10' => "Name of Book",
				'11' => "Name of Song or Album",
				'12' => "Enter Stock Ticker",
				'13' => "Name of Restaurant",
				'15' => "Enter URL", 
				'28' => "Name of Food Store",
			);
	if (isset($placeholders[$_GET['catid']])) {
		$placeholderText = $placeholders[$_GET['catid']];
	} else {
		$placeholderText = "Enter a suitable title";
	}
?>
<div class="form">

<?php
// echo CHtml::form('','post',array('enctype'=>'multipart/form-data'));
	$form=$this->beginWidget('CActiveForm', array(
	'id'=>'clap-form',
	'enableAjaxValidation'=>false,
	'enableClientValidation' => false,
	'htmlOptions' => array('enctype'=>'multipart/form-data')
)); ?>


	<?php 
        if ($form->errorSummary($model)) {
            echo '<div class="prevboxflex"></div>';
            echo "<div class='fullerrorbox'>";
            echo $form->errorSummary($model); 
            echo "</div>";
            echo '<br clear="all">';
        }
    ?>

	<div class="prevbox"></div>	
	<div class="roundbox fieldbox" id="posttitle">
		<?php echo $form->label($model,'title'); echo "<br>"; ?>	
		<?php echo $form->textArea($model,'title', array('placeholder' => $placeholderText, 'id' => 'titletext')); ?>		
        <br><br><br>
        <div class='charLeftText'><div id="charLeft">50</div>Characters left</div>
	</div>
	<div class="roundboxspan2 fieldbox" id="postclap">
		<?php echo $form->label($model,'clap'); echo "<br>";?>
		<?php echo $form->textArea($model,'clap', array('id' => 'claptext' )); ?>
	</div>
	<?php if (isset($_GET['question']) && ($_GET['question']) == '1') { ?>
		
	<?php } else { ?>
	<div class="roundboxspan2 fieldbox" id ="ratingsbox" >
        <br>
		<?php $model->rating = 3; ?>
        <div class="postrating1">
		<?php echo $form->label($model,'rating'); ?>
        </div>
        <div class="postrating2">
		<?php echo $form->dropDownList($model,'rating', array("3" => "3", "4" => "4", "5" => "5"), array('id' => 'ratingtext')); ?>
        </div>
        <br clear='all'><br>
        <div class="postrating1">
		<?php echo $form->label($model,'privacy'); ?>
        </div>
        <div class="postrating2">
		<?php // echo $form->dropDownList($model,'privacy', array("0" => "Public", "2" => "Followers")); ?>
		<?php echo $form->dropDownList($model,'privacy', array("0" => "Public")); ?>
        </div>
        <br clear='all'><br>
        <div class="postrating1">
        <?php echo $form->label($model, 'Post to Facebook'); ?>
        </div>
        <div class="postrating2">
        <?php echo "<input type=checkbox id=fbpostcheckbox name=fbpost value='1' onclick='checkFBPermissions()' >"; ?> 
        </div>
        <br clear='all'>
        <!--
        <div id='fbpostmsg' class='messages' style='display:none;'>
        &nbsp;&nbsp;To post to Facebook, you need to <div class="fb-login-button" data-max-rows="1" data-size="small" data-show-faces="false" data-auto-logout-link="false" scope="email,user_friends,publish_actions" onlogin=uponLoginSuccess()> login to facebook</div> <br>&nbsp;&nbsp;and authorize Claps to post to your wall.
        </div> -->
	</div>
	<?php } ?>
	<?php if (isset($_GET['question']) && ($_GET['question']) == '1') { ?>
		<div class="roundbox">
        <br><br><br><br><br>
        <?php echo $form->label($model, 'Post to Facebook'); ?>
        <?php echo "<input type=checkbox id=fbpostcheckbox name=fbpost value='1' onclick='checkFBPermissions()' >"; ?> 
        </div>
		<div class="roundbox fieldbox">
		<?php echo CHtml::submitButton('Post Request', array('id' => 'clapsubmit')); ?>

		</div>
	<?php } ?>
	
	<br clear="all">
	<?php if (isset($_GET['question']) && ($_GET['question']) == '1') { ?>
	<?php } else { ?>
	<div class="prevbox"></div>

	<div class="roundbox">
		<?php echo CHtml::submitButton('Post my Clap', array('id' => 'clapsubmit')); ?>
    </div>
	<div class="roundboxspan2 fieldbox">
		<?php echo $form->label($model,'Image (optional)');?>
        <br><br>
		<?php echo $form->fileField($model,'imageFile', array('size' => 10)); ?>
		<?php echo $form->error($model,'imageFile'); ?>
	</div>	
	<div class="roundboxspan2 fieldbox">
        <?php echo $form->label($model,'Help'); ?>
        <div id='helpbox'><br>Click on any box to get relevant help.</div>
	</div>	
	<br clear="all">	
	<?php } ?>


<?php 
echo $form->hiddenField($model,'categoryId', array('value' => $_GET['catid']));
if (isset($_GET['question']) && ($_GET['question'] == '1')) {
	echo $form->hiddenField($model, 'question', array('value' => $_GET['question']));
}
// if its a reply then we need to store question attribute as 2 in the db
// we also need to store association between question and answer in answers tables
if (isset($_GET['rClap']) && ($_GET['rClap'] != "")) {
	printf("<input type='hidden' name='rClap' value='%s'>", $_GET['rClap']);
	echo $form->hiddenField($model, 'question', array('value' => '2'));
}


$this->endWidget(); 
?>

</div><!-- form -->
