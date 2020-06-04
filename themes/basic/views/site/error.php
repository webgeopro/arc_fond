<?php
$this->breadcrumbs=array(
	'Error',
);
if( $code == 404 ) { 
?>
<h1 class="b-title">Ой! Страница не найдена</h1>
<h2 class="b-title_2">Возможно, это произошло потому что:</h2>
<ul>
	<li>вы ошиблись в наборе адреса страницы;</li>
	<li>вам дали неверную ссылку;</li>
	<li>нужная вам страница была удалена или перемещена.</li>
</ul>
<h2 class="b-title_2">Что делать?</h2>
<ul>
	<li>нажмите на кнопку &laquo;<a href="<?php echo Yii::app()->request->url?>" onclick="window.history.go(-1);return false;">Назад</a>&raquo; в браузере;</li>
	<li>попробуйте <a href="<?php echo Yii::app()->request->getBaseUrl(true);?>">начать с начала</a>;</li>
	<li>если вы уверены, что ошибка произошла по нашей вине, <a href="mailto:<?php echo Yii::app()->params['adminEmail']?>">напишите нам</a>.</li>
</ul>
<?php } else { ?>
<h1 class="b-title">Извините, произошла ошибка <?php echo $code ?></h1>
<h2 class="b-title_2">Что это значит?</h2>
<p>
<?php echo CHtml::encode($message); ?>
</p>

<h2 class="b-title_2">Что делать?</h2>
<ul>
	<li>нажмите на кнопку &laquo;<a href="<?php echo Yii::app()->request->url?>" onclick="window.history.go(-1);return false;">Назад</a>&raquo; в браузере;</li>
	<li>попробуйте <a href="<?php echo Yii::app()->request->getBaseUrl(true);?>">начать с начала</a>;</li>
	<li>если вы уверены, что ошибка произошла по нашей вине, <a href="mailto:<?php echo Yii::app()->params['adminEmail']?>">напишите нам</a>.</li>
</ul>
<?php } ?>