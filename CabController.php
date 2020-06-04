<?php

class CabController extends Controller
{

    public $layout='//layouts/cab'; // Главный шаблон для кабинета
    
    public function filters() {
        return array(
            'accessControl',
        );
    }
 
    public function actions()
	{
		return (isset($_POST['ajax']) && $_POST['ajax']==='formMessageSend')?array():array(
			'captcha'=>array(
				'class'       => 'CCaptchaAction',
				//'backColor'=>0xFFFFFF,
                'transparent' => true,
                'testLimit'   => 1,
                'maxLength'   => 6,
                'minLength'   => 4,
                'foreColor'   =>0x656565,
			),
		);
	}
    
    public function actionIndex()
	{
        $cs = Yii::app()->clientScript;
        
        $cs->registerCSSFile(Yii::app()->theme->baseUrl.'/css/common.css', $media='');
        $cs->registerCSSFile(Yii::app()->theme->baseUrl.'/css/cab.css', $media='');
        $cs->registerCSSFile(Yii::app()->theme->baseUrl.'/css/jsuggest.css', $media='');
        $cs->registerCSSFile(Yii::app()->request->baseUrl . '/css/' . Yii::app()->params['themeUI'] . '/jquery-ui.custom.css', $media='');
        
        $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.editinplace.js', CClientScript::POS_HEAD);
        $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.form.js', CClientScript::POS_HEAD);
        $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.jsuggest.js', CClientScript::POS_HEAD);
        $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/cab.js', CClientScript::POS_HEAD);
        $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/common.js', CClientScript::POS_HEAD);     
        
        $userID = Yii::app()->user->getId(); //die("/$userID=$userID");
        if ( ($loginID = trim($_GET["loginID"])) ) { //die("loginID=$loginID");
            $tmpUser = User::model()->find('username=:loginID', array(':loginID'=>$loginID));
            $pageID = $tmpUser["id"];
        } elseif (!($pageID = (int)$_GET["pID"])) {
            $pageID = $userID;
        } elseif ( !User::model()->exists('id=:pageID', array(':pageID'=>$pageID)) ) {
            $pageID = $userID;
        } 
        if ($userID != $pageID) { # Страница отображается для всех
            if ( About::model()->exists('owner_id=:userID', array(':userID'=>$pageID)) )
                $Tabs[] = array('title'=>'О компании','url'=>'about');
            if ( Jobs::model()->exists('owner_id=:userID', array(':userID'=>$pageID)) )
                $Tabs[] = array('title'=>'Вакансии', 'url'=>'jobs', 'id'=>'tabJobs');
            if ( News::model()->exists('owner_id=:userID', array(':userID'=>$pageID)) )
                $Tabs[] = array('title'=>'Новости', 'url'=>'news', 'id'=>'tabNews');
            if ( Discounts::model()->exists('owner_id=:userID', array(':userID'=>$pageID)) )
                $Tabs[] = array('title'=>'Скидки', 'url'=>'discounts', 'id'=>'tabDiscounts');
            if ( Articles::model()->exists('owner_id=:userID', array(':userID'=>$pageID)) )
                $Tabs[] = array('title'=>'Статьи', 'url'=>'articles', 'id'=>'tabArticles');

            $viz = $this->renderPartial('editor_vizitka', array('user'=>User::model()->findByPk($pageID), 'boolEdit'=>false), true);

        } else { # Страница отображается в режиме редактирования (все вкладки)
            $Tabs = array(
                array('title'=>'О компании','url'=>'about', 'id'=> 'tabAbout'),
                array('title'=>'Вакансии',  'url'=>'jobs',      'id'=>'tabJobs'),
                array('title'=>'Новости',   'url'=>'news',      'id'=>'tabNews'),
                array('title'=>'Скидки',    'url'=>'discounts', 'id'=>'tabDiscounts'),
                array('title'=>'Статьи',    'url'=>'articles',  'id'=>'tabArticles'),
            );

            $viz = $this->renderPartial('editor_vizitka', array('user'=>User::model()->findByPk($userID), 'pageID'=>$pageID), true);
        }
        
        if ( Tabs::model()->count('owner_id=:userID', array(':userID'=>$pageID)) ) { # Добавляем пользовательские закладки
            $rows = Tabs::model()->findAll('owner_id=:userID', array(':userID'=>$pageID));
            foreach ($rows as $row) {
                $Tabs[] = array('title'=>$row['name'], 'url'=>'tabs', 'id'=>$row['id'], 'userTabID'=>$row['id']);
            }
        }
        $this->render('index', array(
            'Tabs'   => $Tabs,
            'pageID' => $pageID,
            'user'   => User::model()->findByPk($pageID),
            'viz'    => $viz,
            'rating' => new Rating,
        ));
	}
    
