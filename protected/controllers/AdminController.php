<?php

include_once 'MyController.php';

class AdminController extends MyController
{
	public $layout='//layouts/main';
	public $page;
	public $action;
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
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('sendinvites'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

    public function actionSendInvites()
    {
        $inviteModel = new Invite;
        $emailCounter = $inviteModel->sendInviteEmail();
        echo "$emailCounter invite emails sent out";
        Yii::app()->end();
    }


}
