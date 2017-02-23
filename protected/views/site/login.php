<?php
include_once 'jsincludes.php';
printf("<div class='prevbox'></div>");
printf("<div class='roundbox'></div>");
echo "<div class='roundbox'>"; 
?>
<div class='form'>
<?php

$form=$this->beginWidget('CActiveForm', array(
        'id'=>'invite-form',
        'enableAjaxValidation'=>false,
        'enableClientValidation' => false,
        'htmlOptions' => array('enctype'=>'multipart/form-data')
)); 
?>

<div class="row">
<?php // echo $form->label($loginFormModel,'u'); ?>
<?php echo $form->textField($loginFormModel,'username',array('size' => 16, 'placeholder' => 'Username', 'id' => 'usernamebutton')); ?>
<?php echo $form->error($loginFormModel,'username'); ?>
</div>

<div class="row">
<?php // echo $form->label($loginFormModel,'p'); ?>
<?php echo $form->passwordField($loginFormModel,'password',array('size' => 16, 'placeholder' => 'Password', 'id' => 'passwordbutton')); ?>
<?php echo $form->error($loginFormModel,'password'); ?>
</div>

<div class="row buttons">
<?php echo CHtml::submitButton('Login',array('id' => 'smallloginbutton')); 
$this->endWidget();
?>
    <br><br><span class="signupmsg1">(Existing Users)</span>
</div>
</div><!-- end of form -->
</div>

<div class='roundbox'><div class='promosubboxbigfont'><br><br>OR</div></div>
<div class='roundbox'>
<form>
<?
//    echo "<a href='$loginUrl'>Login using Facebook</a>";
    printf("<img class='fbloginbutton' src='%s' onclick=\"parent.location='%s'\">", Yii::app()->request->getBaseUrl() . "/images/fb_login_icon.gif", $loginUrl);
?>

</form>
    <br><br><span class="signupmsg2">(Existing Users / New Users)</span>
</div>
<div class='roundbox'>
  <div class="forgotpassword"><a href='<?php echo Yii::app()->request->getBaseUrl() . "/site/forgotpassword";?>'>Forgot Password?</a></div>
  <div class="forgotpasswordform">
  <?php
  /* PPS Commenting out temporarily
  $form=$this->beginWidget('CActiveForm', array(
        'id'=>'resetpwd-form',
        'enableAjaxValidation'=>false,
        'enableClientValidation' => false,
        'htmlOptions' => array('enctype'=>'multipart/form-data')
  )); 
  echo $form->textField($loginFormModel,'email',array('size' => 20, 'placeholder' => 'Email', 'id' => 'forgotpwdemail')); 
  echo CHtml::submitButton('Reset Password',array('id' => 'smallloginbutton')); 
  $this->endWidget(); */

 ?>
  </div>
</div>