    /**
     * Получение первой страницы для закладок (vahTabs)
     * Ajax-запрос
     * @return HTML (заполненный шаблон)
     */
    public function actionGet()
	{
        if (Yii::app()->request->isAjaxRequest) {# Пришел ли ajax-запрос
            $pageID = (int)$_POST["pID"]; # Приводим запрашиваемый ID к числу
            if ( User::model()->exists('id=:pageID', array(':pageID'=>$pageID)) ) { #Проверка существования пользователя, а след.-но и запрашиваемой страницы.
                $page  = stripslashes(strip_tags($_POST['url']));
                $field = stripslashes(strip_tags($_POST['field']));
                $params   = array('condition'=>'owner_id=:pageID','order'=>'data DESC','params'=>array(':pageID'=>$pageID));
                $params_wd= array('condition'=>'owner_id=:pageID','params'=>array(':pageID'=>$pageID));
                if ( $pageID == Yii::app()->user->getId() ){ # Если пользователь - администратор страницы (ID пользователя = ID запрашиваемой страницы)
                    if ('tabs' == $field) {
                        $post = Tabs::model()->findByPk($page);
                        $out = $this->renderPartial('editor_txt', array(
                            'post'  => $post, 
                            'name'  => 'Tabs_txt', 
                            'field' => 'txt',
                            'tabID' => "tabs_$page",
                            'height'=>  '500',
                            'element_id'=>"Tabs::$page::txt",
                            'additional'=>'<a href="" name="'.$page.'" id="aTabDelete" onClick="return fTabDelete(this)">Удалить закладку</a><br /><br />'
                        ));
                    } elseif ( in_array($page, Yii::app()->params['pages']) ) { # Дополнительная проверка корректности имени закладки
                    switch ($page) {
                            case 'about':
                                $post = About::model()->find($params_wd);
                                if(! $post) {
                                	$post = new About();
                                	$post->owner_id = Yii::app()->user->getId();
                                	if($post->validate()) {
                                		$post->save();
                                	}
//                                	else {
//                                		print_r($post->errors);
//                                	}
								}
                                $out = $this->renderPartial('editor_txt', array(
                                	'post'=>$post, 
                                	'name'=>'About_txt', 
                                	'height'=>  '480', 
                                	'field'=>'txt',
	                                'tabID' => "tabAbout",
		                            'element_id'=>"About::$post->id::txt",
//		                            'element_id'=>"About::$pageID::txt",
                                ), true);
                                break;
                            case 'news':
                                $post = News::model()->with('rubricator')->findAll($params);
                                foreach ( RubNews::model()->findAll() as $rub )
                                    $select[] = $rub['title'].':'.$rub['id'];
                                $select = '"'.implode(',', $select).'"';
                                $out = $this->renderPartial('editor_news', array('post'=>$post, 'select'=>$select), true);
                                break;
                            case 'discounts':
                                $post = Discounts::model()->findAll($params);
                                $out = $this->renderPartial('editor_discounts', array('post'=>$post), true);
                                break;
                            case 'articles':
                            	$params['condition'] = Articles::model()->tableAlias . '.owner_id=:pageID';
                                $post = Articles::model()->with('rubricator', 'source')->findAll($params);
                                foreach ( RubArticles::model()->findAll() as $rub )
                                    $select[] = $rub['title'].':'.$rub['id'];
                                $select = '"'.implode(',', $select).'"';
                                $out = $this->renderPartial('editor_articles', array('post'=>$post, 'select'=>$select), true);
                                break;
                            case 'jobs':
                                $post = Jobs::model()->with('fk_towns', 'fk_spec')->findAll($params);
                                $out = $this->renderPartial('editor_jobs', array('post'=>$post), true);
                                break;
                            case 'vizitka':
                                $post = User::model()->findByPk($pageID);
                                $data = $post->requisites;
                                $out = $this->renderPartial(
                                	'editor_vizitka', 
	                                array(
	                                	'user' => $post, 
	                                	'requisites_fields' => Requisites::model()->attributeNames(), 
	                                	'requisites_model' => $data ? $data : Requisites::model(), 
	                                	'boolEdit' => (trim($_POST["viewer"])) ? false : true,
	                                	'pageID' => $pageID
	                                ), 
	                                true);
                                break;
                            case 'comApp':
                                $post = ComPersonal::model()->findAll('owner_id=:userID', array(':userID'=>$pageID));
                                $out = $this->renderPartial('editor_comApp', array('comPersonal'=>$post, 'boolEdit'=>true, 'pageID'=>$pageID), true);
                                break;
                            case 'opinions':
                                $post = Opinions::model()->findAllByAttributes(array('recipient_id'=>$pageID));
                                $out = $this->renderPartial('editor_opinions', array('opinions'=>$post, 'boolEdit'=>true,'pageID'=>$pageID), true);
                                break;
                            default: ;
                        }
                    } else { # Имени закладки нет в списке допустимых
                        die('Отладочная информация :: Нет в списке.');
                    }
                    die($out); # Вывод сформатированного содержимого
                } else { # Не администратор. Обычный просмотр страницы // Без редактора
                    #die('Отладочная информация :: '.$_POST['url']." Пользователь не администратор.");
                    if ('tabs' == $field) {
                        $post = Tabs::model()->findByPk($page);
                        $out = $post['txt'];#die($out);
                    } elseif ( in_array($page, Yii::app()->params['pages']) ) { # Дополнительная проверка корректности имени закладки
                        switch ($page) {
                            case 'about':
                                $post = About::model()->find($params_wd);
                                $out = $post['txt'];
                                break;
                            case 'news':
                                $post = News::model()->with('rubricator')->findAll($params);
                                foreach ( RubNews::model()->findAll() as $rub )
                                    $select[] = $rub['title'].':'.$rub['id'];
                                $select = '"'.implode(',', $select).'"';
                                $out = $this->renderPartial('viewer_news', array('post'=>$post, 'select'=>$select));
                                break;
                            case 'discounts':
                                $post = Discounts::model()->findAll($params);
                                $out = $this->renderPartial('viewer_discounts', array('post'=>$post));
                                break;
                            case 'articles':
                            	$params['condition'] = Articles::model()->tableAlias . '.owner_id=:pageID';
                                $post = Articles::model()->with('rubricator', 'source')->findAll($params);
                                foreach ( RubArticles::model()->findAll() as $rub )
                                    $select[] = $rub['title'].':'.$rub['id'];
                                $select = '"'.implode(',', $select).'"';
                                $out = $this->renderPartial('viewer_articles', array('post'=>$post, 'select'=>$select));
                                break;
                            case 'jobs':
                                $post = Jobs::model()->with('fk_towns', 'fk_spec')->findAll($params);
                                $out = $this->renderPartial('viewer_jobs', array('post'=>$post));
                                break;
                            case 'vizitka':
                                $post = User::model()->findByPk($pageID);
                                $out = $this->renderPartial('editor_vizitka', array('user'=>$post, 'boolEdit'=>false), true);
                                break;
                            case 'comApp':
                                $post = ComPersonal::model()->findAll('owner_id=:userID', array(':userID'=>$pageID));
                                $out = $this->renderPartial('editor_comApp', array('comPersonal'=>$post, 'boolEdit'=>false, 'pageID'=>$pageID), true);
                                break;
                            case 'opinions':
                                $post = Opinions::model()->findAllByAttributes(array('recipient_id'=>$pageID));
                                $out = $this->renderPartial('editor_opinions', array('opinions'=>$post, 'boolEdit'=>false,'pageID'=>$pageID), true);
                                break;
                            default: ;
                        }
                    }
                }
                die($out);
            } else { # Пользователь не сущ., следовательно нет и его страницы
                die("Отладочная информация :: Пользователь не существует.");
            } 
        } else { # Не ajax-запрос
            die('Отладочная информация :: Не Ajax-запрос.');
        }
    }
    
