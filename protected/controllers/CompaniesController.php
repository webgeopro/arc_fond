<?php

class CompaniesController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}

		public function actionView()
	{
		$id = (int)$_GET['id'];
        if (!$id) {
            $this->redirect('/companies');
        }
        $person = User::model()->findByPk($id);
        if (!$person->id) {
            $this->redirect('/companies');
        }
        $this->render('view', array('company'=>$person,));
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