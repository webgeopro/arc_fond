<?php
class SlAjaxTabs extends CWidget{
	public $tabs;
	public function run(){
		if(!isset($this->tabs)){
			throw new CHttpException(500,'"tabs" have to be set!');
		}
		$this->render('slAjaxTabs',array('tabs'=>$this->tabs));
	}
}
?>