    /**
     * Получение текста поля для wysiwyg-редактора
     * Ajax-запрос
     * @return HTML (заполненный шаблон, вместе со скриптом редактора)
     */
    public function actionGetField()
	{
        if (Yii::app()->request->isAjaxRequest and !Yii::app()->user->isGuest) {# Пришел ли ajax-запрос
            $post = explode('::', $_POST["type_id"], 3);
            switch ($post[0]) {
                case 'discount_editor': # -- Описание конкретной скидки (WYSIWYG)
                    $out = Discounts::model()->findByPk($post[1]);
                    if ( !empty($out->id) and $out->owner_id == Yii::app()->user->getId() )
                        $out = $this->renderPartial('editor_txt', array(
                            'post'  => $out, 
                            'name'  => 'Discounts_descr', 
                            'field' => 'descr',
                            'tabID'=> 'tabDiscounts',
                            'height'=>  '300',
                            'element_id'=>"Discounts::$post[1]::descr"));
                    break;
                case 'article_editor':
                    $out = Articles::model()->findByPk($post[1]);
                    if ( !empty($out->id) and $out->owner_id == Yii::app()->user->getId() )
                        $out = $this->renderPartial('editor_txt', array(
                            'post'  => $out, 
                            'name'  => 'Articles_descr', 
                            'field' => 'descr',
                            'tabID' => 'tabArticles',
                            'height'=>  '480',  
                            'element_id'=>"Articles::$post[1]::descr"));
                    break;
                case 'job_editor': # -- Редактированной конкретной вакансии
                    if ( 'new' == $post[1] ) {
                        $jobs = new Jobs;
                        $out = $this->renderPartial('editor_job', array(
                                'post'  => $jobs,
                                'new'   => true, 
                                'model' => $model,
                                'name'  => 'Jobs_comment', 
                                'tabID' => 'tabJobs',
                                'height'=>'300',
                                'field'=>'comment',
                                'element_id'=>"Jobs::$post[1]::comment",
                                'formEdit'=> $_POST['form_edit']?'1':''));
                    } else {
                        $jobs = Jobs::model()->with('fk_towns', 'fk_spec')->findByPk($post[1]);
                        if ( !empty($jobs->id) and $jobs->owner_id == Yii::app()->user->getId() ) {
                            $out = $this->renderPartial('editor_job', array(
                                'post'  => $jobs, 
                                'model' => $model,
                                'name'  => 'Jobs_comment', 
                                'tabID' => 'tabJobs',
                                'height'=>'300',
                                'field'=>'comment',
                                'element_id'=>"Jobs::$post[1]::comment",
                                'formEdit'=> $_POST['form_edit']?'1':''));
                        }
                    }
                    
                    break;
                default: ;
            }
            die($out);
        }
    }
    
