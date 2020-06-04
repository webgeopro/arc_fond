<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	
<!--	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />-->
<!--	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />-->
<!--    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/cab.css" />-->
<!--    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />-->
<!--    <link rel="stylesheet" type="text/css" href="<?=Yii::app()->request->baseUrl?>/css/<?=Yii::app()->params['themeUI']?>/jquery-ui.custom.css" />-->
    
    <script type="text/javascript" src="/js/jquery.js"></script>
    <script type="text/javascript" src="<?=Yii::app()->theme->baseUrl?>/js/jquery-ui.custom.min.js"></script>
    
    <title>КЭСИНН :: Кабинет</title>
</head>

<body class="cabinet">

<div class="container" id="page">
    <div id="divMainMenuLogin">
        <?if (Yii::app()->user->isGuest):?>
            <a href="<?=Yii::app()->getModule('user')->loginUrl[0]?>">Войти</a>
        <?else:?>
            <a href="<?=Yii::app()->getModule('user')->logoutUrl[0]?>">Выйти</a>
        <?endif?>
    </div>
    
	<div id="mainmenu">
        <div id="divMainMenuLogo" title="На главную" onclick="location.href='/'"></div>
		<div id="divMainFind">
            <input type="text" name="findName" id="inpFindName" title="Поиск по названию организации" />
            <input type="image" id="inpFindSubmit" value="Поиск" title="Поиск с учетов региона" />
            <select>
                <option>Везде</option>
                <option>Красноярск</option>
                <option>Красноярский край</option>
                <option>Абакан</option>
            </select>
        </div>
        <?/*$this->widget('zii.widgets.CMenu',array(
            'id'=>'ulMenu',
            'lastItemCssClass'=>'last',
            'encodeLabel'=>false,
			'items'=>array(
				array('label'=>"Главная", 'url'=>'http://cesinn.ru'),
				array('label'=>"О фонде", 'url'=>'/site/page/view/about'),
				array('label'=>"Контакты", 'url'=>'/site/contact'),
                array('label'=>"Люди", 'url'=>'/people'),
                array('label'=>"Компании", 'url'=>'/companies'),
				//array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
				//array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
			                                                                 #Yii::app()->getModule('user')->t("Login")
                array('url'=>Yii::app()->getModule('user')->loginUrl, 'label'=>'Войти', 'visible'=>Yii::app()->user->isGuest),
                #array('url'=>Yii::app()->getModule('user')->registrationUrl, 'label'=>Yii::app()->getModule('user')->t("Register"), 'visible'=>Yii::app()->user->isGuest),
                #array('url'=>Yii::app()->getModule('user')->profileUrl, 'label'=>Yii::app()->getModule('user')->t("Profile"), 'visible'=>!Yii::app()->user->isGuest),
                array('url'=>Yii::app()->getModule('user')->logoutUrl, 'label'=>Yii::app()->getModule('user')->t("Logout"), 'visible'=>!Yii::app()->user->isGuest), //.' ('.Yii::app()->user->name.')'
            ),
		));*/ ?>
	</div>

	<?/*$this->widget('zii.widgets.CBreadcrumbs', array(
		'links'=>$this->breadcrumbs,
	));*/?>

	<?=$content; ?>
    
    <br class="clear" /><br class="clear" />
	
    <?/*<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> <a href="">Детский Благотворительный фонд.</a><br/>
	</div>*/?>

</div>
<div id="global-ajax-loader">Загрузка...</div>
</body>
</html>