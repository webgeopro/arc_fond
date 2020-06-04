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
                'foreColor'   => 0x656565,
			),
		);
	}
    
    /**
     * Проверка прав на редактирование страницы
     * @param int $pageID - номер страницы
     * 
     * @return boolean [TRUE - владелец и оплаченный аккаунт, FALSE - гость/ user/ владелец, но НЕ ОПЛАЧЕН ак.]
     */
    public function getAccess($pageID=null)
	{
		if ($pageID == Yii::app()->user->getId() and Yii::app()->getModule('user')->isPaid())
            return true;
            
        return false;
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
        if ($userID != $pageID or !Yii::app()->getModule('user')->isPaid()) { # Страница отображается для всех
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
                array('title'=>'О компании','url'=>'about', 	'id'=>'tabAbout'),
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
        
		$activitySelected = array();
		$user = User::model()->findByPk($pageID);
	    foreach($user->activity as $activity) {
	    	$activitySelected[$activity->id] = $activity->name;
		}
        
        $this->render('index', array(
            'Tabs'   => $Tabs,
            'pageID' => $pageID,
            'user'   => User::model()->findByPk($pageID),
            'viz'    => $viz,
            'rating' => new Rating,
        	'activity' => $activitySelected,
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
            $pageID = (int)$_POST['pID']; # Приводим запрашиваемый ID к числу
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
                              	$activityItems = Activity::model()->roots()->findAll();
                              	// массив используется JavaScript для построения списка
                                $activitySelected = array();
                                foreach($post->activity as $activity) {
                                	$activitySelected[$activity->id] = array(
                                		'title' => $activity->name,
                                		'checked' => true
                                	);	
                                }
                                $out = $this->renderPartial(
                                	'editor_vizitka', 
	                                array(
	                                	'user' => $post, 
	                                	'requisites_fields' => Requisites::model()->attributeNames(), 
	                                	'requisites_model' => $data ? $data : Requisites::model(), 
	                                	'boolEdit' => (trim($_POST["viewer"])) ? false : true,
	                                	'pageID' => $pageID,
	                                	'activityItems' => $activityItems,
	                                	'activitySelected' => $activitySelected,
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
                            $out->attributes = $_POST;
//                            $out->town0 = Jobs::getTown($_POST["town0"]);
//                            $out->town  = Jobs::getTown($_POST["town"]);
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
                            // Сохранение сфер деятельности
                            $out = User::model()->findByPk(Yii::app()->user->getId());
                            $out->activity = ! empty($_POST['activity']) && is_array($_POST['activity']) ? $_POST['activity'] : array();
		                    if(! $out->save()) {
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
    
	/**
	 * Действие выбора области деятельности (Activity)
     * Ajax-вывод многоуровневых списков
     * @return Array [Список областей, html {option}].
	 */
	public function actionActivityOptions()
	{
		if(Yii::app()->request->isAjaxRequest) {
			if( !empty($_GET['root_id']) && $_GET['root_id'] >= 0) {
				$root = Activity::model()->findByPk($_GET['root_id']);
				if($root) {
					$items = $root->children()->findAll();						
				}
				else {
					die;
				}
			}
			else {
				$items = Activity::model()->roots()->findAll();				
			}
			if( !empty($items)) {
				$str = '';
				foreach($items as $item) {
					$str .= "<option value=\"{$item->id}\">{$item->name}</option>";			
				}
				die($str);
			}
			die;
		}
        die($str); 
	}
	
    /**
     * Объединение полей для вывода.
     * @param array Поля формы для слияния
     * 
     * @return string
     */
    public function fieldUnion($params)
	{
        if (!count($params)) return '';
        $temp = array();
        
        foreach ($params as $p) {
            $p = trim($p);
            if (!empty($p)) {
                $temp[] = $p;
            }
        }
        $res = trim(implode(', ', $temp));
        if (',' == substr($res, -1)) {
            $res = substr($p, 0, strlen($p)-1);
        }
        
        return $res;
    }

	/**
	 * Действие выбора города
     * Ajax-вывод многоуровневых списков
     * @return Array [Список областей, html {option}].
	 */
	public function actionTownsOptions()
	{
		if(Yii::app()->request->isAjaxRequest) {
			$items = Towns::model()->getChildren($_GET['root_id']);
			if( !empty($items)) {
				$str = '';
				foreach($items as $item) {
					$str .= "<option value=\"{$item->id}\">{$item->name}</option>";			
				}
				die($str);
			}
			die;
		}
        die($str); 
	}
	
//	/**
//	 * Метод для обновления сфер деятельности
//	 * Обновление от 30 декабря 2011 года (ЯрМап)
//	 */
//	public function actionUpdateActivity() {
//		$activities = unserialize('a:24:{i:0;a:2:{s:5:"title";s:70:"Автомобили, Автосервисы, Автозапчасти";s:8:"children";a:2:{i:25735;a:2:{s:5:"title";s:63:"Автосалоны, авто-мото, спецтехника";s:8:"children";a:4:{i:883;a:1:{s:5:"title";s:24:"Автоаукционы";}i:13871;a:1:{s:5:"title";s:20:"Автосалоны";}i:1043;a:1:{s:5:"title";s:37:"Грузовые автомобили";}i:13320;a:1:{s:5:"title";s:22:"Спецтехника";}}}i:1125;a:2:{s:5:"title";s:68:"Автосервис, автозапчасти, автотовары";s:8:"children";a:41:{i:1045;a:1:{s:5:"title";s:28:"Автоаксессуары";}i:1073;a:1:{s:5:"title";s:26:"Автодиски -опт";}i:1072;a:1:{s:5:"title";s:34:"Автодиски -розница";}i:385;a:1:{s:5:"title";s:32:"Автозапчасти -опт";}i:436;a:1:{s:5:"title";s:40:"Автозапчасти -розница";}i:1132;a:1:{s:5:"title";s:80:"Автомагнитолы, автозвук -продажа, установка";}i:711;a:1:{s:5:"title";s:18:"Автомойки";}i:1128;a:1:{s:5:"title";s:24:"Авторазборки";}i:1149;a:1:{s:5:"title";s:20:"Автосервис";}i:1131;a:1:{s:5:"title";s:113:"Автосигнализации, противоугонные системы -продажа, установка";}i:865;a:1:{s:5:"title";s:20:"Автостекло";}i:1129;a:1:{s:5:"title";s:20:"Автотюнинг";}i:694;a:1:{s:5:"title";s:46:"Автохимия, автомасла -опт";}i:695;a:1:{s:5:"title";s:54:"Автохимия, автомасла -розница";}i:893;a:1:{s:5:"title";s:18:"Авточехлы";}i:607;a:1:{s:5:"title";s:24:"Автошины -опт";}i:608;a:1:{s:5:"title";s:32:"Автошины -розница";}i:1057;a:1:{s:5:"title";s:18:"Автоэмали";}i:648;a:1:{s:5:"title";s:16:"АЗС, АГЗС";}i:1109;a:1:{s:5:"title";s:24:"Аккумуляторы";}i:709;a:1:{s:5:"title";s:39:"Водный транспорт -опт";}i:710;a:1:{s:5:"title";s:47:"Водный транспорт -розница";}i:1144;a:1:{s:5:"title";s:23:"Замена масла";}i:901;a:1:{s:5:"title";s:73:"Запчасти к железнодорожному транспорту";}i:1118;a:1:{s:5:"title";s:79:"Запчасти к мототехнике, водному транспорту";}i:844;a:1:{s:5:"title";s:72:"Запчасти к спецтехнике, сельхозтехнике";}i:1127;a:1:{s:5:"title";s:45:"Кузовной ремонт, Окраска";}i:399;a:1:{s:5:"title";s:30:"Мототехника -опт";}i:452;a:1:{s:5:"title";s:38:"Мототехника -розница";}i:5891;a:1:{s:5:"title";s:32:"Развал, Схождение";}i:1138;a:1:{s:5:"title";s:33:"Ремонт автостекол";}i:1135;a:1:{s:5:"title";s:39:"Ремонт автоэлектрики";}i:1136;a:1:{s:5:"title";s:21:"Ремонт АКПП";}i:1126;a:1:{s:5:"title";s:33:"Ремонт двигателей";}i:1151;a:1:{s:5:"title";s:67:"Ремонт и заправка автокондиционеров";}i:1137;a:1:{s:5:"title";s:35:"Ремонт спецтехники";}i:1150;a:1:{s:5:"title";s:41:"Ремонт стоек, подвески";}i:1133;a:1:{s:5:"title";s:77:"Технический осмотр транспорта (Техосмотр)";}i:1130;a:1:{s:5:"title";s:35:"Тонирование стекол";}i:5955;a:1:{s:5:"title";s:73:"Утилизация автомобилей по госпрограмме";}i:1134;a:1:{s:5:"title";s:20:"Шиномонтаж";}}}}}i:1;a:2:{s:5:"title";s:38:"Бизнес, офис, финансы";s:8:"children";a:32:{i:1161;a:1:{s:5:"title";s:32:"Автокредитование";}i:1189;a:1:{s:5:"title";s:30:"Автострахование";}i:12613;a:1:{s:5:"title";s:35:"Адвокатские услуги";}i:14222;a:1:{s:5:"title";s:124:"Адреса замены полисов обязательного медицинского страхования (ОМС)";}i:12506;a:1:{s:5:"title";s:35:"Аудиторские услуги";}i:351;a:1:{s:5:"title";s:10:"Банки";}i:707;a:1:{s:5:"title";s:18:"Банкоматы";}i:1211;a:1:{s:5:"title";s:31:"Бартерные услуги";}i:352;a:1:{s:5:"title";s:10:"Биржи";}i:353;a:1:{s:5:"title";s:33:"Брокерские услуги";}i:662;a:1:{s:5:"title";s:39:"Бухгалтерские услуги";}i:860;a:1:{s:5:"title";s:33:"Дилинговые центры";}i:1044;a:1:{s:5:"title";s:40:"Защита авторских прав";}i:361;a:1:{s:5:"title";s:53:"Информационное обслуживание";}i:655;a:1:{s:5:"title";s:43:"Ипотечное кредитование";}i:1192;a:1:{s:5:"title";s:39:"Коллекторские услуги";}i:817;a:1:{s:5:"title";s:70:"Кредиты физическим, юридическим лицам";}i:654;a:1:{s:5:"title";s:33:"Лизинговые услуги";}i:355;a:1:{s:5:"title";s:16:"Ломбарды";}i:1159;a:1:{s:5:"title";s:67:"Миграционные, иммиграционные услуги";}i:12614;a:1:{s:5:"title";s:37:"Нотариальные услуги";}i:356;a:1:{s:5:"title";s:51:"Операции с ценными бумагами";}i:661;a:1:{s:5:"title";s:52:"Паевые инвестиционные фонды";}i:1186;a:2:{s:5:"title";s:31:"Патентные услуги";s:8:"children";a:1:{i:1187;a:1:{s:5:"title";s:100:"Разработка и регистрация товарных знаков, изобретений";}}}i:861;a:1:{s:5:"title";s:48:"Представительство в судах";}i:889;a:1:{s:5:"title";s:51:"Прием платежей от населения";}i:660;a:1:{s:5:"title";s:67:"Регистрация, ликвидация предприятий";}i:659;a:1:{s:5:"title";s:54:"Сертификация, Лицензирование";}i:12525;a:1:{s:5:"title";s:31:"Страховые услуги";}i:658;a:1:{s:5:"title";s:41:"Таможенное оформление";}i:1012;a:1:{s:5:"title";s:39:"Факторинговые услуги";}i:363;a:1:{s:5:"title";s:57:"Юридические агентства, конторы";}}}i:2;a:2:{s:5:"title";s:46:"Досуг, развлечения, хобби";s:8:"children";a:19:{i:3;a:1:{s:5:"title";s:20:"Бильярдные";}i:12132;a:1:{s:5:"title";s:14:"Боулинг";}i:5;a:1:{s:5:"title";s:55:"Букмекерские конторы, Лотереи";}i:6169;a:1:{s:5:"title";s:14:"Дайвинг";}i:647;a:1:{s:5:"title";s:25:"Детский досуг";}i:12141;a:1:{s:5:"title";s:16:"Зоопарки";}i:3389;a:1:{s:5:"title";s:6:"КВН";}i:9;a:1:{s:5:"title";s:71:"Кинотеатры, развлекательные комплексы";}i:213;a:1:{s:5:"title";s:64:"Компьютерные клубы, Интернет клубы";}i:12133;a:1:{s:5:"title";s:23:"Ночные клубы";}i:249;a:1:{s:5:"title";s:67:"Организация и проведение праздников";}i:6105;a:1:{s:5:"title";s:59:"Организация экскурсий по городу";}i:26;a:1:{s:5:"title";s:34:"Парки, заповедники";}i:656;a:1:{s:5:"title";s:46:"Пейнтбол, страйкбол, тиры";}i:6106;a:1:{s:5:"title";s:43:"Праздничное оформление";}i:5510;a:1:{s:5:"title";s:6:"Тир";}i:28;a:1:{s:5:"title";s:31:"Увлечения,  хобби";}i:12;a:1:{s:5:"title";s:8:"Цирк";}i:554;a:1:{s:5:"title";s:33:"Эротические клубы";}}}i:3;a:2:{s:5:"title";s:56:"Животный мир, растительный мир";s:8:"children";a:2:{i:490;a:2:{s:5:"title";s:23:"Животный мир";s:8:"children";a:11:{i:13818;a:1:{s:5:"title";s:18:"Аквариумы";}i:64;a:1:{s:5:"title";s:43:"Ветеринарные препараты";}i:905;a:1:{s:5:"title";s:45:"Ветеринарные учреждения";}i:1165;a:1:{s:5:"title";s:42:"Гостиницы для животных";}i:13845;a:1:{s:5:"title";s:18:"Зооателье";}i:1119;a:1:{s:5:"title";s:44:"Клубы домашних животных";}i:702;a:1:{s:5:"title";s:59:"Комбикорм, кормовые добавки -опт";}i:703;a:1:{s:5:"title";s:67:"Комбикорм, кормовые добавки -розница";}i:13844;a:1:{s:5:"title";s:57:"Ритуальные услуги для животных";}i:581;a:1:{s:5:"title";s:60:"Товары для животных, Зоомагазины";}i:886;a:1:{s:5:"title";s:44:"Товары для животных -опт";}}}i:495;a:2:{s:5:"title";s:31:"Растительный мир";s:8:"children";a:8:{i:1098;a:1:{s:5:"title";s:43:"Заказ и доставка цветов";}i:491;a:1:{s:5:"title";s:36:"Семена, саженцы -опт";}i:496;a:1:{s:5:"title";s:44:"Семена, саженцы -розница";}i:492;a:1:{s:5:"title";s:42:"Удобрения, добавки -опт";}i:497;a:1:{s:5:"title";s:50:"Удобрения, добавки -розница";}i:498;a:1:{s:5:"title";s:37:"Химические средства";}i:494;a:1:{s:5:"title";s:43:"Цветы, флора-дизайн -опт";}i:499;a:1:{s:5:"title";s:51:"Цветы, флора-дизайн -розница";}}}}}i:4;a:2:{s:5:"title";s:53:"Интернет, Связь, IT технологии";s:8:"children";a:31:{i:1179;a:1:{s:5:"title";s:21:"IP-телефония";}i:233;a:1:{s:5:"title";s:48:"Городские телефонные сети";}i:214;a:1:{s:5:"title";s:37:"Интернет провайдеры";}i:215;a:1:{s:5:"title";s:29:"Интернет услуги";}i:25721;a:1:{s:5:"title";s:25:"ИТ-аутсорсинг";}i:957;a:1:{s:5:"title";s:41:"Кабельное телевидение";}i:969;a:1:{s:5:"title";s:40:"Карты экспресс-оплаты";}i:12278;a:1:{s:5:"title";s:69:"Контент-провайдеры, услуги call-центров";}i:971;a:1:{s:5:"title";s:33:"Курьерские службы";}i:790;a:1:{s:5:"title";s:48:"Монтаж компьютерных сетей";}i:1099;a:1:{s:5:"title";s:66:"Монтаж телекоммуникационных систем";}i:234;a:1:{s:5:"title";s:29:"Операторы связи";}i:228;a:1:{s:5:"title";s:44:"Операторы сотовой связи";}i:12280;a:1:{s:5:"title";s:82:"Отделения электросвязи, переговорные пункты";}i:225;a:1:{s:5:"title";s:35:"Почтовые агентства";}i:226;a:1:{s:5:"title";s:35:"Почтовые отделения";}i:6161;a:1:{s:5:"title";s:27:"Почтовые ящики";}i:897;a:1:{s:5:"title";s:86:"Проектирование и строительство объектов связи";}i:1167;a:1:{s:5:"title";s:79:"Системы навигации, пеленгации, мониторинга";}i:1037;a:1:{s:5:"title";s:35:"Системы радиосвязи";}i:853;a:1:{s:5:"title";s:81:"Системы регистрации телефонных переговоров";}i:855;a:1:{s:5:"title";s:95:"Системы экспертизы и шумоочистки речевых сообщений";}i:854;a:1:{s:5:"title";s:99:"Системы экстренного оповещения по телефонным каналам";}i:751;a:1:{s:5:"title";s:33:"Спутниковая связь";}i:960;a:1:{s:5:"title";s:61:"Спутниковое, эфирное телевидение";}i:232;a:1:{s:5:"title";s:52:"Телематические услуги связи";}i:1200;a:1:{s:5:"title";s:31:"Точки доступа wi-fi";}i:958;a:1:{s:5:"title";s:37:"Транкинговые услуги";}i:956;a:1:{s:5:"title";s:14:"Хостинг";}i:1175;a:1:{s:5:"title";s:27:"Экспресс-почта";}i:5925;a:1:{s:5:"title";s:43:"Электронная отчетность";}}}i:5;a:2:{s:5:"title";s:34:"Куда пойти учиться";s:8:"children";a:3:{i:13837;a:1:{s:5:"title";s:46:"Высшие учебные заведения";}i:13839;a:1:{s:5:"title";s:46:"Подготовка к поступлению";}i:13838;a:1:{s:5:"title";s:69:"Средне-специальные учебные заведения";}}}i:6;a:2:{s:5:"title";s:52:"Культура, Искусство, Религия";s:8:"children";a:4:{i:6812;a:2:{s:5:"title";s:67:"Библиотеки, Библиотечные коллекторы";s:8:"children";a:2:{i:6815;a:1:{s:5:"title";s:20:"Библиотеки";}i:6819;a:1:{s:5:"title";s:45:"Библиотечные коллекторы";}}}i:25732;a:1:{s:5:"title";s:16:"Граффити";}i:13;a:2:{s:5:"title";s:16:"Культура";s:8:"children";a:8:{i:16;a:1:{s:5:"title";s:20:"Киностудии";}i:17;a:1:{s:5:"title";s:59:"Музеи, выставочные залы, галереи";}i:18;a:1:{s:5:"title";s:45:"Танцевальные коллективы";}i:19;a:1:{s:5:"title";s:41:"Творческие коллективы";}i:20;a:1:{s:5:"title";s:12:"Театры";}i:21;a:1:{s:5:"title";s:20:"Филармонии";}i:22;a:1:{s:5:"title";s:41:"Художественные салоны";}i:15;a:1:{s:5:"title";s:49:"Центры досуга и творчества";}}}i:132;a:2:{s:5:"title";s:14:"Религия";s:8:"children";a:2:{i:133;a:1:{s:5:"title";s:45:"Религиозные организации";}i:12437;a:1:{s:5:"title";s:85:"Церкви, храмы, монастыри, культовые сооружения";}}}}}i:7;a:2:{s:5:"title";s:32:"Мебель, фурнитура";s:8:"children";a:19:{i:5909;a:1:{s:5:"title";s:27:"Детская мебель";}i:194;a:1:{s:5:"title";s:55:"Изготовление мебели под заказ";}i:672;a:1:{s:5:"title";s:31:"Корпусная мебель";}i:628;a:1:{s:5:"title";s:29:"Кухонная мебель";}i:5908;a:1:{s:5:"title";s:14:"Матрасы";}i:726;a:1:{s:5:"title";s:45:"Мебель для ванных комнат";}i:1055;a:1:{s:5:"title";s:80:"Мебель для заведений общественного питания";}i:1026;a:1:{s:5:"title";s:89:"Мебель для медицинских учреждений и лабораторий";}i:674;a:1:{s:5:"title";s:55:"Мебель для учебных учреждений";}i:724;a:1:{s:5:"title";s:30:"Мебель из стекла";}i:683;a:1:{s:5:"title";s:46:"Мебель на металлокаркасе";}i:728;a:1:{s:5:"title";s:37:"Мебельная фурнитура";}i:1139;a:1:{s:5:"title";s:29:"Мебельные ткани";}i:863;a:1:{s:5:"title";s:51:"Металлическая мебель, сейфы";}i:673;a:1:{s:5:"title";s:25:"Мягкая мебель";}i:625;a:1:{s:5:"title";s:27:"Офисная мебель";}i:1102;a:1:{s:5:"title";s:29:"Плетеная мебель";}i:14196;a:1:{s:5:"title";s:49:"Ремонт, реставрация мебели";}i:1007;a:1:{s:5:"title";s:42:"Садово-парковая мебель";}}}i:8;a:2:{s:5:"title";s:50:"Медицина, красота, здоровье";s:8:"children";a:5:{i:12293;a:2:{s:5:"title";s:38:"Аптеки, фармацевтика";s:8:"children";a:5:{i:63;a:1:{s:5:"title";s:12:"Аптеки";}i:990;a:1:{s:5:"title";s:65:"Биологически-активные добавки (БАД)";}i:845;a:1:{s:5:"title";s:30:"Медикаменты -опт";}i:986;a:1:{s:5:"title";s:37:"Медикаменты-розница";}i:65;a:1:{s:5:"title";s:69:"Фармакологические фирмы, организации";}}}i:12575;a:2:{s:5:"title";s:34:"Красота и здоровье";s:8:"children";a:7:{i:3374;a:1:{s:5:"title";s:35:"Инфракрасные сауны";}i:301;a:1:{s:5:"title";s:68:"Косметические салоны, Салоны красоты";}i:1188;a:1:{s:5:"title";s:47:"Наращивание волос, ресниц";}i:909;a:1:{s:5:"title";s:29:"Ногтевые студии";}i:303;a:1:{s:5:"title";s:28:"Парикмахерские";}i:12579;a:1:{s:5:"title";s:14:"Солярии";}i:12578;a:1:{s:5:"title";s:51:"Тату, татуаж, пирсинг салоны";}}}i:30;a:2:{s:5:"title";s:35:"Медицинские услуги";s:8:"children";a:18:{i:31;a:1:{s:5:"title";s:33:"Анонимное лечение";}i:1160;a:1:{s:5:"title";s:51:"Восстановительная медицина";}i:32;a:1:{s:5:"title";s:43:"Врачебная косметология";}i:1096;a:1:{s:5:"title";s:22:"Гинекология";}i:5922;a:1:{s:5:"title";s:26:"Гирудотерапия";}i:1031;a:1:{s:5:"title";s:20:"Гомеопатия";}i:33;a:1:{s:5:"title";s:22:"Диагностика";}i:1061;a:1:{s:5:"title";s:31:"Коррекция зрения";}i:34;a:1:{s:5:"title";s:31:"Коррекция фигуры";}i:35;a:1:{s:5:"title";s:39:"Лечение зависимостей";}i:5923;a:1:{s:5:"title";s:35:"Мануальная терапия";}i:952;a:1:{s:5:"title";s:12:"Массаж";}i:36;a:1:{s:5:"title";s:45:"Нетрадиционная медицина";}i:5894;a:1:{s:5:"title";s:35:"Очищение организма";}i:37;a:1:{s:5:"title";s:41:"Пластическая хирургия";}i:38;a:1:{s:5:"title";s:31:"Услуги психолога";}i:1117;a:1:{s:5:"title";s:20:"Фитоцентры";}i:1027;a:1:{s:5:"title";s:33:"Школы материнства";}}}i:39;a:2:{s:5:"title";s:43:"Медицинские учреждения";s:8:"children";a:18:{i:40;a:1:{s:5:"title";s:16:"Больницы";}i:42;a:1:{s:5:"title";s:18:"Госпитали";}i:43;a:1:{s:5:"title";s:58:"Детские медицинские учреждения";}i:44;a:1:{s:5:"title";s:20:"Диспансеры";}i:45;a:1:{s:5:"title";s:39:"Женские консультации";}i:1215;a:1:{s:5:"title";s:21:"ЛОР-клиники";}i:46;a:1:{s:5:"title";s:43:"Медицинская экспертиза";}i:47;a:1:{s:5:"title";s:59:"Медицинские центры, лаборатории";}i:48;a:1:{s:5:"title";s:10:"Морги";}i:49;a:1:{s:5:"title";s:6:"МСЧ";}i:50;a:1:{s:5:"title";s:22:"Поликлиники";}i:51;a:1:{s:5:"title";s:27:"Родильные дома";}i:52;a:1:{s:5:"title";s:46:"Санатории, профилактории";}i:53;a:1:{s:5:"title";s:68:"Санитарно-эпидемиологический надзор";}i:54;a:1:{s:5:"title";s:48:"Станции переливания крови";}i:55;a:1:{s:5:"title";s:40:"Станции скорой помощи";}i:56;a:1:{s:5:"title";s:24:"Стоматологии";}i:57;a:1:{s:5:"title";s:22:"Травмпункты";}}}i:58;a:2:{s:5:"title";s:61:"Медицинское оборудование, товары";s:8:"children";a:4:{i:1083;a:1:{s:5:"title";s:43:"Массажное оборудование";}i:59;a:1:{s:5:"title";s:86:"Медицинское оборудование, расходные материалы";}i:61;a:1:{s:5:"title";s:12:"Оптика";}i:1004;a:1:{s:5:"title";s:59:"Протезы, ортопедические изделия";}}}}}i:9;a:2:{s:5:"title";s:92:"Наука, Исследования, Сертификация, Стандартизация";s:8:"children";a:2:{i:67;a:2:{s:5:"title";s:73:"Исследования в сфере бизнеса и общества";s:8:"children";a:3:{i:68;a:1:{s:5:"title";s:65:"Маркетинговые исследования, услуги";}i:69;a:1:{s:5:"title";s:55:"Социологические исследования";}i:70;a:1:{s:5:"title";s:45:"Финансовые исследования";}}}i:73;a:2:{s:5:"title";s:92:"Наука, исследования, Сертификация, Стандартизация";s:8:"children";a:9:{i:1062;a:1:{s:5:"title";s:69:"Аттестация рабочих мест, Охрана труда";}i:1116;a:1:{s:5:"title";s:40:"Картография, геодезия";}i:75;a:1:{s:5:"title";s:90:"Научно-исследовательские институты, организации";}i:74;a:1:{s:5:"title";s:68:"Научно-производственные организации";}i:76;a:1:{s:5:"title";s:41:"Проектные организации";}i:843;a:1:{s:5:"title";s:99:"Сертификация, стандартизация приборов и оборудования";}i:842;a:1:{s:5:"title";s:53:"Сертификация товаров и услуг";}i:77;a:1:{s:5:"title";s:11:"СО РАН";}i:1032;a:1:{s:5:"title";s:35:"Экспертиза товаров";}}}}}i:10;a:2:{s:5:"title";s:22:"Образование";s:8:"children";a:7:{i:79;a:2:{s:5:"title";s:46:"Высшие учебные заведения";s:8:"children";a:3:{i:80;a:1:{s:5:"title";s:16:"Академии";}i:81;a:1:{s:5:"title";s:18:"Институты";}i:82;a:1:{s:5:"title";s:24:"Университеты";}}}i:83;a:2:{s:5:"title";s:81:"Дополнительное образование, переподготовка";s:8:"children";a:12:{i:1028;a:1:{s:5:"title";s:18:"Автошколы";}i:926;a:1:{s:5:"title";s:37:"Бухгалтерские курсы";}i:1172;a:1:{s:5:"title";s:81:"Дополнительное образование, переподготовка";}i:915;a:1:{s:5:"title";s:35:"Компьютерные курсы";}i:936;a:1:{s:5:"title";s:14:"Курсы 1С";}i:88;a:1:{s:5:"title";s:27:"Курсы обучения";}i:86;a:1:{s:5:"title";s:36:"Обучение за рубежом";}i:976;a:1:{s:5:"title";s:83:"Обучение мерам противопожарной безопасности";}i:1204;a:1:{s:5:"title";s:41:"Обучение судовождению";}i:891;a:1:{s:5:"title";s:32:"Помощь в обучении";}i:87;a:1:{s:5:"title";s:64:"Учебно-производственные комбинаты";}i:916;a:1:{s:5:"title";s:46:"Школы иностранных языков";}}}i:90;a:2:{s:5:"title";s:66:"Дополнительное среднее образование";s:8:"children";a:2:{i:92;a:1:{s:5:"title";s:33:"Музыкальные школы";}i:94;a:1:{s:5:"title";s:27:"Школы искусств";}}}i:95;a:2:{s:5:"title";s:43:"Дошкольное образование";s:8:"children";a:2:{i:96;a:1:{s:5:"title";s:33:"Детские сады, Ясли";}i:1219;a:1:{s:5:"title";s:44:"Центры развития ребенка";}}}i:97;a:2:{s:5:"title";s:43:"Повышение квалификации";s:8:"children";a:1:{i:98;a:1:{s:5:"title";s:43:"Повышение квалификации";}}}i:99;a:2:{s:5:"title";s:58:"Средне-специальное образование";s:8:"children";a:4:{i:100;a:1:{s:5:"title";s:16:"Колледжи";}i:101;a:1:{s:5:"title";s:10:"Лицеи";}i:102;a:1:{s:5:"title";s:18:"Техникумы";}i:103;a:1:{s:5:"title";s:14:"Училища";}}}i:104;a:2:{s:5:"title";s:10:"Школы";s:8:"children";a:2:{i:106;a:1:{s:5:"title";s:29:"Школы-интернаты";}i:108;a:1:{s:5:"title";s:49:"Школы общеобразовательные";}}}}}i:11;a:2:{s:5:"title";s:39:"Общественное питание";s:8:"children";a:12:{i:116;a:1:{s:5:"title";s:8:"Бары";}i:13477;a:1:{s:5:"title";s:50:"Заведения быстрого питания";}i:5887;a:1:{s:5:"title";s:8:"Кафе";}i:1069;a:1:{s:5:"title";s:85:"Кейтеринг (выездное ресторанное обслуживание)";}i:121;a:1:{s:5:"title";s:33:"Комбинаты питания";}i:645;a:1:{s:5:"title";s:14:"Кофейни";}i:115;a:1:{s:5:"title";s:18:"Кулинарии";}i:6047;a:1:{s:5:"title";s:20:"Пельменные";}i:117;a:1:{s:5:"title";s:16:"Пиццерии";}i:5888;a:1:{s:5:"title";s:18:"Рестораны";}i:120;a:1:{s:5:"title";s:44:"Службы доставки питания";}i:5889;a:1:{s:5:"title";s:37:"Суши-бары, рестораны";}}}i:12;a:2:{s:5:"title";s:95:"Общественные, Социальные, Политические организации";s:8:"children";a:3:{i:123;a:2:{s:5:"title";s:47:"Общественные организации";s:8:"children";a:4:{i:124;a:1:{s:5:"title";s:45:"Благотворительные фонды";}i:125;a:1:{s:5:"title";s:61:"Детские и молодежные организации";}i:127;a:1:{s:5:"title";s:47:"Общественные организации";}i:129;a:1:{s:5:"title";s:45:"Профсоюзные организации";}}}i:130;a:2:{s:5:"title";s:47:"Политические организации";s:8:"children";a:1:{i:131;a:1:{s:5:"title";s:47:"Политические организации";}}}i:135;a:2:{s:5:"title";s:33:"Социальная защита";s:8:"children";a:5:{i:136;a:1:{s:5:"title";s:37:"Детские дома, приюты";}i:139;a:1:{s:5:"title";s:31:"Дома престарелых";}i:138;a:1:{s:5:"title";s:56:"Органы социального содействия";}i:137;a:1:{s:5:"title";s:50:"Службы занятости населения";}i:140;a:1:{s:5:"title";s:33:"Социальная помощь";}}}}}i:13;a:2:{s:5:"title";s:37:"Органы власти, город";s:8:"children";a:8:{i:741;a:2:{s:5:"title";s:54:"Администрации районов города";s:8:"children";a:7:{i:742;a:1:{s:5:"title";s:72:"Администрация Железнодорожного района";}i:743;a:1:{s:5:"title";s:60:"Администрация Кировского района";}i:744;a:1:{s:5:"title";s:60:"Администрация Ленинского района";}i:745;a:1:{s:5:"title";s:64:"Администрация Октябрьского района";}i:746;a:1:{s:5:"title";s:66:"Администрация Свердловского района";}i:747;a:1:{s:5:"title";s:60:"Администрация Советского района";}i:748;a:1:{s:5:"title";s:64:"Администрация Центрального района";}}}i:142;a:2:{s:5:"title";s:62:"Администрация города Красноярска";s:8:"children";a:5:{i:144;a:1:{s:5:"title";s:56:"Глава города и его заместители";}i:6156;a:1:{s:5:"title";s:77:"Депутаты Красноярского городского совета";}i:6095;a:1:{s:5:"title";s:79:"Избирательная комиссия города Красноярска";}i:145;a:1:{s:5:"title";s:102:"Органы администрации города без прав юридического лица";}i:146;a:1:{s:5:"title";s:104:"Органы администрации города с правами юридического лица";}}}i:721;a:2:{s:5:"title";s:35:"Внебюджетные фонды";s:8:"children";a:2:{i:722;a:1:{s:5:"title";s:31:"Пенсионные фонды";}i:723;a:1:{s:5:"title";s:56:"Фонды социального страхования";}}}i:147;a:2:{s:5:"title";s:43:"Государственные службы";s:8:"children";a:17:{i:148;a:1:{s:5:"title";s:12:"Архивы";}i:675;a:1:{s:5:"title";s:69:"Бюро технической инвентаризации (БТИ)";}i:149;a:1:{s:5:"title";s:59:"Военные организации, Военкоматы";}i:1170;a:1:{s:5:"title";s:43:"Государственные службы";}i:150;a:1:{s:5:"title";s:10:"ЗАГСы";}i:151;a:1:{s:5:"title";s:46:"Защита прав потребителей";}i:152;a:1:{s:5:"title";s:18:"Инспекции";}i:1042;a:1:{s:5:"title";s:24:"Казначейства";}i:153;a:1:{s:5:"title";s:31:"Налоговые службы";}i:154;a:1:{s:5:"title";s:33:"Органы статистики";}i:155;a:1:{s:5:"title";s:43:"Природоохранные службы";}i:156;a:1:{s:5:"title";s:44:"Противопожарные  службы";}i:157;a:1:{s:5:"title";s:25:"Службы ГО и ЧС";}i:158;a:1:{s:5:"title";s:69:"Службы лицензирования и сертификации";}i:1164;a:1:{s:5:"title";s:39:"Спасательные станции";}i:1022;a:1:{s:5:"title";s:31:"Телефоны доверия";}i:159;a:1:{s:5:"title";s:10:"Фонды";}}}i:160;a:2:{s:5:"title";s:62:"Правительство Красноярского края";s:8:"children";a:11:{i:166;a:1:{s:5:"title";s:81:"Агентства Правительства Красноярского края";}i:162;a:1:{s:5:"title";s:53:"Губернатор и его заместители";}i:164;a:1:{s:5:"title";s:87:"Департаменты Правительства Красноярского края";}i:737;a:1:{s:5:"title";s:43:"Законодательная власть";}i:167;a:1:{s:5:"title";s:50:"Иные исполнительные органы";}i:1195;a:1:{s:5:"title";s:87:"Министерства Правительства Красноярского края";}i:1197;a:1:{s:5:"title";s:62:"Правительство Красноярского края";}i:161;a:1:{s:5:"title";s:79:"Приемная администрации Красноярского края";}i:165;a:1:{s:5:"title";s:75:"Службы Правительства Красноярского края";}i:163;a:1:{s:5:"title";s:73:"Совет администрации Красноярского края";}i:1169;a:1:{s:5:"title";s:27:"Счетная палата";}}}i:168;a:2:{s:5:"title";s:49:"Правоохранительные органы";s:8:"children";a:15:{i:172;a:1:{s:5:"title";s:45:"Вневедомственная охрана";}i:169;a:1:{s:5:"title";s:10:"ГИБДД";}i:3379;a:1:{s:5:"title";s:49:"Исправительные учреждения";}i:170;a:1:{s:5:"title";s:30:"Медвытрезвители";}i:173;a:1:{s:5:"title";s:85:"Паспортно-визовый учет, миграционный контроль";}i:171;a:1:{s:5:"title";s:14:"Полиция";}i:582;a:1:{s:5:"title";s:22:"Прокуратуры";}i:174;a:1:{s:5:"title";s:18:"РУВД, ГУВД";}i:175;a:1:{s:5:"title";s:39:"Системы безопасности";}i:176;a:1:{s:5:"title";s:58:"Судебно-медицинская экспертиза";}i:618;a:1:{s:5:"title";s:33:"Судебные приставы";}i:177;a:1:{s:5:"title";s:8:"Суды";}i:178;a:1:{s:5:"title";s:14:"Таможня";}i:583;a:1:{s:5:"title";s:33:"Участковые пункты";}i:179;a:1:{s:5:"title";s:6:"ФСБ";}}}i:699;a:2:{s:5:"title";s:53:"Природоохранные организации";s:8:"children";a:2:{i:700;a:1:{s:5:"title";s:32:"Гидрометеослужбы";}i:701;a:1:{s:5:"title";s:53:"Природоохранные организации";}}}i:739;a:2:{s:5:"title";s:48:"Федеральные органы власти";s:8:"children";a:1:{i:740;a:1:{s:5:"title";s:48:"Федеральные органы власти";}}}}}i:14;a:2:{s:5:"title";s:47:"Продовольственные товары";s:8:"children";a:3:{i:501;a:2:{s:5:"title";s:31:"Оптовая торговля";s:8:"children";a:27:{i:510;a:1:{s:5:"title";s:45:"Алкогольные напитки -опт";}i:665;a:1:{s:5:"title";s:51:"Безалкогольные напитки -опт";}i:502;a:1:{s:5:"title";s:37:"Детское питание -опт";}i:871;a:1:{s:5:"title";s:47:"Диетические продукты -опт";}i:705;a:1:{s:5:"title";s:18:"Зерно -опт";}i:503;a:1:{s:5:"title";s:47:"Кондитерские изделия -опт";}i:873;a:1:{s:5:"title";s:59:"Консервированная продукция -опт";}i:504;a:1:{s:5:"title";s:28:"Крупы, Мука -опт";}i:506;a:1:{s:5:"title";s:43:"Макаронные изделия -опт";}i:964;a:1:{s:5:"title";s:51:"Масложировая продукция -опт";}i:507;a:1:{s:5:"title";s:41:"Молочные продукты -опт";}i:509;a:1:{s:5:"title";s:37:"Мясные продукты -опт";}i:1090;a:1:{s:5:"title";s:16:"Пиво -опт";}i:671;a:1:{s:5:"title";s:33:"Питьевая вода -опт";}i:5904;a:1:{s:5:"title";s:34:"Полуфабрикаты -опт";}i:876;a:1:{s:5:"title";s:68:"Продукты быстрого приготовления -опт";}i:513;a:1:{s:5:"title";s:39:"Продукты питания -опт";}i:875;a:1:{s:5:"title";s:57:"Продукты пчеловодства, Мед -опт";}i:508;a:1:{s:5:"title";s:42:"Рыба, морепродукты -опт";}i:515;a:1:{s:5:"title";s:28:"Сахар, Соль -опт";}i:878;a:1:{s:5:"title";s:43:"Снэковая продукция -опт";}i:879;a:1:{s:5:"title";s:56:"Специи, Приправы, Пряности -опт";}i:880;a:1:{s:5:"title";s:61:"Сырье для пищевой промышленности";}i:517;a:1:{s:5:"title";s:56:"Фрукты, овощи, ягоды, грибы -опт";}i:518;a:1:{s:5:"title";s:49:"Хлебобулочные изделия -опт";}i:514;a:1:{s:5:"title";s:40:"Чай, Кофе - продажа опт";}i:881;a:1:{s:5:"title";s:16:"Яйцо -опт";}}}i:704;a:1:{s:5:"title";s:51:"Продовольственные магазины";}i:519;a:2:{s:5:"title";s:35:"Розничная торговля";s:8:"children";a:25:{i:528;a:1:{s:5:"title";s:53:"Алкогольные напитки -розница";}i:666;a:1:{s:5:"title";s:59:"Безалкогольные напитки -розница";}i:520;a:1:{s:5:"title";s:45:"Детское питание -розница";}i:872;a:1:{s:5:"title";s:55:"Диетические продукты -розница";}i:13400;a:1:{s:5:"title";s:41:"Зерно: продажа розница";}i:521;a:1:{s:5:"title";s:55:"Кондитерские изделия -розница";}i:874;a:1:{s:5:"title";s:67:"Консервированная продукция -розница";}i:522;a:1:{s:5:"title";s:36:"Крупы, Мука -розница";}i:530;a:1:{s:5:"title";s:35:"Магазины - чай, кофе";}i:524;a:1:{s:5:"title";s:51:"Макаронные изделия -розница";}i:5929;a:1:{s:5:"title";s:59:"Масложировая продукция -розница";}i:525;a:1:{s:5:"title";s:49:"Молочные продукты -розница";}i:13815;a:1:{s:5:"title";s:34:"Мороженое -розница";}i:527;a:1:{s:5:"title";s:45:"Мясные продукты -розница";}i:1091;a:1:{s:5:"title";s:24:"Пиво -розница";}i:667;a:1:{s:5:"title";s:41:"Питьевая вода -розница";}i:5905;a:1:{s:5:"title";s:42:"Полуфабрикаты -розница";}i:941;a:1:{s:5:"title";s:76:"Продукты быстрого приготовления -розница";}i:961;a:1:{s:5:"title";s:64:"Продукты пчеловодства, Мед-розница";}i:526;a:1:{s:5:"title";s:50:"Рыба, морепродукты -розница";}i:531;a:1:{s:5:"title";s:36:"Сахар, Соль -розница";}i:1078;a:1:{s:5:"title";s:51:"Снэковая продукция -розница";}i:937;a:1:{s:5:"title";s:64:"Специи, Приправы, Пряности -розница";}i:533;a:1:{s:5:"title";s:64:"Фрукты, овощи, ягоды, грибы -розница";}i:534;a:1:{s:5:"title";s:57:"Хлебобулочные изделия -розница";}}}}}i:15;a:2:{s:5:"title";s:24:"Производство";s:8:"children";a:35:{i:616;a:1:{s:5:"title";s:28:"Балконы, Лоджии";}i:1121;a:1:{s:5:"title";s:54:"Животноводство, птицеводство";}i:1104;a:1:{s:5:"title";s:87:"Изготовление запчастей к оборудованию, технике";}i:1051;a:1:{s:5:"title";s:68:"Изготовление торгового оборудования";}i:1064;a:1:{s:5:"title";s:28:"Машиностроение";}i:1173;a:1:{s:5:"title";s:31:"Мебельные фасады";}i:200;a:1:{s:5:"title";s:43:"Пищевая промышленность";}i:965;a:1:{s:5:"title";s:35:"Пресс-формы, штампы";}i:1162;a:1:{s:5:"title";s:24:"Производство";}i:182;a:1:{s:5:"title";s:37:"Производство бумаги";}i:183;a:1:{s:5:"title";s:54:"Производство бытовой техники";}i:184;a:1:{s:5:"title";s:72:"Производство водного транспорта, судов";}i:1053;a:1:{s:5:"title";s:80:"Производство высоковольтного оборудования";}i:1006;a:1:{s:5:"title";s:86:"Производство знаков и плакатов по охране труда";}i:187;a:1:{s:5:"title";s:39:"Производство игрушек";}i:6116;a:1:{s:5:"title";s:63:"Производство изделий из пластмасс";}i:787;a:1:{s:5:"title";s:64:"Производство климатических систем";}i:1005;a:1:{s:5:"title";s:66:"Производство косметических товаров";}i:749;a:1:{s:5:"title";s:70:"Производство котельного оборудования";}i:207;a:1:{s:5:"title";s:68:"Производство медицинских препаратов";}i:1080;a:1:{s:5:"title";s:39:"Производство метизов";}i:975;a:1:{s:5:"title";s:72:"Производство низковольтной аппаратуры";}i:196;a:1:{s:5:"title";s:49:"Производство оборудования";}i:924;a:1:{s:5:"title";s:35:"Производство обуви";}i:197;a:1:{s:5:"title";s:37:"Производство одежды";}i:570;a:1:{s:5:"title";s:47:"Производство окон, дверей";}i:789;a:1:{s:5:"title";s:78:"Производство радиоэлектронной аппаратуры";}i:963;a:1:{s:5:"title";s:39:"Производство РТИ, АТИ";}i:943;a:1:{s:5:"title";s:74:"Производство стеклопластиковых изделий";}i:205;a:1:{s:5:"title";s:51:"Производство тары, упаковки";}i:206;a:1:{s:5:"title";s:39:"Производство техники";}i:887;a:1:{s:5:"title";s:76:"Производство электромонтажной продукции";}i:210;a:1:{s:5:"title";s:58:"Производство ювелирных изделий";}i:1203;a:1:{s:5:"title";s:51:"Промышленная автоматизация";}i:1124;a:1:{s:5:"title";s:30:"Растениеводство";}}}i:16;a:2:{s:5:"title";s:44:"СМИ, Реклама, Полиграфия";s:8:"children";a:3:{i:238;a:2:{s:5:"title";s:20:"Полиграфия";s:8:"children";a:12:{i:813;a:1:{s:5:"title";s:40:"Бумага для полиграфии";}i:239;a:1:{s:5:"title";s:55:"Изготовление печатей, штампов";}i:1142;a:1:{s:5:"title";s:56:"Изготовление пластиковых карт";}i:240;a:1:{s:5:"title";s:24:"Издательства";}i:13805;a:1:{s:5:"title";s:39:"Копировальные услуги";}i:241;a:1:{s:5:"title";s:74:"Материалы и оборудование для полиграфии";}i:1157;a:1:{s:5:"title";s:29:"Офсетная печать";}i:1011;a:1:{s:5:"title";s:64:"Переплет бухгалтерских документов";}i:12232;a:1:{s:5:"title";s:43:"Полиграфические услуги";}i:243;a:1:{s:5:"title";s:20:"Типографии";}i:1010;a:1:{s:5:"title";s:46:"Шелкография, Тампопечать";}i:829;a:1:{s:5:"title";s:43:"Широкоформатная печать";}}}i:244;a:2:{s:5:"title";s:14:"Реклама";s:8:"children";a:18:{i:245;a:1:{s:5:"title";s:43:"PR агентства, Промоакции";}i:1085;a:1:{s:5:"title";s:59:"Видеостудии, студии звукозаписи";}i:643;a:1:{s:5:"title";s:27:"Дизайн рекламы";}i:970;a:1:{s:5:"title";s:54:"Директ маркетинг, директ мэйл";}i:914;a:1:{s:5:"title";s:66:"Изготовление рекламных конструкций";}i:246;a:1:{s:5:"title";s:60:"Информационно-справочные услуги";}i:794;a:1:{s:5:"title";s:39:"Компьютерная вышивка";}i:247;a:1:{s:5:"title";s:68:"Материалы и оборудование для рекламы";}i:248;a:1:{s:5:"title";s:37:"Модельные агентства";}i:578;a:1:{s:5:"title";s:31:"Наружная реклама";}i:913;a:1:{s:5:"title";s:31:"Неоновая реклама";}i:250;a:1:{s:5:"title";s:39:"Производство рекламы";}i:251;a:1:{s:5:"title";s:35:"Размещение рекламы";}i:888;a:1:{s:5:"title";s:43:"Реклама в сети Интернет";}i:912;a:1:{s:5:"title";s:40:"Реклама на транспорте";}i:252;a:1:{s:5:"title";s:37:"Рекламные агентства";}i:814;a:1:{s:5:"title";s:39:"Сувенирная продукция";}i:253;a:1:{s:5:"title";s:32:"Ярмарки, Выставки";}}}i:254;a:2:{s:5:"title";s:54:"Средства массовой информации";s:8:"children";a:9:{i:904;a:1:{s:5:"title";s:58:"Адресно-телефонные справочники";}i:255;a:1:{s:5:"title";s:12:"Газеты";}i:256;a:1:{s:5:"title";s:14:"Журналы";}i:257;a:1:{s:5:"title";s:31:"Интернет-издания";}i:934;a:1:{s:5:"title";s:33:"Интернет-магазины";}i:1177;a:1:{s:5:"title";s:47:"Информационные агентства";}i:259;a:1:{s:5:"title";s:10:"Радио";}i:1180;a:1:{s:5:"title";s:50:"Разработка учебных пособий";}i:260;a:1:{s:5:"title";s:22:"Телевидение";}}}}}i:17;a:2:{s:5:"title";s:53:"Спорт, Туризм, Активный отдых";s:8:"children";a:4:{i:262;a:2:{s:5:"title";s:26:"Охота, Рыбалка";s:8:"children";a:1:{i:263;a:1:{s:5:"title";s:75:"Организации, клубы Охотников и Рыболовов";}}}i:264;a:2:{s:5:"title";s:10:"Спорт";s:8:"children";a:13:{i:1202;a:1:{s:5:"title";s:34:"Авиамоделирование";}i:1220;a:1:{s:5:"title";s:20:"Автоквесты";}i:265;a:1:{s:5:"title";s:16:"Бассейны";}i:890;a:1:{s:5:"title";s:39:"Игровое оборудование";}i:267;a:1:{s:5:"title";s:16:"Ипподром";}i:684;a:1:{s:5:"title";s:21:"Лыжные базы";}i:1082;a:1:{s:5:"title";s:35:"Спортивное питание";}i:268;a:1:{s:5:"title";s:55:"Спортивные клубы, организации";}i:269;a:1:{s:5:"title";s:31:"Спортивные школы";}i:270;a:1:{s:5:"title";s:46:"Стадионы, спорткомплексы";}i:579;a:1:{s:5:"title";s:31:"Тренажерные залы";}i:580;a:1:{s:5:"title";s:39:"Фитнес-клубы, шейпинг";}i:1021;a:1:{s:5:"title";s:23:"Школы танцев";}}}i:838;a:2:{s:5:"title";s:53:"Спортивные, охотничьи товары";s:8:"children";a:5:{i:885;a:1:{s:5:"title";s:20:"Велосипеды";}i:601;a:1:{s:5:"title";s:34:"Оружие, боеприпасы";}i:557;a:1:{s:5:"title";s:53:"Рыболовные, охотничьи товары";}i:479;a:1:{s:5:"title";s:33:"Спортивные товары";}i:634;a:1:{s:5:"title";s:39:"Туристические товары";}}}i:13777;a:2:{s:5:"title";s:39:"Туристические услуги";s:8:"children";a:2:{i:1140;a:1:{s:5:"title";s:21:"Базы отдыха";}i:273;a:1:{s:5:"title";s:45:"Туристические агентства";}}}}}i:18;a:2:{s:5:"title";s:72:"Строительство, Недвижимость, Материалы";s:8:"children";a:3:{i:276;a:2:{s:5:"title";s:24:"Недвижимость";s:8:"children";a:7:{i:277;a:1:{s:5:"title";s:43:"Агентства недвижимости";}i:278;a:1:{s:5:"title";s:31:"Аренда помещений";}i:1141;a:1:{s:5:"title";s:39:"Девелоперские услуги";}i:851;a:1:{s:5:"title";s:31:"Ипотечные услуги";}i:1178;a:1:{s:5:"title";s:44:"Недвижимость за рубежом";}i:1100;a:1:{s:5:"title";s:83:"Оформление документов на недвижимость, землю";}i:906;a:1:{s:5:"title";s:66:"Покупка, продажа земельных участков";}}}i:818;a:2:{s:5:"title";s:65:"Строительные, отделочные материалы";s:8:"children";a:36:{i:954;a:1:{s:5:"title";s:37:"Акриловые материалы";}i:1112;a:1:{s:5:"title";s:40:"Бетон, Раствор, Цемент";}i:799;a:1:{s:5:"title";s:61:"Гидроизоляционные материалы -опт";}i:800;a:1:{s:5:"title";s:69:"Гидроизоляционные материалы -розница";}i:1143;a:1:{s:5:"title";s:50:"Гипсокартон, комплектующие";}i:1033;a:1:{s:5:"title";s:43:"Железобетонные изделия";}i:1050;a:1:{s:5:"title";s:33:"Запорная арматура";}i:1145;a:1:{s:5:"title";s:36:"Кирпич, Бетоноблоки";}i:850;a:1:{s:5:"title";s:33:"Крепежные изделия";}i:757;a:1:{s:5:"title";s:48:"Кровельные материалы - опт";}i:758;a:1:{s:5:"title";s:56:"Кровельные материалы - розница";}i:805;a:1:{s:5:"title";s:45:"Лакокрасочные материалы";}i:777;a:1:{s:5:"title";s:16:"Лестницы";}i:397;a:1:{s:5:"title";s:78:"Материалы для строительства и отделки -опт";}i:450;a:1:{s:5:"title";s:86:"Материалы для строительства и отделки -розница";}i:783;a:1:{s:5:"title";s:70:"Металлоконструкции для строительства";}i:1081;a:1:{s:5:"title";s:12:"Метизы";}i:1154;a:1:{s:5:"title";s:49:"Огнебиозащитные материалы";}i:1030;a:1:{s:5:"title";s:46:"Оргстекло, стеклопластик";}i:1111;a:1:{s:5:"title";s:24:"Песок, Щебень";}i:1035;a:1:{s:5:"title";s:33:"Порошковые краски";}i:754;a:1:{s:5:"title";s:70:"Природный и искусственный камень - опт";}i:755;a:1:{s:5:"title";s:78:"Природный и искусственный камень - розница";}i:953;a:1:{s:5:"title";s:64:"Производство акриловых материалов";}i:797;a:1:{s:5:"title";s:70:"Производство изоляционных материалов";}i:804;a:1:{s:5:"title";s:72:"Производство лакокрасочных материалов";}i:193;a:1:{s:5:"title";s:92:"Производство строительных, отделочных материалов";}i:808;a:1:{s:5:"title";s:54:"Производство сэндвич-панелей";}i:1092;a:1:{s:5:"title";s:53:"Стеновые, пластиковые панели";}i:809;a:1:{s:5:"title";s:35:"Сэндвич-панели -опт";}i:810;a:1:{s:5:"title";s:43:"Сэндвич-панели -розница";}i:802;a:1:{s:5:"title";s:61:"Теплоизоляционные материалы -опт";}i:803;a:1:{s:5:"title";s:69:"Теплоизоляционные материалы -розница";}i:1120;a:1:{s:5:"title";s:53:"Тротуарная плитка, брусчатка";}i:989;a:1:{s:5:"title";s:35:"Фасадные материалы";}i:1025;a:1:{s:5:"title";s:57:"Электроизоляционные материалы";}}}i:279;a:2:{s:5:"title";s:26:"Строительство";s:8:"children";a:68:{i:1147;a:1:{s:5:"title";s:51:"Алмазное сверление, пиление";}i:13840;a:1:{s:5:"title";s:86:"Антикоррозийная обработка металлоконструкций";}i:985;a:1:{s:5:"title";s:78:"Архитектурно-строительное проектирование";}i:11938;a:1:{s:5:"title";s:110:"Благоустройство территорий, озеленение, ландшафтный дизайн";}i:280;a:1:{s:5:"title";s:37:"Буровзрывные работы";}i:766;a:1:{s:5:"title";s:29:"Высотные работы";}i:664;a:1:{s:5:"title";s:39:"Геодезические работы";}i:798;a:1:{s:5:"title";s:26:"Гидроизоляция";}i:1058;a:1:{s:5:"title";s:30:"Гидромелиорация";}i:281;a:1:{s:5:"title";s:67:"Гидротехнические и подводные работы";}i:285;a:1:{s:5:"title";s:33:"Дизайн интерьеров";}i:282;a:1:{s:5:"title";s:41:"Долевое строительство";}i:895;a:1:{s:5:"title";s:30:"Землеустройство";}i:776;a:1:{s:5:"title";s:55:"Изготовление и монтаж лестниц";}i:770;a:1:{s:5:"title";s:16:"Инвестор";}i:5881;a:1:{s:5:"title";s:64:"Инженерно-геологические изыскания";}i:642;a:1:{s:5:"title";s:29:"Инженерные сети";}i:864;a:1:{s:5:"title";s:22:"Камины, печи";}i:283;a:1:{s:5:"title";s:33:"Кровельные работы";}i:753;a:1:{s:5:"title";s:49:"Ландшафтное строительство";}i:756;a:1:{s:5:"title";s:57:"Ландшафтный дизайн, фитодизайн";}i:780;a:1:{s:5:"title";s:58:"Монтаж котельного оборудования";}i:284;a:1:{s:5:"title";s:31:"Монтажные работы";}i:982;a:1:{s:5:"title";s:35:"Монтаж окон, дверей";}i:771;a:1:{s:5:"title";s:57:"Монтаж охранно-пожарных систем";}i:774;a:1:{s:5:"title";s:84:"Монтаж систем вентиляции и кондиционирования";}i:762;a:1:{s:5:"title";s:72:"Монтаж систем отопления, водоснабжения";}i:996;a:1:{s:5:"title";s:22:"Новостройки";}i:1182;a:1:{s:5:"title";s:80:"Обустройство территории, дорожная разметка";}i:1156;a:1:{s:5:"title";s:49:"Огнебиозащитная обработка";}i:617;a:1:{s:5:"title";s:37:"Остекление балконов";}i:769;a:1:{s:5:"title";s:31:"Паркетные работы";}i:1087;a:1:{s:5:"title";s:35:"Порошковая окраска";}i:1008;a:1:{s:5:"title";s:28:"Проектирование";}i:761;a:1:{s:5:"title";s:97:"Проектирование домашних акустических и видео систем";}i:977;a:1:{s:5:"title";s:112:"Проектирование систем охранно-противопожарной безопасности";}i:752;a:1:{s:5:"title";s:44:"Проектно-сметные работы";}i:1158;a:1:{s:5:"title";s:56:"Производство мобильных зданий";}i:1148;a:1:{s:5:"title";s:100:"Производство, усиление проемов в несущих конструкциях";}i:286;a:1:{s:5:"title";s:43:"Прокладка коммуникаций";}i:1049;a:1:{s:5:"title";s:37:"Резка стекла, зеркал";}i:1146;a:1:{s:5:"title";s:44:"Реконструкция, демонтаж";}i:1034;a:1:{s:5:"title";s:49:"Ремонт зданий и сооружений";}i:615;a:1:{s:5:"title";s:49:"Ремонт и отделка помещений";}i:784;a:1:{s:5:"title";s:68:"Ремонт, строительство железных дорог";}i:287;a:1:{s:5:"title";s:22:"Реставрация";}i:768;a:1:{s:5:"title";s:41:"Сантехнические работы";}i:962;a:1:{s:5:"title";s:31:"Сварочные работы";}i:1198;a:1:{s:5:"title";s:43:"Снос зданий, сооружений";}i:1054;a:1:{s:5:"title";s:84:"Создание ледовых, снежных, песчаных скульптур";}i:993;a:1:{s:5:"title";s:72:"Строительство административных зданий";}i:785;a:1:{s:5:"title";s:58:"Строительство деревянных домов";}i:288;a:1:{s:5:"title";s:50:"Строительство жилых зданий";}i:763;a:1:{s:5:"title";s:79:"Строительство и монтаж бассейнов, фонтанов";}i:289;a:1:{s:5:"title";s:85:"Строительство и ремонт дорог, мостов, тоннелей";}i:786;a:1:{s:5:"title";s:45:"Строительство коттеджей";}i:290;a:1:{s:5:"title";s:88:"Строительство промышленных зданий и сооружений";}i:995;a:1:{s:5:"title";s:45:"Строительство саун, бань";}i:1213;a:1:{s:5:"title";s:51:"Строительство трубопровода";}i:1184;a:1:{s:5:"title";s:21:"Теплые полы";}i:1046;a:1:{s:5:"title";s:36:"Термозвукоизоляция";}i:862;a:1:{s:5:"title";s:68:"Услуги грузоподъёмного оборудования";}i:801;a:1:{s:5:"title";s:31:"Устройство полов";}i:767;a:1:{s:5:"title";s:29:"Фасадные работы";}i:291;a:1:{s:5:"title";s:37:"Фундаментные работы";}i:1065;a:1:{s:5:"title";s:37:"Экспертиза проектов";}i:681;a:1:{s:5:"title";s:70:"Экспертиза производственных объектов";}i:292;a:1:{s:5:"title";s:45:"Электромонтажные работы";}}}}}i:19;a:2:{s:5:"title";s:21:"Сфера услуг";s:8:"children";a:11:{i:294;a:2:{s:5:"title";s:55:"Брачные агентства, знакомства";s:8:"children";a:1:{i:295;a:1:{s:5:"title";s:55:"Брачные агентства, знакомства";}}}i:296;a:2:{s:5:"title";s:27:"Бытовые услуги";s:8:"children";a:17:{i:297;a:1:{s:5:"title";s:12:"Ателье";}i:6117;a:1:{s:5:"title";s:43:"Ателье кожаные, меховые";}i:298;a:1:{s:5:"title";s:20:"Бани, Сауны";}i:3402;a:1:{s:5:"title";s:35:"Вывоз мусора, снега";}i:729;a:1:{s:5:"title";s:83:"Дезинфекция, Уничтожение насекомых, грызунов";}i:6162;a:1:{s:5:"title";s:37:"Изготовление ключей";}i:300;a:1:{s:5:"title";s:39:"Клининговые компании";}i:302;a:1:{s:5:"title";s:32:"Няни, гувернантки";}i:984;a:1:{s:5:"title";s:31:"Пошив спецодежды";}i:305;a:1:{s:5:"title";s:20:"Репетиторы";}i:25733;a:1:{s:5:"title";s:63:"Реставрация пухо-перьевых изделий";}i:306;a:1:{s:5:"title";s:33:"Ритуальные услуги";}i:1105;a:1:{s:5:"title";s:31:"Услуги грузчиков";}i:25722;a:1:{s:5:"title";s:32:"Фото на документы";}i:307;a:1:{s:5:"title";s:20:"Фотоуслуги";}i:308;a:1:{s:5:"title";s:38:"Химчистки, Прачечные";}i:910;a:1:{s:5:"title";s:81:"Экстренное открывание замков, врезка замков";}}}i:310;a:2:{s:5:"title";s:37:"Коммунальные услуги";s:8:"children";a:7:{i:1084;a:1:{s:5:"title";s:31:"Аварийные службы";}i:311;a:1:{s:5:"title";s:27:"Газовые службы";}i:312;a:1:{s:5:"title";s:68:"Городские обслуживающие предприятия";}i:313;a:1:{s:5:"title";s:78:"Жилищно-эксплуатационные предприятия, ЖЭУ";}i:314;a:1:{s:5:"title";s:53:"Обслуживание лифтов, продажа";}i:3689;a:1:{s:5:"title";s:29:"Предприятия ЖКХ";}i:13801;a:1:{s:5:"title";s:6:"ТСЖ";}}}i:316;a:2:{s:5:"title";s:37:"Компьютерные услуги";s:8:"children";a:12:{i:691;a:1:{s:5:"title";s:47:"1С, продажа, сопровождение";}i:650;a:1:{s:5:"title";s:58:"Автоматизация бизнес-процессов";}i:1017;a:1:{s:5:"title";s:78:"Автоматизация производственных процессов";}i:317;a:1:{s:5:"title";s:41:"Восстановление данных";}i:641;a:1:{s:5:"title";s:37:"Заправка картриджей";}i:1205;a:1:{s:5:"title";s:53:"Информационная безопасность";}i:318;a:1:{s:5:"title";s:37:"Компьютерная помощь";}i:319;a:1:{s:5:"title";s:47:"Обслуживание предприятий";}i:846;a:1:{s:5:"title";s:55:"Оцифровка видео, аудиозаписей";}i:320;a:1:{s:5:"title";s:69:"Разработка Интернет-сайтов (Web-дизайн)";}i:199;a:1:{s:5:"title";s:68:"Разработка программного обеспечения";}i:698;a:1:{s:5:"title";s:33:"Тиражирование CD/DVD";}}}i:71;a:2:{s:5:"title";s:41:"Консалтинговые услуги";s:8:"children";a:5:{i:657;a:1:{s:5:"title";s:23:"IT-консалтинг";}i:841;a:1:{s:5:"title";s:51:"Образовательный консалтинг";}i:676;a:1:{s:5:"title";s:39:"Оценка собственности";}i:693;a:1:{s:5:"title";s:49:"Управленческий консалтинг";}i:72;a:1:{s:5:"title";s:41:"Финансовый консалтинг";}}}i:565;a:2:{s:5:"title";s:22:"Охрана, сыск";s:8:"children";a:2:{i:566;a:1:{s:5:"title";s:25:"Услуги охраны";}i:619;a:1:{s:5:"title";s:33:"Частные детективы";}}}i:323;a:2:{s:5:"title";s:20:"Проживание";s:8:"children";a:2:{i:325;a:1:{s:5:"title";s:18:"Гостиницы";}i:326;a:1:{s:5:"title";s:18:"Общежития";}}}i:304;a:2:{s:5:"title";s:26:"Прокат, аренда";s:8:"children";a:6:{i:568;a:1:{s:5:"title";s:20:"Автопрокат";}i:567;a:1:{s:5:"title";s:34:"Аудио-видео прокат";}i:569;a:1:{s:5:"title";s:25:"Прокат одежды";}i:852;a:1:{s:5:"title";s:64:"Прокат проекционного оборудования";}i:940;a:1:{s:5:"title";s:54:"Прокат спортивного инвентаря";}i:951;a:1:{s:5:"title";s:36:"Спецтехника -аренда";}}}i:1066;a:2:{s:5:"title";s:25:"Прочие услуги";s:8:"children";a:7:{i:1079;a:1:{s:5:"title";s:37:"Багетные мастерские";}i:1075;a:1:{s:5:"title";s:20:"Гравировка";}i:1086;a:1:{s:5:"title";s:27:"Лазерная резка";}i:1071;a:1:{s:5:"title";s:20:"Объявления";}i:1068;a:1:{s:5:"title";s:69:"Снабжение и комплектация предприятий";}i:1067;a:1:{s:5:"title";s:31:"Услуги астролога";}i:1101;a:1:{s:5:"title";s:49:"Художественные мастерские";}}}i:559;a:2:{s:5:"title";s:30:"Работа, персонал";s:8:"children";a:5:{i:560;a:1:{s:5:"title";s:35:"Кадровые агентства";}i:1040;a:1:{s:5:"title";s:55:"Переводы с иностранных языков";}i:945;a:1:{s:5:"title";s:58:"Предоставление конференц-залов";}i:692;a:1:{s:5:"title";s:47:"Семинары, бизнес-тренинги";}i:1097;a:1:{s:5:"title";s:33:"Сетевой маркетинг";}}}i:327;a:2:{s:5:"title";s:59:"Ремонт и Сервисное обслуживание";s:8:"children";a:28:{i:991;a:1:{s:5:"title";s:49:"Ремонт аудио, видеотехники";}i:334;a:1:{s:5:"title";s:60:"Ремонт банковского оборудования";}i:330;a:1:{s:5:"title";s:42:"Ремонт бытовой техники";}i:331;a:1:{s:5:"title";s:48:"Ремонт водного транспорта";}i:1060;a:1:{s:5:"title";s:27:"Ремонт игрушек";}i:1163;a:1:{s:5:"title";s:59:"Ремонт и сервисное обслуживание";}i:1210;a:1:{s:5:"title";s:131:"Ремонт и сервисное обслуживание систем вентиляции и кондиционирования";}i:1152;a:1:{s:5:"title";s:113:"Ремонт и сервисное обслуживание телекоммуникационных систем";}i:332;a:1:{s:5:"title";s:85:"Ремонт компьютеров, оргтехники, комплектующих";}i:1122;a:1:{s:5:"title";s:62:"Ремонт медицинского оборудования";}i:932;a:1:{s:5:"title";s:60:"Ремонт музыкальных инструментов";}i:339;a:1:{s:5:"title";s:23:"Ремонт обуви";}i:335;a:1:{s:5:"title";s:64:"Ремонт промышленного оборудования";}i:346;a:1:{s:5:"title";s:70:"Ремонт, реставрация ювелирных изделий";}i:336;a:1:{s:5:"title";s:33:"Ремонт сантехники";}i:930;a:1:{s:5:"title";s:54:"Ремонт сетевого оборудования";}i:341;a:1:{s:5:"title";s:50:"Ремонт систем безопасности";}i:342;a:1:{s:5:"title";s:73:"Ремонт средств связи, сотовых телефонов";}i:981;a:1:{s:5:"title";s:78:"Ремонт теплоэнергетического оборудования";}i:343;a:1:{s:5:"title";s:27:"Ремонт техники";}i:337;a:1:{s:5:"title";s:56:"Ремонт торгового оборудования";}i:344;a:1:{s:5:"title";s:23:"Ремонт часов";}i:968;a:1:{s:5:"title";s:50:"Ремонт электрических сетей";}i:997;a:1:{s:5:"title";s:47:"Ремонт электродвигателей";}i:6128;a:1:{s:5:"title";s:49:"Ремонт электроинструмента";}i:1114;a:1:{s:5:"title";s:35:"Ремонт электроники";}i:338;a:1:{s:5:"title";s:76:"Ремонт электротехнического оборудования";}i:333;a:1:{s:5:"title";s:51:"Реставрация мебели, обшивка";}}}}}i:20;a:2:{s:5:"title";s:26:"Сырье, Энергия";s:8:"children";a:5:{i:365;a:2:{s:5:"title";s:6:"Лес";s:8:"children";a:5:{i:366;a:1:{s:5:"title";s:30:"Деревообработка";}i:13804;a:1:{s:5:"title";s:34:"Евродрова, пеллеты";}i:950;a:1:{s:5:"title";s:37:"Заготовка древесины";}i:367;a:1:{s:5:"title";s:31:"Лесные хозяйства";}i:637;a:1:{s:5:"title";s:54:"Лесоматериалы, пиломатериалы";}}}i:368;a:2:{s:5:"title";s:14:"Металлы";s:8:"children";a:5:{i:369;a:1:{s:5:"title";s:37:"Драгоценные металлы";}i:370;a:1:{s:5:"title";s:28:"Металлоизделия";}i:371;a:1:{s:5:"title";s:26:"Металлопрокат";}i:372;a:1:{s:5:"title";s:35:"Обработка металлов";}i:373;a:1:{s:5:"title";s:54:"Прием цветного и черного лома";}}}i:374;a:2:{s:5:"title";s:37:"Полезные ископаемые";s:8:"children";a:1:{i:375;a:1:{s:5:"title";s:54:"Разведка, добыча, переработка";}}}i:378;a:2:{s:5:"title";s:26:"Топливо, Химия";s:8:"children";a:7:{i:25731;a:1:{s:5:"title";s:33:"Красящие вещества";}i:379;a:1:{s:5:"title";s:26:"Нефтепродукты";}i:1194;a:1:{s:5:"title";s:35:"Промышленная химия";}i:1176;a:1:{s:5:"title";s:31:"Технические газы";}i:376;a:1:{s:5:"title";s:10:"Уголь";}i:899;a:1:{s:5:"title";s:55:"Утилизация отходов, Вторсырье";}i:380;a:1:{s:5:"title";s:39:"Химическая продукция";}}}i:381;a:2:{s:5:"title";s:14:"Энергия";s:8:"children";a:5:{i:5880;a:1:{s:5:"title";s:41:"Генерирующие компании";}i:13821;a:1:{s:5:"title";s:118:"Пункты приема и утилизации отработанных энергосберегающих ламп";}i:382;a:1:{s:5:"title";s:48:"Теплоэнергоснабжение, ТЭЦ";}i:5879;a:1:{s:5:"title";s:45:"Электросетевые компании";}i:5878;a:1:{s:5:"title";s:45:"Энергосбытовые компании";}}}}}i:21;a:2:{s:5:"title";s:56:"Торговля - Промышленные товары";s:8:"children";a:18:{i:823;a:2:{s:5:"title";s:53:"Аудио, видео, бытовая техника";s:8:"children";a:9:{i:389;a:1:{s:5:"title";s:61:"Аудио, видео, бытовая техника -опт";}i:440;a:1:{s:5:"title";s:69:"Аудио, видео, бытовая техника -розница";}i:387;a:1:{s:5:"title";s:60:"Аудио – видеоматериалы, CD, DVD -опт";}i:438;a:1:{s:5:"title";s:68:"Аудио – видеоматериалы, CD, DVD -розница";}i:907;a:1:{s:5:"title";s:70:"Звуковое, световое, видеооборудование";}i:586;a:1:{s:5:"title";s:53:"Музыкальные инструменты -опт";}i:453;a:1:{s:5:"title";s:61:"Музыкальные инструменты -розница";}i:430;a:1:{s:5:"title";s:28:"Фототовары -опт";}i:484;a:1:{s:5:"title";s:36:"Фототовары -розница";}}}i:836;a:2:{s:5:"title";s:27:"Детские товары";s:8:"children";a:4:{i:441;a:1:{s:5:"title";s:27:"Детские товары";}i:391;a:1:{s:5:"title";s:22:"Игрушки -опт";}i:442;a:1:{s:5:"title";s:30:"Игрушки -розница";}i:5890;a:1:{s:5:"title";s:46:"Товары для новорожденных";}}}i:840;a:2:{s:5:"title";s:51:"Климатическое оборудование";s:8:"children";a:10:{i:772;a:1:{s:5:"title";s:61:"Вентиляционное оборудование -опт";}i:773;a:1:{s:5:"title";s:69:"Вентиляционное оборудование -розница";}i:3391;a:1:{s:5:"title";s:37:"Встроенные пылесосы";}i:415;a:1:{s:5:"title";s:49:"Климатическая техника -опт";}i:604;a:1:{s:5:"title";s:57:"Климатическая техника -розница";}i:778;a:1:{s:5:"title";s:51:"Котельное оборудование -опт";}i:779;a:1:{s:5:"title";s:59:"Котельное оборудование -розница";}i:1115;a:1:{s:5:"title";s:57:"Очистители воздуха, ионизаторы";}i:795;a:1:{s:5:"title";s:39:"Системы обогрева -опт";}i:796;a:1:{s:5:"title";s:47:"Системы обогрева -розница";}}}i:822;a:2:{s:5:"title";s:46:"Книги, канцелярия, бумага";s:8:"children";a:6:{i:388;a:1:{s:5:"title";s:42:"Бумага, Канцтовары -опт";}i:439;a:1:{s:5:"title";s:50:"Бумага, Канцтовары -розница";}i:393;a:1:{s:5:"title";s:51:"Книги, печатные издания -опт";}i:445;a:1:{s:5:"title";s:59:"Книги, печатные издания -розница";}i:1153;a:1:{s:5:"title";s:57:"Специализированная литература";}i:25706;a:1:{s:5:"title";s:35:"Учебная литература";}}}i:839;a:2:{s:5:"title";s:42:"Компьютеры, оргтехника";s:8:"children";a:7:{i:394;a:1:{s:5:"title";s:56:"Компьютеры, комплектующие -опт";}i:446;a:1:{s:5:"title";s:64:"Компьютеры, комплектующие -розница";}i:1089;a:1:{s:5:"title";s:16:"Ноутбуки";}i:421;a:1:{s:5:"title";s:47:"Оборудование сетевое -опт";}i:474;a:1:{s:5:"title";s:55:"Оборудование сетевое -розница";}i:1074;a:1:{s:5:"title";s:59:"Оргтехника, расходные материалы";}i:468;a:1:{s:5:"title";s:45:"Программное обеспечение";}}}i:820;a:2:{s:5:"title";s:46:"Оборудование, инструмент";s:8:"children";a:19:{i:6120;a:1:{s:5:"title";s:30:"Бензоинструмент";}i:994;a:1:{s:5:"title";s:61:"Деревообрабатывающий инструмент";}i:974;a:1:{s:5:"title";s:54:"Детское игровое оборудование";}i:14218;a:1:{s:5:"title";s:47:"Измерительный инструмент";}i:392;a:1:{s:5:"title";s:30:"Инструменты -опт";}i:444;a:1:{s:5:"title";s:38:"Инструменты -розница";}i:967;a:1:{s:5:"title";s:49:"Металлорежущий инструмент";}i:870;a:1:{s:5:"title";s:41:"Насосное оборудование";}i:404;a:1:{s:5:"title";s:68:"Оборудование для бассейнов, саун -опт";}i:457;a:1:{s:5:"title";s:76:"Оборудование для бассейнов, саун -розница";}i:1201;a:1:{s:5:"title";s:60:"Оборудование для мусоропроводов";}i:25734;a:1:{s:5:"title";s:64:"Оборудование для энергосбережения";}i:1029;a:1:{s:5:"title";s:107:"Осветительное, электроустановочное, силовое оборудование";}i:928;a:1:{s:5:"title";s:20:"Подшипники";}i:998;a:1:{s:5:"title";s:52:"Электродвигатели, редукторы";}i:868;a:1:{s:5:"title";s:34:"Электроинструмент";}i:412;a:1:{s:5:"title";s:69:"Электротехническое оборудование -опт";}i:465;a:1:{s:5:"title";s:77:"Электротехническое оборудование -розница";}i:983;a:1:{s:5:"title";s:67:"Ювелирный инструмент и оборудование";}}}i:832;a:2:{s:5:"title";s:49:"Оборудование промышленное";s:8:"children";a:32:{i:999;a:1:{s:5:"title";s:39:"Буровое оборудование";}i:1183;a:1:{s:5:"title";s:47:"Вышивальное оборудование";}i:1002;a:1:{s:5:"title";s:51:"Газобаллонное оборудование";}i:1024;a:1:{s:5:"title";s:53:"Гидравлическое оборудование";}i:1000;a:1:{s:5:"title";s:50:"Горно-шахтное оборудование";}i:1181;a:1:{s:5:"title";s:53:"Грузоподъемное оборудование";}i:632;a:1:{s:5:"title";s:65:"Деревообрабатывающее оборудование";}i:1001;a:1:{s:5:"title";s:86:"Клининговое оборудование, расходные материалы";}i:857;a:1:{s:5:"title";s:49:"Лабораторное оборудование";}i:966;a:1:{s:5:"title";s:67:"Металлообрабатывающее оборудование";}i:455;a:1:{s:5:"title";s:56:"Оборудование для автосервисов";}i:403;a:1:{s:5:"title";s:46:"Оборудование для АЗС -опт";}i:456;a:1:{s:5:"title";s:54:"Оборудование для АЗС -розница";}i:1036;a:1:{s:5:"title";s:88:"Оборудование для нанесения полимерных покрытий";}i:458;a:1:{s:5:"title";s:75:"Оборудование для пищевой промышленности";}i:408;a:1:{s:5:"title";s:94:"Оборудование для птицеводства, животноводства -опт";}i:461;a:1:{s:5:"title";s:102:"Оборудование для птицеводства, животноводства -розница";}i:713;a:1:{s:5:"title";s:109:"Оборудование для соляриев, парикмахерских, салонов красоты";}i:806;a:1:{s:5:"title";s:89:"Пневматическое, компрессорное оборудование -опт";}i:807;a:1:{s:5:"title";s:97:"Пневматическое, компрессорное оборудование -розница";}i:459;a:1:{s:5:"title";s:55:"Полиграфическое оборудование";}i:460;a:1:{s:5:"title";s:49:"Промышленное оборудование";}i:734;a:1:{s:5:"title";s:22:"РТИ, АТИ -опт";}i:735;a:1:{s:5:"title";s:30:"РТИ, АТИ -розница";}i:816;a:1:{s:5:"title";s:63:"Сварочное оборудование, материалы";}i:409;a:1:{s:5:"title";s:66:"Сельско-хозяйственное оборудование";}i:610;a:1:{s:5:"title";s:57:"Строительное оборудование -опт";}i:609;a:1:{s:5:"title";s:65:"Строительное оборудование -розница";}i:988;a:1:{s:5:"title";s:75:"Упаковочное, маркировочное оборудование";}i:689;a:1:{s:5:"title";s:47:"Швейное оборудование -опт";}i:690;a:1:{s:5:"title";s:55:"Швейное оборудование -розница";}i:717;a:1:{s:5:"title";s:53:"Энергетическое оборудование";}}}i:833;a:2:{s:5:"title";s:41:"Оборудование торговое";s:8:"children";a:9:{i:630;a:1:{s:5:"title";s:74:"Кассовые аппараты, весовое оборудование";}i:401;a:1:{s:5:"title";s:53:"Оборудование банковское -опт";}i:6171;a:1:{s:5:"title";s:61:"Оборудование банковское -розница";}i:987;a:1:{s:5:"title";s:74:"Оборудование для баров, кафе, ресторанов";}i:411;a:1:{s:5:"title";s:70:"Оборудование торгово-выставочное -опт";}i:464;a:1:{s:5:"title";s:78:"Оборудование торгово-выставочное -розница";}i:687;a:1:{s:5:"title";s:55:"Оборудование холодильное -опт";}i:688;a:1:{s:5:"title";s:63:"Оборудование холодильное -розница";}i:1019;a:1:{s:5:"title";s:86:"Установка и обслуживание платежных терминалов";}}}i:824;a:2:{s:5:"title";s:46:"Одежда, обувь, галантерея";s:8:"children";a:27:{i:1103;a:1:{s:5:"title";s:27:"Верхняя одежда";}i:1063;a:1:{s:5:"title";s:27:"Головные уборы";}i:923;a:1:{s:5:"title";s:25:"Детская обувь";}i:867;a:1:{s:5:"title";s:27:"Детская одежда";}i:939;a:1:{s:5:"title";s:39:"Джинсовая одежда -опт";}i:927;a:1:{s:5:"title";s:47:"Джинсовая одежда -розница";}i:917;a:1:{s:5:"title";s:35:"Женская одежда -опт";}i:918;a:1:{s:5:"title";s:43:"Женская одежда -розница";}i:715;a:1:{s:5:"title";s:34:"Кожгалантерея -опт";}i:651;a:1:{s:5:"title";s:42:"Кожгалантерея -розница";}i:600;a:1:{s:5:"title";s:36:"Меха, дубленки, кожа";}i:919;a:1:{s:5:"title";s:35:"Мужская одежда -опт";}i:920;a:1:{s:5:"title";s:43:"Мужская одежда -розница";}i:922;a:1:{s:5:"title";s:23:"Нижнее белье";}i:413;a:1:{s:5:"title";s:18:"Обувь -опт";}i:466;a:1:{s:5:"title";s:26:"Обувь -розница";}i:925;a:1:{s:5:"title";s:40:"Одежда для беременных";}i:812;a:1:{s:5:"title";s:31:"Свадебные салоны";}i:948;a:1:{s:5:"title";s:35:"Секонд Хэнд (Second Hand)";}i:5906;a:1:{s:5:"title";s:26:"Спецобувь -опт";}i:5907;a:1:{s:5:"title";s:34:"Спецобувь -розница";}i:423;a:1:{s:5:"title";s:28:"Спецодежда -опт";}i:477;a:1:{s:5:"title";s:36:"Спецодежда -розница";}i:921;a:1:{s:5:"title";s:45:"Спортивная одежда, обувь";}i:1166;a:1:{s:5:"title";s:58:"Средства индивидуальной защиты";}i:590;a:1:{s:5:"title";s:28:"Сумки, чемоданы";}i:25730;a:1:{s:5:"title";s:46:"Чулочно-носочные изделия";}}}i:837;a:2:{s:5:"title";s:29:"Охранные товары";s:8:"children";a:7:{i:900;a:1:{s:5:"title";s:100:"Аварийно-спасательное и противопожарное оборудование";}i:624;a:1:{s:5:"title";s:61:"Автоматические ворота, шлагбаумы";}i:949;a:1:{s:5:"title";s:35:"Домофонные системы";}i:811;a:1:{s:5:"title";s:34:"Ограждения, заборы";}i:475;a:1:{s:5:"title";s:63:"Охранные, противопожарные системы";}i:1095;a:1:{s:5:"title";s:45:"Системы видеонаблюдения";}i:911;a:1:{s:5:"title";s:68:"Спутниковый противоугонный комплекс";}}}i:825;a:2:{s:5:"title";s:35:"Предметы интерьера";s:8:"children";a:19:{i:1088;a:1:{s:5:"title";s:14:"Витражи";}i:720;a:1:{s:5:"title";s:38:"Двери, комплектующие";}i:473;a:1:{s:5:"title";s:34:"Жалюзи, рольставни";}i:793;a:1:{s:5:"title";s:43:"Замки, скобяные изделия";}i:575;a:1:{s:5:"title";s:28:"Зеркала, стекла";}i:638;a:1:{s:5:"title";s:29:"Изделия кованые";}i:13813;a:1:{s:5:"title";s:14:"Карнизы";}i:620;a:1:{s:5:"title";s:59:"Кафель, керамическая плитка -опт";}i:621;a:1:{s:5:"title";s:67:"Кафель, керамическая плитка -розница";}i:827;a:1:{s:5:"title";s:38:"Керамогранит, мрамор";}i:781;a:1:{s:5:"title";s:10:"Ковры";}i:623;a:1:{s:5:"title";s:36:"Люстры, светильники";}i:782;a:1:{s:5:"title";s:35:"Напольные покрытия";}i:1218;a:1:{s:5:"title";s:31:"Натяжные потолки";}i:826;a:1:{s:5:"title";s:8:"Обои";}i:576;a:1:{s:5:"title";s:36:"Окна, комплектующие";}i:1107;a:1:{s:5:"title";s:22:"Перегородки";}i:1003;a:1:{s:5:"title";s:37:"Потолочные покрытия";}i:931;a:1:{s:5:"title";s:80:"Фурнитура и комплектующие для окон и дверей";}}}i:435;a:2:{s:5:"title";s:25:"Разные товары";s:8:"children";a:24:{i:1020;a:1:{s:5:"title";s:22:"Антиквариат";}i:1041;a:1:{s:5:"title";s:18:"Бижутерия";}i:788;a:1:{s:5:"title";s:53:"Бильярдные столы, аксессуары";}i:1212;a:1:{s:5:"title";s:51:"Запорные устройства, пломбы";}i:1070;a:1:{s:5:"title";s:33:"Новогодние товары";}i:1076;a:1:{s:5:"title";s:33:"Обувная косметика";}i:3381;a:1:{s:5:"title";s:35:"Одноразовая посуда";}i:3382;a:1:{s:5:"title";s:26:"Пакеты, пленки";}i:649;a:1:{s:5:"title";s:35:"Предметы искусства";}i:1023;a:1:{s:5:"title";s:37:"Продажа бриллиантов";}i:1077;a:1:{s:5:"title";s:33:"Ритуальные товары";}i:733;a:1:{s:5:"title";s:32:"Сувениры, подарки";}i:427;a:1:{s:5:"title";s:39:"Табачные изделия -опт";}i:481;a:1:{s:5:"title";s:47:"Табачные изделия -розница";}i:428;a:1:{s:5:"title";s:34:"Тара, упаковка -опт";}i:482;a:1:{s:5:"title";s:42:"Тара, упаковка -розница";}i:929;a:1:{s:5:"title";s:56:"Товары для беременных -розница";}i:1168;a:1:{s:5:"title";s:32:"Товары для отдыха";}i:677;a:1:{s:5:"title";s:34:"Фейерверки, салюты";}i:5953;a:1:{s:5:"title";s:16:"Часы -опт";}i:486;a:1:{s:5:"title";s:24:"Часы -розница";}i:487;a:1:{s:5:"title";s:35:"Эротические товары";}i:434;a:1:{s:5:"title";s:41:"Ювелирные изделия -опт";}i:488;a:1:{s:5:"title";s:49:"Ювелирные изделия -розница";}}}i:828;a:2:{s:5:"title";s:53:"Сантехническое оборудование";s:8:"children";a:10:{i:759;a:1:{s:5:"title";s:46:"Водосливные системы - опт";}i:760;a:1:{s:5:"title";s:54:"Водосливные системы - розница";}i:603;a:1:{s:5:"title";s:46:"Сантехника, санфаянс -опт";}i:449;a:1:{s:5:"title";s:54:"Сантехника, санфаянс -розница";}i:420;a:1:{s:5:"title";s:95:"Сантехническое, гидротехническое оборудование -опт";}i:472;a:1:{s:5:"title";s:103:"Сантехническое, гидротехническое оборудование -розница";}i:764;a:1:{s:5:"title";s:46:"Системы водоочистки - опт";}i:765;a:1:{s:5:"title";s:54:"Системы водоочистки - розница";}i:973;a:1:{s:5:"title";s:61:"Системы отопления, водоснабжения";}i:1108;a:1:{s:5:"title";s:53:"Туалетные кабины, биотуалеты";}}}i:830;a:2:{s:5:"title";s:27:"Средства связи";s:8:"children";a:11:{i:640;a:1:{s:5:"title";s:57:"Антенны, антенное оборудование";}i:979;a:1:{s:5:"title";s:72:"Запчасти для ремонта сотовых телефонов";}i:680;a:1:{s:5:"title";s:73:"Оборудование телекоммуникационное -опт";}i:679;a:1:{s:5:"title";s:81:"Оборудование телекоммуникационное -розница";}i:731;a:1:{s:5:"title";s:21:"Офисные АТС";}i:856;a:1:{s:5:"title";s:51:"Профессиональные диктофоны";}i:1056;a:1:{s:5:"title";s:24:"Радиостанции";}i:716;a:1:{s:5:"title";s:61:"Сотовые телефоны, аксессуары -опт";}i:476;a:1:{s:5:"title";s:69:"Сотовые телефоны, аксессуары -розница";}i:426;a:1:{s:5:"title";s:53:"Средства связи, телефоны -опт";}i:480;a:1:{s:5:"title";s:61:"Средства связи, телефоны -розница";}}}i:834;a:2:{s:5:"title";s:16:"Текстиль";s:8:"children";a:8:{i:1123;a:1:{s:5:"title";s:56:"Каркасно-тентовые конструкции";}i:596;a:1:{s:5:"title";s:30:"Материалы, ткани";}i:1110;a:1:{s:5:"title";s:16:"Мех, кожа";}i:1014;a:1:{s:5:"title";s:43:"Портьерные ткани, шторы";}i:614;a:1:{s:5:"title";s:83:"Постельные принадлежности, текстиль для дома";}i:6163;a:1:{s:5:"title";s:10:"Пряжа";}i:1185;a:1:{s:5:"title";s:16:"Текстиль";}i:443;a:1:{s:5:"title";s:33:"Швейная фурнитура";}}}i:670;a:2:{s:5:"title";s:67:"Торговые центры, супермаркеты, рынки";s:8:"children";a:1:{i:946;a:1:{s:5:"title";s:10:"Рынки";}}}i:835;a:2:{s:5:"title";s:55:"Хозяйственные, бытовые товары";s:8:"children";a:11:{i:685;a:1:{s:5:"title";s:68:"Бытовая химия, чистящие средства -опт";}i:686;a:1:{s:5:"title";s:76:"Бытовая химия, чистящие средства -розница";}i:395;a:1:{s:5:"title";s:48:"Косметика, парфюмерия -опт";}i:447;a:1:{s:5:"title";s:56:"Косметика, парфюмерия -розница";}i:448;a:1:{s:5:"title";s:45:"Кухонные принадлежности";}i:602;a:1:{s:5:"title";s:12:"Посуда";}i:471;a:1:{s:5:"title";s:66:"Садово-огородный инвентарь, техника";}i:944;a:1:{s:5:"title";s:39:"Средства гигиены -опт";}i:3385;a:1:{s:5:"title";s:47:"Средства гигиены -розница";}i:431;a:1:{s:5:"title";s:26:"Хозтовары -опт";}i:485;a:1:{s:5:"title";s:34:"Хозтовары -розница";}}}i:831;a:2:{s:5:"title";s:22:"Электроника";s:8:"children";a:8:{i:663;a:1:{s:5:"title";s:51:"Геодезическое оборудование";}i:611;a:1:{s:5:"title";s:20:"Кабель -опт";}i:612;a:1:{s:5:"title";s:28:"Кабель -розница";}i:697;a:1:{s:5:"title";s:62:"Контрольно-измерительные приборы";}i:980;a:1:{s:5:"title";s:35:"Оптические приборы";}i:417;a:1:{s:5:"title";s:22:"Радиодетали";}i:469;a:1:{s:5:"title";s:22:"Электроника";}i:1013;a:1:{s:5:"title";s:31:"Элементы питания";}}}}}i:22;a:2:{s:5:"title";s:67:"Торговые центры, рынки, спецмагазины";s:8:"children";a:1:{i:483;a:1:{s:5:"title";s:55:"Торговые центры, Супермаркеты";}}}i:23;a:2:{s:5:"title";s:38:"Транспорт, Перевозки";s:8:"children";a:8:{i:544;a:2:{s:5:"title";s:26:"Авиатранспорт";s:8:"children";a:6:{i:546;a:1:{s:5:"title";s:36:"Авиагрузоперевозки";}i:992;a:1:{s:5:"title";s:24:"Авиакомпании";}i:1016;a:1:{s:5:"title";s:26:"Аэронавигация";}i:545;a:1:{s:5:"title";s:62:"Аэропорты, аэродромы, аэровокзалы";}i:584;a:1:{s:5:"title";s:59:"Справочные, продажа авиабилетов";}i:8102;a:1:{s:5:"title";s:60:"Чартерные авиационные перевозки";}}}i:536;a:2:{s:5:"title";s:45:"Автомобильный транспорт";s:8:"children";a:10:{i:537;a:1:{s:5:"title";s:97:"Автобазы, Автоколонны, Автотранспортные предприятия";}i:538;a:1:{s:5:"title";s:20:"Автовокзал";}i:1009;a:1:{s:5:"title";s:22:"Автостоянки";}i:894;a:1:{s:5:"title";s:26:"Автоэвакуатор";}i:892;a:1:{s:5:"title";s:28:"Автоэкспертиза";}i:714;a:1:{s:5:"title";s:33:"Заказ спецтехники";}i:708;a:1:{s:5:"title";s:63:"Междугородние автогрузоперевозки";}i:1113;a:1:{s:5:"title";s:55:"Международные грузоперевозки";}i:1048;a:1:{s:5:"title";s:43:"Пассажирские перевозки";}i:10058;a:1:{s:5:"title";s:37:"Таксомоторные парки";}}}i:540;a:2:{s:5:"title";s:12:"Водный";s:8:"children";a:2:{i:542;a:1:{s:5:"title";s:25:"Речной вокзал";}i:541;a:1:{s:5:"title";s:57:"Речные, морские грузоперевозки";}}}i:547;a:2:{s:5:"title";s:62:"Городской общественный транспорт";s:8:"children";a:2:{i:548;a:1:{s:5:"title";s:43:"Автобусные предприятия";}i:549;a:1:{s:5:"title";s:68:"Трамвайно-троллейбусные предприятия";}}}i:13445;a:2:{s:5:"title";s:49:"Железнодорожный транспорт";s:8:"children";a:4:{i:652;a:1:{s:5:"title";s:61:"Железнодорожные вокзалы, станции";}i:653;a:1:{s:5:"title";s:59:"Железнодорожные грузоперевозки";}i:1015;a:1:{s:5:"title";s:74:"Железнодорожные пассажирские перевозки";}i:1209;a:1:{s:5:"title";s:65:"Справочные, железнодорожные билеты";}}}i:5895;a:2:{s:5:"title";s:18:"Логистика";s:8:"children";a:2:{i:1106;a:1:{s:5:"title";s:39:"Логистические услуги";}i:309;a:1:{s:5:"title";s:31:"Складские услуги";}}}i:8132;a:2:{s:5:"title";s:29:"Продажа билетов";s:8:"children";a:1:{i:8133;a:1:{s:5:"title";s:20:"Авиабилеты";}}}i:10059;a:2:{s:5:"title";s:45:"Такси, прокат транспорта";s:8:"children";a:7:{i:10063;a:1:{s:5:"title";s:23:"Водное такси";}i:10062;a:1:{s:5:"title";s:29:"Воздушное такси";}i:884;a:1:{s:5:"title";s:55:"Городские автогрузоперевозки";}i:5910;a:1:{s:5:"title";s:29:"Заказ автобусов";}i:10065;a:1:{s:5:"title";s:37:"Междугороднее такси";}i:10064;a:1:{s:5:"title";s:19:"Мото-такси";}i:10060;a:1:{s:5:"title";s:27:"Такси легковое";}}}}}}');
//		$this->_actionUpdateActivity($activities);	
//	}
//	
//	private function _actionUpdateActivity($items, &$root = null) {
//		foreach($items as $item) {
//			$leaf = new Activity;
//			$leaf->name = $item['title'];
//			if($root) {
//				if(! $leaf->appendTo( $root )) {
//					print_r($leaf->errors);
//					die;
//				}
//			}
//			else {
//				if(! $leaf->saveNode() ) {
//					print_r($leaf->errors);
//					die;
//				}
//			}
//			if(count($item['children'])) {
//				$this->_actionUpdateActivity($item['children'], $leaf);
//			}
//		}
//	}
}