    /**
     * Сохранение данных Основного контента на странице пользователя через ajax.
     * Использование jquery-плагина EditInPlace (ver. 2.1)
     * Имя модели :: ID записи :: Имя поля 
     */
    public function actionInPlaceSave()
	{
        if (Yii::app()->request->isAjaxRequest) {
            if ( !empty($_POST["element_id"]) and !Yii::app()->user->isGuest) {
                $post = explode('::', $_POST["element_id"], 3);
                $post[0] = ucfirst($post[0]);
                $item = $post[0]::model()->findByPk($post[1]);
                if ( ($item['owner_id'] and $item['owner_id'] == Yii::app()->user->getId()) or ($item['company_id'] == Yii::app()->user->getId()) ) {
                    if ( 0 != strcmp($_POST["original_html"], $_POST["update_value"])) {
                        $item[$post[2]] = $_POST["update_value"];
                        if ($item->validate()) {
                            $item->save();
                            die($item[$post[2]]);
                        }
                        if ( $product ) {
                            
                        }
                    }
                    else {
                    	
                    }
                }
                die($_POST["original_html"]);
            }
        }
    }

    /**
     * Сохранение данных Основного контента на странице пользователя через ajax.
     */
    public function actionWysiwygSave()
	{#die(print_r($_POST));
        $result = array();
        if (Yii::app()->request->isAjaxRequest) {
            if ( !empty($_POST["element_id"]) ) {
                $post = explode('::', $_POST["element_id"], 3); # ( Имя модели :: ID записи :: Имя поля )
                $post[0] = ucfirst($post[0]);
                $item = $post[0]::model()->findByPk($post[1]);
                if ($item['owner_id'] == Yii::app()->user->getId()) {
                    $item[$post[2]] = $_POST["content"];
                    if ($item->validate()) {
                        $item->save();
                        $result['result'] = 'success';
//                        die($item[$post[2]]);
                        # Redirect на get! или просто renderPartial
                    }
                    else {
                    	$result['result'] = 'errorSave';
                    	$result['errors'] = $item->errors; 
                    }
                }
                else {
                	$result['result'] = 'errorUser';
                    $result['errors'] = array('Ошибка идентификатора пользователя');
                }
//	            die($_POST["original_html"]);
            }
        }
        die(json_encode($result));
    }

