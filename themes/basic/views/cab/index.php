<table id="tableMain" cellpadding="0" cellspacing="0">
    <tr>
        <td style="width:235px;"> <?#--------------------------------------------------------- ЛЕВАЯ КОЛЛОНКА ?>
            <div id="divLogo">
                <div id="divLogoMiddle" style="background:url('<?=$user->profile->getImageUrl('middle')?>') left bottom no-repeat;">&nbsp;
<!--                	<div id="ajax-loader">&nbsp;</div>-->
                </div>
                <div id="divMask">
            </div>
            <?if ($pageID == Yii::app()->user->getId()):?>
                <?$form = $this->beginWidget('CActiveForm', array(
                    'id'=>'formLogo',
                    'action'=>'/cab/logoSave',
                    'htmlOptions'=>array(
                        'enctype'=>'multipart/form-data',),));?>
                <?=$form->fileField($user->profile, 'image');?><?#=$form->error($user->profile,'image'); ?>
                <?$this->endWidget();?>
            <?endif;?>
            </div>
            <div id="divAppointment">
                <?#$this->widget('dbfAppointment', array('pageID'=>$pageID))?>
            </div>
            <div id="divMessages">
                <?$this->widget('dbfMessages', array('pageID'=>$pageID))?>
            </div>
            <div id="divPartners">
                <?#$this->widget('dbfPartners', array('pageID'=>$pageID))?>
            </div>
            <div id="divOpinions">
                <?$this->widget('dbfOpinions', array('pageID'=>$pageID))?>
            </div>
        </td>
        <td style=""> <?#----------------------------------------------------------------- ЦЕНТРАЛЬНАЯ КОЛЛОНКА ?>
            <div id="divVizitka"><?=$viz?></div>
            <br style="clear:both;" />
            <div id="divCabContent">
                <?$this->widget('application.extensions.vahtabs.VahTabs', array('tabs'=>$Tabs, 'pageID'=>$pageID, 'cntLimit'=>100));?>
                <div id="divCabContentMaskL"></div><div id="divCabContentMaskR"></div>
                <br class="clear" />
                <div id="divCabContentBottom"></div>
            </div>
        </td>
        
        <td style="width:186px;"> <?#---------------------------------------------------------- ПРАВАЯ КОЛЛОНКА ?>
            <div id="divDirector">
            	<?php if(! empty($activity) && is_array($activity)) {?>
            	<h4>Сферы деятельности:</h4>
            	<ul class="b-activity">
            	<?php foreach ($activity as $title) {?>
            		<li><?php echo $title ?></li>
            	<?php }?>
            	</ul>
            	<?php }?>
                <?$this->widget('comApp', array('pageID'=>$pageID))?>
            </div>
            <!--<div id="divPhoto" style="margin-top: 20px;"><br />Фото</div>
            <div id="divAudio"><br />Аудио</div>
            <div id="divVideo"><br />Видео</div>-->
            <div id="divRating" style="margin-top: 20px;">
                    <?$this->widget('CStarRating', array(
                        'name' => 'rating',
                        'minRating' => '1',
                        'maxRating' => '5',
                        'value' => $rating->getRating($pageID),
                        'starWidth'=>'15',
                        'ratingStepSize' => '1',
                        //'titles'=>  RatingDesc::GetArray(),
                        'allowEmpty'=>false,
                        'readOnly'=>$rating->checkVoice($pageID),
                        'callback' => 'function(value) {SetRating(value, '.$pageID.');}',
                    ));?><br />
                &nbsp;&nbsp;&nbsp;Голосов: <?=$rating->getCount($pageID)?>    
            </div>
        </td>
    </tr>
</table>

