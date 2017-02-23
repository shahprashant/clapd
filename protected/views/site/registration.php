<?php

include_once 'jsincludes.php';

$userid = Yii::app()->facebook->getUser();

if (isset($showRegForm)) {
    echo "<div class='form'>";
    $form=$this->beginWidget('CActiveForm', array(
        'id'=>'reg-form',
        'enableClientValidation'=>true,
        'enableAjaxValidation'=>false,
        'clientOptions'=>array(
        'validateOnSubmit'=>true,
        ),
    )); 
}
echo "<div class='prevbox'></div>";
printf("<div class='roundbox'></div>");
echo "<div class='roundboxspan3 displaytable' >";


if (isset($showVerifiedEmail)) {
    echo "<p class='tablecell'><b><font color='darkgreen'>Congratulations. Your Email has been Verified.</font></b><br><br>";
    echo "Now you need to link your Facebook account so that:<br>a) We can find your Facebook friends who are with us.<br> b) We can post your Claps to Facebook (with your permission on each post)</p>";
} elseif (isset($showRegForm)) {
    echo "<div class='tablecell'>";
    if (isset($error)) { 
        displayError($error);
        echo "<br>";
    }
    echo '<b>Now you need to create a Claps account by choosing a username/password.</b> <br><br>';
    echo $form->error($regFormModel,'username'); 
    echo $form->error($regFormModel,'password'); 
    echo '<div class="signupsubbox1">';
    echo $form->label($regFormModel,'username'); 
    echo '</div>';
    echo '<div class="signupsubbox2">';
    echo $form->textField($regFormModel,'username',array('size' => 15)); 
    echo '&nbsp;&nbsp;&nbsp;(Min. of 4 chars)';
    echo '</div>';

    echo "<br clear='all'>";
    echo '<div class="signupsubbox1">';
    echo $form->label($regFormModel,'password');
    echo '</div>';
    echo '<div class="signupsubbox2">';
    echo $form->passwordField($regFormModel,'password',array('size' => 15)); 
    echo '&nbsp;&nbsp;&nbsp;(Min. of 6 chars)';
    echo '</div>';
    echo "<br clear='all'>";

    echo '<div class="signupsubbox1">';
    echo "<label>Email</label>" ; 
    echo '</div>';
    echo '<div class="signupsubbox2">';
    echo $email ;
    echo '</div>';
    printf("<input type=hidden name=fbUserId value='%s'>", $fbUserId);
    printf("<input type=hidden name=email value='%s'>", $email);
    echo "</div><!-- end of div tablecell -->";
} else {
    echo "<h2>Unauthorized Access. Please return to the home page.</h2>";
}
    echo "</div>";

    if (isset($showVerifiedEmail)) {
        printf("<div class='roundbox displaytable'>");
        printf("<p class='tablecell'><img src='%s' onclick=\"parent.location='%s'\"></p>", Yii::app()->request->getBaseUrl() . "/images/facebook-signup.png", $fbLoginUrl);
        echo "</div>";
    } elseif (isset($showRegForm)) {
        echo "<div class='roundbox'>";
        echo CHtml::submitButton('Create Account', array('id' => 'clapsubmit'));
        echo "</div>";
    } else {
        printf("<div class='roundbox'></div>");
    }
    echo "<div class='nextbox'></div>";

    if (isset($showRegForm)) {
        $this->endWidget();
        echo "</div><!-- end of div form -->";
    }
?>
