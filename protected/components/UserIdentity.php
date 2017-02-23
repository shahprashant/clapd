<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{

    private $fbUserId;
    private $fbUsername;
    const ERROR_FBUSER_INVALID=3;

	/**
	 * Authenticates a user.
	 */

	
	public function authenticate()
	{
        if (isset($this->fbUserId)  && ($this->fbUserId != "")) {
            $user=User::model()->find('fbUserId=?',array($this->fbUserId));
		    if($user===null) {
			    $this->errorCode=self::ERROR_FBUSER_INVALID;
            } else {
            }

        } elseif (isset($this->username) && isset($this->password)) {
		    $username=strtolower($this->username);
            $user=User::model()->find('LOWER(username)=?',array($username));
		    if($user===null) {
			    $this->errorCode=self::ERROR_USERNAME_INVALID;
		    } else if (!($this->validatePassword($this->password, $user['password']))) {
	 		    $this->errorCode=self::ERROR_PASSWORD_INVALID;
                $user = null;
            }
        }        
		if ($user !== null) {
			$this->username=$user->username;
			//$this->setState('name', $user->name);
			$this->setState('name', $user->name);
			$this->setState('username', $user->username);
			$this->setState('id', $user->id);
			//$this->setState('avatar', $user->avatar);
			//$this->setState('avatar', "abc.jpg");


			$this->errorCode=self::ERROR_NONE;
		}
		return $this->errorCode==self::ERROR_NONE;
	}

    public function setFBUserId($fbUserId)
    {
        $this->fbUserId = $fbUserId;
    }

    public function setFBUsername($fbUsername)
    {
        $this->fbUsername = $fbUsername;
    }

    //Validates a password
    //returns true if hash is the correct hash for that password
    //$hash - the hash created by HashPassword (stored in your DB)
    //$password - the password to verify
    //returns - true if the password is valid, false otherwise.
    function validatePassword($password, $correctHash)
    {
        $salt = substr($correctHash, 0, 64); //get the salt from the front of the hash
        $validHash = substr($correctHash, 64, 64); //the SHA256

        $testHash = hash("sha256", $salt . $password); //hash the password being tested
/*
        echo "password: $password<br>";
        echo "correctHash: $correctHash<br>";
        echo "validHash: $validHash<br>";
        echo "testHash: $testHash<br>"; */
    
        //if the hashes are exactly the same, the password is valid
        return $testHash === $validHash;
    }


	
	/*
	public function getId()
	{
		return $this->_id;
	}
	*/
	

	
	
	
}
