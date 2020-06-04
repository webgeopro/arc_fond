<?php

class SiteController extends Controller
{
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
		$cs = Yii::app()->clientScript;
        $cs->registerCSSFile(Yii::app()->theme->baseUrl.'/css/common.css', $media='');		
        $cs->registerCSSFile(Yii::app()->theme->baseUrl.'/css/basic.css', $media='');
        $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/index.js', CClientScript::POS_HEAD);     
        $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/common.js', CClientScript::POS_HEAD);     
        
        $model=new UserLogin;
        
        $this->layout = 'start';
        
		$this->render('index',array('model'=>$model,));
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
	    	else {
				$this->layout = 'error';
	    		$this->render('error', $error);
	    	}
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
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
	
	/**
	 * AJAX авторизация пользователя
	 */
	public function actionLoginAjax()
	{
        if (!Yii::app()->request->isAjaxRequest) {
            die(json_encode(array('result'=>'noAjax')));
		}
        if (Yii::app()->user->isGuest) {
			$model=new UserLogin;
			
			if(isset($_POST['UserLogin'])) {
				$model->attributes=$_POST['UserLogin'];
				if ($model->validate()) {
					$this->lastVisit();
//@todo Залепуха (адрес кабинета пользвоателя)					
                    $address = Yii::app()->getModule('user')->getCabAddress();
					die(json_encode(array('result'=>'success', 'address'=>$address)));
				} else {
				    die(json_encode(array('result'=>'errorAuth')));
				}
			} else {
                die(json_encode(array('result'=>'errorPOST')));
			}
		}
	}
	
	/**
	 * AJAX выход пользователя
	 */
	public function actionLogoutAjax()
	{
        Yii::app()->user->logout();
        if (Yii::app()->user->isGuest) {
            die(json_encode(array('result'=>'success')));
        } else {
            die(json_encode(array('result'=>'error')));
        }
	}
	
	/**
	 * Время последнего визита пользователя
	 */
	private function lastVisit() 
	{
		$lastVisit = User::model()->notsafe()->findByPk(Yii::app()->user->id);
		$lastVisit->lastvisit = time();
		$lastVisit->save();
	}
	
	/**
	 * Получение адреса зарегистрированного пользователя
	 * @todo перенести в модель пользователя справка тут http://www.yiiframework.com/wiki/60/
	 * @deprecated Перенесено в МОДУЛЬ пользователь: UserModule.php: Yii::app()->getModule('user')->getCabAddress()
	 */
	public function cabAddress()
	{
        return Yii::app()->getModule('user')->getCabAddress();
    }
}