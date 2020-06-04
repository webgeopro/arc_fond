<?php
$this->pageTitle=Yii::app()->name . ' - '.UserModule::t("Login");
$this->breadcrumbs=array(
	UserModule::t("Login"),
);
?>


<h1 class="b-title">Войти в систему<?php //echo UserModule::t("Login"); ?></h1>
<?php if(Yii::app()->user->hasFlash('loginMessage')): ?>

<div class="success">
	<?php echo Yii::app()->user->getFlash('loginMessage'); ?>
</div>

<?php endif; ?>

<div class="b-info">
	<div class="b-info__icon">?</div>
	<div class="b-info__text"><?php echo UserModule::t("Please fill out the following form with your login credentials:"); ?><br/><span class="required">*</span> Обязательно к заполнению</div>
</div>

<div class="b-form">
<?php echo CHtml::beginForm(); ?>

	<?php
		$errorsHtml = CHtml::errorSummary($model);
		if($errorsHtml) { 
	?>
	<div class="b-form__errors" id="error_summary">
		<?php echo $errorsHtml ?>
	</div>
	<?php } ?>
	
	<div class="b-form__row">
		<label class="b-form__label">Логин или e-mail:</label>
		<?php echo CHtml::activeTextField($model,'username',array('class'=>'b-form__text')) ?>
		<span class="required">*</span>
	</div>
	
	<div class="b-form__row">
		<label class="b-form__label">Пароль:</label>
		<?php echo CHtml::activePasswordField($model,'password',array('class'=>'b-form__text')) ?>
		<span class="required">*</span>
	</div>
	
	<div class="b-form__row">
		<p class="b-form__hint">
		<?php echo CHtml::link(UserModule::t("Register"),Yii::app()->getModule('user')->registrationUrl); ?><br/> 
		<?php echo CHtml::link(UserModule::t("Lost Password?"),Yii::app()->getModule('user')->recoveryUrl); ?>
		</p>
	</div>
	
	<div class="b-form__row rememberMe">
		<?php echo CHtml::activeCheckBox($model,'rememberMe'); ?>
		<?php echo CHtml::activeLabelEx($model,'rememberMe',array('class'=>'b-form__label')); ?>
	</div>

	<div class="b-form__row submit">
		<span class="b-form__label">&nbsp;</span>
		<?php echo CHtml::submitButton(UserModule::t("Login")); ?>
	</div>
	
<?php echo CHtml::endForm(); ?>
</div><!-- form -->


<?php
$form = new CForm(array(
    'elements'=>array(
        'username'=>array(
            'type'=>'text',
            'maxlength'=>32,
        ),
        'password'=>array(
            'type'=>'password',
            'maxlength'=>32,
        ),
        'rememberMe'=>array(
            'type'=>'checkbox',
        )
    ),

    'buttons'=>array(
        'login'=>array(
            'type'=>'submit',
            'label'=>'Login',
        ),
    ),
), $model);
?>