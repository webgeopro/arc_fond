<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	
    <link rel="stylesheet" type="text/css" href="<?=Yii::app()->request->baseUrl?>/css/main.css" />
    <link rel="stylesheet" type="text/css" href="<?=Yii::app()->theme->baseUrl?>/css/common.css" />
    <link rel="stylesheet" type="text/css" href="<?=Yii::app()->theme->baseUrl?>/css/cab.css" />
    <link rel="stylesheet" type="text/css" href="<?=Yii::app()->theme->baseUrl?>/css/find.css" />
    <link rel="stylesheet" type="text/css" href="<?=Yii::app()->theme->baseUrl?>/css/<?=Yii::app()->params['themeUI']?>/jquery-ui.custom.css" />
    
    <script type="text/javascript" src="/js/jquery.js"></script>
    <script type="text/javascript" src="/js/common.js"></script>
    <script type="text/javascript" src="<?=Yii::app()->theme->baseUrl?>/js/jquery-ui.custom.min.js"></script>
    <script type="text/javascript" src="/js/find.js"></script>
    
    <title>КЭСИНН :: Поиск</title>
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
	</div>

	<?/*$this->widget('zii.widgets.CBreadcrumbs', array(
		'links'=>$this->breadcrumbs,
	));*/?>

	<?=$content;?>
    
    <br class="clear" /><br class="clear" />
	
    <?/*<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> <a href="">Детский Благотворительный фонд.</a><br/>
	</div>*/?>

</div>

</body>
</html>