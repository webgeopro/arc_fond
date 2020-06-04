<?if(Yii::app()->user->hasFlash('registration')):?>
	<div class="success">
	<?=Yii::app()->user->getFlash('registration'); ?>
	</div>
<?else:?>
	<h1 class="b-title">Регистрация юридического лица</h1>
	<div class="b-info">
		<div class="b-info__icon">?</div>
		<div class="b-info__text">Впервые? Пожалуйста введите свои контактные данные. Эти данные позволят вам покупать быстрее и с комфортом, а также отслеживать выполнение заказа и просматривать историю покупок.<br/><span class="required">*</span> Обязательно к заполнению</div>
	</div>
	
	<?=CHtml::beginForm('','post',array('name'=>'create_account', 'enctype'=>'multipart/form-data', 'onsubmit'=>'return check_form(create_account)'));?>
	<div class="b-form">
		<h2 class="b-title b-form__title">Общая информация</h2>
		<div class="b-form__row b-form__row_long">
			<?=CHtml::activeLabel($profile,'orgname', array('class'=>'b-form__label'))?>
			<?=CHtml::activeTextField($profile,'orgname', array('class'=>'b-form__text'))?>
			<?php if($profile->isAttributeRequired('orgname')) {?><span class="required">*</span><?php } ?>
		</div>
		<div class="b-form__row b-form__row_long">
			<?=CHtml::activeLabel($profile,'form', array('class'=>'b-form__label'))?>
			<?=CHtml::activeListBox($profile,'form', $profile->jurForm, array('class'=>'b-form__text', 'size'=>1))?>
			<?php if($profile->isAttributeRequired('form')) { ?><span class="required">*</span><?php } ?>
		</div>
