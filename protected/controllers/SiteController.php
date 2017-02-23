<?php


include_once 'MyController.php';

class SiteController extends MyController
{

	public $layout='//layouts/main';
	public $page;
    public $pageDesp;
	public $action;
	public $layoutParams;
    public $showCommentForm;


	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
				// captcha action renders the CAPTCHA image displayed on the contact page
				'captcha'=>array(
						'class'=>'CCaptchaAction',
						'backColor'=>0xFFFFFF,
				),
				// page action renders "static" pages stored under 'protected/views/site/pages'
				// They can be accessed via: index.php?r=site/page&view=FileName
				'page'=>array(
						'class'=>'CViewAction',
				),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		$model=new LoginForm;
		$data = array();

		if ((Yii::app()->user->isGuest) && (!isset($_GET['user']))) {
			// collect user input data
			if(isset($_POST['LoginForm']))
			{
				$model->attributes=$_POST['LoginForm'];
				// validate user input and redirect to the previous page if valid
				if($model->validate() && $model->login()) {
					$this->redirect(Yii::app()->user->returnUrl);
						
				}
			} else {
				$clapsPromos = getClapsPromos();
				$data['clapsPromos'] = $clapsPromos;
                $this->page = "Home";
		        $this->layout = '//layouts/invite';
				$this->render('site/promo', $data);
			}
		} else {
            
			if (isset($_GET['catid']) && isset($_GET['user'])) {
                // Claps by User for a particular category
				$clapsModel = new Clap;
                $clapsData = $clapsModel->getClapsByUserForCategory($_GET['user'], $_GET['catid']);
			    $data['clapsData'] = $clapsData;
			    $this->page = 'UserClapsForCategory';
				$this->layoutParams['catText'] = $clapsData[$_GET['user']]['catText'];
				$this->layoutParams['author'] = $clapsData[$_GET['user']]['author'];
				$avatar = $clapsData[$_GET['user']]['avatar'];
				$this->layoutParams['avatarUrl'] = getProfileImageUrl($avatar, "sqr", $_GET['user']);
				$this->layoutParams['profileUrl'] =  Yii::app()->request->getBaseUrl() . "/" . $_GET['user'] . "/profile";
				$this->layoutParams['userClapsUrl'] =  Yii::app()->request->getBaseUrl() . "/" . $_GET['user']; 
                // set facebook meta tags
                Yii::app()->facebook->ogTags['og:title'] = $clapsData[$_GET['user']]['author'] . "'s Claps in " . $clapsData[$_GET['user']]['catText']; 
                Yii::app()->facebook->ogTags['og:description'] = "Latest Reviews: " .getReviewedTitles($clapsData[$_GET['user']]['claps'],200) . "...";
                Yii::app()->facebook->ogTags['og:site_name'] = $clapsData[$_GET['user']]['author']. "'s Claps"; 

                Yii::app()->facebook->ogTags['og:type'] = "clapsnet:entity"; 
                Yii::app()->facebook->ogTags['og:image'] = Yii::app()->request->getBaseUrl(true) . "/images/clapd_stacked_250.png";
			    $this->render('site/index', $data);

			} elseif (isset($_GET['catid'])) {
                // Claps By Category
				$clapsModel = new Clap;

				if (isset($_GET['sort']) && ($_GET['sort'] == 'user')) {
					$clapsInfo = $clapsModel->getClapsforCategoryByUser(Yii::app()->user->id, $_GET['catid'], 0, Yii::app()->params['numContentRows']+1, Yii::app()->params['numContentCols']+1);
					$data['clapsData'] = $clapsInfo['clapsData'];
					$this->page = 'CategoryClapsByUser';
					$this->layoutParams['catText'] = $clapsInfo['catText'];

				} else {
					$clapsData = $clapsModel->getClapsforCategory(Yii::app()->user->id, $_GET['catid']);
					$data['clapsData'] = $clapsData;
					$this->page = 'CategoryClaps';
					$this->layoutParams['catText'] = $clapsData[$_GET['catid']]['catText'];
				}
			    $avatar = $this->user['avatar']; 
				$this->layoutParams['avatarUrl'] = getProfileImageUrl($avatar, "sqr", Yii::app()->user->getName());
				$this->layoutParams['author'] = Yii::app()->user->name;
				$this->layoutParams['profileUrl'] =  Yii::app()->request->getBaseUrl() . "/" . Yii::app()->user->username . "/profile";
			    $this->render('site/index', $data);
			} elseif (isset($_GET['user']) && isset($_GET['sort']) && ($_GET['sort'] == 'cat')) {
				
				// Claps by User but Sorted by Category
				$clapsModel = new Clap;
				$clapsInfo = $clapsModel->getClapsByUser($_GET['user'], 0, 0, "cat", Yii::app()->params['numContentRows']+1, Yii::app()->params['numContentCols']+1 );

				if (count($clapsInfo) > 0) {
					$clapsData = $clapsInfo['clapsData'];
				}
				
				if (count($clapsData) > Yii::app()->params['numContentRows']) {
					$clapsData = array_slice($clapsData,0,Yii::app()->params['numContentRows'], true);
					$data['nextClapRow'] = Yii::app()->params['numContentRows'];
				} else {
					$data['nextClapRow'] = -1; // end of data.
				}	
				
				if (is_array($clapsData)) {
					$data['clapsData'] = $clapsData;
				}
								
				$this->page = 'UserClapsByCategory';
				$this->layoutParams['author'] = $clapsInfo['author'];
				$this->layoutParams['authorId'] = $clapsInfo['authorId'];
				$avatar = $clapsInfo['avatar'];
				$this->layoutParams['avatarUrl'] = getProfileImageUrl($avatar, "sqr", $_GET['user']);
				$this->layoutParams['profileUrl'] =  Yii::app()->request->getBaseUrl() . "/" . $_GET['user'] . "/profile";
			    $this->render('site/index', $data);
			} elseif (isset($_GET['user']) && isset($_GET['sort']) && ($_GET['sort'] == 'score')) {
				// Claps by User but Sorted by Score
					$clapsModel = new Clap;
					$clapsData = $clapsModel->getClapsByUser($_GET['user'], Yii::app()->params['numDefaultClaps'], 0, "score");
					$data['clapsData'] = $clapsData;
					$this->page = 'UserClapsByScore';
					$this->layoutParams['author'] = $clapsData[$_GET['user']]['author'];
					$this->layoutParams['authorId'] = $clapsData[$_GET['user']]['authorId'];
					$avatar = $clapsData[$_GET['user']]['avatar'];
					$this->layoutParams['avatarUrl'] = getProfileImageUrl($avatar, "sqr", $_GET['user']);
					$this->layoutParams['profileUrl'] =  Yii::app()->request->getBaseUrl() . "/" . $_GET['user'] . "/profile";
			        $this->render('site/index', $data);

			} elseif (isset($_GET['user']) && (isset($_GET['tag']))) {

					// Claps by Hashtags
					$clapsModel = new Clap;
					$clapsData = $clapsModel->getClapsByUserHashtag($_GET['user'], $_GET['tag']);
					$data['clapsData'] = $clapsData;
					$this->page = 'UserHashtagClaps';
					$this->layoutParams['hashtag'] = $_GET['tag'];
					$this->layoutParams['author'] = $clapsData[$_GET['user']]['author'];
					$this->layoutParams['profileUrl'] =  Yii::app()->request->getBaseUrl() . "/" . $_GET['user'] . "/profile";
					$avatar = $clapsData[$_GET['user']]['avatar'];
					$this->layoutParams['avatarUrl'] = getProfileImageUrl($avatar, "sqr", $_GET['user']);
			        $this->render('site/index', $data);

			} elseif (isset($_GET['user']) && (isset($_GET['tags']))) {

                    // Get User Tags
					$clapsModel = new Clap;
                    $tagsData = $clapsModel->getHashtags($_GET['user']);
                    $data['tagsArray'] = $tagsData[$_GET['user']]['tagsArray'];
                    $this->page = 'UserTags';
					$this->layoutParams['author'] = $tagsData[$_GET['user']]['author'];
					$this->layoutParams['profileUrl'] =  Yii::app()->request->getBaseUrl() . "/" . $_GET['user'] . "/profile";
					$avatar = $tagsData[$_GET['user']]['avatar'];
					$this->layoutParams['avatarUrl'] = getProfileImageUrl($avatar, "sqr", $_GET['user']);
			        $this->render('site/tags', $data);
					
			} elseif (isset($_GET['user'])) {
			
					// Claps by User
					$clapsModel = new Clap;
					$clapsData = $clapsModel->getClapsByUser($_GET['user'], Yii::app()->params['numDefaultClaps'], 0);
					$data['clapsData'] = $clapsData;
					$this->page = 'UserClaps';
					$this->layoutParams['author'] = $clapsData[$_GET['user']]['author'];
					$this->layoutParams['authorId'] = $clapsData[$_GET['user']]['authorId'];
					$avatar = $clapsData[$_GET['user']]['avatar'];
					$this->layoutParams['avatarUrl'] = getProfileImageUrl($avatar, "sqr", $_GET['user']);
					$this->layoutParams['profileUrl'] =  Yii::app()->request->getBaseUrl() . "/" . $_GET['user'] . "/profile";
			        $this->render('site/index', $data);
			} elseif (isset($_GET['saved']) && ($_GET['saved'] == '1')) {
					// Saved Claps 
					$clapsModel = new Clap;
					$clapsData = $clapsModel->getSavedClaps($this->user['username']);
					$data['clapsData'] = $clapsData;
					$this->page = 'SavedClaps';
					$this->layoutParams['author'] = $clapsData[$this->user['username']]['author'];
					$avatar = $clapsData[$this->user['username']]['avatar'];
					$this->layoutParams['avatarUrl'] = getProfileImageUrl($avatar, "sqr", $this->user['username']);
					$this->layoutParams['profileUrl'] =  Yii::app()->request->getBaseUrl() . "/" . $this->user['username'] . "/profile";
			        $this->render('site/index', $data);
			} elseif (isset($_GET['clap'])) {
                    // Clap Answers to a Question
					$clapsModel = new Clap;
					$clapsData = $clapsModel->getClapAnswers($_GET['clap']);
                    $data['clapsData'] = $clapsData;
                    $this->page = 'ClapAnswers';
			        $avatar = $this->user['avatar']; 
				    $this->layoutParams['avatarUrl'] = getProfileImageUrl($avatar, "sqr", Yii::app()->user->getName());
				    $this->layoutParams['author'] = Yii::app()->user->name;
				    $this->layoutParams['profileUrl'] =  Yii::app()->request->getBaseUrl() . "/" . Yii::app()->user->username . "/profile";
			        $this->render('site/index', $data);
			} else {
				$clapsModel = new Clap;
				$clapsData = $clapsModel->getClapsforUser(Yii::app()->user->id, Yii::app()->params['numContentRows']+1, Yii::app()->params['numContentCols']+1);

				if (count($clapsData) > Yii::app()->params['numContentRows']) {
					$clapsData = array_slice($clapsData,0,Yii::app()->params['numContentRows'], true);
					$data['nextClapRow'] = Yii::app()->params['numContentRows'];
				} else {
					$data['nextClapRow'] = -1; // end of data. 
				}
				
				if (is_array($clapsData)) {
					$data['clapsData'] = $clapsData;					
				}
				

				$this->page = 'Claps';
                if (!Yii::app()->user->isGuest) {
			        $avatar = $this->user['avatar']; 
                } else 
                    $avatar = "";
				$this->layoutParams['avatarUrl'] = getProfileImageUrl($avatar, "sqr", Yii::app()->user->getName());
				$this->layoutParams['author'] = Yii::app()->user->name;
				$this->layoutParams['profileUrl'] =  Yii::app()->request->getBaseUrl() . "/" . Yii::app()->user->username . "/profile";
			    $this->render('site/index', $data);
			}

		}

		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'

		

	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('site/error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$headers="From: {$model->email}\r\nReply-To: {$model->email}";
				mail(Yii::app()->params['adminEmail'],$model->subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$this->layout = '//layouts/invite';
		$loginFormModel=new LoginForm;
        $data = array();

		// if it is ajax validation request
        /*
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		} */

		// check if username, password have entered.  
		if(isset($_POST['LoginForm']))
		{
			$loginFormModel->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($loginFormModel->validate() && $loginFormModel->login()) {
				$this->redirect(Yii::app()->request->getBaseUrl(true));
            } else {
                $data['loginFormModel'] = $loginFormModel;
        		$this->render('site/login', $data);
            }
        } elseif (!Yii::app()->user->isGuest) {
            // already logged in
            $this->redirect(Yii::app()->request->getBaseUrl(true));
		} else {
          
            // if redirected from facebook then check for fb login
            if (isset($_GET['fb']) && ($_GET['fb'] == '1')) {
                // check if signed in via facebook
                $fbUserId = Yii::app()->facebook->getUser();
                if ($fbUserId) {
                    // check if user is in our database
                    $userRow = User::model()->find('fbUserId=:fbUserId',array(':fbUserId' => $fbUserId));
                    if ($userRow) { 
                      if ($loginFormModel->loginFBUser($fbUserId)) {
                        $this->redirect(Yii::app()->request->getBaseUrl(true));
                      } else {
                      }
                    } else {
                      // user is logged into facebook but not registered with us. So make him register
                      $registrationUrl = Yii::app()->request->getBaseUrl(true) . "/site/registration?reg=1"; 
                      $this->redirect($registrationUrl);
                    }
                } else {
                   // user did not authorize 
                   // show the various login options form
                    $nextUrl = Yii::app()->request->getBaseUrl(true) . "/site/login?fb=1"; 
                    $loginUrl = Yii::app()->facebook->getLoginUrl(array('scope' => 'email,user_friends', 'redirect_uri' => $nextUrl));
    		       // display the login form
                    $data['loginFormModel'] = $loginFormModel;
                    $data['loginUrl'] = $loginUrl;
                    $this->page = "Login";
        		    $this->render('site/login', $data);
                }
            } else {

                // show the various login options form
                $nextUrl = Yii::app()->request->getBaseUrl(true) . "/site/login?fb=1"; 
                $loginUrl = Yii::app()->facebook->getLoginUrl(array('scope' => 'email,user_friends', 'redirect_uri' => $nextUrl));
    		    // display the login form
                $data['loginFormModel'] = $loginFormModel;
                $data['loginUrl'] = $loginUrl;
                $this->page = "Login";
        		$this->render('site/login', $data);
            }
        }
	}

	/**
	 * Displays the forgot password page
	 */
	public function actionForgotPassword()
	{
		$this->layout = '//layouts/invite';
        $data = array();
        $pc = $_GET['pc']; // get password reset code if it exists 
        $this->page = "Forgot Password";

        if (!$pc) {
		    $forgotPasswordFormModel=new ForgotPasswordForm;
		    // check if username or email has been entered
		    if(isset($_POST['ForgotPasswordForm']))
		    {
			    $forgotPasswordFormModel->attributes=$_POST['ForgotPasswordForm'];
			    if ($forgotPasswordFormModel->emailResetLink()) {
                   $data['emailSent'] = 1;
                } else {
                   $data['emailSent'] = 0;
                }
		    } else {
    		    // display the login form
            }
            $data['forgotPasswordFormModel'] = $forgotPasswordFormModel;
            $this->page = "Forgot Password";
            $this->pageDesp = "Forgot Password";
            $this->render('site/forgotpassword', $data);
        } elseif  (isset($_POST['ChangePasswordForm'])) {
           // new password has been entered
            $pc = $_POST['pc'];
		    $changePasswordFormModel=new ChangePasswordForm;
		    $changePasswordFormModel->attributes=$_POST['ChangePasswordForm'];
            if ($changePasswordFormModel->changePassword($pc)) {
                // success in changing password

                $data['changePasswordSuccess'] = 1;
            }  else {
                $data['changePasswordSuccess'] = 0;
                // could not change password
            }
            $this->page = "Change Password";
            $this->pageDesp = "Change Password";
            $this->render('site/changepassword', $data);
        } elseif ($pc) {
            $this->page = "Change Password";
            $this->pageDesp = "Change Password";
            // password reset code in url. show change password form
		    $changePasswordFormModel=new ChangePasswordForm;
            if ($changePasswordFormModel->verifyPasswordResetCode($pc)) { 
                $data['changePasswordFormModel'] = $changePasswordFormModel;
		        $changePasswordFormModel=new ChangePasswordForm;
                $data['changePasswordFormModel'] = $changePasswordFormModel;
                $data['pc'] = $pc;
                $this->render('site/changepassword', $data);
            } else {
                $this->page = "Change Password";
                $this->pageDesp = "Change Password";
               // invalid code 
                $data['invalidPasswordResetCode']  = 1;
                $data['changePasswordFormModel'] = $changePasswordFormModel;
                $this->render('site/changepassword', $data);
            }
        } else {
            // if nothing else then show forgot password form
		    $forgotPasswordFormModel=new ForgotPasswordForm;
            $data['forgotPasswordFormModel'] = $forgotPasswordFormModel;
            $this->page = "Forgot Password";
            $this->pageDesp = "Forgot Password";
            $this->render('site/forgotpassword', $data);
        }
	}


	/**
	 * Displays the forgot password page
	 */
	public function actionChangePassword()
	{
		$this->layout = '//layouts/invite';
		$forgotPasswordFormModel=new ForgotPasswordForm;
        $data = array();

		// if it is ajax validation request
        /*
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		} */

		// check if username or email has been entered
		if(isset($_POST['ForgotPasswordForm']))
		{
			$forgotPasswordFormModel->attributes=$_POST['ForgotPasswordForm'];
			if ($forgotPasswordFormModel->emailResetLink()) {
            } else {
               error_log("Forgot Password:Email Reset Link: failure");
            }
		} else {
          
    		    // display the login form
        }
        $data['forgotPasswordFormModel'] = $forgotPasswordFormModel;
        $this->page = "Forgot Password";
        $this->render('site/forgotpassword', $data);
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

	public function actionDetails()
	{
		$this->layout='//layouts/details';
		$clapData = array();
        $commentsData = array();
        $userCatClaps = array();
        $userOtherClaps = array();
        $data = array();
		$highlights = array(); 
		
		if (isset($_GET['clap'])) {
			$clapModel = new Clap;
			$clapData = $clapModel->getClapDetails($_GET['clap']);
			if (isset($_GET['boxrow']))
				$clapData['boxrow'] = $_GET['boxrow'];
			$highlights = getClapHighlights($clapData['clap']);
			if (count($highlights) > 0 ) {
				$clapData['highlights'] = $highlights;
			}
			if (isset($clapData['avatar'])) {
				$clapOwnerAvatarUrl = getProfileImageUrl($clapData['avatar'], "thb", $clapData['username']);
				$clapData['clapOwnerAvatarUrl'] = $clapOwnerAvatarUrl;
			}
			
			// get privacy to determine whether to show comment form or not 
			if (!Yii::app()->user->isGuest) {
				if ($clapData['privacy'] == 0) {
					$showCommentForm = true;
				} else {
					$userId = Yii::app()->user->id;
					$connectionsModel = new Connections; 
					if ($connectionsModel->isFollowing($userId, $clapData['userId'])) {
						$showCommentForm = true;
					} else {
						$showCommentForm = false;
					}
				}
			}  else {
                $showCommentForm = false;
            }

			$commentsData['showCommentForm'] = $showCommentForm;

			$commentModel = new Comment;
			$commentsData['commentModel'] = $commentModel;
            if ($showCommentForm) {
			    $comments = $commentModel->getComments($_GET['clap']);
                if (count($comments) > 0) {
    			    $commentsData['comments'] = $comments;
                }
            }
		}

		if (isset($_GET['ajax']) && ($_GET['ajax'] == "1")) {
			// this is from ajax call

/* Temporarily commenting out by PPS 

            $cs=Yii::app()->clientScript;
            $cs->scriptMap=array(
                    'jquery.js'=>false,
                    'jquery.ui.js'=>false,
            ); 
*/
			$this->page = "DetailsAjax";
			$this->renderPartial('site/_details', $clapData);
			$this->renderPartial('site/_comments', $commentsData, false, true);
		} else {

			// this is perma link
            $data['clapData'] = $clapData;
            $shortDesc=$clapData['clap'];
            if (preg_match('/^.{1,200}\b/s', $shortDesc, $match)) {
                $shortDesc=$match[0];
            }
            Yii::app()->facebook->ogTags['og:title'] = getSingularNameForCategory($clapData['category']) . ": " . $clapData['title']; 
            Yii::app()->facebook->ogTags['og:description'] = trim($shortDesc) . "..."; 
            Yii::app()->facebook->ogTags['og:site_name'] = $clapData['name'] . "'s Claps"; 

            Yii::app()->facebook->ogTags['og:type'] = "clapsnet:entity"; 
            if (array_key_exists(image,$clapData)  && ($clapData['image'] != "")) {
                Yii::app()->facebook->ogTags['og:image'] = Yii::app()->request->getBaseUrl(true) . "/userdata/" . $clapData['username'] . "/" . $clapData['id'] . "/" . getRegularImage($clapData['image']); 
            } else {
                Yii::app()->facebook->ogTags['og:image'] = Yii::app()->request->getBaseUrl(true) . "/images/clapd_stacked_250.png";
            }
            Yii::app()->facebook->ogTags['clapsnet:category'] = $clapData['category']; 
            Yii::app()->facebook->ogTags['clapsnet:rating'] = $clapData['rating']; 
	
            $userCatClaps = $clapModel->getSummaryClapsByUserForCategory($clapData['username'], $clapData['categoryId'], $_GET['clap']);
            $userOtherClaps = $clapModel->getSummaryClapsByUserExcludingCategory($clapData['username'], $clapData['categoryId']);

			$this->page = "DetailsPermaLink";
		
            /*
			$commentsData = array();
			$commentModel = new Comment;
			if(isset($_POST['Comment']))
			{
				$commentModel->attributes=$_POST['Comment'];
				if($commentModel->save())
					$this->redirect(array('comment/view','id'=>$commentModel->id));
			}
			$commentsData['commentModel'] = $commentModel;
			$comments = $commentModel->getComments($_GET['clap']);
			$commentsData['comments'] = $comments;
			
			$data['commentsData'] = $commentsData; */
    		$data['commentsData'] = $commentsData;
    		$this->layoutParams['userCatClaps'] = $userCatClaps;
    		$this->layoutParams['userOtherClaps'] = $userOtherClaps;
			$this->render('site/details', $data);
			
		}
	}
	
	public function actionComments()
	{
		
		$commentsData = array();
		$commentModel = new Comment;
		$commentsData['model'] = $commentModel;
		if(isset($_POST['Comment']) && ($_POST['Comment'] != ""))
		{
			$commentModel->attributes=$_POST['Comment'];
			$commentModel->userId = Yii::app()->user->id;
			if($commentModel->save()) {
				// add to feedback score 
				$clapsModel = new Clap;
				$feedbackScore = 2; 
				$clapsModel->addFeedbackScore($commentModel->attributes['clapId'], $feedbackScore);
				$commentsData['comment'] = $commentModel->attributes['comment'];
				$commentsData['name'] = Yii::app()->user->name;
				$this->renderPartial('site/_singlecomment', $commentsData);
			} else {
				//TODO
				//error_log(print_r($commentModel->getErrors(), true));
			}
		} else {
        /*
			$comments = $commentModel->getComments($_GET['clap']);
			$commentsData['comments'] = $comments;
			$commentsData['commentModel'] = $commentModel;
            $data['commentsData'] = $commentsData;
			//$this->renderPartial('_comments', $commentsData, false, true);
			$this->renderPartial('site/_comments', $data, false, true);
            */
		}
		return;
	}
	
	public function actionGetMoreClapCols()
	{
		$clapModel = new Clap;
		$catid = $_GET['catid'];
		$nextClapCol = $_GET['nextClapCol'];
		$boxrow = $_GET['boxrow'];
		//$boxid = $_GET['boxid'];
		$clapsData = $clapModel->getClapsforCategory(Yii::app()->user->id, $catid, Yii::app()->params['numContentCols']+1 , $nextClapCol-1);
		//error_log(print_r($clapsData,true));
		if (is_array($clapsData)) {
			$catClapsData['claps'] = $clapsData[$catid]['claps'];
			$catClapsData['nextClaps'] = false;
			$catClapsData['prevClaps'] = false;
			if ($nextClapCol > 1) {
				$catClapsData['prevClaps'] = true;
			}
			
			if (count($catClapsData['claps']) > Yii::app()->params['numContentCols']) {
				$catClapsData['claps'] = array_slice($catClapsData['claps'],0,Yii::app()->params['numContentCols'],true);
				$catClapsData['nextClaps'] = true;  
			};
			// $ccc = count($catClapsData[$catid]['claps']);
			// error_log("nextClapCol:" . $nextClapCol . ", ccc:" . $ccc . ", pY:" . $clapsData[$catid]['prevClaps'] . ", nY:" . $clapsData[$catid]['nextClaps'] . "#"  );

		}
		$data['clapsData'] = $catClapsData;
		$data['boxrow'] = $boxrow;
		//$data['boxid'] = $boxid;

		$this->renderPartial('site/_claps', $data);
	}
	
	public function actionGetMoreClapRows() 
	{
		$clapsModel = new Clap;
		$nextClapRow = $_GET['nextClapRow'];
		$nextBoxIdCount = $_GET['nextBoxIdCount'];
		

		$data = array();
		$clapsData = $clapsModel->getClapsforUser(Yii::app()->user->id, Yii::app()->params['numContentRows']+1, Yii::app()->params['numContentCols']+1, $nextClapRow);
				
		if (count($clapsData) > Yii::app()->params['numContentRows']) {
			$clapsData = array_slice($clapsData,0,Yii::app()->params['numContentRows'],true);
			$data['nextClapRow'] = $nextClapRow + Yii::app()->params['numContentRows'];
		} else {
			$data['nextClapRow'] = -1; // end of data.
		}
		if (is_array($clapsData)) {
			$data['clapsData'] = $clapsData;
			$data['boxidcount'] = $nextBoxIdCount;
			$data['boxcount'] = 0;
		}

		$this->page = "Claps";
		$this->renderPartial('site/_indexclapboxes',$data);
	}
	
	public function actionNotifications() 
	{
		$this->layout='//layouts/notifications';
		$useractionsModel = new Useractions;
		$data = array(); 
		$notificationsArray = $useractionsModel->getNotifications(Yii::app()->user->id);
		$data['notificationsArray'] = $notificationsArray;
		$this->render('site/notifications', $data);
	}
	
	public function actionInvite()
	{
		$inviteFormModel = new InviteForm;
		$data = array();
		$this->layout = '//layouts/invite';
		if (isset($_POST['InviteForm']))
		{
			$inviteFormModel->attributes=$_POST['InviteForm'];
			// validate user input and redirect to the previous page if valid
			if ($inviteFormModel->validate()) {
                // dont send the actual email from this script. An offline script will send the email.  
				$ret = $inviteFormModel->registerClapsInterest();
                $data['emailSent'] = true;
			}
		} else {
			$data['inviteFormModel'] = $inviteFormModel;
		}
        $this->page = "Invite";
        $this->pageDesp = "Request Invite";
		$this->render('site/invite', $data);
	}

/*
	public function actionVerifyInvite()
	{
		$this->layout = '//layouts/invite';
		$inviteModel = new Invite;
		if (isset($_GET['cc'])) {
			// decrypt confirmation code  and get email and code 
			if ($inviteModel->validateInvite($_GET['cc']) === true) {
				$this->render('site/registration');
			}
		}
	}
    */

    public function actionRegistration()
    {
		$this->layout = '//layouts/invite';
        $data = array();
        $regFormModel = new RegForm; 


		if (isset($_GET['cc']) && (!isset($_GET['reg']))) {
            // Deal with confirmation Code (Step-1) 
		    $inviteModel = new Invite;
			// decrypt confirmation code  and get email and code 
			if ($inviteModel->validateInvite($_GET['cc']) === true) {
                $nextUrl = Yii::app()->request->getBaseUrl(true) . "/site/registration?reg=1&cc=" . $_GET['cc']; 
                $fbLoginUrl = Yii::app()->facebook->getLoginUrl(array('scope' => 'email,user_friends', 'redirect_uri' => $nextUrl));
                //$fbLoginUrl = Yii::app()->facebook->getLoginUrl(array('redirect_uri' => $nextUrl));
                $data['showVerifiedEmail'] = true;
                $data['fbLoginUrl'] = $fbLoginUrl;
			} else {
                $data['error'] = Yii::t('app', 'ERROR_CONF_CODE') ;
            }
            $this->pageDesp = "Link Facebook Account";
		} elseif (isset($_GET['reg']) && ($_GET['reg'] == '1') && (!isset($_POST['RegForm']))) {
            // Signing up by connecting to facebook (Step-2)
            //$fbUser = Yii::app()->facebook->api('/me');
            $fbUser = Yii::app()->facebook->getInfo();
            if ($fbUser) {
                $fbUserId = $fbUser['id'];
                // check if user is in our database
                $userRow = User::model()->find('fbUserId=:fbUserId',array(':fbUserId' => $fbUserId));
                if (!$userRow) { 
                    $data['showRegForm'] = true;
                    $data['fbUserId'] = $fbUserId;
                    $data['regFormModel'] = $regFormModel;
                    $data['email'] = $fbUser['email'];
                } else {
                    $data['error'] = Yii::t('app', 'ERROR_INVITE') ;
                }
            }
            $this->pageDesp = "Create Claps Account";
        } elseif (isset($_POST['RegForm'])) {
            // Registration form filled out (Step-3)
            $regFormModel->attributes = $_POST['RegForm'];
            $regFormModel->fbUserId = $_POST['fbUserId'];
            $regFormModel->email = $_POST['email'];
            $errorMsg = "";
            if ($regFormModel->createClapsUser($errorMsg)) {
                $this->redirect(Yii::app()->request->getBaseUrl(true) . "?accountsuccess=1");
            } else {
                if ($errorMsg) {
                   $data['error'] = $errorMsg; 
                } else 
                   $data['error'] = Yii::t('app', 'ERROR_CREATE_USER') ;
                $data['showRegForm'] = true;
                $data['fbUserId'] = $_POST['fbUserId'];
                $data['regFormModel'] = $regFormModel;
                $data['email'] = $_POST['email'];
            }
        }
        $this->page = "Registration";
        $this->render('site/registration', $data);
    }


	public function actionInfo() 
	{
		$highlights = array(
            'Clapd (pronounced as Clapped) is a social network to capture the things you <b>would recommend</b> to your friends.',
            "Claps are <b>categorized</b> and presented in a <b>unique format</b> which makes it easier to find information.",
			'A Clap has a <b>score</b> which increases as it accumulates more feedback.',
			'Claps can be viewed by a variety of <b>options</b> - By Time, By User, By Category, By Hashtags, etc.',
			'Claps can have just <b>3 ratings</b> : 3,4 or 5. As we said earlier mention only the things you would recommend to your friends.',
			'Ratings are indicated by <b>geometric shapes</b> (3-triangle, 4-rectangle, 5-pentagon).',
			'You can browse vertically, horizontally and deep (by flipping).',
			'A Clap can have one image associated with it.',
			'Click on any Clap to expand it.',
			'You can incorporate <b>Cheers</b> in your vibes by +WORDS.', 
			'You can further <b>categorize</b> claps or link them across categories by tagging them #WORDS.', 
			'You can provide <b>Reference Links</b> by ^URL.',
			'Providing Cheers, Tags, Reference Links increases your Clap\'s Score.',
			'Cheers, Hashtags, Reference URLs are shown separately in the Clap Details Page.',
			'You can browse a <b>top level category</b> also which will include Claps from all sub categories.',
            "Claps can be <b>Liked</b>, found <b>Useful</b> and <b>Saved</b>.",
            "Individual Claps can be posted to <b>facebook</b> as well as a list of your top Claps in any category.", 
            "Each user has a profile page which gives statistics on his activity on the site.",
            "&nbsp;",
            "&nbsp;",
			
		);
		$data['clapHighlights'] = $highlights;
		$this->layout='//layouts/highlights';
		$this->render('site/info', $data);
	}

	public function actionAbout() 
	{
        $aboutArray = array(
            'The Concept' => "Often during the course of conversation with our friends, we end up recommending many things to each other. Be it Movies, Books, Recreation Places, Stocks, Kids Classes, Mobile Apps, and many other such things. There was no single place on the web to put in all your recommendations and browse your friends' recommendations in an easy manner. Hence out of this need was born the concept of Claps.",
            // 'The Positive Spin' => "There is too much negativity in this world. One look at any newspaper is enough proof of that. We wanted a website which would reflect positivity and good thoughts. Hence we decided to restrict ratings on Claps to the range 3-5. We want things that you WOULD recommend to your friends and not the things that you WON'T recommend. To support our idea of providing a general feeling of \"goodness\" on the site, we also have categories like \"Good Deed for the Day\", \"People Hero\", \"Quotes\", \"Humor\", etc.", 
            'The Team' => "Claps in its current format was Conceptualized, Designed and Developed by Prashant Shah - an ex-Yahoo! with several years of software application development experience behind him.", 
        );
        $data['aboutArray'] = $aboutArray;
		$this->layout='//layouts/about';
		$this->render('site/about', $data);

    }

	public function actionFaq() 
	{
        $faqArray = array(
            'What do the triangle, square, pentagon signify?' => "These geometric shapes represent the ratings. Triangle=3, Square=4, Pentagon=5.", 
            'How do I find my facebook friends on Claps?' => "You need to click on 'Find Friends' in the menu. Once you see your facebook friends you can either follow them or block them.",
            'How do I improve my score?' => "You can improve your score by writing a detailed Clap which will attact more comments and thus improve your score. You can also add +CheerWords and #HashTags to improve your score.",
            'Who do I contact for support?' => "support@clapd.com",
        );
        $data['faqArray'] = $faqArray;
		$this->layout='//layouts/faq';
		$this->render('site/faq', $data);

    }

    public function actionMessage() {
        if (isset($_GET['accountsuccess']) && ($_GET['accountsuccess'] == '1')) {
            echo "Your account has been created. <br><br>To help you get started, your account is automatically following 3 Official Clap Buddies. You can Unfollow them anytime. <br><br>You can regularly click \"Find Friends\" to connect with your Facebook friends who join this site. <br><br>Login to start using Claps now!";
        }
    }

	public function actionPrivacy() 
	{
        $privacyArray = array(
            'What information do we collect?' => "We collect information from you when you register on our site, fill out a form or write a recommendation in form of a Clap.  <br /><br />When ordering or registering on our site, as appropriate, you may be asked to enter your: name or e-mail address. You may, however, visit our site anonymously.<br /><br />Google, as a third party vendor, uses cookies to serve ads on your site.
            Google's use of the DART cookie enables it to serve ads to your users based on their visit to your sites and other sites on the Internet.
            Users may opt out of the use of the DART cookie by visiting the Google ad and content network privacy policy..<br /><br />",
            'What do we use your information for?' => "Any of the information we collect from you may be used in one of the following ways: <br /><br />; To personalize your experience<br />(your information helps us to better respond to your individual needs)<br /><br />; To improve our website<br />(we continually strive to improve our website offerings based on the information and feedback we receive from you)<br /><br />; To improve customer service<br />(your information helps us to more effectively respond to your customer service requests and support needs)<br /><br /><br />; To administer a contest, promotion, survey or other site feature<br /><br /><br />; To send periodic emails<br /><blockquote>The email address you provide for order processing, may be used to send you information and updates pertaining to your order, in addition to receiving occasional company news, updates, related product or service information, etc.</blockquote>Note: If at any time you would like to unsubscribe from receiving future emails, we include detailed unsubscribe instructions at the bottom of each email.<br /><br /><br /><br />",
            'How do we protect your information?' => "We implement a variety of security measures to maintain the safety of your personal information when you enter, submit, or access your personal information. <br /><br />",
            'Do we use cookies?' => "Yes (Cookies are small files that a site or its service provider transfers to your computers hard drive through your Web browser (if you allow) that enables the sites or service providers systems to recognize your browser and capture and remember certain information<br /><br /> We use cookies to understand and save your preferences for future visits and compile aggregate data about site traffic and site interaction so that we can offer better site experiences and tools in the future. We may contract with third-party service providers to assist us in better understanding our site visitors. These service providers are not permitted to use the information collected on our behalf except to help us conduct and improve our business.<br /><br />",
            'Do we disclose any information to outside parties?' => "We do not sell, trade, or otherwise transfer to outside parties your personally identifiable information. This does not include trusted third parties who assist us in operating our website, conducting our business, or servicing you, so long as those parties agree to keep this information confidential. We may also release your information when we believe release is appropriate to comply with the law, enforce our site policies, or protect ours or others rights, property, or safety. However, non-personally identifiable visitor information may be provided to other parties for marketing, advertising, or other uses.<br /><br />",
            'Third party links' => "Occasionally, at our discretion, we may include or offer third party products or services on our website. These third party sites have separate and independent privacy policies. We therefore have no responsibility or liability for the content and activities of these linked sites. Nonetheless, we seek to protect the integrity of our site and welcome any feedback about these sites.<br /><br />",
            'Online Privacy Policy Only' => "This online privacy policy applies only to information collected through our website and not to information collected offline.<br /><br />",
            'Terms and Conditions' => "Please also visit our Terms and Conditions section establishing the use, disclaimers, and limitations of liability governing the use of our website at <a href='http://www.clapd.com/site/terms'>http://www.clapd.com/site/terms</a><br /><br />",
            'Your Consent' => "By using our site, you consent to our <a style='text-decoration:none; color:#3C3C3C;' href='http://www.freeprivacypolicy.com/' target='_blank'>online privacy policy</a>.<br /><br />",
            'Changes to our Privacy Policy' => "If we decide to change our privacy policy, we will post those changes on this page, and/or update the Privacy Policy modification date below. <br /><br />This policy was last modified on June 9, 2012<br /><br />support@clapd.com<br /><br /><span></span><span></span>This policy is powered by Trust Guard <a style='color:#000; text-decoration:none;' href='http://www.trust-guard.com/PCI-Compliance-s/65.htm' target='_blank'>PCI compliance</a> scans.",

        );
        $data['privacyArray'] = $privacyArray;
		$this->layout='//layouts/privacy';
		$this->render('site/privacy', $data);

    }

	public function actionTerms() 
	{
        $termsArray = array(
            'Introduction' => "Welcome to www.clapd.com. This website is owned and operated by www.clapd.com. By visiting our website and accessing the information, resources, services, products, and tools we provide, you understand and agree to accept and adhere to the following terms and conditions as stated in this policy (hereafter referred to as 'User Agreement'), along with the terms and conditions as stated in our Privacy Policy (please refer to the Privacy Policy section below for more information). <br><br>
            
            This agreement is in effect as of Mar 19, 2013. <br><br>
            
            We reserve the right to change this User Agreement from time to time without notice. You acknowledge and agree that it is your responsibility to review this User Agreement periodically to familiarize yourself with any modifications. Your continued use of this site after such modifications will constitute acknowledgment and agreement of the modified terms and conditions. ",
            'Responsible Use and Conduct' => "By visiting our website and accessing the information, resources, services, products, and tools we provide for you, either directly or indirectly (hereafter referred to as 'Resources'), you agree to use these Resources only for the purposes intended as permitted by (a) the terms of this User Agreement, and (b) applicable laws, regulations and generally accepted online practices or guidelines. <br><br>
            
            Wherein, you understand that: <br><br>
            
            a. In order to access our Resources, you may be required to provide certain information about yourself (such as identification, contact details, etc.) as part of the registration process, or as part of your ability to use the Resources. You agree that any information you provide will always be accurate, correct, and up to date. <br><br>
            
            b. You are responsible for maintaining the confidentiality of any login information associated with any account you use to access our Resources. Accordingly, you are responsible for all activities that occur under your account/s. <br><br>
            
            c. Accessing (or attempting to access) any of our Resources by any means other than through the means we provide, is strictly prohibited. You specifically agree not to access (or attempt to access) any of our Resources through any automated, unethical or unconventional means. <br><br>
            
            d. Engaging in any activity that disrupts or interferes with our Resources, including the servers and/or networks to which our Resources are located or connected, is strictly prohibited. <br><br>
            
            e. Attempting to copy, duplicate, reproduce, sell, trade, or resell our Resources is strictly prohibited. <br><br>
            
            f. You are solely responsible any consequences, losses, or damages that we may directly or indirectly incur or suffer due to any unauthorized activities conducted by you, as explained above, and may incur criminal or civil liability. <br><br>
            
            g. We may provide various open communication tools on our website, such as blog comments, blog posts, public chat, forums, message boards, newsgroups, product ratings and reviews, various social media services, etc. You understand that generally we do not pre-screen or monitor the content posted by users of these various communication tools, which means that if you choose to use these tools to submit any type of content to our website, then it is your personal responsibility to use these tools in a responsible and ethical manner. By posting information or otherwise using any open communication tools as mentioned, you agree that you will not upload, post, share, or otherwise distribute any content that: <br><br>
            
            i. Is illegal, threatening, defamatory, abusive, harassing, degrading, intimidating, fraudulent, deceptive, invasive, racist, or contains any type of suggestive, inappropriate, or explicit language;<br>
            ii. Infringes on any trademark, patent, trade secret, copyright, or other proprietary right of any party;<br>
            Iii. Contains any type of unauthorized or unsolicited advertising;<br>
            Iiii. Impersonates any person or entity, including any www.clapd.com employees or representatives.<br><br>
            
            
            We have the right at our sole discretion to remove any content that, we feel in our judgment does not comply with this User Agreement, along with any content that we feel is otherwise offensive, harmful, objectionable, inaccurate, or violates any 3rd party copyrights or trademarks. We are not responsible for any delay or failure in removing such content. If you post content that we choose to remove, you hereby consent to such removal, and consent to waive any claim against us. <br><br>
            
            h. We do not assume any liability for any content posted by you or any other 3rd party users of our website. However, any content posted by you using any open communication tools on our website, provided that it doesn't violate or infringe on any 3rd party copyrights or trademarks, becomes the property of www.clapd.com, and as such, gives us a perpetual, irrevocable, worldwide, royalty-free, exclusive license to reproduce, modify, adapt, translate, publish, publicly display and/or distribute as we see fit. This only refers and applies to content posted via open communication tools as described, and does not refer to information that is provided as part of the registration process, necessary in order to use our Resources. All information provided as part of our registration process is covered by our privacy policy.<br><br> 
            
            i. You agree to indemnify and hold harmless www.clapd.com and its parent company and affiliates, and their directors, officers, managers, employees, donors, agents, and licensors, from and against all losses, expenses, damages and costs, including reasonable attorneys' fees, resulting from any violation of this User Agreement or the failure to fulfill any obligations relating to your account incurred by you or any other person using your account. We reserve the right to take over the exclusive defense of any claim for which we are entitled to indemnification under this User Agreement. In such event, you shall provide us with such cooperation as is reasonably requested by us. ",
            'Privacy' => "Your privacy is very important to us, which is why we've created a separate Privacy Policy in order to explain in detail how we collect, manage, process, secure, and store your private information. Our privacy policy is included under the scope of this User Agreement. To read our privacy policy in its entirety, click here.", 
            'Limitation of Warranties' => "By using our website, you understand and agree that all Resources we provide are 'as is' and 'as available'. This means that we do not represent or warrant to you that:
            i) the use of our Resources will meet your needs or requirements.
            ii) the use of our Resources will be uninterrupted, timely, secure or free from errors.
            iii) the information obtained by using our Resources will be accurate or reliable, and
            iv) any defects in the operation or functionality of any Resources we provide   will be repaired or corrected.

