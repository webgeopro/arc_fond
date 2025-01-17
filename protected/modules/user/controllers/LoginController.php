<?php

class LoginController extends Controller
{
	public $defaultAction = 'login';
	
	public $layout='//layouts/user';
	public $title='Форма входа';
	

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{#die(print_r($_POST));
		$cs = Yii::app()->clientScript;
		$cs->registerCSSFile(Yii::app()->theme->baseUrl.'/css/common.css', $media='');
		
		if (Yii::app()->user->isGuest) {
			$model=new UserLogin;
			// collect user input data
			if(isset($_POST['UserLogin']))
			{
				$model->attributes=$_POST['UserLogin'];
				// validate user input and redirect to previous page if valid
				if($model->validate()) {
					$this->lastVisit();
					if (!empty($_POST['fromMain']))
                        $this->redirect(Yii::app()->homeUrl);
                    if (strpos(Yii::app()->user->returnUrl,'/index.php')!==false)
						$this->redirect(Yii::app()->controller->module->returnUrl);
					else
						$this->redirect(Yii::app()->user->returnUrl);
				}
			}
			// display the login form
			$this->render('/user/login',array('model'=>$model));
		} else
			$this->redirect(Yii::app()->controller->module->returnUrl);
	}
	
	private function lastVisit() {
		$lastVisit = User::model()->notsafe()->findByPk(Yii::app()->user->id);
		$lastVisit->lastvisit = time();
		$lastVisit->save();
	}

}