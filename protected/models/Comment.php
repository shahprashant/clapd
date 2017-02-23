<?php

/**
 * This is the model class for table "comment".
 *
 * The followings are the available columns in table 'comment':
 * @property string $id
 * @property string $userId
 * @property string $clapId
 * @property string $comment
 * @property string $createtime
 */
class Comment extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Comment the static model class
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
		return 'comment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('userId, clapId, comment', 'required'),
			array('userId, clapId', 'length', 'max'=>20),
			array('comment', 'length', 'max'=>250),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, userId, clapId, comment, createtime', 'safe', 'on'=>'search'),
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
			'clapId' => 'Clap',
			'comment' => 'Comment',
			'createtime' => 'Createtime',
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
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('createtime',$this->createtime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
/*
	public function save()
	{
		
		$sql = "INSERT INTO comment (userId, clapId, clapOwnerId, comment) values ('$this->userId', '$this->clapId', '$clapOwnerId', '$this->comment') ";
		$commentCmd = Yii::app()->db->createCommand($sql);
		if ($commentCmd->execute() > 0) {
			return true;
		} else
			return false;
	} */
	
	public function getComments($clapId)
	{
		
		$commentsReader = Yii::app()->db->createCommand()
		->select('c.id,c.userId,c.comment,u.username,u.name')
		->from('comment c')
		->join('user u', 'c.userId=u.id')
		->where('c.clapId = "' . $clapId . '"')
		->order('c.createtime')
		->query();
	
		$commentsDataArray = array();
		$commentsDataArray = $commentsReader->readAll();
	
		return $commentsDataArray;
	}
}
