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
        if (confirm('Удалить новость?')) {
            var divNews = "#divNews_"+this.id;
            $.post("/cab/delete", { 
                'element_id' : $(this).attr('name'),
                }, function(res) {
                    if (res == 'success') $(divNews).remove();
            });
        }
        return false;
    });
    $(".aAdd").click(function() {
        if (confirm('Добавить новость?')) {
            $.post("/cab/add", { type_id: this.id } );
            $("#tabNews").click();
        }
        return false;
    });

});
</script>
<a href="" id="news" class="b-button aAdd">Добавить новость</a><br /><br />
<?foreach ($post as $news):?>
<?if ($dataTemp != $news['data']):
    $dataTemp = $news['data'];?>
    <div class="divData"><?=$news['userDate']?>&nbsp;&nbsp;&nbsp;</div>
<?endif;?>
<div class="divNews b-row b-row_edit" id="divNews_<?=$news['id']?>">
    <a class="b-button b-button_right aDel aDelNews" id="<?=$news['id']?>" name="news::<?=$news['id']?>">удалить</a>
    <div class="b-row__title spEditInPlace_area" id="news::<?=$news['id']?>::title" title="Кликните мышью для редактирования"><?=$news['title']?></div>
    <div class="b-row__tag spEditInPlace_sel" id="news::<?=$news['id']?>::rubricator_id" title="Кликните мышью для редактирования"><?=$news['rubricator']['title']?></div>
    <div class="b-row__body spEditInPlace_area" id="news::<?=$news['id']?>::descr" title="Кликните мышью для редактирования"><?=$news['descr']?></div>
</div>
<?endforeach;?>