<?php
class VahTabs extends CWidget{
	public $tabs=array();   # Закладки
    public $pageID; # ID страницы пользователя (/id12)
    public $cntLimit = 80;  # Количество букв в закладках одного блока <ul>
    public $userTabID = ''; # Id для пользовательских закладок. Участвует в ajax-запросе.
    
	public function run(){
		$cs = Yii::app()->clientScript;
        $cs->registerCSSFile(Yii::app()->theme->baseUrl.'/css/vahTabs.css');
        $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/vahTabs.js', CClientScript::POS_HEAD);  
        
        if(!isset($this->tabs)){
			//throw new CHttpException(500,'Не определены закладки!'); // Надо ли выдавать исключение? 
		}
        $firstContent = about::model()->find('owner_id=:userID', array(':userID'=>$this->pageID));
        #die('content='.print_r($firstContent));
		$this->render('VahTabs',array(
            'tabs'      => $this->tabs, 
            'pageID'    => $this->pageID, 
            'cntLimit'  => $this->cntLimit,
            'userTabID' => $this->userTabID,
            'firstContent' => $firstContent['txt']?$firstContent['txt']:'',
            ));
	}
}