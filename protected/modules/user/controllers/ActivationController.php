<?php

class ActivationController extends Controller
{
	public $defaultAction = 'activation';

	public $layout='//layouts/user';
	public $title='Активация пользователя';
	
	/**
	 * Activation user account
	 */
	public function actionActivation () {
		$cs = Yii::app()->clientScript;
		$cs->registerCSSFile(Yii::app()->theme->baseUrl.'/css/common.css', $media='');
		
		$email = $_GET['email'];
		$activkey = $_GET['activkey'];
		if ($email&&$activkey) {
			$find = User::model()->notsafe()->findByAttributes(array('email'=>$email));
			if (isset($find)&&$find->status) {
			    $this->render('/user/message',array('title'=>UserModule::t("User activation"),'content'=>UserModule::t("You account is active.")));
			} elseif(isset($find->activkey) && ($find->activkey==$activkey)) {
				mkdir('./uploads/users/' . $find->id . '/');
				mkdir('./uploads/users/' . $find->id . '/com_app/');
				mkdir('./uploads/users/' . $find->id . '/com_app/full/');
				
				$find->activkey = UserModule::encrypting(microtime());
				$find->status = 1;
				$find->save();
			    $this->render('/user/message',array('title'=>UserModule::t("User activation"),'content'=>UserModule::t("You account is activated.")));
			} else {
			    $this->render('/user/message',array('title'=>UserModule::t("User activation"),'content'=>UserModule::t("Incorrect activation URL.")));
			}
		} else {
			$this->render('/user/message',array('title'=>UserModule::t("User activation"),'content'=>UserModule::t("Incorrect activation URL.")));
		}
	}

}