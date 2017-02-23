<?php

include_once 'MyController.php';

class ClapController extends MyController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	//public $layout='//layouts/column2';
	//public $layout='//layouts/add';
	public $layout='//layouts/main';
	public $page;
	public $layoutParams;


	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','browse','highlights'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('post','update','delete'),
				'users'=>array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionPost()
	{
		$categoriesData = array();
		$data = array();
		$clapModel=new Clap;
		Yii::import("ext.EPhpThumb.EPhpThumb"); // for thumbnail and resized imgs generation

		// Uncomment the following line if AJAX validation is needed
		//$this->performAjaxValidation($clapModel);
		if ((!Yii::app()->user->isGuest) && (isset($_POST['Clap'])))
		{
			$clapModel->attributes=$_POST['Clap'];
			$clapModel->userId = Yii::app()->user->id;
			
			// Calculate Score 
			$postScore = getPostScore($clapModel->attributes['clap']);
			$clapModel->postScore = $postScore;			

			$imageFile=CUploadedFile::getInstance($clapModel,'imageFile');
			if ( (is_object($imageFile) && get_class($imageFile)==='CUploadedFile'))
				$clapModel->image = $imageFile->getName();

			
			if($clapModel->save()) {
				if (is_object($imageFile)) {
					$userFolder = Yii::app()->getBasePath() . "/../userdata/" . Yii::app()->user->getName();
					$imgFolder = $userFolder . "/" . $clapModel->id ;
					if(!is_dir($userFolder)) {
						mkdir($userFolder);
					}
					if(!is_dir($imgFolder)) {
						mkdir($imgFolder);
					}
					
					// generate thumbnail 
					list($imgName, $imgExtension)  = explode(".",$imageFile->getName());
					$thumbImgName = $imgName . "_thb" . "." . $imgExtension;
					$thumbImgFile = $imgFolder . "/" . $thumbImgName;
					
					$squareImgName = $imgName . "_sqr" . "." . $imgExtension;
					$squareImgFile = $imgFolder . "/" . $squareImgName; 
					
					$regularImgName = $imgName . "_reg" . "." . $imgExtension;
					$regularImgFile = $imgFolder . "/" . $regularImgName;
					
					$thumb=new EPhpThumb();
					$thumb->init();
					
					$thumb->create($imageFile->getTempName())->adaptiveResize(75,75)->save($thumbImgFile);
					$thumb->create($imageFile->getTempName())->adaptiveResize(125,125)->save($squareImgFile);
					$thumb->create($imageFile->getTempName())->resize(450,450)->save($regularImgFile);

					// save the img name in the db
					$clapModel->updateImage($imageFile->getName());

				}
                    // if facebook post is checked 
                    if (isset($_POST['fbpost']) && ($_POST['fbpost'] == "1") ) {
                      try {
                        $access_token = Yii::app()->facebook->getAccessToken();
                        $fbPostArray = array(
                            'access_token' => $access_token,
                            'entity' => Yii::app()->request->getBaseUrl(true) . formPermaLink($clapModel->id, $clapModel->title)

                        );
                        Yii::app()->facebook->api('/me/clapsnet:clap_for','post', $fbPostArray);
                      } catch (FacebookApiException $e) {
                      error_log("--s-----");
                        error_log($e->getType());
                        error_log($e->getMessage());
                      error_log("--e-----");
                      }
                    }

				// If its a reply to a Clap then save the association in a separate table
				if ($_GET['rClap']) {
					$clapModel->saveAnswer($_GET['rClap']);
				}
				
				// Save any hash tags
				$hashTags = getClapHashTags($clapModel->clap);

				if (count($hashTags) > 0) {
					$clapModel->saveHashTags($hashTags);
				}
				$this->redirect(Yii::app()->request->getBaseUrl(true));
			} else {
				//TODO Handle error condition
				//var_dump($clapModel->getErrors()); exit;
			}	
		} else if (isset($_GET['catid'])) {
			$categoriesModel = new Category;
			$catText = $categoriesModel->getCategoryText($_GET['catid']);
			$data['catText'] = $catText;

            //PPS
            /*
            $permissions = Yii::app()->facebook->api('/me/permissions','get'); 
            if ($permissions['data'][0]['publish_actions'] == 0) {
                $additionalFBPermissionUrl = Yii::app()->facebook->getLoginUrl(array('scope' => 'publish_actions'));
                $data['additionalFBPermissionUrl'] = $additionalFBPermissionUrl;
            } */
            
			$this->layoutParams['catText'] = $catText;
		} else {		
				// display the categories page
				$categoriesModel = new Category;
				$categoriesData = $categoriesModel->getCategoryBrowseTree();
				$data['categoriesData'] = $categoriesData;
		}

		$data['model'] = $clapModel;
        $data['fbUserId'] = $this->user['fbUserId'];
		$this->page = 'PostClap';
        if (!Yii::app()->user->isGuest) {
		    $avatar = $this->user['avatar']; 
        } else 
            $avatar = "";
		$this->layoutParams['avatarUrl'] = getProfileImageUrl($avatar, "sqr", Yii::app()->user->getName());
		$this->layoutParams['author'] = Yii::app()->user->name;
		$this->layoutParams['profileUrl'] =  Yii::app()->request->getBaseUrl() . "/" . Yii::app()->user->username . "/profile";
		$this->render('clap/post',$data);
	}
	
	public function actionBrowse()
	{
		// display the categories page
		$categoriesModel = new Category;
		$categoriesData = $categoriesModel->getCategoryBrowseTree();
		$data['categoriesData'] = $categoriesData;
		$this->page = 'BrowseCategories';
        if (!Yii::app()->user->isGuest) {
		    $avatar = $this->user['avatar']; 
		    $this->layoutParams['avatarUrl'] = getProfileImageUrl($avatar, "sqr", Yii::app()->user->getName());
    		$this->layoutParams['author'] = Yii::app()->user->name;
		    $this->layoutParams['profileUrl'] =  Yii::app()->request->getBaseUrl() . "/" . Yii::app()->user->username . "/profile";
        }
		$this->render('clap/browse',$data);
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Clap']))
		{
			$model->attributes=$_POST['Clap'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('clap/update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete()
	{
		if(Yii::app()->request->isPostRequest)
		{
            $clapId = $_POST['clapId'];
			// we only allow deletion via POST request
			$clapModel = $this->loadModel($clapId);
            if ($clapModel['userId'] == $this->user['id']) {
                if ($clapModel->delete()) {
                    $this->redirect(Yii::app()->request->getBaseUrl(true));
                } else {
                }
            }
		}
		else {
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
        }
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Clap');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Clap('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Clap']))
			$model->attributes=$_GET['Clap'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Clap::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='clap-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
}
