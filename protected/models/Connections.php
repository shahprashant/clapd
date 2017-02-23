<?php

/**
 * This is the model class for table "connections".
 *
 * The followings are the available columns in table 'connections':
 * @property string $userId
 * @property string $follows
 */
class Connections extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Connections the static model class
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
		return 'connections';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('userId, follows', 'required'),
			array('userId, follows', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('userId, follows', 'safe', 'on'=>'search'),
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
			'userId' => 'User',
			'follows' => 'Follows',
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

		$criteria->compare('userId',$this->userId,true);
		$criteria->compare('follows',$this->follows,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function setConnections($userId)
	{
		$ret = true;
		// find the latest user id 
		$userRow = Yii::app()->db->createCommand()
		->select('*')
		->from('user u ')
		->order('id desc')
		->limit(1)
		->queryRow();
		
		$latestUserId = $userRow['id'];
		
		for ($i = 1; $i <= $latestUserId ; $i++) 
		{
			if ($i != $userId) {
				$insertSql = "INSERT INTO connections (userId, follows) values ('$userId','$i') ON DUPLICATE KEY UPDATE `follows`='$i'";
				$connectionsReader = Yii::app()->db->createCommand($insertSql);
				if ($connectionsReader->execute() > 0) {
					
				} else {
					$ret = false;
				}
			}
		}
		
		return ($ret);
	}
	
	/*
	 * Is user1 following user2? 
	 */
	public function isFollowing($userId1, $userId2) 
	{
		// if both users are same return true
		if ($userId1 == $userId2) {
			return true;
		}
		
		$connectionsRow = Yii::app()->db->createCommand()
		->select('*')
		->from('connections c')
		->where("userId = '$userId1' and follows = '$userId2' and blocked=0")
		->limit(1)
		->queryRow();
	
		if ($connectionsRow) {
			return true;
		} else {
			return false;
		}
	}
	
	public function getFollowers($userId, $from=0, $limit=50)
	{
		$connectionsArray = Yii::app()->db->createCommand()
			->select('c.userId, c.follows, u.id as userId, u.name, u.username, u.avatar')
			->from('connections c')
			->join('user u', 'c.userId = u.id')
			->where("follows = $userId and blocked=0")
			->limit($limit, $from)
			->queryAll();
		
		return ($connectionsArray);
	}
	
	public function getFollowing($userId, $from=0, $limit=50)
	{
		$connectionsArray = Yii::app()->db->createCommand()
			->select('c.userId, c.follows, u.id as userId, u.name, u.username, u.avatar')
			->from('connections c')
			->join('user u', 'c.follows = u.id')
			->where("c.userId = $userId and blocked=0")
			->limit($limit, $from)
			->queryAll();
	
		return ($connectionsArray);
	}	
	
	public function blockUser($userId, $follows)
	{
		$sql = "UPDATE connections set blocked=1 where userId=$userId and follows=$follows";
		$connectionsCmd = Yii::app()->db->createCommand($sql);
		if ($connectionsCmd->execute() > 0) {
			return true;
		} else
			return false;
	}

	public function unfollowUser($userId, $follows)
	{
		$sql = "DELETE from connections where userId=$userId and follows=$follows";
		$connectionsCmd = Yii::app()->db->createCommand($sql);
		if ($connectionsCmd->execute() > 0) {
			return true;
		} else
			return false;
	}	

    public function findUnconnectedFriends($userId) 
    {
        $connectionsArray = array();

        $fbFriends = Yii::app()->facebook->api('/me/friends');
        $fbFriendsStr = "''";
        if (is_array($fbFriends) && (count($fbFriends['data']) > 0)) {
            foreach ($fbFriends['data'] as $index => $fbFriendInfo) {
                $fbFriendsArray[$fbFriendInfo['id']] = $fbFriendInfo['name'];
            }
            if (count($fbFriendsArray) > 0) {
                $fbFriendsStr = implode(",", array_keys($fbFriendsArray)); 
            } 
        }

		$connectionsResults = Yii::app()->db->createCommand()
			->select('c.follows, u.fbUserId')
			->from('connections c')
			->join('user u', 'c.follows = u.id')
			->where("c.userId = $userId")
			->queryAll();

        foreach ($connectionsResults as $connection) {
            $connectionsArray[] = $connection['fbUserId'];
        }
        $connectionsStr = implode(",", array_values($connectionsArray));

        $sql = "select id,username,name,avatar from user u where u.fbUserId IN ($fbFriendsStr)" ;
        if ($connectionsStr) { 
            $sql .= " and u.fbUserId NOT IN ($connectionsStr)"; 
        }


        $unconnectedFBFriends = Yii::app()->db->createCommand()->setText($sql)->queryAll();

        return ($unconnectedFBFriends);

    }

    public function followUsers($userId, $followUsersArray)
    {
        $sep = "";

        foreach ($followUsersArray as $followUser) {
            $valuesStr .= $sep . "($userId, $followUser)";
            $sep = ",";
        }
	    $insertSql = "INSERT INTO connections (userId, follows) values $valuesStr";
        $connectionsCmd = Yii::app()->db->createCommand($insertSql);
        if ($connectionsCmd->execute() > 0) {
            return true;
        } else {
            return false;
        }
    }

}