    /**
     * Сохранение файлов Основного контента на странице пользователя через ajax.
     */
    public function actionLogoSave()
	{
        $profile = Profile::model()->findByPk(Yii::app()->user->getId());#die(print_r($_POST));
        $profile->attributes = $_POST['Profile'];
        #$profile->image = $_POST['Profile[image]'];
        if ( $profile->validate() ) {
            if ( $profile->save() ) {#die(print_r($profile));
                $res['result'] = 'success';
                $res['logo'] = $profile->getImageUrl('middle');
            } else {
                $res['result'] = 'errorSave';
            }
        } else {
            $res['result'] = 'errorValidate';
        }
        die(json_encode($res));
    }
        
    /**
     * Сохранение файлов-изображений через ajax. НЕ ИСПОЛЬЗУЕТСЯ
     */
    public function actionImageSave()
	{
        if (true) { #(Yii::app()->request->isAjaxRequest) {
            if ( !empty($_GET["element_id"]) and !Yii::app()->user->isGuest) {
                $post = explode('::', $_GET["element_id"], 3);
                $post[0] = ucfirst($post[0]);
                $item = $post[0]::model()->findByPk($post[1]);
                if ( $item['owner_id'] and $item['owner_id'] == Yii::app()->user->getId() ) {
                    $item->attributes = $_POST;#die(print_r($item));
                    if ( $item->validate() ) {
                        if ( $item->save() ) {
                            $res['result'] = 'success';
                            $res['logo'] = $item->getImageUrl('thumb');
                        } else {
                            $res['result'] = 'errorSave';
                        }
                    } else {
                        $res['result'] = 'errorValidate';
                    }
                }
                die(json_encode($res));
            }
        }die(json_encode(array('result'=>'noAjax')));
    }

    /**
     * Сохранение файлов-изображений через ajax. ЗАГЛУШКА-ПРОБНИК для comApp
     */
    public function actionComAppSave()
	{
        if ( empty($_GET["pID"]) or Yii::app()->user->isGuest) die; 
        $id = (int)$_GET['pID'];
        $item = ComPersonal::model()->findByPk($id);
        if ( $item['owner_id'] and $item['owner_id'] == Yii::app()->user->getId() ) {
            $item->attributes = $_POST['ComPersonal'];
            if ( $item->validate() ) {
                if ( $item->save() ) {
                    $res['result'] = 'success';
                    $res['logo'] = $item->getImageUrl('thumb');
                    $res['id'] = $id;
                } else {
                    $res['result'] = 'errorSave';
                }
            } else {
                $res['result'] = 'errorValidate';
            }
        }
        die(json_encode($res));
    }
            
