<script language="javascript">
$("document").ready(function () {
//    $(".spEditInPlace_procent").editInPlace({
//        url: "/cab/inPlaceSave",
//        bg_over: "#F6EBEC",
//        field_type: "text",
//        saving_image: "<?=Yii::app()->theme->baseUrl?>/images/ajax_loader.gif",
//        on_blur: "save"
//    });
    $(".spEditInPlace_procent").editInPlace({
        url: "/cab/inPlaceSave",
        bg_over: "#F6EBEC",
        field_type: "text",
        saving_image: "<?=Yii::app()->theme->baseUrl?>/images/ajax_loader.gif",
        on_blur: "save"
    });
    $(".spEditInPlace_text").editInPlace({
        url: "/cab/inPlaceSave",
        bg_over: "#F6EBEC",
        field_type: "text",
        saving_image: "<?=Yii::app()->theme->baseUrl?>/images/ajax_loader.gif",
        on_blur: "save"
    });
    
    $(".aDelDiscount").click(function() {
        if (confirm('Удалить скидку?')) {
            var divDiscount = "#divDiscount_"+this.id;
            $.post("/cab/delete", { 
                'element_id' : $(this).attr('name'),
                }, function(res) {
                    if (res == 'success') $(divDiscount).remove();
            });
        }
        return false;
    });
    $(".aAdd").click(function() {
        if (confirm('Добавить скидку?')) {
            $.post("/cab/add", { type_id: this.id } );
            $("#tabDiscounts").click();
        }
        return false;
    });
    $(".divEditInPlace_wysiwyg").click(function() {
        $("#preloader").show();
        $(this).load("/cab/getField", { type_id: this.id } );
        $("#preloader").hide();
        /*$.ajax({
            type: "POST",
            data: "type_id="+$(this).attr('lang'),
            url: "/cab/getField", 
            cache: false,
            success: function(message) {			            	
			    $(this).empty().append(message);
			    $("#preloader").hide();             
            }
        });*/			        
        return false;
    });

});
</script>
<a href="" id="discounts" class="b-button aAdd">Добавить новую скидку</a><br /><br />
<?foreach ($post as $dis):?>
<?if ($dataTemp != $dis['data']):
    $dataTemp = $dis['data'];?>
    <div class="divData"><?=$dis['userDate']?>&nbsp;&nbsp;&nbsp;</div>
<?endif;?>
<div class="divDiscounts b-row b-row_edit" id="divDiscount_<?=$dis['id']?>">
    <a class="b-button b-button_right aDel aDelDiscount" id="<?=$dis['id']?>" name="discounts::<?=$dis['id']?>">удалить</a>
    <div class="b-row__icon b-row__icon_discount"><span class="spEditInPlace_procent" id="discounts::<?=$dis['id']?>::procent" title="Кликните мышью для редактирования"><?=$dis['procent']?></span>%</div>
    <div class="b-row__title spEditInPlace_text" id="discounts::<?=$dis['id']?>::title" title="Кликните мышью для редактирования"><?=$dis['title']?></div>
    <div class="b-row__body divEditInPlace_wysiwyg" id="discount_editor::<?=$dis['id']?>::descr" title="Кликните мышью для редактирования"><?=$dis['descr']?></div>
    <span class="b-row__separator">&nbsp;</span>
</div>
<?endforeach;?>