<?php
/**
 * $Id$
 * Error page layout
 * 
 * @author Andrey Matveev, <andrey.g.matveev@gmail.com>
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<? echo Yii::app()->theme->baseUrl?>/css/reset.css" type="text/css" />
<link rel="stylesheet" href="<? echo Yii::app()->theme->baseUrl?>/css/error.css" type="text/css" />
<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>
<body class="error">
	<div class="b-wrap">
		<div class="b-header">
			<a href="<?php echo Yii::app()->request->getBaseUrl(true); ?>" class="b-logo" title="<?php echo CHtml::encode(Yii::app()->name); ?>"><?php echo CHtml::encode(Yii::app()->name); ?></a>
		</div>
		<div class="b-content">
			
<?php echo $content ?>			
			
		</div>
		<div class="clear">&nbsp;</div>
	</div>
	<div class="b-bg">&nbsp;</div>
	<div class="b-footer">
		<p class="b-footer__copy">Copyright © 2011 <a href="/">Детский фонд</a></p>
	</div>
</body>
</html>