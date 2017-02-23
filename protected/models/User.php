<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property string $id
 * @property string $username
 * @property string $password
 * @property string $name
 * @property string $email
 * @property string $avatar
 * @property string $createtime
 * @property string $fbUserId
 * @property string $fbUsername
 */
class User extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('username, password, name, email, avatar, createtime', 'required'),
			array('username', 'length', 'max'=>25),
			array('name, avatar', 'length', 'max'=>100),
			array('email', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, username, password, name, email, avatar, createtime', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => 'Username',
			'password' => 'Password',
			'name' => 'Name',
			'email' => 'Email',
			'avatar' => 'Avatar',
			'createtime' => 'Createtime',
			'fbUserId' => 'Facebook UserId',
			'fbUsername' => 'Facebook Username',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('avatar',$this->avatar,true);
		$criteria->compare('createtime',$this->createtime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    /*
	public function validatePassword($password)			
	{
		return $password===$this->password;
	} */
	
	public function getUserInfo($userId) 
	{
        // if int, then compare with userId otherwise compare with username
        if (is_numeric($userId)) {
		    $userInfo = Yii::app()->db->createCommand()
    		->select('*')
    		->from('user u')
    		->where("id=$userId")
    		->limit(1)
    		->queryRow();
        } else {
		    $userInfo = Yii::app()->db->createCommand()
    		->select('*')
    		->from('user u')
    		->where("username='$userId'")
    		->limit(1)
    		->queryRow();
        }
		
		return ($userInfo);
	}


	public function updateAvatar($imgName)	
	{
		if ($imgName) {
			$userUpdateCommand = Yii::app()->db->createCommand();
			$userUpdateCommand->update('user', array(
					'avatar'=> $imgName,
			), 'id=:id', array(':id'=>$this->id));
		}
	}

    public function getPopularClap($userId) 
    {
        $clapRow = Yii::app()->db->createCommand()
            ->select('id, title, clap, postScore, feedbackScore, (postScore + feedbackScore) as yscore')
            ->from('clap')
            ->where("userId=$userId")
            ->order("yscore desc")
            ->limit(1)
            ->queryRow();

        if ($clapRow) {
            return $clapRow; 
        } else {
            return false;
        }
    }

    public function getLatestClap($userId) 
    {
        $clapRow = Yii::app()->db->createCommand()
            ->select('id, title, clap, postScore, feedbackScore, (postScore + feedbackScore) as yscore')
            ->from('clap')
            ->where("userId=$userId")
            ->order("createtime desc")
            ->limit(1)
            ->queryRow();

        if ($clapRow) {
            return $clapRow; 
        } else {
            return false;
        }
    }

    public function getNumberOfClaps($userId) 
    {
        $numOfClaps = 0;

        $clapRow = Yii::app()->db->createCommand()
            ->select('count(*) as sum')
            ->from('clap')
            ->where("userId=$userId")
            ->queryRow();

        if ($clapRow) {
            $numOfClaps = $clapRow['sum']; 
        } 

        return $numOfClaps;
    }

    public function getNumberOfUseractions($userId, $type) 
    {
        $numOfUseractions = 0;

        $useractionsRow = Yii::app()->db->createCommand()
            ->select('count(*) as sum')
            ->from('useractions')
            ->where("userId=$userId and type=$type")
            ->queryRow();

        if ($useractionsRow) {
            $numOfUseractions = $useractionsRow['sum']; 
        } 

        return $numOfUseractions;
    }

    public function getNumberOfComments($userId) 
    {
        $numOfComments = 0;

        $commentRow = Yii::app()->db->createCommand()
            ->select('count(*) as sum')
            ->from('comment')
            ->where("userId=$userId")
            ->queryRow();

        if ($commentRow) {
            $numOfComments = $commentRow['sum']; 
        } 

        return $numOfComments;
    }

    public function getAvgClapScore($userId) 
    {
        $avgClapScore = 0;

        $clapScoreRow = Yii::app()->db->createCommand()
            ->select('sum(postScore + feedbackScore) as totalYscore')
            ->from('clap')
            ->where("userId=$userId")
            ->queryRow();

        $clapCountRow = Yii::app()->db->createCommand()
            ->select('count(*) as numClaps')
            ->from('clap')
            ->where("userId=$userId")
            ->queryRow();

        if (($clapScoreRow) && ($clapCountRow) && ($clapCountRow['numClaps'] > 0)) {
            
            $avgClapScore = ((intval($clapScoreRow['totalYscore']))/(intval($clapCountRow['numClaps'])));
        } else 
            $avgClapScore = 0;

        return $avgClapScore;
    }

    public function getNumberOfFollowers($userId) 
    {
        $numOfFollowers = 0;
		$connectionsRow = Yii::app()->db->createCommand()
			->select('count(*) as numFollowers')
			->from('connections c')
			->where("follows = $userId and blocked=0")
			->queryRow();

        if ($connectionsRow) {
            $numOfFollowers = $connectionsRow['numFollowers']; 
        } 

        return $numOfFollowers;
    }

    public function getNumberOfFollowings($userId) 
    {
        $numOfFollowings = 0;
		$connectionsRow = Yii::app()->db->createCommand()
			->select('count(*) as numFollowings')
			->from('connections c')
			->where("userId = $userId and blocked=0")
			->queryRow();

        if ($connectionsRow) {
            $numOfFollowings = $connectionsRow['numFollowings']; 
        } 

        return $numOfFollowings;
    }
            
	
    public function getFavoriteCategory($userId) 
    {
        $clapRow = Yii::app()->db->createCommand()
            ->select('categoryId, c.category, count(*) as catClaps')
            ->from('clap y')
            ->join('category c', 'y.categoryId = c.id')
            ->where("userId=$userId")
            ->group('categoryId')
            ->order('catClaps desc')
            ->limit(1)
            ->queryRow();

        if ($clapRow) {
            return ($clapRow['category']);
        } else 
            return false;
    }

}
