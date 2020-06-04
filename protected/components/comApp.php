<?php
class comApp extends CWidget {
	
    public $pageID; # ID страницы пользователя / компании
    
    public function run() {
		$cs = Yii::app()->clientScript;
        $cs->registerCSSFile(Yii::app()->theme->baseUrl.'/css/comApp.css');
        if ( !$cs->isScriptFileRegistered(Yii::app()->theme->baseUrl.'js/jquery.easySlider.js', CClientScript::POS_HEAD) )
            $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.easySlider.js', CClientScript::POS_HEAD);
        #if ( !$cs->isScriptFileRegistered(Yii::app()->theme->baseUrl.'js/jquery.ocupload.js', CClientScript::POS_HEAD) )
        #    $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.ocupload.js', CClientScript::POS_HEAD);
        $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/comApp.js', CClientScript::POS_HEAD);
        
        $comPhoto = ComPersonal::model()->findAll('owner_id=:userID', array(':userID'=>$this->pageID));
            
        $this->render('comApp', array(
            'comPhoto' => $comPhoto,
            'pageID'   => $this->pageID,
        ));
	}
}