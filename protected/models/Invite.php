<?php

/**
 * This is the model class for table "invite".
 *
 * The followings are the available columns in table 'invite':
 * @property string $id
 * @property string $email
 * @property string $code
 * @property string $createtime
 */
class Invite extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Invite the static model class
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
		return 'invite';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('email, code, createtime', 'required'),
			array('email', 'length', 'max'=>100),
			array('code', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, email, code, createtime', 'safe', 'on'=>'search'),
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
			'email' => 'Email',
			'code' => 'Code',
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
		$criteria->compare('email',$this->email,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('createtime',$this->createtime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	

	function validateInvite($code) 
	{
		$ret = false;


        $updateSql = "update invite set verified=1 where code='$code' and approved=1";
        $inviteUpdateCmd = Yii::app()->db->createCommand($updateSql);
        if ($inviteUpdateCmd->execute() > 0) {
            $ret =  true;
        } else {
            // check if already verified
            $sql = "select * from invite where code='$code' and approved=1";
            $inviteRow = Yii::app()->db->createCommand()->setText($sql)->queryRow();
            if (($inviteRow) && ($inviteRow['verified'] == 1)) {
                $ret = true;
            } else {
                $ret = false;
            }
        }

		return ($ret);	
	}

    function sendInviteEmail() 
    {
        $emailCounter = 0;

        $sql = "select * from invite where approved=1 and emailsent=0"; 
        $inviteArray = Yii::app()->db->createCommand()->setText($sql)->queryAll();
        foreach ($inviteArray as $inviteRow) {
            $inviteId = $inviteRow['id'];
            $code = $inviteRow['code'];

			$inviteUrl = Yii::app()->request->getBaseUrl(true) . "/site/registration?cc=$code";
			$Name = "Yibes Invite"; //senders name
			$from = Yii::app()->params['fromEmail']; //senders e-mail adress
			$recipient = $inviteRow['email']; //recipient
			$mail_body = <<<EOT
            <html>
            <head>
                <title>Invitation to join Yibes</title>
            </head>
            <body>
            <br>
            Greetings,<br>
            <br> 
            Here is your invitation to join Yibes. <br> 
            Yibes is a social network to capture the things you would recommend to your friends. <br> 
            <br> 
            Please click on below url to activate and setup a user.
            <br> 
            <a href='$inviteUrl'>$inviteUrl</a> <br> 
            <br> 
            Thanks for Joining Yibes.  <br> 
            -The Yibes Team
            </body>
            </html>
EOT;
			$subject = "Invitation to join Yibes"; //subject
            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= "From: ". $Name . " <" . $from . ">\r\n"; //optional headerfields
			$ret = mail($recipient, $subject, $mail_body, $headers);

            if ($ret === true) {
                $emailtime = date("Y-m-d H:i:s",time());
                $updateSql = "update invite set emailsent=1, emailtime='$emailtime' where id=$inviteId";
                $inviteUpdateCmd = Yii::app()->db->createCommand($updateSql);
                if ($inviteUpdateCmd->execute() > 0) {
                    $emailCounter++;
                }
            }
        }

        return ($emailCounter);
    }
}