    /**
     * Сохранение файлов Основного контента на странице пользователя через ajax.
     */
    public function actionFilesSave()
	{die(print_r($_POST));
        if (Yii::app()->request->isAjaxRequest) {
            if ( !empty($_POST["element_id"]) ) {
                $post = explode('::', $_POST["element_id"], 3); # ( Имя модели :: ID записи :: Имя поля )
                $post[0] = ucfirst($post[0]);
                $item = $post[0]::model()->findByPk($post[1]);
                if ($item['owner_id'] == Yii::app()->user->getId()) {
                    $item[$post[2]] = $_POST["content"];
                    if ($item->validate()) {
                        $item->save();
                        #die($item[$post[2]]);
                        # Redirect на get! или просто renderPartial
                    }
                }
            die($_POST["original_html"]);
            }
        }
    }
    
    /**
     * Сохранение файлов Основного контента на странице пользователя через ajax.
     * element_id = model :: new / id
     */
    public function actionSave()
	{
        if (Yii::app()->request->isAjaxRequest) {
            $res['result'] = 'error';
            if ( !empty($_POST["element_id"]) ) {
                $post = explode('::', $_POST["element_id"]);
                if ( ($elID=(int)$post[1] or 'new'==$post[1]) and in_array($post[0], Yii::app()->params['pages'])) {
                    $res = array();
                    switch ($post[0]) {
                        case 'jobs': # Редактирование конкретной вакансии
                            if ($elID) {
                                $out = Jobs::model()->with('fk_towns')->findByPk($elID);
                                if ( empty($out->id) ) {
                                    $out = new Jobs;
                                    $out->owner_id = Yii::app()->user->getId();
                                }
                            } elseif ( 'new'==$post[1] ) { # НОВАЯ вкансия
                                $out = new Jobs;
                                $out->owner_id = Yii::app()->user->getId();
                            } else die(json_encode($res));
                            $out->attributes=$_POST;
                            $out->towns_id = $out->getTown($_POST["towns_id"]);
                            $out->specialities_id = $out->getSpec($_POST["specialities_id"]);
                            #die(print_r($out));
                            if ( $out->validate() ) {
                                if ($out->save()) {
                                    $res['id']     = $elID;
                                    $res['result'] = 'success';
                                }
                            } else {
                                $res['id']     = $elID;
                                $res['result'] = 'errorValidate';
                                $res['err_mes']= array('1', '2', '3', '4');    # Заглушки
                                $res['err_el'] = array('#1', '#2', '#3', '#4');# для тестирования
                                $res['errors'] = $out->errors;
                            }
                            break;
                        case 'vizitka':
                        	// Информация профиля
                            $out = Profile::model()->findByPk(Yii::app()->user->getId()); #die(print_r($_POST));
                            $out->attributes = $_POST;  //die(print_r($out->attributes));
                            $out->town0 = Jobs::getTown($_POST["town0"]);
                            $out->town  = Jobs::getTown($_POST["town"]);
                            if ($out->validate()) {
                                if ($out->save()) {
                                    $res['result'] = 'success';
                                } else {
                                    $res['result'] = 'errorSave';
                                    $res['errors'] = $out->errors;
                                }
                            } else {
                                $res['result'] = 'errorValidate';
                                $res['errors'] = $out->errors;
                            }
                            
                            // Реквизиты
                            if(is_array($_POST['Requisites'])) {
                            	$out = Requisites::model()->findByPk(Yii::app()->user->getId());
                            	if(! $out) {
                            		$out = new Requisites;
                            		$out->user_id = Yii::app()->user->getId();
                            	}
                            	$out->attributes = $_POST['Requisites'];
                            	if($res['result'] == 'success' && $out->validate()) {
                            		$out->save();
                            	}
                            	else {
	                                $res['result'] = 'errorValidate';
	                                if($out->errors) {
                                		$res['errors']['requisites'] = $out->errors;
	                                }
                            	}
                            }
                            
                            break;
                        case 'vizitka_logo':
                            $res['result'] = 'success';
                            break;
                        default:;
                    }
                } 
            }
        }
        die(json_encode($res));
    }
    
