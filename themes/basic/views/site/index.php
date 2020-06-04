<div class="b-cloud">
	<a href="/find?tags=news" style="font-size:42px;top:15px;left:447px;">Новости</a>
	<a href="/find?tags=education" style="font-size:26px;top:97px;left:0;">Образование</a>
	<a href="/find?tags=health" style="font-size:30px;top:113px;left:278px;">Здравоохранение</a>
	<a href="/find?tags=business" style="font-size:18px;top:152px;left:256px;">Бизнес</a>
	<a href="/find?tags=sport" style="font-size:45px;top:174px;left:386px;">Спорт</a>
	<a href="/find?tags=bb" style="font-size:20px;top:224px;left:242px;">Доска объявлений</a>
	<a href="/find?tags=job" style="font-size:28px;top:237px;left:0;">Работа</a>
	<a href="/find?tags=persons" style="font-size:15px;top:198px;left:151px;">Персоны</a>
	<a href="/find?tags=culture" style="font-size:15px;top:139px;left:98px;">Культура</a>
	<a href="/find?tags=org" style="font-size:15px;top:42px;left:0px;">Некоммерческие<br/>организации</a>
	<a href="/find?tags=blogs" style="font-size:15px;top:273px;left:220px;">Мнения блоги<br/>форумы</a>
</div>
<div class="b-map">
	<ul class="b-navi b-navi_popup" id="startpage_navi_popup">
		<li class="b-navi__item"><a href="#">Главная</a></li>
		<li class="b-navi__item"><a href="#">О фонде</a></li>
		<li class="b-navi__item"><a href="#">Контакты</a></li>
		<li class="b-navi__item"><a href="#">Люди</a></li>
		<li class="b-navi__item"><a href="#">Компания</a></li>
		<li class="b-navi__item"><a href="#">Профиль</a></li>
	</ul>
</div>

<?php /*$this->pageTitle=Yii::app()->name; ?>

<div id="left-column">
    <div id="rating"></div>
    <div id="work"></div>
    <div id="cloudTags"></div>
</div>
<div id="rubricator">
    <table id="rubricator">
    <tr>
        <td>&nbsp;</td>
        <td>Детство</td>
        <td>Юность</td>
        <td>Молодость</td>
        <td>Зрелость</td>
        <td>Мудрость</td>
        <td>Государство</td>
    </tr>
    <tr>
        <td>Здравоохранение</td>
        <td><a href="" name="">Ссылка</a></td>
        <td><a href="" name="">Ссылка</a></td>
        <td><a href="" name="">Ссылка</a></td>
        <td><a href="" name="">Ссылка</a></td>
        <td><a href="" name="">Ссылка</a></td>
        <td><a href="" name="">Ссылка</a></td>
    </tr>
    <tr>
        <td>Образование</td>
        <td><a href="" name="">Ссылка</a></td>
        <td><a href="" name="">Ссылка</a></td>
        <td><a href="" name="">Ссылка</a></td>
        <td><a href="" name="">Ссылка</a></td>
        <td><a href="" name="">Ссылка</a></td>
        <td><a href="" name="">Ссылка</a></td>
    </tr>
    <tr>
        <td>Культура</td>
        <td><a href="" name="">Ссылка</a></td>
        <td><a href="" name="">Ссылка</a></td>
        <td><a href="" name="">Ссылка</a></td>
        <td><a href="" name="">Ссылка</a></td>
        <td><a href="" name="">Ссылка</a></td>
        <td><a href="" name="">Ссылка</a></td>
    </tr>
    <tr>
        <td>Спорт</td>
        <td><a href="" name="">Ссылка</a></td>
        <td><a href="" name="">Ссылка</a></td>
        <td><a href="" name="">Ссылка</a></td>
        <td><a href="" name="">Ссылка</a></td>
        <td><a href="" name="">Ссылка</a></td>
        <td><a href="" name="">Ссылка</a></td>
    </tr>
    <tr>
        <td>Труд</td>
        <td><a href="" name="">Ссылка</a></td>
        <td><a href="" name="">Ссылка</a></td>
        <td><a href="" name="">Ссылка</a></td>
        <td><a href="" name="">Ссылка</a></td>
        <td><a href="" name="">Ссылка</a></td>
        <td><a href="" name="">Ссылка</a></td>
    </tr>
    <tr>
        <td>Отдых</td>
        <td><a href="" name="">Ссылка</a></td>
        <td><a href="" name="">Ссылка</a></td>
        <td><a href="" name="">Ссылка</a></td>
        <td><a href="" name="">Ссылка</a></td>
        <td><a href="" name="">Ссылка</a></td>
        <td><a href="" name="">Ссылка</a></td>
    </tr>
    <tr>
        <td>Общение/форумы</td>
        <td><a href="" name="">Ссылка</a></td>
        <td><a href="" name="">Ссылка</a></td>
        <td><a href="" name="">Ссылка</a></td>
        <td><a href="" name="">Ссылка</a></td>
        <td><a href="" name="">Ссылка</a></td>
        <td><a href="" name="">Ссылка</a></td>
    </tr>
    <tr>
        <td>Участники/рейтинги</td>
        <td><a href="" name="">Ссылка</a></td>
        <td><a href="" name="">Ссылка</a></td>
        <td><a href="" name="">Ссылка</a></td>
        <td><a href="" name="">Ссылка</a></td>
        <td><a href="" name="">Ссылка</a></td>
        <td><a href="" name="">Ссылка</a></td>
    </tr>
    </table>
</div>

<div id="personal">
    <?if (Yii::app()->user->isGuest):?>
    <div id="loginDefault" class="centerColumn" style="width:100%;margin:0 auto;">
    <?=CHtml::beginForm(Yii::app()->getModule('user')->loginUrl);?>
    <fieldset>
        <?=CHtml::label('Логин','UserLogin[username]', array('class'=>'inputLabel'))?><br />
		<?=CHtml::textField('UserLogin[username]','',array('class'=>'inputField'))?>
        <br class="clearBoth"/>
        
        <?=CHtml::label('Пароль','UserLogin[password]', array('class'=>'inputLabel'))?><br />
		<?=CHtml::passwordField('UserLogin[password]','',array('class'=>'inputField'))?>
        <br class="clearBoth"/><br class="clearBoth"/>
        
        &nbsp;
        <?=CHtml::link('Регистрация',Yii::app()->getModule('user')->registrationUrl, array('class'=>'important'))?> 
        /
        <?=CHtml::link('Забыли пароль?',Yii::app()->getModule('user')->recoveryUrl, array('class'=>'important'))?>
        <br class="clearBoth"/><br class="clearBoth"/>
        
        <?=CHtml::checkBox('UserLogin[rememberMe]')?>
		<?=CHtml::label('Запомнить меня','UserLogin[rememberMe]')?>
        
        <div class="row submit" style="float: right;">
		<?=CHtml::submitButton('Вход'); ?>&nbsp;&nbsp;
        </div>
        <input type="hidden" name="fromMain" value="1" />
    </fieldset>
    <?=CHtml::endForm(); ?>
    </div>
    <?endif;?>
</div>*/?>