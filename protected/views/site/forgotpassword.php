<?php
include_once 'jsincludes.php';
printf("<div class='prevbox'></div>");
printf("<div class='roundbox'></div>");
echo "<div class='roundboxspan3'>"; 
?>
<div class='form'>
<?php

$form=$this->beginWidget('CActiveForm', array(
        'id'=>'forgotpassword-form',
        'enableAjaxValidation'=>false,
        'enableClientValidation' => false,
        'htmlOptions' => array('enctype'=>'multipart/form-data')
)); 
?>

<div class="row forgotpassword">
<?php 
    if (($emailSent) && ($emailSent == "1")) { 
       echo "An email has been sent to you with furthur instructions.";
    } elseif (($emailSent) && ($emailSent == "0")) { 
       echo "The email could not be sent. Please try again.";
    } else { 
       echo $form->textField($forgotPasswordFormModel,'username_email',array('size' => 30, 'placeholder' => 'Username or Email', 'id' => 'username_email_button')); 
       echo $form->error($forgotPasswordFormModel,'username_email'); 
    }
?>
</div>

<div class="row buttons forgotpassword">
<?php 
    if (!$emailSent) {
        echo CHtml::submitButton('Submit',array('id' => 'smallloginbutton3boxes')); 
    }
$this->endWidget();
?>
</div>
</div><!-- end of form -->
</div>

<div class='roundbox'>
Please submit your email or username. We will send an email which will contain an url to change your password. Please check in your SPAM/BULK folder also for this email.  
</div>
