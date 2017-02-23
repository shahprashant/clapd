<?php

/**
 * This is the model class for table "clap".
 *
 * The followings are the available columns in table 'clap':
 * @property integer $id
 * @property string $title
 * @property string $clap
 * @property string $image
 * @property string $userId
 * @property integer $categoryId
 * @property double $rating
 * @property integer $privacy
 * @property integer $question
 * @property string $createtime
 * @property string $updatetime
 * @property string $refreshtime
 * @property string $imageFile
 */
class Clap extends CActiveRecord
{
	
	public $imageFile; // used to handle file upload
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Clap the static model class
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
		return 'clap';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, clap, userId, categoryId', 'required'),
			array('categoryId', 'numerical', 'integerOnly'=>true),
			array('rating', 'numerical'),
			array('privacy','numerical'),
			array('question','numerical'),
			array('title', 'length', 'max'=>255),			
			array('image', 'length', 'max'=>100),
			array('userId', 'length', 'max'=>20),
			array('updatetime, refreshtime', 'safe'),
		 	array('imageFile', 'file', 'types'=>'jpg, gif, png', "allowEmpty" => true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, clap, image, userId, categoryId, rating, createtime, updatetime, refreshtime', 'safe', 'on'=>'search'),
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
			'title' => 'Title',
			'clap' => 'Clap',
			'image' => 'Image',
			'userId' => 'User',
			'categoryId' => 'Category',
			'rating' => 'Rating',
			'privacy' => 'Privacy',
			'question' => 'Question',
			'createtime' => 'Createtime',
			'updatetime' => 'Updatetime',
			'refreshtime' => 'Refreshtime',
			'imageFile' => 'ImageFile',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('clap',$this->clap,true);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('userId',$this->userId,true);
		$criteria->compare('categoryId',$this->categoryId);
		$criteria->compare('rating',$this->rating);
		$criteria->compare('privacy', $this->privacy);
		$criteria->compare('question', $this->question);
		$criteria->compare('createtime',$this->createtime,true);
		$criteria->compare('updatetime',$this->updatetime,true);
		$criteria->compare('refreshtime',$this->refreshtime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	/*
	 * get Claps for User
	 */
	public function getClapsforUser($userId, $numOfCats, $clapsPerCat, $from=0)
	{
		$retArray = array();
		
		// get connections for the user
		$connectionsReader = Yii::app()->db->createCommand()
			->select('*')
			->from('connections c')
			->where("userId=$userId")
			->query();
		while(($connectionsRow=$connectionsReader->read())!==false) {
			$connectionsArray[] = $connectionsRow['follows'];
		}
		
		// add self to connections
		$connectionsArray[] = $userId;
		
		$connectionsStr = implode(',',$connectionsArray);
			
		// select unique categories ordered by time of last update desc
		$catsql = "select y.categoryId, c.category, MAX(y.createtime) as maxtime, count(*) from clap y, category c 
		           where y.categoryId = c.id and y.userId IN ($connectionsStr)  
		           group by y.categoryId 
		           order by maxtime desc limit $from, $numOfCats";
		$uniqueCategoryReader = Yii::app()->db->createCommand()
								->setText($catsql)
								->query();
		
		// for each category get some latest claps
		while(($uniqueCategoryRow=$uniqueCategoryReader->read())!==false) {
			$catid = $uniqueCategoryRow['categoryId'];
			$catText = $uniqueCategoryRow['category'];
			
			//var_dump($catid);
			$clapsArray = Yii::app()->db->createCommand()
			->select('y.id,y.title,y.clap,y.rating,y.question,y.privacy,y.categoryId as catid,y.createtime,y.image, y.postScore, y.feedbackScore, u.username,u.name,c.category,c.parent')
			->from('clap y')
			->join('user u', 'y.userId=u.id')
			->join('category c', 'y.categoryId=c.id')
			->where('y.userId IN (' . $connectionsStr . ')' . ' AND ' . 'y.categoryId = ' . $catid)
			->order('y.createtime desc')
			->limit($clapsPerCat)			
			->queryAll();
			
			$clapIdsArray = array();
			
			if (count($clapsArray) > 0) {
				$clapsArray = $this->getUseractionsOnClaps($clapsArray);
				$retArray[$catid]['catText'] = $catText;
				$retArray[$catid]['claps'] = $clapsArray;
			}  // end if (count($clapsArray)
		
		} // end of while 
		return $retArray;
	}
	
	public function getClapsforCategory($userId, $catId, $limit=20, $from=0)
	{
		$retArray = array();
	
		if ($from < 0) {
			$from = 0;
		}
		
		// find out if its a top level category 		
		$categoryRow=Category::model()->find('id=:id', array(':id'=> $catId));
		
		$catParent = $categoryRow['parent'];
		
		$connectionsReader = Yii::app()->db->createCommand()
		->select('*')
		->from('connections c')
		->where("userId=$userId")
		->query();
		while(($connectionsRow=$connectionsReader->read())!==false) {
			$connectionsArray[] = $connectionsRow['follows'];
		}
		
		// add self to connections
		$connectionsArray[] = $userId;
		
		$connectionsStr = implode(',',$connectionsArray);
		
		$clapsCommand = Yii::app()->db->createCommand()
			->select('y.id,y.title,y.clap,y.rating,y.privacy,y.image,y.createtime,y.postScore,y.feedbackScore,u.username,u.name,c.category')
			->from('clap y')
			->join('user u', 'y.userId=u.id')
			->join('category c', 'y.categoryId=c.id')
			->order('y.createtime desc')
			->limit($limit, $from);
		
		if ($catParent == "0") {
			$leafCategoryArray = array();
			$leafCategories = Yii::app()->db->createCommand()
					->select('c.id')
					->from('category c')
					->where("c.parent = $catId")
					->queryAll();
			foreach ($leafCategories as $leafCat) {
				$leafCategoryArray[] = $leafCat['id'];
			}
			$leafCategoriesStr = join(",", $leafCategoryArray);
			
			$clapsCommand->where("y.userId IN ($connectionsStr) AND y.categoryId IN ($leafCategoriesStr)");
		} else {
			$clapsCommand->where("y.userId IN ($connectionsStr) AND y.categoryId = $catId");
		}

		$clapsDataArray = array();
		$clapsDataArray = $clapsCommand->queryAll();
		
		if (count($clapsDataArray) > 0) {
			$clapsDataArray = $this->getUseractionsOnClaps($clapsDataArray);
			$categoryRow=Category::model()->find('id=:id', array(':id'=> $catId));
			$catText = $categoryRow['category'];
		
		
			$retArray[$catId]['catText'] = $catText;
			$retArray[$catId]['claps'] = $clapsDataArray;	
		}	
		
		return $retArray;
	}
	
	public function getClapsforCategoryByUser($userId, $catId, $rootcat=0, $numOfUsers, $clapsPerUser)
	{
		$retArray = array();
		$clapsData = array();
		
		$categoryRow=Category::model()->find('id=:id', array(':id'=> $catId));
		$catText = $categoryRow['category'];
		$catParent = $categoryRow['parent'];

		$connectionsReader = Yii::app()->db->createCommand()
		->select('*')
		->from('connections c')
		->where("userId=$userId")
		->query();
		while(($connectionsRow=$connectionsReader->read())!==false) {
			$connectionsArray[] = $connectionsRow['follows'];
		}
		
		// add self to connections
		$connectionsArray[] = $userId;
		
		$connectionsStr = implode(',',$connectionsArray);
		
		// If category is a top level category then find out the leaf categories
		if ($catParent == "0") {
			$leafCategoryArray = array();
			$leafCategories = Yii::app()->db->createCommand()
				->select('c.id')
			->from('category c')
			->where("c.parent = $catId")
			->queryAll();
			foreach ($leafCategories as $leafCat) {
				$leafCategoryArray[] = $leafCat['id'];
			}
			$leafCategoriesStr = join(",", $leafCategoryArray);
		}
		
		// select unique users ordered by time of last update desc
		if ($catParent == "0") {
			$usersql = "select y.userId, u.username, u.name, u.avatar, y.categoryId, MAX(y.createtime) as maxtime, count(*) from clap y, user u
						where y.userId = u.id and y.userId IN ($connectionsStr) and y.categoryId IN ($leafCategoriesStr)
						group by y.userId
						order by maxtime desc limit 0, $numOfUsers";
			$uniqueUserReader = Yii::app()->db->createCommand()
				->setText($usersql)
				->query();
		} else {
			$usersql = "select y.userId, u.username, u.name, u.avatar, y.categoryId, MAX(y.createtime) as maxtime, count(*) from clap y, user u
			where y.userId = u.id and y.userId IN ($connectionsStr) and y.categoryId = $catId
			group by y.userId
			order by maxtime desc limit 0, $numOfUsers";
			$uniqueUserReader = Yii::app()->db->createCommand()
			->setText($usersql)
			->query();			
		}
		
		// for each user get some latest claps
		while(($uniqueUserRow=$uniqueUserReader->read())!==false) {
		
			$uid = $uniqueUserRow['userId'];
			$author = $uniqueUserRow['name'];
			$username = $uniqueUserRow['username'];
			$avatar = $uniqueUserRow['avatar'];
			$avatarUrl = getProfileImageUrl($avatar, "sqr", $username);
			
			//var_dump($catid);
			$clapsCommand = Yii::app()->db->createCommand()
			->select('y.id,y.title,y.clap,y.rating,y.privacy,y.createtime,y.image, y.postScore, y.feedbackScore, u.username,u.name')
			->from('clap y')
			->join('user u', 'y.userId=u.id')
			->order('y.createtime desc')
			->limit($clapsPerUser);

			
			if ($catParent == "0") {			
				$clapsCommand->where("y.categoryId IN ($leafCategoriesStr) and y.userId=$uid");
			} else {
				$clapsCommand->where("y.categoryId = $catId and y.userId=$uid");
			}

			$clapsArray = $clapsCommand->queryAll();

			if (count($clapsArray) > 0) {
				$clapsArray = $this->getUseractionsOnClaps($clapsArray);
				$clapsData[$username]['author'] = $author;
				$clapsData[$username]['claps'] = $clapsArray;
				$clapsData[$username]['avatarUrl'] = $avatarUrl;
			}  // end if (count($clapsArray)
		}
		
		$retArray['catText'] = $catText;
		$retArray['clapsData'] = $clapsData;
		
		return $retArray;
		
	}	

	public function getClapsByUser($username, $limit, $from=0, $sort="", $numOfCats=0, $clapsPerCat=0)
	{
		$retArray = array();

		$userRow=User::model()->find('username=:username', array(':username'=> $username));
		$userId = $userRow['id'];
		$author = $userRow['name'];
		$avatar = $userRow['avatar'];
	
		if ($sort == "") {
			$clapsArray = Yii::app()->db->createCommand()
			->select('y.id,y.title,y.clap,y.rating,y.privacy,y.image,y.createtime, y.postScore,y.feedbackScore, u.username,u.name,c.category,c.id as catid')
			->from('clap y')
			->join('user u', 'y.userId=u.id')
			->join('category c', 'y.categoryId=c.id')
			->where('u.username = "' . $username . '"')
			->order('y.createtime desc')
			->limit($limit, $from)
			->queryAll();			
			
			if (count($clapsArray) > 0) {
				$clapsArray = $this->getUseractionsOnClaps($clapsArray);
				$retArray[$username]['claps'] = $clapsArray;
			}
			$retArray[$username]['authorId'] = $userId;
			$retArray[$username]['author'] = $author;
			$retArray[$username]['avatar'] = $avatar;
			
		} elseif ($sort == "cat") {
			
			$clapsData = array();
			
			$catsql = "select y.categoryId, c.category, MAX(y.createtime) as maxtime, count(*) from clap y, category c
				where y.categoryId = c.id and y.userId = $userId 
				group by y.categoryId 
				order by maxtime desc limit $from, $numOfCats";
			
			$uniqueCategoryReader = Yii::app()->db->createCommand()
			->setText($catsql)
			->query();
			
			
			while(($uniqueCategoryRow=$uniqueCategoryReader->read())!==false) {
				$catid = $uniqueCategoryRow['categoryId'];
				$catText = $uniqueCategoryRow['category'];
			
				//var_dump($catid);
				$clapsArray = Yii::app()->db->createCommand()
				->select('y.id,y.title,y.clap,y.rating,y.privacy,y.image,y.createtime,y.image, y.postScore, y.feedbackScore,u.username,u.name,c.category,c.parent')
				->from('clap y')
				->join('user u', 'y.userId=u.id')
				->join('category c', 'y.categoryId=c.id')
				->where("y.userId = $userId AND y.categoryId = $catid")
				->order('y.createtime desc')
				->limit($clapsPerCat)
				->queryAll();		
				
			
				if (count($clapsArray) > 0) {
					$clapsArray = $this->getUseractionsOnClaps($clapsArray);
				
					$clapsData[$catid]['catText'] = $catText;
					$clapsData[$catid]['claps'] = $clapsArray;
				}
			} // end of while
			$retArray['authorId'] = $userId;
			$retArray['author'] = $author;
			$retArray['avatar'] = $avatar;
			$retArray['clapsData'] = $clapsData;
		} elseif ($sort == "score") {
			$clapsArray = Yii::app()->db->createCommand()
			->select('y.id,y.title,y.clap,y.rating,y.privacy,y.image,y.createtime, y.postScore,y.feedbackScore, (y.postScore + y.feedbackScore) as yscore, u.username,u.name,c.category,c.id as catid')
			->from('clap y')
			->join('user u', 'y.userId=u.id')
			->join('category c', 'y.categoryId=c.id')
			->where('u.username = "' . $username . '"')
			->order('yscore desc')
			->limit($limit, $from)
			->queryAll();			
			
			if (count($clapsArray) > 0) {
				$clapsArray = $this->getUseractionsOnClaps($clapsArray);
				$retArray[$username]['claps'] = $clapsArray;
			}
		    $retArray[$username]['authorId'] = $userId;
			$retArray[$username]['author'] = $author;
			$retArray[$username]['avatar'] = $avatar;
        }

		return $retArray;
	}	
	
	public function getClapsByUserHashtag($username, $tag, $limit=20, $from=0)
	{
		$retArray = array();
		
		$userRow=User::model()->find('username=:username', array(':username'=> $username));
		$userId = $userRow['id'];
		$author = $userRow['name'];
		$avatar = $userRow['avatar'];
		
		$hashtagRow=Hashtag::model()->find('tag=:tag', array(':tag'=> "#".$tag));
		$hashtagId = $hashtagRow['id'];
		
		$clapsArray = Yii::app()->db->createCommand()
		->select('y.id,u.username,y.title,y.clap,y.rating,y.privacy,y.image,y.createtime, y.postScore,y.feedbackScore, c.category,c.id as catid')
		->from('clap y')
		->join('claptag yt', 'yt.clapId = y.id')		
		->join('category c', 'y.categoryId=c.id')
		->join('user u', 'y.userId=u.id')
		->where("y.userId = '$userId' and yt.hashtagId = '$hashtagId'")
		->order('y.createtime desc')
		->limit($limit, $from)
		->queryAll();
		/*
		$clapsText = Yii::app()->db->createCommand()
		->select('y.id,y.title,y.clap,y.rating,y.createtime, y.postScore,y.feedbackScore, c.category,c.id as catid')
		->from('clap y')
		->join('claptag yt', 'yt.clapId = y.id')
		->join('category c', 'y.categoryId=c.id')
		->where("y.userId = '$userId' and yt.hashtagId = '$hashtagId'")
		->order('y.createtime desc')
		->limit($limit, $from)
		->getText();*/
		

		
		if (count($clapsArray) > 0) {
			$clapsArray = $this->getUseractionsOnClaps($clapsArray);
		    $retArray[$username]['authorId'] = $userId;
			$retArray[$username]['author'] = $author;
			$retArray[$username]['avatar'] = $avatar;
			$retArray[$username]['claps'] = $clapsArray;
		}
		
		return ($retArray);

	}

	public function getClapsByUserForCategory($username, $catId, $limit=20, $from=0)
	{
		$retArray = array();
		
		$userRow=User::model()->find('username=:username', array(':username'=> $username));
		$userId = $userRow['id'];
		$author = $userRow['name'];
		$avatar = $userRow['avatar'];

		$categoryRow=Category::model()->find('id=:id', array(':id'=> $catId));
		$catText = $categoryRow['category'];
		$catParent = $categoryRow['parent'];
	
		$clapsCommand = Yii::app()->db->createCommand()
		->select("y.id, u.username as username,y.title,y.clap,y.rating,y.privacy,y.image,y.createtime, y.postScore,y.feedbackScore, c.category,c.id as catid")
		->from('clap y')
		->join('category c', 'y.categoryId=c.id')
		->join('user u', 'y.userId=u.id')
		->order('y.createtime desc')
		->limit($limit, $from);

		if ($catParent == "0") {
			$leafCategoryArray = array();
			$leafCategories = Yii::app()->db->createCommand()
					->select('c.id')
					->from('category c')
					->where("c.parent = $catId")
					->queryAll();
			foreach ($leafCategories as $leafCat) {
				$leafCategoryArray[] = $leafCat['id'];
			}
			$leafCategoriesStr = join(",", $leafCategoryArray);
			
			$clapsCommand->where("y.userId = '$userId' AND y.categoryId IN ($leafCategoriesStr)");
		} else {
		    $clapsCommand->where("y.userId = '$userId' and y.categoryId = '$catId'");
		}

		$clapsArray = $clapsCommand->queryAll();
		
		if (count($clapsArray) > 0) {
			$clapsArray = $this->getUseractionsOnClaps($clapsArray);
		    $retArray[$username]['authorId'] = $userId;
			$retArray[$username]['author'] = $author;
			$retArray[$username]['avatar'] = $avatar;
			$retArray[$username]['claps'] = $clapsArray;
			$retArray[$username]['catText'] = $catText;
		}
		
		return ($retArray);
	}
	
	public function getUseractionsOnClaps($clapsArray)	 
	{
		$clapIdsArray = array();
		
		if (count($clapsArray) > 0) {
		
			// form a string of clapIds for that category
			foreach ($clapsArray as $clapRow) {
				$clapIdsArray[] = $clapRow['id'];
			}
			$clapIdsStr = join(",", $clapIdsArray);
		
			// get the number of likes, usefuls, saves for each clap
			$userActionsReader = Yii::app()->db->createCommand()
			->select('clapId, type, count(*) as total')
			->from('useractions u')
			->where("clapId IN ($clapIdsStr)")
			->group('clapId, type')
			->query();
		
		
			$userActions = array();
			while(($userActionsReaderRow=$userActionsReader->read())!==false) {
				$userActionsClapId = $userActionsReaderRow['clapId'];
				$userActionsType = $userActionsReaderRow['type'];
				$userActionsTotal = $userActionsReaderRow['total'];
				$userActions[$userActionsClapId][$userActionsType] = $userActionsTotal;
			}
		
			// find out whether user has performed any likes, usefuls or saves
			$selfActionsReader = Yii::app()->db->createCommand()
			->select('clapId, type, userId')
			->from('useractions u')
			->where("clapId IN ($clapIdsStr)")
			->query();
		
			$selfActions = array();
			while(($selfActionsRow=$selfActionsReader->read())!==false) {
				$selfActionsClapId = $selfActionsRow['clapId'];
				$selfActionsType = $selfActionsRow['type'];
				$selfActions[$selfActionsClapId][$selfActionsType] = 1;
			}
		
		
			// Attach the likes, usefuls, saves info to each Clap record
			foreach ($clapsArray as $index => $clapRow) {
				if (isset($userActions[$clapRow['id']]["1"])) {
					$clapRow['likes'] = $userActions[$clapRow['id']]["1"];
				};
				if (isset($userActions[$clapRow['id']]["2"])) {
					$clapRow['usefuls'] = $userActions[$clapRow['id']]["2"];
				}
				if (isset($userActions[$clapRow['id']]["3"])) {
					$clapRow['saves'] = $userActions[$clapRow['id']]["3"];
				}
		
				// set values for selfLike, selfUseful and selfSave
				if (isset($selfActions[$clapRow['id']]["1"])) {
					$clapRow['selfLike'] = 1;
		
				}
				if (isset($selfActions[$clapRow['id']]["2"])) {
					$clapRow['selfUseful'] = 1;
				}
				if (isset($selfActions[$clapRow['id']]["3"])) {
					$clapRow['selfSave'] = 1;
				}
				$clapsArray[$index] = $clapRow;
			}
		}
			
			return $clapsArray;
		
	}
	
	public function getClapDetails($clapId) 
	{
		$clapData = Yii::app()->db->createCommand()
		->select('y.id,y.title,y.clap,y.rating,y.privacy,y.question,y.createtime,y.image,y.postScore,y.feedbackScore,u.id as userId,u.username,u.name,u.avatar,c.category,c.id as categoryId')
		->from('clap y')
		->join('user u', 'y.userId=u.id')		
		->join('category c', 'y.categoryId=c.id')
		->where('y.id = ' . $clapId)
		->limit(1)
		->queryRow();

		// get the number of likes, usefuls, saves for each clap
		$userActionsReader = Yii::app()->db->createCommand()
		->select('clapId, type, count(*) as total')
		->from('useractions u')
		->where("clapId = " . $clapData['id'])
		->group('type')
		->query();


		
		$userActions = array();
		while(($userActionsReaderRow=$userActionsReader->read())!==false) {
			$userActionsType = $userActionsReaderRow['type'];
			$userActionsTotal = $userActionsReaderRow['total'];
			if ($userActionsType == "1") {
				$clapData['likes'] = $userActionsTotal;
			}
			if ($userActionsType == "2") {
				$clapData['usefuls'] = $userActionsTotal;
			}
			if ($userActionsType == "3") {
				$clapData['saves'] = $userActionsTotal;
			}
		}

		// find out whether user has performed any likes, usefuls or saves
		$selfActionsReader = Yii::app()->db->createCommand()
		->select('clapId, type, userId')
		->from('useractions u')
		->where("clapId = " . $clapData['id'])
		->query();
		
		$selfActions = array();
		while(($selfActionsRow=$selfActionsReader->read())!==false) {
			$selfActionsClapId = $selfActionsRow['clapId'];
			$selfActionsType = $selfActionsRow['type'];
			if ($selfActionsType == "1") {
				$clapData['selfLike'] = 1;
			}
			if ($selfActionsType == "2") {
				$clapData['selfUseful'] = 2;
			}
			if ($selfActionsType == "3") {
				$clapData['selfSave'] = 3;
			}
		}
		
		return ($clapData);
	}
	
	public function updateImage($imgName)	
	{
		if ($imgName) {
			$clapUpdateCommand = Yii::app()->db->createCommand();
			$clapUpdateCommand->update('clap', array(
					'image'=> $imgName,
			), 'id=:id', array(':id'=>$this->id));
		}
	}
	
	public function addFeedbackScore($clapId, $feedbackScore)
	{
		$sql = "update clap set feedbackScore = feedbackScore + $feedbackScore where id=$clapId";
		$clapUpdateCommand = Yii::app()->db->createCommand($sql);
		if ($clapUpdateCommand->execute() > 0) {
			return true;
		} else {
			return false;
		}		
	}
	
	public function saveHashtags($hashtags)
	{
		$ret = true;
		// check if hash tag already exists 
		foreach ($hashtags as $hashtag) {
			$hashtagId = '';
			

			$hashtagRow=Hashtag::model()->find('tag=:tag', array(':tag'=> $hashtag));

			if (count($hashtagRow) > 0) {
				$hashtagId = $hashtagRow['id'];
			} else {
				// need to create a hashtag 
				$sql = "INSERT INTO hashtag (tag) values ('$hashtag') ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)"; 
				

				$hashtagReader = Yii::app()->db->createCommand($sql);
				if ($hashtagReader->execute() > 0) {
					$hashtagId = Yii::app()->db->lastInsertID;
				}
			}
			if ($hashtagId) {
				// insert into claptag table 
				$claphashSql = "INSERT INTO claptag (clapId, clapOwnerId, categoryId, hashtagId) values ('$this->id','$this->userId','$this->categoryId','$hashtagId')";
				$claphashReader = Yii::app()->db->createCommand($claphashSql);
				if ($claphashReader->execute() > 0) {

				} else {
					$ret = false;
				}
			}
		}
		
		return $ret;
	}
	
	public function saveAnswer($rClap) 
	{
		$sql = "INSERT INTO answers (clapId, replyToClapId) values ('$this->id', '$rClap')";
		$answersCmd = Yii::app()->db->createCommand($sql);
		if ($answersCmd->execute() > 0) {
			$ret = true;
		} else {
			$ret = false;
		}
		return ($ret);
		
	}

    public function deleteClap() 
    {
        $clapId = $this->id;
        $clapSql = "delete from clap where id='$clapId'";
		$clapCmd = Yii::app()->db->createCommand($clapSql);
        if ($clapCmd->execute() > 0) {
            $answersSql = "delete from answers where clapId = '$clapId'";
            $answersCmd = Yii::app()->db->createCommand($answersSql);
            $answersCmd->execute();

            $commentSql = "delete from comment where clapId = '$clapId'";
            $commentCmd = Yii::app()->db->createCommand($commentSql);
            $commentCmd->execute();

            $useractionsSql = "delete from useractions where clapId = '$clapId'";
            $useractionsCmd = Yii::app()->db->createCommand($useractionsSql);
            $useractionsCmd->execute();
        }
    }
	
	public function getClapAnswers($clapId)
	{
		$retArray = array();
	
        // get the question
		$clapsQuestionArray = Yii::app()->db->createCommand()
		->select('y.id,y.title,y.clap,y.rating,y.privacy,y.image,y.createtime, y.postScore,y.feedbackScore')
		->from('clap y')
		->where("y.id = '$clapId'")
        ->queryAll();

        // get the answers
		$clapsAnswersArray = Yii::app()->db->createCommand()
		->select('y.id,y.title,y.clap,y.rating,y.privacy,y.image,y.createtime, y.postScore,y.feedbackScore')
		->from('clap y')
		->join('answers a', 'a.clapId = y.id')		
		->where("a.replyToClapId = '$clapId'")
		->order('y.createtime desc')
		->queryAll();

        $clapsArray = array_merge($clapsQuestionArray, $clapsAnswersArray);
		
		if (count($clapsArray) > 0) {
			$clapsArray = $this->getUseractionsOnClaps($clapsArray);
			$retArray[$clapId]['claps'] = $clapsArray;
		}
		
		return ($retArray);
	}

	public function getSavedClaps($username)
	{
		$retArray = array();
		
		$userRow=User::model()->find('username=:username', array(':username'=> $username));
		$userId = $userRow['id'];
		$author = $userRow['name'];
		$avatar = $userRow['avatar'];

		$clapsArray = Yii::app()->db->createCommand()
		->select('y.id,y.title,y.clap,y.rating,y.privacy,y.image,y.createtime, y.postScore,y.feedbackScore, c.category,c.id as catid')
		->from('clap y')
		->join('useractions u', 'u.clapId = y.id')		
		->join('category c', 'y.categoryId=c.id')
		->where("u.userId = '$userId' and u.type = 3")
		->order('y.createtime desc')
		->queryAll();

		
		if (count($clapsArray) > 0) {
			$clapsArray = $this->getUseractionsOnClaps($clapsArray);
		}
		    $retArray[$username]['authorId'] = $userId;
			$retArray[$username]['author'] = $author;
			$retArray[$username]['avatar'] = $avatar;
			$retArray[$username]['claps'] = $clapsArray;
		
		return ($retArray);

	}

	public function getHashtags($username)
	{
		$retArray = array();
		
		$userRow=User::model()->find('username=:username', array(':username'=> $username));
		$userId = $userRow['id'];
		$author = $userRow['name'];
		$avatar = $userRow['avatar'];

		$tagsArray = Yii::app()->db->createCommand()
		->select('h.id, h.tag, count(*) as numClaps')
		->from('claptag y')
		->join('hashtag h', "h.id = y.hashtagId")		
		->where("y.clapOwnerId = '$userId' ")
        ->group('y.hashTagId')
        ->order('numClaps desc')
		->queryAll();

		$retArray[$username]['authorId'] = $userId;
		$retArray[$username]['author'] = $author;
		$retArray[$username]['avatar'] = $avatar;
		$retArray[$username]['tagsArray'] = $tagsArray;
		
		return ($retArray);

	}

	public function getSummaryClapsByUserForCategory($username, $catId, $excludeClap, $limit=3)
	{
		$retArray = array();
		
		$userRow=User::model()->find('username=:username', array(':username'=> $username));
		$userId = $userRow['id'];
		$author = $userRow['name'];
		$avatar = $userRow['avatar'];

		$categoryRow=Category::model()->find('id=:id', array(':id'=> $catId));
		$catText = $categoryRow['category'];
		$catParent = $categoryRow['parent'];
	
		$clapsCommand = Yii::app()->db->createCommand()
		->select('y.id,u.username,u.name,y.title,y.rating,y.privacy,y.createtime, y.postScore,y.feedbackScore, c.category,c.id as catid,c.parent')
		->from('clap y')
		->join('category c', 'y.categoryId=c.id')
        ->join('user u', 'y.userId = u.id')
		->order('y.createtime desc')
		->limit($limit, 0);

        $leafCategoryArray = array();
		$leafCategories = Yii::app()->db->createCommand()
					->select('c.id')
					->from('category c')
					->where("c.parent = $catParent")
					->queryAll();
		foreach ($leafCategories as $leafCat) {
				$leafCategoryArray[] = $leafCat['id'];
		}
	    $leafCategoriesStr = join(",", $leafCategoryArray);
			
	    $clapsCommand->where("y.userId = '$userId' AND y.categoryId IN ($leafCategoriesStr) AND y.id != $excludeClap");

    	$clapsArray = $clapsCommand->queryAll();

        $retArray = $clapsArray;
		
		
	    return ($retArray);
	}

	public function getSummaryClapsByUserExcludingCategory($username, $catId, $limit=2)
	{
		$retArray = array();
		
		$userRow=User::model()->find('username=:username', array(':username'=> $username));
		$userId = $userRow['id'];
		$author = $userRow['name'];
		$avatar = $userRow['avatar'];

		$categoryRow=Category::model()->find('id=:id', array(':id'=> $catId));
		$catText = $categoryRow['category'];
		$catParent = $categoryRow['parent'];
	
		$clapsCommand = Yii::app()->db->createCommand()
		->select('y.id,u.username,u.name,y.title,y.rating,y.privacy,y.createtime, y.postScore,y.feedbackScore, c.category,c.id as catid')
		->from('clap y')
		->join('category c', 'y.categoryId=c.id')
        ->join('user u', 'y.userId = u.id')
		->order('y.createtime desc')
		->limit($limit, 0);

        $leafCategoryArray = array();
		$leafCategories = Yii::app()->db->createCommand()
					->select('c.id')
					->from('category c')
					->where("c.parent = $catParent")
					->queryAll();
		foreach ($leafCategories as $leafCat) {
				$leafCategoryArray[] = $leafCat['id'];
		}
	    $leafCategoriesStr = join(",", $leafCategoryArray);
			
	    $clapsCommand->where("y.userId = '$userId' AND y.categoryId NOT IN ($leafCategoriesStr)");

        // debug query
        // var_dump($clapsCommand->getText());
	    $clapsArray = $clapsCommand->queryAll();
		
	    $retArray = $clapsArray;

		return ($retArray);
	}
}
