<?php
/**
 * $Id$
 * Start page (homepage) layout
 * 
 * @author Andrey Matveev, <andrey.g.matveev@gmail.com>
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<? echo Yii::app()->theme->baseUrl?>/css/reset.css" type="text/css" />
<link rel="stylesheet" href="<? echo Yii::app()->theme->baseUrl?>/css/startpage.css" type="text/css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->getBaseUrl(true); ?>/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl?>/js/jquery.hint.js"></script>
<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>
<body class="startpage">
	<div class="b-wrap">
		<div class="b-leftside b-leftside_startpage">
			<div class="b-city">
				<p class="b-city__current">Красноярск</p>
<?php /* //@todo выбор города: временно залочен
				<p class="b-pseudo b-city__link">Другой город</p>
<?php */?>				
			</div>
			<div class="b-block">
				<div class="b-block__content">&nbsp;</div>
			</div>
		</div>
		<div class="b-content">
			<?php $this->widget('zii.widgets.CMenu',array(
	            'htmlOptions'=>array('class' => 'b-navi b-navi_head'), # id не работает
	            'lastItemCssClass'=>'last',
				'items'=>array(
					array('label'=>'Главная', 'url'=>'/', 'itemOptions' => array('class' => 'b-navi__item')),
					array('label'=>'О фонде', 'url'=>'/site/page', 'itemOptions' => array('class' => 'b-navi__item')),
					array('label'=>'Контакты', 'url'=>'/site/contact', 'itemOptions' => array('class' => 'b-navi__item')),
	                array('label'=>'Люди', 'url'=>'/people', 'itemOptions' => array('class' => 'b-navi__item')),
	                array('label'=>'Компании', 'url'=>'/companies', 'itemOptions' => array('class' => 'b-navi__item')),
					//array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
					//array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
				                                                                 #Yii::app()->getModule('user')->t("Login")
	                array(
	                	'url'=>Yii::app()->getModule('user')->loginUrl, 
	                	'label'=>'Войти', 
	                	'visible'=>Yii::app()->user->isGuest, 
	                	'itemOptions' => array('class' => 'b-navi__item'),
	                	'linkOptions' => array('id' => 'linkLogIn'), // нужно для включения AJAX-авторизации
	                	'template' => '
							{menu}
							<div id="divLogIn" class="b-dialog b-dialog_login">
								<input class="b-inputtext" id="inpLogin" type="text" name="LoginForm[login]" value="" title="Логин" />
								<input class="b-inputtext" id="inpPass" type="password" name="LoginForm[password]" value="" title="password" />
								<button id="bnLogIn" class="b-inputbutton b-inputbutton_login">Вход</button>
								<a class="b-link" href="/user/registration">Регистрация</a><br />
								<a class="b-link" href="/user/recovery/recovery">Забыли пароль?</a>
				            </div>'
	                ),
	                #array('url'=>Yii::app()->getModule('user')->registrationUrl, 'label'=>Yii::app()->getModule('user')->t("Register"), 'visible'=>Yii::app()->user->isGuest),
//	                array('url'=>Yii::app()->getModule('user')->profileUrl, 'label'=>Yii::app()->getModule('user')->t("Profile"), 'visible'=>!Yii::app()->user->isGuest,'itemOptions' => array('class' => 'b-navi__item')),
	                array('url'=>Yii::app()->getModule('user')->getCabAddress(), 'label'=>'Личный кабинет', 'visible'=>!Yii::app()->user->isGuest,'itemOptions' => array('class' => 'b-navi__item')),
	                array('url'=>Yii::app()->getModule('user')->logoutUrl, 'label'=>Yii::app()->getModule('user')->t("Logout"), 'visible'=>!Yii::app()->user->isGuest,'linkOptions' => array('id' => 'linkLogOut'),'itemOptions' => array('class' => 'b-navi__item'), 'template' => '
							{menu}
							<div id="divLogIn" class="b-dialog b-dialog_login">
								<input class="b-inputtext" id="inpLogin" type="text" name="LoginForm[login]" value="" title="Логин" />
								<input class="b-inputtext" id="inpPass" type="password" name="LoginForm[password]" value="" title="password" />
								<button id="bnLogIn" class="b-inputbutton b-inputbutton_login">Вход</button>
								<a class="b-link" href="/user/registration">Регистрация</a><br />
								<a class="b-link" href="/user/recovery/recovery">Забыли пароль?</a>
				            </div>')
	            ),
			)); ?>
			<?php /*
			<ul class="b-navi b-navi_head">
				<li class="b-navi__item"><a href="#">Главная</a></li>
				<li class="b-navi__item"><a href="#">О фонде</a></li>
				<li class="b-navi__item"><a href="#">Контакты</a></li>
				<li class="b-navi__item"><a href="#">Люди</a></li>
				<li class="b-navi__item"><a href="#">Компания</a></li>
				<?php if(Yii::app()->user->isGuest) { ?>
				<li class="b-navi__item">
					<a id="linkLogIn" href="<?php echo Yii::app()->getModule('user')->loginUrl[0] ?>">Войти</a>
					<div id="divLogIn" class="b-dialog b-dialog_login">
						<input class="b-inputtext" id="inpLogin" type="text" name="LoginForm[login]" value="" title="Логин" />
						<input class="b-inputtext" id="inpPass" type="password" name="LoginForm[password]" value="" title="password" />
						<button id="bnLogIn" class="b-inputbutton b-inputbutton_login">Вход</button>
						<a class="b-link" href="/user/registration">Регистрация</a><br />
						<a class="b-link" href="/user/recovery/recovery">Забыли пароль?</a>
		            </div>
				</li>
				<?php } else { ?>
				<li class="b-navi__item"><a href="<?php echo Yii::app()->getModule('user')->profileUrl[0] ?>">Профиль</a></li>
				<li class="b-navi__item"><a href="<?php echo Yii::app()->getModule('user')->logoutUrl[0] ?>">Выход</a></li>
				<?php }?>
			</ul>
			<script language="javascript">$('#divLogIn input[title!=""]').hint();</script> 
			*/?>
			
			<a href="<?php echo Yii::app()->request->getBaseUrl(true); ?>" class="b-logo" title="<?php echo CHtml::encode(Yii::app()->name); ?>"><?php echo CHtml::encode(Yii::app()->name); ?></a>
<?php echo $content ?>			
			<div class="b-search">
				<input type="text" class="b-search__text b-search__text_inactive" value="Поиск" />
				<input type="submit" class="b-search__button" value="Найти" />
			</div>
		</div>
	</div>
	<div id="global-ajax-loader">Загрузка...</div>
</body>
</html>