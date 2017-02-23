<?php

/**
 * InviteForm class.
 * InviteForm is the data structure for inviting a new user to the site
 *  It is used by the 'invite' action of 'SiteController'.
 */
class InviteForm extends CFormModel
{
	public $email;


	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('email', 'required'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'email'=>'Email',
		);
	}
	
	public function registerYibesInterest() {
		// save in the invite table 
		$code = md5(uniqid(rand(), true)); 
		$sql = "INSERT INTO invite (email, code) values ('$this->email', '$code') ON DUPLICATE KEY UPDATE `code`='$code'";

		$inviteCmd = Yii::app()->db->createCommand($sql);
		$inviteCmd->execute();
		if ($inviteCmd->execute() > 0) {
            $ret = true;
		} else
            $ret = false;

/* Email will be sent out by an offline script
			$emailEncrypted = $this->email;
			$inviteUrl = Yii::app()->request->getBaseUrl(true) . "/?r=site/registration&cc=$code";
			$Name = "Yibes Invite"; //senders name
			$from = Yii::app()->params['fromEmail']; //senders e-mail adress
			$recipient = $this->email; //recipient
			$mail_body = <<<EOT
            <html>
            Greetings,\r\n
            \r\n
            Here is your invitation to join Yibes. \r\n
            Yibes is a social network to capture the things you would recommend to your friends. \r\n
            \r\n
            Please click on below url to activate and setup a user.
            \r\n 
            <a href='$inviteUrl'>$inviteUrl</a> \r\n
            \r\n
            Thanks for Joining Yibes ! \r\n
            -The Yibes Team
            </html>
EOT;
			$subject = "Invitation to join Yibes"; //subject
			$header = "From: ". $Name . " <" . $from . ">\r\n"; //optional headerfields
			$ret = mail($recipient, $subject, $mail_body, $header);
			return $ret;  */
	
        return $ret;
		
	}



}
