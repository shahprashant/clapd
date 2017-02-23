<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="language" content="en" />

<!-- blueprint CSS framework -->
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>
<center>
<div class="container" id="page">

<!-- box5 -->
<?php if (!Yii::app()->user->isGuest) { ?>
	<div class="roundbox">Y I B E S</div>
<?php } else { ?>
	<div class="roundbox">&nbsp;</div>
<?php } ?>
<!-- end box5 -->

<!-- box4 -->
<div class="roundbox">
  - About Us <br>
  - FAQ <br>
  - Help <br>
</div>
<!-- end box4 -->

<!-- box3 -->
<div class="roundbox">
<?php 
if (!Yii::app()->user->isGuest) { 
	echo "Welcome " . Yii::app()->user->getName();
} else { 
	$model=new LoginForm;
?>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
'id'=>'login-form',
'enableClientValidation'=>true,
'clientOptions'=>array(
'validateOnSubmit'=>true,
),
)); ?>

<div class="row">
<?php echo $form->labelEx($model,'username'); ?>
<?php echo $form->textField($model,'username'); ?>
<?php echo $form->error($model,'username'); ?>
</div>

<div class="row">
<?php echo $form->labelEx($model,'password'); ?>
<?php echo $form->passwordField($model,'password'); ?>
<?php echo $form->error($model,'password'); ?>
</div>

<div class="row buttons">
<?php echo CHtml::submitButton('Login'); ?>
</div>

<?php $this->endWidget(); ?>
</div><!-- form -->
<?php } ?>
</div>
<!-- end box3 -->

<!-- box2 -->
<div class="roundbox" id="promo">&nbsp;</div>
<!-- end box2 -->

<!-- box1 -->
<?php if (!Yii::app()->user->isGuest) { ?>
	<div class="roundbox">YOUR PHOTO</div>
<?php } else { ?>
	<div class="roundbox">Y I B E S</div>
<?php } ?>
<!-- end box1 -->


<br clear="all">


<?php echo $content; ?>
<br clear="all">
<div id="footer">
Copyright &copy; <?php echo date('Y'); ?> by clapd.com.<br/>
All Rights Reserved.<br/>
<?php echo Yii::powered(); ?>
</div><!-- footer -->

</div>
</center>
<!-- page -->

</body>
</html>