    /**
     * Добавление НОВЫХ данных Основного контента на странице пользователя через ajax.
     * type_id = model
     */
    public function actionAdd()
	{
        if (Yii::app()->request->isAjaxRequest) {# Пришел ли ajax-запрос
            if ( Yii::app()->user->getId() and !Yii::app()->user->isGuest ){
                $page = $_POST['type_id']; 
                if ( in_array($page, Yii::app()->params['pages']) ) { # Дополнительная проверка корректности имени закладки
                    switch ($page) {
                        case 'news':
                            $post = new News;
                            $post['title'] = 'Заголовок новости';
                            $post['descr'] = 'Описание новости';
                            $post['owner_id'] = Yii::app()->user->getId();
                            $post['rubricator_id'] = '1';
                            $post['data'] = date('Y-m-d');
                            $post->save();
                            $out = $this->renderPartial('editor_news', array('post'=>$post));
                            break;
                        case 'discounts':
                            $post = new Discounts;
                            $post['title'] = 'Заголовок скидки';
                            $post['descr'] = 'Описание скидки';
                            $post['owner_id'] = Yii::app()->user->getId();
                            $post['procent'] = 5;
                            $post['data'] = date('Y-m-d');
                            $post->save();
                            $out = $this->renderPartial('editor_discounts', array('post'=>$post));
                            break;
                        case 'articles':
                            $post = new Articles;
                            $post['title'] = 'Заголовок статьи';
                            $post['descr'] = 'Описание статьи';
                            $post['owner_id'] = Yii::app()->user->getId();
                            $post['data'] = date('Y-m-d');
                            $post->save();
                            $out = $this->renderPartial('editor_articles', array('post'=>$post));
                            break;
                        case 'tabs':
                            $tabName = stripslashes(strip_tags($_POST['name']));
                            $post = new Tabs;
                            $post['name'] = $tabName;
                            $post['data'] = date('Y-m-d H:i:s');
                            $post['owner_id'] = Yii::app()->user->getId();
                            if ($post->validate()) {
                                $post->save();
                                $out = 'success';
                            } else {
                                $out = 'errorValidate';
                            }
                            break;
                        case 'comApp':
                            $post = new ComPersonal;
                            $post['position'] = '_Должность';
                            $post['owner_id'] = Yii::app()->user->getId();
                            if ($post->save()) {
                                $out = 'success';
                            } else {
                                $out = 'errorValidate';
                            }
                            break;
                        default: ;
                    }
                    die($out);
                }
            }
        }
    }
        
