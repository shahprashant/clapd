<?php

/**
 * ChangePasswordForm class.
 * ChangePasswordForm is the data structure for keeping
 * forgot password form data. It is used by the 'forgotpassword' action of 'SiteController'.
 */
class ChangePasswordForm extends CFormModel
{
	public $password;


	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('password', 'required'),
            array('password','length','min' => 6),
            array('password','length','max' => 20),
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
	public function changePassword($passwordResetCode)
	{
        $ret = false;
		if(!$this->hasErrors() && ($passwordResetCode != "") && ($this->password != ""))
		{
            $userRow=User::model()->find('passwordResetCode=:passwordResetCode', array(':passwordResetCode'=> $passwordResetCode));
            if ($userRow) {
                $userRow->password = RegForm::hashPassword($this->password); 
                $userRow->passwordResetCode = '';
                $userRow->save();
                $ret = true;
            } else {
				$this->addError('password','Invalid Password Reset Code.');
                $ret = false;
            }
		}
        return $ret;
	}

	public function verifyPasswordResetCode($passwordResetCode)
	{
        $ret = false;
		if(!$this->hasErrors() && ($passwordResetCode != ""))
		{
            $userRow=User::model()->find('passwordResetCode=:passwordResetCode', array(':passwordResetCode'=> $passwordResetCode));
            if ($userRow) {
                $ret = true;
            } else {
                $ret = false;
            }
		}
        return $ret;
	}


}