<?php /*?>
			<label class="b-form__label">Фамилия</label>
			<?=CHtml::activeTextField($profile,'cface_lname', array('class'=>'b-form__text'))?>
			<label class="b-form__label">Имя</label>
			<?=CHtml::activeTextField($profile,'cface_sname', array('class'=>'b-form__text'))?>
			<label class="b-form__label">Отчество</label>
			<?=CHtml::activeTextField($profile,'cface_fname', array('class'=>'b-form__text'))?>
*/?>		
		<h2 class="b-title b-form__title">Юридический адрес</h2><br/>
		<div class="b-form__row">
			<?=CHtml::activeLabel($profile,'town0', array('class'=>'b-form__label'))?>
			<div class="b-form__row_half">
				<?if(@$_POST['Profile']['town0_title']):?>
				    <input type="text" name="Profile[town0_title]" value="<?=@$_POST['Profile']['town0_title']?>" class="townSelectField  b-form__text" />
                    <input type="hidden" name="Profile[town0]" value="<?=@$_POST['Profile']['town0']?>" />
				<?else:?>
				    <?=CHtml::activeTextField($profile,'town0', array('class'=>'townSelectField b-form__text'))?>
				<?endif?>
				<?if($profile->isAttributeRequired('town0')):?>
                    <span class="required">*</span>
                <?endif?>
			</div>
            <div class="b-form__row_half">
				<?=CHtml::activeLabel($profile,'index0', array('class'=>'b-form__label'))?>
				<?=CHtml::activeTextField($profile,'index0', array('class'=>'b-form__text'))?>
				<?if($profile->isAttributeRequired('index0')):?>
                    <span class="required">*</span>
                <?endif?>
			</div>
		</div>
		<div class="b-form__row b-form__row_long">
			<?=CHtml::activeLabel($profile,'address0', array('class'=>'b-form__label'))?>
			<?=CHtml::activeTextField($profile,'address0', array('class'=>'b-form__text'))?>
			<?if($profile->isAttributeRequired('address0')):?>
                <span class="required">*</span>
            <?endif?>
		</div>
		<h2 class="b-title b-form__title">Фактический адрес</h2>
		<div class="b-form__row">
			<?=CHtml::activeLabel($profile,'town', array('class'=>'b-form__label', 'style'=>'float:left'))?><div class="b-form__row_half" style='float:left'>
				<?php if(@$_POST['Profile']['town_title']) {?>
				<input type="text" name="Profile[town_title]" value="<?=@$_POST['Profile']['town_title']?>" class="townSelectField b-form__text" />
			    <input type="hidden" name="Profile[town]" value="<?=@$_POST['Profile']['town']?>" />
				<?php }else { ?>
				<?=CHtml::activeTextField($profile,'town', array('class'=>'townSelectField b-form__text'))?>
				<?php }?>
				<?php if($profile->isAttributeRequired('town')) {?><span class="required">*</span><?php } ?>
			</div><div class="b-form__row_half">
				<?=CHtml::activeLabel($profile,'index', array('class'=>'b-form__label'))?>
				<?=CHtml::activeTextField($profile,'index', array('class'=>'b-form__text'))?>
				<?php if($profile->isAttributeRequired('index')) {?><span class="required">*</span><?php } ?>
			</div>
		</div>
		<div class="b-form__row b-form__row_long">
			<?=CHtml::activeLabel($profile,'address', array('class'=>'b-form__label'))?>
			<?=CHtml::activeTextField($profile,'address', array('class'=>'b-form__text'))?>
			<?php if($profile->isAttributeRequired('address')) {?><span class="required">*</span><?php } ?>
		</div>
		<?php
		// Поле с ошибками нужно помещать после последнего длинного поля (div.b-form__row_long)			
			$errorsHtml = CHtml::errorSummary($form) . CHtml::errorSummary($profile);
			if($errorsHtml) { 
		?>
		<div class="b-form__errors" id="error_summary">
			<?php echo $errorsHtml ?>
		</div>
		<?php } ?>
		<h2 class="b-title b-form__title">Дополнительные данные</h2>
		<div class="b-form__row">
			<?=CHtml::activeLabel($profile,'inn', array('class'=>'b-form__label'))?>
			<?=CHtml::activeTextField($profile,'inn', array('class'=>'b-form__text'))?>
			<?php if($profile->isAttributeRequired('inn')) {?><span class="required">*</span><?php } ?>
		</div>
		<div class="b-form__row">
			<?=CHtml::activeLabel($profile,'cphone', array('class'=>'b-form__label'))?>
			<?=CHtml::activeTextField($profile,'cphone', array('class'=>'b-form__text'))?>
			<?php if($profile->isAttributeRequired('cphone')) {?><span class="required">*</span><?php } ?>
		</div>
		<h2 class="b-title b-form__title">Информация о пользователе</h2>
		<div class="b-form__row">
			<label class="b-form__label">E-mail</label>
			<?=CHtml::activeTextField($form,'email', array('class'=>'b-form__text'))?>
			<?php if($form->isAttributeRequired('email')) {?><span class="required">*</span><?php } ?>
		</div>
		<div class="b-form__row">
			<label class="b-form__label">Логин</label>
			<?=CHtml::activeTextField($form,'username', array('class'=>'b-form__text'))?>
			<?php if($form->isAttributeRequired('username')) {?><span class="required">*</span><?php } ?>
		</div>
		<div class="b-form__row">
			<label class="b-form__label">Пароль</label>
			<?=CHtml::activePasswordField($form,'password', array('class'=>'b-form__text'))?>
			<?php if($form->isAttributeRequired('password')) {?><span class="required">*</span><?php } ?>
		</div>
		<div class="b-form__row">
			<label class="b-form__label">Повторите пароль</label>
			<?=CHtml::activePasswordField($form,'verifyPassword', array('class'=>'b-form__text'))?>
			<?php if($form->isAttributeRequired('verifyPassword')) {?><span class="required">*</span><?php } ?>
		</div>
		<?if (UserModule::doCaptcha('registration')):?>
		<h2 class="b-title b-form__title">Защита от спама</h2>
		<div class="b-form__row b-form__row_captcha">
			<label class="b-form__label">Введите код, указанный на картинке</label>
			<?=CHtml::activeTextField($form,'verifyCode', array('class'=>'b-form__text'));?>
			<span class="required">*</span>
		</div>
		<div class="b-form__row">
			<div class="b-form__label">&nbsp;</div>
			<div class="b-captcha"><?$this->widget('CCaptcha', array('buttonLabel'=>'Получить новый код','clickableImage'=>true));?></div>
		</div>
		<?endif;?>
		<div class="b-form__row">
			<span class="b-form__label">&nbsp;</span>
			<?=CHtml::submitButton('Отправить') ?>
		</div>
		</div>
	</div>
	</form>
<?endif;?>

<script>
$(".autocomplete")
	.keydown(function (e) {
		// лочим enter, чтобы при выборе автокомплита не было отправки формы
		if(e.keyCode == 13) return false;
	})
	.jSuggest({
	    url: "/user/registration/autocomplete", type: "POST",
	    data: "iKeywords",
	    minchar: 2,loadingImg: '<?=Yii::app()->theme->baseUrl?>/images/ajax_loader.gif', loadingText: 'Подождите...',
	    delay: 500, autoChange: false,
	    opacity: 1.0, zindex: 20000
	});
</script>