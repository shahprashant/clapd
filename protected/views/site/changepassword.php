<?php
include_once 'jsincludes.php';
printf("<div class='prevbox'></div>");
printf("<div class='roundbox'></div>");
echo "<div class='roundboxspan3'>"; 
?>
<div class='form'>
<?php

/*
$form=$this->beginWidget('CActiveForm', array(
        'id'=>'forgotpassword-form',
        'enableAjaxValidation'=>false,
        'enableClientValidation' => false,
        'htmlOptions' => array('enctype'=>'multipart/form-data')
)); */

    $form=$this->beginWidget('CActiveForm', array(
        'id'=>'forgotpassword-form',
        'enableClientValidation'=>true,
        'clientOptions'=>array(
        'validateOnSubmit'=>true,
        ),
    )); 

?>

<div class="row forgotpassword">
<?php
if (($changePasswordSuccess) && ($changePasswordSuccess == "1")) { 
    $loginUrl = Yii::app()->request->getBaseUrl() . "/site/login";
    echo "Your password has been changed. Please <a href='$loginUrl'>Login</a>";
} elseif (($changePasswordSuccess) && ($changePasswordSuccess == "0")) { 
    echo "Sorry, your new password could not be saved. Please try again.";
} elseif (($invalidPasswordResetCode) && ($invalidPasswordResetCode == "1")) { 
    echo "This is an invalid or expired URL. ";
} else { 
    echo $form->error($changePasswordFormModel,'password'); 
    //echo $form->passwordField($changePasswordFormModel,'password',array('size' => 30, 'placeholder' => 'New Password', 'id' => 'password_button')); 
    echo $form->passwordField($changePasswordFormModel,'password',array('size' => 15)); 
    echo '&nbsp;&nbsp;&nbsp;(Min. of 6 chars)';
    printf("<input type=hidden name=pc value='%s'>", $pc);
}
?>
</div>

<?php 
   if (($changePasswordSuccess) || ($invalidPasswordResetCode)) { 
   } else { 
      echo '<div class="row buttons">';
      echo CHtml::submitButton('Submit',array('id' => 'smallloginbutton3boxes')); 
      echo '</div>';
   }
   $this->endWidget();
?>
</div><!-- end of form -->
</div>

<?php if (($changePasswordSuccess) || ($invalidPasswordResetCode)) { ?>
<div class='roundbox'></div>
<?php } else { ?>
<div class='roundbox'>
Please enter your new password 
</div>
<?php } ?>
