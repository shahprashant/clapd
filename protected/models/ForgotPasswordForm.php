<?php

/**
 * ForgotPasswordForm class.
 * ForgotPasswordForm is the data structure for keeping
 * forgot password form data. It is used by the 'forgotpassword' action of 'SiteController'.
 */
class ForgotPasswordForm extends CFormModel
{
	public $username_email;


	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('username_email', 'required'),
			array('username_email', 'safe'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
		);
	}

	/**
	 * Sends a reset link for password 
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function emailResetLink()
	{
        $ret = false;
		if(!$this->hasErrors() && ($this->username_email != ""))
		{
            if (strstr($this->username_email,"@") === false) {
               // its username
               $userRow=User::model()->find('username=:username', array(':username'=> $this->username_email));
            } else {
               // its an email
               $userRow=User::model()->find('email=:email', array(':email'=> $this->username_email));
            }
            if ($userRow) {
                $passwordResetCode = $this->getPasswordResetCode($userRow->username); 
                $userRow->passwordResetCode = $passwordResetCode;    
                $userRow->save();
                $userEmail = $userRow->email;
                $resetUrl = Yii::app()->request->getBaseUrl(true) . "/site/forgotpassword?pc=$passwordResetCode" ; 
                $this->sendMail($userEmail, $resetUrl);
                $ret = true;
            } else {
				$this->addError('username_email','Username or Email not found');
                $ret = false;
            }
		}
        return $ret;
	}

    public function sendMail($recipient, $resetUrl) {
            $Name = "Clapd Support"; //senders name
            $from = Yii::app()->params['fromEmail']; //senders e-mail adress
            $mail_body = <<<EOT
            <html>
            <head>
                <title>Clapd: Password Reset</title>
            </head>
            <body>
            <br>
            Hello,<br>
            <br>
            You indicated that you have forgotten your password for Clapd.com.<br><br>
            Please click on the link below and change your password. 
            <br>
            <a href='$resetUrl'>$resetUrl</a> <br>
            <br>
            If you did not initiate the request, then you need not do anything. Your password will remain the same. <br>
            <br><br>
            Clapd is a social network to capture the things you would recommend to your friends. <br>
            <br>
            -The Clapd Team
            </body>
            </html>
EOT;
            $subject = "Clapd: Password Reset"; //subject
            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .= "From: ". $Name . " <" . $from . ">\r\n"; //optional headerfields
            $ret = mail($recipient, $subject, $mail_body, $headers);
            return $ret;
    }

    //Takes a password and returns the salted hash
    //$password - the password to hash
    //returns - the hash of the password (128 hex characters)
    public function getPasswordResetCode($username) {
    
        $salt = bin2hex(mcrypt_create_iv(8, MCRYPT_DEV_URANDOM)); //get random bits in hex
        $hash = hash("sha1", $salt . $username); //prepend the salt, then hash
        //store the salt and hash in the same string, so only 1 DB column is needed
        $hashPass = $salt . $hash;
        return $hashPass;
    }


}
?>
