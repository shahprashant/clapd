<?php

/**
 * This is the model class for table "useractions".
 *
 * The followings are the available columns in table 'useractions':
 * @property string $id
 * @property string $userId
 * @property string $clapId
 * @property integer $type
 * 
 * type = 1 (Like), 2 (Useful), 3 (Save)
 */
class Useractions extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Useractions the static model class
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
		return 'useractions';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('userId, clapId, type', 'required'),
			array('type', 'numerical', 'integerOnly'=>true),
			array('userId, clapId', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, userId, clapId, type', 'safe', 'on'=>'search'),
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
			'userId' => 'User',
			'clapId' => 'Claps',
			'type' => 'Type',
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
		$criteria->compare('userId',$this->userId,true);
		$criteria->compare('clapId',$this->clapId,true);
		$criteria->compare('type',$this->type);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function save()
	{
		$sql = "INSERT INTO useractions (userId, clapId, type) values ('$this->userId', '$this->clapId', '$this->type') ON DUPLICATE KEY UPDATE `type`='$this->type'";
		$connectionsReader = Yii::app()->db->createCommand($sql);
		if ($connectionsReader->execute() > 0) {
			return true;
		} else 
			return false;
	}
	
	public function getNotificationsCount($userId) 
	{
		// get last notification check time 
		$userRow=User::model()->find('id=:userId', array(':userId'=> $userId));
		$notificationTime = $userRow['notificationtime'];
		
		$currentTime = time(); 
		
		if ($notificationTime) {
			// check whether notification time is more than 10 days 
			$notificationTimeStamp = strtotime($notificationTime);
			$timeDiff = $currentTime - $notificationTimeStamp; 
		
		
			// max notification is for latest 10 days only
			if ($timeDiff > 86400 * 10) {
				$timeDiff = 86400 * 10;
				$notificationTime = date("Y-m-d H:i:s", $currentTime-$timeDiff);
			}
			
		} else {
			// if no notification in db then just give notifications for past 2 days. 
			$timeDiff = 86400 * 2;
			$notificationTime = date("Y-m-d H:i:s", $currentTime-$timeDiff);
		}
		
		
		$useractionsData = Yii::app()->db->createCommand()
		->select('count(*) as useractionsCount')
		->from('useractions u, clap y')
		->where("u.clapId = y.id and y.userId = $userId and u.createtime > '$notificationTime'")
		->queryRow();
		
		$commentsData = Yii::app()->db->createCommand()
		->select('count(*) as commentsCount')
		->from('comment c, clap y')
		->where("c.clapId = y.id and y.userId =  $userId and c.createtime > '$notificationTime'")
		->queryRow();
		
		$notificationsCount = $useractionsData['useractionsCount'] + $commentsData['commentsCount'];
		
		return ($notificationsCount);
		
	}

	public function getNotifications($userId)
	{
		
		$currentTime = time();
	   // a week of notifications
		$notificationTime = date("Y-m-d H:i:s", $currentTime - (86400 * 7));
		
		/*
		$useractionsArray = Yii::app()->db->createCommand()
		->select('u.name, ua.type, ua.createtime, y.title')
		->from('useractions ua, clap y, user u')
		->where("ua.clapId = y.id and ua.userId = u.id and y.userId = $userId and ua.createtime > '$notificationTime'")
		->queryAll();

		$commentsArray = Yii::app()->db->createCommand()
		->select('u.name, ua.createtime, y.title')
		->from('comment c, clap y, user u')
		->where("c.clapId = y.id and c.userId = u.id and y.userId = $userId and c.createtime > '$notificationTime'")
		->queryAll();
		*/
		
		$notificationsSql = "(select u.name as name, ua.type as type, ua.createtime as createtime, y.id as clapId, y.title as title 
		                     from useractions ua, clap y, user u 
		                     where ua.clapId = y.id and ua.userId = u.id and y.userId = $userId and ua.createtime > '$notificationTime') 
		                     UNION
		                     (select u.name as name, '0' as type, c.createtime as createtime, y.id as clapId, y.title as title  
		                     from comment c, clap y, user u 
		                     where c.clapId = y.id and c.userId = u.id and y.userId = $userId and c.createtime > '$notificationTime' ) 
		                     order by createtime desc";
		
		$notificationsArray = Yii::app()->db->createCommand()->setText($notificationsSql)->queryAll();

		return ($notificationsArray);
		
	}

    public function getUseractionsForClap($clapId, $type)
    {
        $useractionsArray = Yii::app()->db->createCommand()
			->select('u.name as name, u.username as username')
			->from('useractions ua')
			->join('user u', 'ua.userId=u.id')
            ->where("ua.clapId = $clapId and ua.type = $type")
            ->queryAll();

        return $useractionsArray;
    }
}
