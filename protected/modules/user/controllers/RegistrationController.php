<?php

class RegistrationController extends Controller
{
	public $defaultAction = 'registration';
	
	public $layout = '//layouts/user';
	public $title = 'Форма регистрации';	

	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return (isset($_POST['ajax']) && $_POST['ajax']==='registration-form')?array():array(
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'transparent'=>true,
				//'clickableImage'=>true,
			),
		);
	}
	/**
	 * Registration user
	 */
	public function actionRegistration() {
        $cs = Yii::app()->clientScript;
		$cs->registerCSSFile(Yii::app()->theme->baseUrl.'/css/common.css', $media='');
        $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/common.js', CClientScript::POS_HEAD);
        $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/registration.js', CClientScript::POS_HEAD);
        if ( !$cs->isScriptFileRegistered(Yii::app()->theme->baseUrl.'js/jquery.jsuggest.js', CClientScript::POS_HEAD) )
            $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.jsuggest.js', CClientScript::POS_HEAD);
        $cs->registerCSSFile(Yii::app()->theme->baseUrl.'/css/jsuggest.css', $media='');
//        $cs->registerCSSFile(Yii::app()->theme->baseUrl.'/css/registration.css', $media='');
        
        $model = new RegistrationForm;
        $profile=new Profile;
        $profile->regMode = true;
            
			// ajax validator
			if(isset($_POST['ajax']) && $_POST['ajax']==='registration-form')
			{
				echo UActiveForm::validate(array($model,$profile));
				Yii::app()->end();
			}
			
		    if (Yii::app()->user->id) {
		    	$this->redirect(Yii::app()->controller->module->profileUrl);
		    } else {
		    	if(isset($_POST['RegistrationForm'])) {#die(print_r($_POST));
					$model->attributes=$_POST['RegistrationForm'];#die(print_r($model));
					$profile->attributes=((isset($_POST['Profile'])?$_POST['Profile']:array()));
                    // Подставляем города
                    // @todo Заполнить города
//                    $profile->town0 = Towns::getTown($_POST['Profile']['town0']);
//                    $profile->town  = Towns::getTown($_POST['Profile']['town']);
//                    /* Присваиваем форму собственности прямо с формы! (у юрлица select иначе 0)*/
//                    if (true) {
//                        $profile->form = 1; 
//                    } else {
//                        // Подставляем форму собственности
//                        
//                    }
                    //die('<pre>'.print_r($profile->attributes).'</pre>');
					if($model->validate()&&$profile->validate())
					{//die('validation 1');
						$soucePassword = $model->password;
						$model->activkey=UserModule::encrypting(microtime().$model->password);
						$model->password=UserModule::encrypting($model->password);
						$model->verifyPassword=UserModule::encrypting($model->verifyPassword);
						$model->createtime=time();
						$model->lastvisit=((Yii::app()->controller->module->loginNotActiv||(Yii::app()->controller->module->activeAfterRegister&&Yii::app()->controller->module->sendActivationMail==false))&&Yii::app()->controller->module->autoLogin)?time():0;
						$model->superuser=0;
						$model->status=((Yii::app()->controller->module->activeAfterRegister)?User::STATUS_ACTIVE:User::STATUS_NOACTIVE);
						
						if ($model->save()) {//die('validation 2');
							$profile->user_id=$model->id;
							$profile->save(); #die('After SAVE<br><pre>'.print_r($profile->attributes).'</pre>');
							if (Yii::app()->controller->module->sendActivationMail) {
								$activation_url = $this->createAbsoluteUrl('/user/activation/activation',array("activkey" => $model->activkey, "email" => $model->email));
								UserModule::sendMail($model->email,UserModule::t("You registered from {site_name}",array('{site_name}'=>Yii::app()->name)),UserModule::t("Please activate you account go to {activation_url}",array('{activation_url}'=>$activation_url)));
							}
							
							if ((Yii::app()->controller->module->loginNotActiv||(Yii::app()->controller->module->activeAfterRegister&&Yii::app()->controller->module->sendActivationMail==false))&&Yii::app()->controller->module->autoLogin) {
									$identity=new UserIdentity($model->username,$soucePassword);
									$identity->authenticate();
									Yii::app()->user->login($identity,0);
									$this->redirect(Yii::app()->controller->module->returnUrl);
							} else {
								if (!Yii::app()->controller->module->activeAfterRegister && !Yii::app()->controller->module->sendActivationMail) {
									Yii::app()->user->setFlash('registration',UserModule::t("Thank you for your registration. Contact Admin to activate your account."));
								} elseif(Yii::app()->controller->module->activeAfterRegister&&Yii::app()->controller->module->sendActivationMail==false) {
									Yii::app()->user->setFlash('registration',UserModule::t("Thank you for your registration. Please {{login}}.",array('{{login}}'=>CHtml::link(UserModule::t('Login'),Yii::app()->controller->module->loginUrl))));
								} elseif(Yii::app()->controller->module->loginNotActiv) {
									Yii::app()->user->setFlash('registration',UserModule::t("Thank you for your registration. Please check your email or login."));
								} else {
									Yii::app()->user->setFlash('registration',UserModule::t("Thank you for your registration. Please check your email."));
								}
								$this->refresh();
							}
						}//die('after validation 2');
					} else {
                        /*if( $profile->hasErrors() ) {#die('$profile has Errors');
                            $profileErrorsStr = '';
                            foreach($profile->getErrors() as $value) {
                                $profileErrors[] = $value[0];
                            }
                            $profileErrorsStr = '<br><ul>'.implode('<li>', $profileErrors).'</ul>';
                        }
                        if( $model->hasErrors() ) {#die('$model has Errors');
                            foreach($model->getErrors() as $value) {
                                $modelErrors[] = $value[0];
                            }
                            $modelErrorsStr = '<br><ul>'.implode('<li>', $modelErrors).'</ul>';
                        }*/
                       #die('NO validation 1.<br><ul>'.implode('<li>',$profileErrors).'</ul>');#.implode('<li>',$modelErrors)
                        $profile->validate();
//                      $profile->town0 = $_POST['Profile']['town0_title'];
//	                    $profile->town  = $_POST['Profile']['town_title'];
                    }
				}
			    $this->render('/user/registration',array('form'=>$model,'model'=>$model,'profile'=>$profile));
		    }
	}
	/**
	 * Действие autocomplete.
     * Ajax-автодополнение ввода, поле "Город"
     * @return Array [Список городов, html {ul}].
	 */
	public function actionAutocomplete()
	{
        if ( !empty($_POST['iKeywords']) ) {
            $ar = Towns::model()->findAll( array (
                        'select'    => 'id, name',
                        'condition' => 'name LIKE :keywords',
                        'params'    => array (':keywords' => $_POST['iKeywords'].'%'),
                        'order'     => 'name',
                        'limit'     => 10,
                        ));
            $str = '<ul>';
            foreach($ar as $val) {
                $str .= '<li>'.$val->getAttribute('name').'</li>';
            }
            $str .= '</ul>';
        };        
        die($str); #return $str;
	}
}