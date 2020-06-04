<?php $DEFAULT_TEXT = 'добавить'; ?>

<?if ($boolEdit): #Редактирование визитки ?>
<script language="javascript">$("document").ready(function () {
    $(".divComAppInnerBox span").editInPlace({
        url: "/cab/inPlaceSave",
        bg_over: "#F6EBEC",
        field_type: "text",
        saving_image: "<?=Yii::app()->theme->baseUrl?>/images/ajax_loader.gif",
        on_blur: "save",
        default_text: '<?php echo $DEFAULT_TEXT; ?>'
    });
    $(".aComAppDel").click(function() {
        if (confirm('Вы действительно хотите удалить сотрудника?')) {
            var options = {
                type: "POST",
                data: "element_id=comPersonal::" + this.name,
                url: "/cab/delete", 
                success: function(message) {			            	
				    if ('success' == message) {
                        $("#divComAppEdit_"+this.name).remove();
                        $("#aComAppAll").click(); // ЗАглушка. Пред. ф-ия не работает...
                    }
                }
            }
            $.ajax(options);
        }
        return false;
    });
    $("#aComAppAdd").click(function(){
        var options = {
                type: "POST",
                data: "type_id=comApp",
                url: "/cab/add", 
                cache: false,
                success: function(message) {			            	
				    if ('success' == message) {
				        $("#aComAppAll").click();
				    } else {
				        alert('Произошла ошибка при добавлении!');
				    }             
                }
                
            }
        $.ajax(options);
        return false;
    });
    $("._imgComApp").click(function() { // Используется форма
        $("#inpField").val(this.name);
        $("#formComApp").attr('action', '/cab/imageSave?element_id='+this.name);
        $("#inpImage").click();
        
        return false;
    });
    $("#_inpImage").change(function() { // Используется форма
        if ($("#inpImage").val()) {
            //$("#yt_image").val($("#inpImage").val());
            $("#formComApp").ajaxSubmit({
                dataType: 'json',
                success : showResponse
            });
        }
        
        return false;
    });
});
function showResponse(data)
{
    if ('success' == data.result) {
        alert(data.logo);
        //$("#divLogoMiddle").css('background-image', 'url("'+data.logo+'")');
    } else {
        alert('При сохранении обнаружены ошибки.');
    }
}
function comAppSave(ob)
{
    $(ob).ajaxSubmit({
        dataType: 'json',
        success : showcomAppSaveResponse
    });
}
function showcomAppSaveResponse(data)
{
    if ('success' == data.result) {
        $("#img_"+data.id).attr('src', data.logo);
    } else {
        alert('При сохранении обнаружены ошибки.');
    }
}
</script>
<a href="" class="b-button" id="aComAppAdd">добавить сотрудника</a><br style="clear: both;" />
<?foreach ($comPersonal as $com):$cnt++;?>
    <?if ($cnt % 2):?><br class="clear" /><?endif?>
    <div class="divComAppInnerBox">
        <div class="divComAppInnerImg">
            <img src="/uploads/users/<?=$pageID?>/com_app/<?=$com['image']?>" class="imgComApp" name="comPersonal::<?=$com['id']?>" id="img_<?=$com['id']?>" />
        </div>
        <a class="aComApp aComAppDel" name="<?=$com['id']?>" title="удалить">
            <img src="<?=Yii::app()->theme->baseUrl?>/images/cab/del.png" width="16" height="16" />
        </a>
        <div class="divComAppInnerText">
            <div class="divComAppFIO">
                Фамилия: <span id="comPersonal::<?=$com['id']?>::lname" title="Изменить Фамилию"><?=$com['lname'] ? $com['lname'] : $DEFAULT_TEXT ?></span><br />
                Имя: <span id="comPersonal::<?=$com['id']?>::fname" title="Изменить Имя"><?=$com['fname'] ? $com['fname'] : $DEFAULT_TEXT ?></span> <br />
                Отчество: <span id="comPersonal::<?=$com['id']?>::sname" title="Изменить Отчество"><?=$com['sname'] ? $com['sname'] : $DEFAULT_TEXT ?></span>
            </div>
            Должность: <span id="comPersonal::<?=$com['id']?>::position" title="Изменить должность"><?=$com['position'] ? $com['position'] : $DEFAULT_TEXT?></span><br />
            Телефон: <span id="comPersonal::<?=$com['id']?>::phone" title="Изменить телефон"><?=$com['phone'] ? $com['phone'] : $DEFAULT_TEXT?></span><br />
            E-mail: <span id="comPersonal::<?=$com['id']?>::email" title="Изменить E-mail"><?=$com['email'] ? $com['email'] : $DEFAULT_TEXT ?></span><br />
            ICQ: <span id="comPersonal::<?=$com['id']?>::icq" title="Изменить ICQ"><?=$com['icq'] ? $com['icq'] : $DEFAULT_TEXT?></span><br />
            Skype: <span id="comPersonal::<?=$com['id']?>::skype" title="Изменить Skype"><?=$com['skype'] ? $com['skype'] : $DEFAULT_TEXT ?></span>
        </div>
        <?$form = $this->beginWidget('CActiveForm', array(
                'id'=>'formLogo_'.$com['id'],
                'action'=>'/cab/comAppSave?pID='.$com['id'],
                'htmlOptions'=>array(
                    'enctype'=> 'multipart/form-data',
                    'class'  => 'comAppForm',
                    'name'   => 'formLogo_'.$com['id'],
                ), ));
            echo $form->fileField(comPersonal::model()->findByPk($com['id']), 'image', array(
                'onChange'=> 'comAppSave('.'formLogo_'.$com['id'].')',
                'style'   => 'width:200px;clear:both;',
                ));
        $this->endWidget();?>
    </div>
    <?if (!($cnt % 2)):?><br class="clear" /><br /><hr class="hrComApp" /><?endif?>
<?endforeach?> 

<?else: # Просмотр "Весь состав" ?>

<?foreach ($comPersonal as $com):$cnt++;?>
    <?if ($cnt % 2):?><br class="clear" /><?endif?>
    <div class="divComAppInnerBox">
        <div class="divComAppInnerImg">
            <img src="/uploads/users/<?=$pageID?>/com_app/<?=$com['image']?>" />
        </div>
        <div class="divComAppInnerText">
            <div class="divComAppFIO">
                <?=$com['lname']?$com['lname'].'<br />':''?>
                <?=$com['fname']?$com['fname']:''?> <?=$com['sname']?$com['sname']:''?>
            </div>
            <?=$com['position']?><br />
            <?=$com['phone']?'Тел.: '.$com['phone'].'<br />':''?>
            <?=$com['email']?'E-mail: '.$com['email'].'<br />':''?>
            <?=$com['icq']?'ICQ: '.$com['icq'].'<br />':''?>
            <?=$com['skype']?'Skype: '.$com['skype']:''?>
        </div>
    </div>
    <?if (!($cnt % 2)):?><br class="clear" /><br /><hr class="hrComApp" /><?endif?>
<?endforeach?> 
<?endif?>