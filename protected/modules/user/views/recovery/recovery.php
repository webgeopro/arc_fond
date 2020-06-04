<?php $this->pageTitle=Yii::app()->name . ' - '.UserModule::t("Restore");
$this->breadcrumbs=array(
	UserModule::t("Login") => array('/user/login'),
	UserModule::t("Restore"),
);
?>

<h1 class="b-title"><?php echo UserModule::t("Restore"); ?></h1>

<?php if(Yii::app()->user->hasFlash('recoveryMessage')): ?>
<div class="success">
<?php echo Yii::app()->user->getFlash('recoveryMessage'); ?>
</div>
<?php else: ?>

<div class="b-form">
<?php echo CHtml::beginForm(); ?>

	<?php
		$errorsHtml = CHtml::errorSummary($form);
		if($errorsHtml) { 
	?>
	<div class="b-form__errors" id="error_summary">
		<?php echo $errorsHtml ?>
	</div>
	<?php } ?>
	<div class="b-form__row">
		<?php echo CHtml::activeLabel($form,'login_or_email', array('class'=>'b-form__label')); ?>
		<?php echo CHtml::activeTextField($form,'login_or_email', array('class'=>'b-form__text')) ?>
		<p class="b-form__hint"><?php echo UserModule::t("Please enter your login or email addres."); ?></p>
	</div>
	
	<div class="b-form__row submit">
		<span class="b-form__label">&nbsp;</span>
		<?php echo CHtml::submitButton(UserModule::t("Restore")); ?>
	</div>

<?php echo CHtml::endForm(); ?>
</div><!-- form -->
<?php endif; ?>