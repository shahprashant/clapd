<?php

include_once 'jsincludes.php';

echo "<div class='form'>";
echo "<div class='prevbox'></div>";
printf("<div class='roundbox'></div>");
if (isset($emailSent) && ($emailSent === true)) {
    echo "<div class='roundboxspan3'>";
    echo "<div class='inviterequest'>";
    echo "Thanks for your interest in Clapd. You will be sent an Invite soon. Once you receive the email, click on the link in the email to join Clapd.";
    echo "</div>";
    echo "</div>";
    printf("<div class='roundbox'></div>");
} else {
    if (isset($emailSent) && ($emailSent === false)) {
        echo "<div class='roundboxspan3'>";
        echo "<div class='inviterequest'>";
        echo "There was a problem in sending the invite. Please try again.<br><br>";
        echo "</div>";
        echo "</div>";
        printf("<div class='roundbox'></div>");
    } else {
        // echo $this->renderPartial('site/_inviteform', array('inviteFormModel'=>$inviteFormModel));
        echo "<div class='roundboxspan3'>";
        echo "<div class='inviterequest'>";

        $form=$this->beginWidget('CActiveForm', array(
		'id'=>'invite-form',
		'enableAjaxValidation'=>false,
		'enableClientValidation' => true,
        'clientOptions'=>array('validateOnSubmit'=>true,),
		'htmlOptions' => array('enctype'=>'multipart/form-data')
        )); 

        echo "Clapd is in alpha stage. Please enter your Email Address to receive the invite. A very limited number of invites are being distributed at this time.<br><br>";
        echo $form->textField($inviteFormModel,'email', array('size' => 40, 'placeholder' => 'Email'));
        echo $form->error($inviteFormModel,'email');


        echo "</div>";
        echo "</div>";
        echo "<div class='roundbox'>";
        echo CHtml::submitButton('Submit',array('id' => 'clapsubmit'));
        $this->endWidget();
        echo "</div>";
    }
}
echo "</div>";
echo "<div class='nextbox'></div>";
echo "</div>";

?>
