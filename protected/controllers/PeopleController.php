<?php

class PeopleController extends Controller
{
	public function actionIndex()
	{
		$cs = Yii::app()->clientScript;
        $cs->registerCSSFile(Yii::app()->theme->baseUrl.'/css/basic.css', $media='');
        #$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/index.js', CClientScript::POS_HEAD); 
        
        $this->render('index');
	}


	public function actionView()
	{
		$cs = Yii::app()->clientScript;
        $cs->registerCSSFile(Yii::app()->theme->baseUrl.'/css/basic.css', $media='');
        #$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/index.js', CClientScript::POS_HEAD); 
        
        $id = (int)$_GET['id'];
        if (!$id) {
            $this->redirect('/people');
        }
        $person = User::model()->findByPk($id);
        if (!$person->id) {
            $this->redirect('/people');
        }
        $this->render('view', array('person'=>$person,));
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
}