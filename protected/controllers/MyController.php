<?php

class MyController extends Controller
{
	var $user;
	
	public function __construct()
    {
            //parent::__construct();
            if (!Yii::app()->user->isGuest) {
            	$userId = Yii::app()->user->id;
            	$userInfo=User::model()->getUserInfo($userId);
                if ($userInfo) {
            		$this->user = $userInfo;
                } else {
                    Yii::app()->user->logout();
                }
            }
    }

    protected function afterRender($view, &$output)
    {
        parent::afterRender($view,$output);
        //Yii::app()->facebook->addJsCallback($js); // use this if you are registering any $js code you want to run asyc
        Yii::app()->facebook->initJs($output); // this initializes the Facebook JS SDK on all pages
        Yii::app()->facebook->renderOGMetaTags(); // this renders the OG tags
        return true;
    }
	
	
}
?>
