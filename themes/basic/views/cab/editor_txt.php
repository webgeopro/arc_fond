<?=$additional?>
<?php 
	echo CHtml::activeTextArea($post, $field, array('rows'=>6, 'cols'=>60));
	$this->widget('application.extensions.editor.editor', array('name'=>$name, 'type'=>'fckeditor', 'height'=>$height));
?>
<a id="inpEditorSave" class="aSave" href="">Сохранить</a>
<a id="inpEditorCancel" class="aCancel" href="">Отменить</a>
<div style="clear:both">&nbsp;</div>
<script language="javascript">
$("document").ready(function () { 
    $("#inpEditorSave").click(function (){
        var fck = FCKeditorAPI.GetInstance("<?=$name?>")
        $.post(
			"/cab/wysiwygSave", 
			{ 
	            element_id: "<? echo $element_id?>", 
	            content: fck.GetHTML() 
			}, 
			function (data) {
				if(data.result == 'success') {
					alert('Данные успешно сохранены');
					$("#<?=$tabID?>").click();
				}
				else {
					if(data.errors && data.errors.length > 0) {
						var str = 'При сохранении возникли ошибки:\n';
						for(index in data.errors) {
							str += data.errors[index] + '\n';
						}
						alert(str);
					}
				}
				return false;
			},
			'json'
		);
        return false;
    });
    $("#inpEditorCancel").click(function (){
        $("#<?=$tabID?>").click();
        return false;
    });
});
</script>