    /**
     * Удаление данных Основного контента на странице пользователя через ajax.
     * model::id_record
     */
    public function actionDelete()
	{
        if (Yii::app()->request->isAjaxRequest) {
            if ( !empty($_POST["element_id"]) ) {
                $post = explode('::', $_POST["element_id"], 2);
                $post[0] = ucfirst($post[0]);
                $item = $post[0]::model()->findByPk($post[1]);#die('{"result":"success","cnt":'.$post[0]::model()->getCount($post[1]).'}');
                if ($item['owner_id'] == Yii::app()->user->getId() or $item['recipient_id'] == Yii::app()->user->getId()) {
                    if ($item->findByPk($post[1])->delete()) //Вставляем загрузку модели для обработки afterDelete()
                        if ($_POST["cnt"]) {
                            $cnt = $post[0]::model()->getCount($post[1]);
                            die(json_encode(array('result'=>'success', 'cnt'=>$cnt-1)));
                        } else die('success');
                }
                die('error');
            }
        }
    }
    /**
     * Удаление данных о сотруднике через ajax.
     * model::id_record
     */
    public function actionDeletePersonal()
	{
        if (Yii::app()->request->isAjaxRequest) {
            if ( !empty($_POST["element_id"]) ) {
                $post = explode('::', $_POST["element_id"], 2);
                $post[0] = ucfirst($post[0]);
                $item = $post[0]::model()->findByPk($post[1]);#die('{"result":"success","cnt":'.$post[0]::model()->getCount($post[1]).'}');
                if ($item['owner_id'] == Yii::app()->user->getId() or $item['recipient_id'] == Yii::app()->user->getId()) {
                    if ($item->deleteByPk($post[1]))
                            die(json_encode(array('result'=>'success', 'cnt'=>$cnt-1)));
                }
                die('error');
            }
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

    /**
	 * Действие autocomplete (Специальность).
     * Ajax-автодополнение ввода, поле "Специальность"
     * @return Array [Список специальностей, html {ul}].
     * Дублируется с Towns autocomplete TODO: оптимизировать!
	 */
	public function actionSpecAutocomplete()
	{
        if ( !empty($_POST['iKeywords']) ) {
            $ar = Specialities::model()->findAll( array (
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

    /**
     * Получение списка сообщений через ajax.
     * type: [all;unread;favorite]
     */
    public function actionGetMessages()
	{
        if (Yii::app()->request->isAjaxRequest) {
            if ( !empty($_POST["messageType"]) ) {
                $this->widget('dbfMessages', array('pageID'=>$pageID, 'messageType'=>trim(strip_tags($_POST["messageType"]))));
            }
        }
    }
    
    /**
     * Получение количества непрочитанных сообщений через ajax. (Вызывается через SetInterval)
     * type: [all;unread;favorite]
     */
    public function actionGetCntMessages()
	{
        if (Yii::app()->request->isAjaxRequest) {
            $this->widget('dbfMessages', array('pageID'=>$pageID, 'set'=>'getCnt::true'));
        }
    }
    /**
     * Получение списка сообщений через ajax.
     * type::id_message
     */
    public function actionSetMessage()
	{
        if (Yii::app()->request->isAjaxRequest) {
            if ( !empty($_POST["messageStatus"]) ) {
                $this->widget('dbfMessages', array('set'=>trim(strip_tags($_POST["messageStatus"]))));
            }
        }
    }

    /**
     * Отправка сообщений через ajax.
     * type::Text + pID
     */
    public function actionSendMessage()
	{
        if (Yii::app()->request->isAjaxRequest) {
            if ( !empty($_POST["messageBody"]) and !empty($_POST["pID"]) ) {
                $this->widget('dbfMessages', array('pageID'=>$_POST["pID"], 'set'=>'send::'.trim(strip_tags($_POST["messageBody"]))));
            }
        }
    }
    
    /**
     * Сохранение рейтинга компании через ajax.
     */
    public function actionSetRating($recipient_id, $rate) 
    {#die('here');
        $rating = Rating::model()->findByAttributes(array(
                'recipient_id' => $recipient_id,
                'owner_id' => Yii::app()->user->id,
            ));
        if ($rating->id) {
            $rating->rate = $rate;
            $rating->data = date('Y-m-d');
        } else {
            $rating = new Rating;
            $rating->recipient_id = $recipient_id;
            $rating->owner_id     = Yii::app()->user->id;
            $rating->rate = $rate;
            $rating->data = date('Y-m-d');
        }
        if ($rating->save())
            echo 'success';     
    }
    /**
     * Отправка сообщений через ajax.
     * type::Text + pID + sendType
     */
    public function actionSend()
	{
        if (Yii::app()->request->isAjaxRequest) {
            if ( !empty($_POST["messageBody"]) and !empty($_POST["pID"]) and !empty($_POST["sendType"]) ) {
                switch ($_POST["sendType"]){
                    case 'opinions':
                        $this->widget('dbfOpinions', array('pageID'=>$_POST["pID"], 'set'=>'send::'.trim(strip_tags($_POST["messageBody"]))));
                        break;
                }
            }
        }
    }
}