            Furthermore, you understand and agree that: 

            v) any content downloaded or otherwise obtained through the use of our Resources is done at your own discretion and risk, and that you are solely responsible for any damage to your computer or other devices for any loss of data that may result from the download of such content.
            vi) no information or advice, whether expressed, implied, oral or written, obtained by you from www.clapd.com or through any Resources we provide shall create any warranty, guarantee, or conditions of any kind, except for those expressly outlined in this User Agreement.
            ",
            'Limitation of Liability' => "In conjunction with the Limitation of Warranties as explained above, you expressly understand and agree that any claim against us shall be limited to the amount you paid, if any, for use of products and/or services. Www.clapd.com will not be liable for any direct, indirect, incidental, consequential or exemplary loss or damages which may be incurred by you as a result of using our Resources, or as a result of any changes, data loss or corruption, cancellation, loss of access, or downtime to the full extent that applicable limitation of liability laws apply. ",
            'Copyrights/Trademarks' => "All content and materials available on www.clapd.com, including but not limited to text, graphics, website name, code, images and logos are the intellectual property of www.clapd.com, and are protected by applicable copyright and trademark law. Any inappropriate use, including but not limited to the reproduction, distribution, display or transmission of any content on this site is strictly prohibited, unless specifically authorized by www.clapd.com. ",
            'Termination of Use' => "You agree that we may, at our sole discretion, suspend or terminate your access to all or part of our website and Resources with or without notice and for any reason, including, without limitation, breach of this User Agreement. Any suspected illegal, fraudulent or abusive activity may be grounds for terminating your relationship and may be referred to appropriate law enforcement authorities. Upon suspension or termination, your right to use the Resources we provide will immediately cease, and we reserve the right to remove or delete any information that you may have on file with us, including any account or login information.", 
            'Governing Law' => "This website is controlled by www.clapd.com. It can be accessed by most countries around the world. By accessing our website, you agree that the statutes and laws of our state, without regard to the conflict of laws and the United Nations Convention on the International Sales of Goods, will apply to all matters relating to the use of this website and the purchase of any products or services through this site. 

            Furthermore, any action to enforce this User Agreement shall be brought in the federal or state courts You hereby agree to personal jurisdiction by such courts, and waive any jurisdictional, venue, or inconvenient forum objections to such courts. ", 
            'Guarantee' => "UNLESS OTHERWISE EXPRESSED, www.clapd.com EXPRESSLY DISCLAIMS ALL WARRANTIES AND CONDITIONS OF ANY KIND, WHETHER EXPRESS OR IMPLIED, INCLUDING, BUT NOT LIMITED TO THE IMPLIED WARRANTIES AND CONDITIONS OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NON-INFRINGEMENT.", 
            'Contact Information' => "If you have any questions or comments about these our Terms of Service as outlined above, you can contact us at:

            support@clapd.com",



        );
        $data['termsArray'] = $termsArray;
		$this->layout='//layouts/privacy';
		$this->render('site/terms', $data);

    }
}
