<script language="javascript">
$("document").ready(function () {
    $(".spEditInPlace_inp").editInPlace({
        url: "/cab/inPlaceSave",
        bg_over: "#F6EBEC",
        field_type: "text",
        saving_image: "<?=Yii::app()->theme->baseUrl?>/images/ajax_loader.gif",
        on_blur: "save"
    });
    $(".spEditInPlace_area").editInPlace({
        url: "/cab/inPlaceSave",
        bg_over: "#F6EBEC",
        field_type: "textarea",
        saving_image: "<?=Yii::app()->theme->baseUrl?>/images/ajax_loader.gif",
        textarea_rows: 4,
        on_blur: "save"
    });
    $(".spEditInPlace_sel").editInPlace({
        url: "/cab/inPlaceSave",
        bg_over: "#F6EBEC",
        field_type: "select",
        select_options: <?=$select?>,
        select_text: 'Выберите тему',
        saving_image: "<?=Yii::app()->theme->baseUrl?>/images/ajax_loader.gif",
        on_blur: "save"
    });
    $(".aDelNews").click(function() {
        if (confirm('Удалить статью?')) {
            var divArticles = "#divArticles_"+this.id;
            $.post("/cab/delete", { 
                'element_id' : $(this).attr('name'),
                }, function(res) {
                    if (res == 'success') $(divArticles).remove();
            });
        }
        return false;
    });
    $(".aAdd").click(function() {
        if (confirm('Добавить статью?')) {
            $.post("/cab/add", { type_id: this.id } );
            $("#tabArticles").click();
        }
        return false;
    });
    $(".wysiwyg").click(function() {
        $("#divWysiwyg").load("/cab/getField", { type_id: this.id } );
        $("#divEditorFloat").css('top',$(window).scrollTop()).show();
        return false;
    });
    $(".aWysiwygClose").click(function() {
        $("#divEditorFloat, #divSourceSelect").hide(150);
        return false;
    });
    $(".aAddFiles").click(function() {
        $("#inpSourceID").val($(this).attr("rel"));
        $("#divSourceSelect").show();
        return false;
    });
    $("#aSourceFileSave").click(function() {
        if ( $("#inpSourceName").val() && $("#inpSourceFile").val() ) {
            var pageID = $("#inpSourceID").val();
            $.post("/cab/add", { 
                'element_id' : $(this).attr('name'),
                'pageID'     : pageID
                }, function(data) {
                    if (data['res'] == 'success') { 
                        //Добавить данные в $("#spFiles_"=pageID).html(data['files']);
                        $(".aWysiwygClose").click();
                    }
            }, 'json');
        } else {
            alert('Не все поля заполнены!');
        }
        return false;
    });
});
</script>
<a href="" id="articles" class="b-button aAdd">Добавить статью</a><br /><br />
<?foreach ($post as $art):?>
<?if ($dataTemp != $art['data']):
    $dataTemp = $art['data'];?>
    <div class="divData"><?=$art['userDate']?>&nbsp;&nbsp;&nbsp;</div>
<?endif;?>

<div class="divArticles b-row b-row_edit" id="divArticles_<?=$art['id']?>">
    <a class="b-button b-button_right aDelNews aDel" id="<?=$art['id']?>" name="articles::<?=$art['id']?>">удалить</a>
    <div class="b-row__title spEditInPlace_area" id="articles::<?=$art['id']?>::title" title="Кликните мышью для редактирования"><?=$art['title']?></div>
    <div class="b-row_tag spEditInPlace_sel" id="articles::<?=$art['id']?>::rubricator_id" title="Кликните мышью для редактирования"><?=$art['rubricator']['title']?></div>
    <div class="b-row__body wysiwyg" id="article_editor::<?=$art['id']?>::descr" title="Кликните мышью для редактирования"><?=strip_tags(substr($art['descr'],0,300))?></div>
    <div class="b-row__author">
    Автор статьи: <span class="spEditInPlace_inp" id="articles::<?=$art['id']?>::author" title="Кликните мышью для редактирования"><?=$art['author']?></span><br />
    </div>
    <div class="b-row__author">
	    <a href="" rel="<?=$art['id']?>" class="aAddFiles">Добавить файл-оригинал статьи</a><br />
	    <div class="b-row__body" id="spFiles_<?=$art['id']?>"></div>
	    <ul>
	    <?php 
	    if(is_array($art['source'])) {
	    	foreach($art['source'] as $file) { ?>
	        <li><?php echo $file->name?> <a href="" id="articles::<?=$file['id']?>" class="aDelFiles" title="удалить">удалить</a></li>
	    <?php 
	    	}}
	    ?>
	    </ul>
    </div>
</div>
<?endforeach;?>
<div id="divEditorFloat">
	<div id="divWysiwyg" style="width:100%;height:480px;"></div>
</div>
<div id="divSourceSelect" style="width:350px;height:134px;position:absolute;display:none;margin:0 auto;top:200px;background:#fff;padding:4px 10px;border:1px solid gray;">
    <input type="hidden" name="inpSourceID" id="inpSourceID" value="" />
    Название файла<font color="red">*</font><input type="text" id="inpSourceName" style="width: 90%;" /><br />
    Выберите файл<font color="red">*</font><input type="file" id="inpSourceFile" value="Выберите файл" style="width:100%;" />
    Поля, помеченные <font color="red">*</font>, обязательны для заполнения!<br /><br />
    <a href="" id="aSourceFileSave" name="">Добавить файл</a>
    <a href="" class="aWysiwygClose" style="float:right;">Отмена</a>
</div>