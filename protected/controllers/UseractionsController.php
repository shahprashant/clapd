<?php

include_once 'MyController.php';

class UseractionsController extends MyController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

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
				'actions'=>array('index','view','getnotificationscount','getusers'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','save'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
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
	public function actionCreate()
	{
		$model=new Useractions;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Useractions']))
		{
			$model->attributes=$_POST['Useractions'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
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

		if(isset($_POST['Useractions']))
		{
			$model->attributes=$_POST['Useractions'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Useractions');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Useractions('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Useractions']))
			$model->attributes=$_GET['Useractions'];

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
		$model=Useractions::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='useractions-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionSave()
	{
		if  ((!Yii::app()->user->isGuest) && (isset($_POST['clapId'])) && (isset($_POST['type']))) {
			$useractionsModel=new Useractions;
			$useractionsModel->clapId = $_POST['clapId'];
			$useractionsModel->type = $_POST['type']; // Like = type 1, Useful = type 2, Save = type 3
			$useractionsModel->userId = Yii::app()->user->id;
			if ($useractionsModel->save()) { 
			
				// update the feedback score
				$clapModel = new Clap;
				$feedbackScore = 0;
				if ($_POST['type'] == 1) {
					$feedbackScore = 1; // 1 point for Like
				} elseif ($_POST['type'] == 2) {
					$feedbackScore = 2; // 2 points for Useful
				} elseif ($_POST['type'] == 3) {
					$feedbackScore = 2; // 2 points for Save
				}
				if ($feedbackScore > 0) {
					$clapModel->addFeedbackScore($_POST['clapId'], $feedbackScore);
				}
			} else {

			}
		}

	}
	
	public function actionGetNotificationsCount() 
	{
		$data = array();
		$useractionsModel = new Useractions;
		$userId = Yii::app()->user->id;
		$notificationsCount = $useractionsModel->getNotificationsCount($userId);
		$data['notificationsCount'] = $notificationsCount;
		$this->renderPartial('useractions/_notificationscount', $data);
	}

    public function actionGetUsers()
    {
        $data = array();
        $useractionsModel = new Useractions; 
        $usersArray = $useractionsModel->getUseractionsForClap($_GET['clapId'], $_GET['type']);
        if (count($usersArray) > 0) {
            $data['usersArray'] = $usersArray;
        }
		$this->renderPartial('useractions/_getusers', $data);
    }
}
