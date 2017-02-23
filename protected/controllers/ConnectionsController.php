<?php

include_once 'MyController.php';

class ConnectionsController extends MyController
{
	
	public $layout='//layouts/main';
	public $page;
	public $layoutParams;
	
	
	public function actionIndex()
	{
		$this->render('index');
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
	
	public function actionFollowers() {
		$connectionsModel = new Connections;
		$data = array(); 


		if (isset($_GET['user']) && ($_GET['user'] != '')) {
			$userId = $_GET['user'];
			$userInfo = User::model()->getUserInfo($userId);
		} else {
			$userId = Yii::app()->user->id;
			$userInfo = $this->user;
		}
		$followArray = $connectionsModel->getFollowers($userId);
		$data['followArray'] = $followArray; 
		$this->page = "Followers";
		if (isset($userInfo['avatar']) && ($userInfo['avatar'] != '')) {
			$this->layoutParams['avatarUrl'] = getProfileImageUrl($userInfo['avatar'], "sqr", $userInfo['username']);
			$this->layoutParams['author'] = $userInfo['name'];
			$this->layoutParams['profileUrl'] =  Yii::app()->request->getBaseUrl() . "/" . $userInfo['username'] . "/profile";
		};
		$this->render('connections/follow', $data);
	}
	
	public function actionFollowing() {
		$connectionsModel = new Connections;
		$data = array();
	
	
		if (isset($_GET['user']) && ($_GET['user'] != '')) {
			$userId = $_GET['user'];
			$userInfo = User::model()->getUserInfo($userId);
		} else {
			$userId = Yii::app()->user->id;
			$userInfo = $this->user;
		}
		$followArray = $connectionsModel->getFollowing($userId);
		$data['followArray'] = $followArray;
		$this->page = "Following";
		if (isset($userInfo['avatar']) && ($userInfo['avatar'] != '')) {
			$this->layoutParams['avatarUrl'] = getProfileImageUrl($userInfo['avatar'], "sqr", $userInfo['username']);
			$this->layoutParams['author'] = $userInfo['name'];
			$this->layoutParams['profileUrl'] =  Yii::app()->request->getBaseUrl() . "/" . $userInfo['username'] . "/profile";
		};

		$this->render('connections/follow', $data);
	}
	
	public function actionSetConnections() {
		if (isset($_GET['user'])) {
			$userId = $_GET['user'];
			$connectionsModel = new Connections;
			$ret = $connectionsModel->setConnections($userId);
			if ($ret === true) {
				echo "Connections set for user " . $_GET['user'];
			} else {
				echo "There was some error while setting connections !";
			}
		} else {
			echo "No user provided";
		}
		
	}
	
	public function actionBlockUser() {
		if (isset($_GET['userId']) && (isset($_GET['follows']))) {
			$ret = Connections::model()->blockUser($_GET['userId'], $_GET['follows']);
			$ret_json = json_encode($ret);
			echo $ret_json;
		}
	}
	
	public function actionUnfollowUser() {
		if (isset($_GET['userId']) && (isset($_GET['follows']))) {
			$ret = Connections::model()->UnfollowUser($_GET['userId'], $_GET['follows']);
			$ret_json = json_encode($ret);
			echo $ret_json;
		}
	}
	
    public function actionFindFriends() {
        $connectionsModel = new Connections;
        $this->page = "FindFriends";
        if ((isset($_POST['follow'])) && (!(Yii::app()->user->isGuest))) {
            $followUsersArray = $_POST['followUser'];
            // $followUsersArray = explode(",",$followUsers);
            if (count($followUsersArray) > 0) {
               $connectionsModel->followUsers(Yii::app()->user->id, $followUsersArray); 
            }    
            $this->redirect(Yii::app()->request->getBaseUrl() . "/connections/following");

        } elseif (!(Yii::app()->user->isGuest) && (isset($this->user['fbUserId']))) {
            $fbFriends = $connectionsModel->findUnconnectedFriends(Yii::app()->user->id);
            $data['fbFriends'] = $fbFriends;
            if (!Yii::app()->user->isGuest) {
    		    $avatar = $this->user['avatar']; 
    		    $this->layoutParams['avatarUrl'] = getProfileImageUrl($avatar, "sqr", Yii::app()->user->getName());
     		    $this->layoutParams['author'] = Yii::app()->user->name;
		        $this->layoutParams['profileUrl'] =  Yii::app()->request->getBaseUrl() . "/" . Yii::app()->user->username . "/profile";
            }
            $this->render('connections/findfriends', $data);
        }
    }
	
}
