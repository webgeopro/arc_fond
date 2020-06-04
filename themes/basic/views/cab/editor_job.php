<?if ($formEdit):?>
<script language="javascript">
$("document").ready(function () {
	$('.townSelectField').selectableTree({'url':'/cab/townsOptions'});
    $('.aCancel').click(function() {
        var id = $(this).attr('rel');
        if ( 0 == id) {
            str = '<div id="divJobs_0" class="divJobs" style="padding:4px 10px;">' 
                + '<a href="" class="aEdit aAdd" name="job_editor::new" id="0">Добавить новую вакансию</a>'
                + '</div>';
            $("#divJobs_0").html(str);
        } else {    
            $("#divJobs_" + id).load("/cab/getField",{
                type_id: this.name
            });
        }
        return false;
    });
    $('.aSave').click(function() {
        var fck = FCKeditorAPI.GetInstance("<?=$name?>")
        $("#inpComment").val(fck.GetHTML());
        $('#formJob_'+this.id).ajaxSubmit({success:aClick, dataType:'json'}); 
        return false;
    });
    $(".autocomplete").jSuggest({
        url: "/cab/autocomplete", type: "POST",
        data: "iKeywords",
        minchar: 2,loadingImg: '<?=Yii::app()->theme->baseUrl?>/images/ajax_loader.gif', loadingText: 'Подождите...',
        delay: 500, autoChange: false,
        opacity: 1.0, zindex: 20000
    });
    $(".specAutocomplete").jSuggest({
        url: "/cab/specAutocomplete", type: "POST",
        data: "iKeywords",
        minchar: 2, loadingImg: '<?=Yii::app()->theme->baseUrl?>/images/ajax_loader.gif', loadingText: 'Подождите...',
        delay: 500, autoChange: false,
        opacity: 1.0, zindex: 20000
    });


}); 
function aClick(data)
{
    if ('success' == data.result) {
        $('#aSave_'+data.id).click()
    } else if ('errorValidate' == data.result) { 
        alert("Обнаружены ошибки. \nЗапись не сохранена!");
        /*if (0 < data.err_el.length) {
            var str = ' '
            for (var i=0; i<data.err_el.length; i++) {
                str += data.err_el[i]+"  "; 
            }
        alert(str);
        }*/
    } else {
        alert('Внутренняя ошибка. \nК сожалению, невозможно внести изменения.');
    }
} 
</script>

<form method="post" action="/cab/save" id="formJob_<?=$new?'0':$post['id']?>" class="formJob">
<input type="hidden" name="element_id" value="jobs::<?=$new?'new':$post['id']?>" />
<input type="hidden" name="comment" id="inpComment" value="" />
<div style="width:500px;float:left">
    <input type="text" style="width:100%;" name="towns_id" id="towns_id" value="<?=$post['fk_towns']['name']?>" class="townSelectField" style="position:relative"/>
    Выберите город
</div>
<div style="width:100px;float:right;">
    <input  type="text" style="width:100%;" name="salary" value="<?=$post['salary']?>" />
    Зарплата (руб.)
</div>

<br style="clear:both;" />
<div style="width:100%;">
    <input type="text" name="specialities_id" value="<?=$post['fk_spec']['name']?>" id="speciality" class="specAutocomplete" style="width:100%;" />
    Выберите специальность
</div>

<div style="float:left;width:140px;">
    <?=CHtml::dropDownList('exp', $post['exp'], Jobs::model()->exp)?>
    Опыт
</div>
<div style="float:left;width:100px;">
    <?=CHtml::dropDownList('male', $post['male'], Jobs::model()->male)?>
    Пол
</div>
<div style="float:left;width:70px;">
    <input  type="text" style="width:100%;" name="ageFrom" value="<?=$post['ageFrom']?>" />
    Возраст от
</div>
<div style="float:left;width:70px;">
    <input  type="text" style="width:100%;" name="ageTill" value="<?=$post['ageTill']?>" />
    ... до (лет)
</div> 
<div style="float:left;width:120px;padding-left:14px;">
    <?=CHtml::dropDownList('education', $post['education'], Jobs::model()->education)?>
    Образование
</div>

<br style="clear:both;" />
<div style="float:left;width:100%;height:300px;margin-bottom:10px;">
    <?=CHtml::activeTextArea($post,$field,array('rows'=>6, 'cols'=>60));
    $this->widget('application.extensions.editor.editor',
                   array('name'=>$name, 'type'=>'fckeditor', 'height'=>$height, 'toolbar'=>'Basic'));?>
    Примечание
</div>
</form> 
<div id="divButtons_<?=$job['id']?>" style="float:right;">
    <br />
    <a href="" class="b-button aSave" name="job_editor::<?=$new?'0':$post['id']?>" id="<?=$new?'0':$post['id']?>" style="margin:10px;">сохранить</a>
    <a href="" class="b-button aCancel" name="job_editor::<?=$new?'0':$post['id']?>" id="aSave_<?=$new?'0':$post['id']?>" rel="<?=$new?'0':$post['id']?>">отменить</a>
</div>
<br style="clear:both;" />&nbsp;
<?else:?>

<script language="javascript">$("document").ready(function () {
    $('.aEdit').click(function() {
        $("#divJobs_"+this.id).load("/cab/getField",{
            type_id: this.name,
            form_edit: true,
        });
        return false;
    });
});</script>
<div class="b-row_edit">
    <a class="b-button b-button_right aEdit" name="job_editor::<?=$post['id']?>" id="<?=$post['id']?>">Изменить</a>
    <div class="b-row__title">
	    <?=$post['fk_spec']['name']?>
	    <span class="b-row__salary"><?=$post['salary']?> руб.</span>
	</div>
    <a class="b-button b-button_right aDel aDelJob" id="<?=$post['id']?>" name="jobs::<?=$post['id']?>">удалить</a>
    
    <div class="b-row__body">
    	<span><?=$post['fk_towns']['name']?></span><br/>
	    <span>Опыт: <?=Jobs::model()->exp[$post['exp']]?></span>,
	    <span>пол: <?=Jobs::model()->male[$post['male']]?></span>,
	    <span>от <?=$post['ageTill']?"{$post['ageFrom']} до {$post['ageTill']}":$post['ageFrom']?> лет</span><br/>
	    <span>Образование <?=Jobs::model()->education[$post['education']]?></span>
    </div>
    
    <div class="b-row__body"><?=$post['comment']?></div>
<!--    <span class="b-row__separator">&nbsp;</span>-->
</div>    
	<?php /*?>
    <span><?=$post['fk_towns']['name']?></span>
    <span style="float:right;"><?=$post['salary']?> руб.</span><br style="clear:both;" />
    <span><?=$post['fk_spec']['name']?></span><br style="clear:both;" />
    <span><?=Jobs::model()->exp[$post['exp']]?></span>
    <span><?=Jobs::model()->male[$post['male']]?></span> 
    <span>от <?=$post['ageTill']?"{$post['ageFrom']} до {$post['ageTill']}":$post['ageFrom']?> лет</span>
    <span><?=Jobs::model()->education[$post['education']]?></span>
    <br style="clear:both;" />
    <span><?=$post['comment']?></span>
    <span style="float: right;">
        <a href="" class="aEdit" name="job_editor::<?=$post['id']?>" id="<?=$post['id']?>">Изменить</a> 
        <a href="" class="aDel" name="job_editor::<?=$post['id']?>" id="<?=$post['id']?>">Удалить</a>
    </span>
    <?php */?>
<?endif;?>