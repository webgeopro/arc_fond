<?php
$this->pageTitle=Yii::app()->name . ' - Написать письмо';
$this->breadcrumbs=array(
	'Контакты',
);
?>

<h1>Обратная связь</h1>

<?php if(Yii::app()->user->hasFlash('contact')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('contact'); ?>
</div>

<?php else: ?>

<p>
Если у вас возникли вопросы, свяжитесь с нами, заполнив и отправив форму, отображенную ниже. Спасибо.
</p>
<table><tr>
<td style="width:50%;vertical-align:top;">
    <div id="divMap" style="background:grey;width:100%;height:300px;font-size:20px;color:white;"><br />&nbsp;&nbsp;Интерактивная карта</div>
    <div id="divAddress" style="background:silver;width:100%;height:150px;margin-top:10px;">
        <br />
        <ul style="list:none;">
            <li>Красноярск</li>
            <li>ул. Аэровокзальная, 13</li>
            <li>тел.: 29-24-555, 25-000-87</li>
        </ul>
    </div>
</td>
<td>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm'); ?>

	<p class="note">Поля, отмеченные <span class="required">*</span> обязательны к заполнению.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'subject'); ?>
		<?php echo $form->textField($model,'subject',array('size'=>60,'maxlength'=>128)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'body'); ?>
		<?php echo $form->textArea($model,'body',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<?php if(extension_loaded('gd')): ?>
	<div class="row">
		<?php echo $form->labelEx($model,'verifyCode'); ?>
		<div>
		<?php $this->widget('CCaptcha'); ?>
		<?php echo $form->textField($model,'verifyCode'); ?>
		</div>
		<div class="hint">Пожалуйста, введите буквы, показанные на картинке выше.
            <br />Регистр значение не имеет.
		</div>
	</div>
	<?php endif; ?>

	<div class="row buttons">
		<?php echo CHtml::submitButton("Отправить"); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
</td></tr></table>
<?php endif; ?>