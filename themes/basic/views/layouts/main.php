<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
    <meta  />
	
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
    <link rel="stylesheet" type="text/css" href="<?=Yii::app()->request->baseUrl?>/css/<?=Yii::app()->params['themeUI']?>/jquery-ui.custom.css" />
    
    <script type="text/javascript" src="/js/jquery.js"></script>
    <script type="text/javascript" src="<?=Yii::app()->theme->baseUrl?>/js/jquery-ui.custom.min.js"></script>
    
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" id="page">

	<div id="header">
		<div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?> (Шапка+логотип)</div>
	</div><!-- header -->

	<div id="mainmenu">
		<?php $this->widget('zii.widgets.CMenu',array(
            'htmlOptions'=>array('id'=>'menu'), # НЕ работает!
            'lastItemCssClass'=>'last',
			'items'=>array(
				array('label'=>'Главная', 'url'=>'/'),
				array('label'=>'О фонде', 'url'=>'/site/page'),
				array('label'=>'Контакты', 'url'=>'/site/contact'),
                array('label'=>'Люди', 'url'=>'/people'),
                array('label'=>'Компании', 'url'=>'/companies'),
				//array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
				//array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
			                                                                 #Yii::app()->getModule('user')->t("Login")
                array('url'=>Yii::app()->getModule('user')->loginUrl, 'label'=>'Войти', 'visible'=>Yii::app()->user->isGuest),
                #array('url'=>Yii::app()->getModule('user')->registrationUrl, 'label'=>Yii::app()->getModule('user')->t("Register"), 'visible'=>Yii::app()->user->isGuest),
                array('url'=>Yii::app()->getModule('user')->getCabAddress(), 'label'=>Yii::app()->getModule('user')->t("Profile"), 'visible'=>!Yii::app()->user->isGuest),
                array('url'=>Yii::app()->getModule('user')->logoutUrl, 'label'=>Yii::app()->getModule('user')->t("Logout"), 'visible'=>!Yii::app()->user->isGuest), //.' ('.Yii::app()->user->name.')'
            ),
		)); ?>
	</div><!-- mainmenu -->

	<?php $this->widget('zii.widgets.CBreadcrumbs', array(
		'links'=>$this->breadcrumbs,
	)); ?><!-- breadcrumbs -->

	<?php echo $content; ?>

	<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> <a href="">Детский фонд.</a><br/>
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>