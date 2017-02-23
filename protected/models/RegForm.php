<?php

/**
 * RegForm class.
 */
class RegForm extends CFormModel
{
	public $username;
	public $password;
    public $email;
    public $fbUserId;
    public $fbUsername;
    public $fbName;


	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('username, password, email', 'required'),
            array('username','length','min' => 4), 
            array('username','length','max' => 20), 
            array('password','length','min' => 6), 
            array('password','length','max' => 20), 
            array('username','checkForUniqueUsername'),
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
	 * Checks  the password.
	 */
	public function checkPassword($attribute,$params)
	{
        $pattern = '/^(?=.*\d(?=.*\d))(?=.*[a-zA-Z](?=.*[a-zA-Z])).{5,}$/';
        if(!preg_match($pattern, $this->$attribute))
            $this->addError($attribute, 'your password is not strong enough!');
		if ($this->hasErrors()) {
            return false;
		}
	}

    /**
     * Checks the username
     */
	public function checkUsername($attribute,$params)
	{
		if(!$this->hasErrors())
		{
		}
	}

    public function checkForUniqueUsername($attribute, $params) {
    error_log("PPS1");
        if (!$this->hasErrors()) {
    error_log("PPS2");
            $this->addError('username',"Username is already taken.");
        }
    }


    public function createClapsUser(&$error) 
    {
        Yii::import("ext.EPhpThumb.EPhpThumb"); // for thumbnail and resized imgs generation
        $userModel = new User; 
        $userModel->username = $this->username;
        $userModel->password = $this->hashPassword($this->password);
        $userModel->fbUserId = $this->fbUserId;
        $userModel->email = $this->email;
        if ($this->fbUserId) {
            try {
                $fbUserProfile = Yii::app()->facebook->api('/me');
                if (($fbUserProfile) && (isset($fbUserProfile['name']))) {
                    if (isset($fbUserProfile['username'])) {
                        $userModel->fbUsername = $fbUserProfile['username'];
                    } else {
                        $userModel->fbUsername = "";
                    }
                    $userModel->name = $fbUserProfile['name'];
                } else {
                    return false;
                }
            } catch (FacebookApiException $e) {
                return false;
            }
        }
//        print_r($userModel);
        try {
            $userModel->save();
            // save user profile photo
            $userFolder = Yii::app()->getBasePath() . "/../userdata/" . $this->username;                    
            $profileFolder = $userFolder . "/profile" ;                    
            if(!is_dir($userFolder)) {
                mkdir($userFolder);                    
            }
            if(!is_dir($profileFolder)) {
                mkdir($profileFolder); 
            } 
            
            $img = file_get_contents('https://graph.facebook.com/'.$this->fbUserId.'/picture?type=large');
            $file = $profileFolder . "/" . $this->username . ".jpg"; 
            if (file_put_contents($file, $img) !== false) {
                $thumb=new EPhpThumb();
                $thumb->init();
                $thumbImgFile = $profileFolder . "/" . $this->username . "_thb.jpg";
                $squareImgFile = $profileFolder . "/" . $this->username . "_sqr.jpg";
                $thumb->create($file)->adaptiveResize(75,75)->save($thumbImgFile);
                $thumb->create($file)->adaptiveResize(125,125)->save($squareImgFile);
                $userModel->updateAvatar($this->username . ".jpg");
            }

            // delete the entry from invite table 
            $deleteSql = "delete from invite where email='$this->email' limit 1";
            $inviteDeleteCmd = Yii::app()->db->createCommand($deleteSql);
            $inviteDeleteCmd->execute();

            // make the user by default follow Claps Buddies
            $connectionsModel = new Connections;
            $clapsBuddies = explode(",", Yii::app()->params['CLAPS_BUDDIES']);
            $connectionsModel->followUsers($userModel->id, $clapsBuddies); 

            return true; 
        } catch (CDbException $e) {
            // unable to save user
            // duplicate entry error code is 1062
            if ($e->errorInfo[1] == '1062') {
                $error = "Username '" . $this->username . "' is already taken. Please choose another username.";
            }
            return false;
        }
        return false;
    }

    //Takes a password and returns the salted hash
    //$password - the password to hash
    //returns - the hash of the password (128 hex characters)
    function hashPassword($password)
    {
        $salt = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM)); //get 256 random bits in hex
        $hash = hash("sha256", $salt . $password); //prepend the salt, then hash
        //store the salt and hash in the same string, so only 1 DB column is needed
        $hashPass = $salt . $hash;
        return $hashPass;
    }

}
