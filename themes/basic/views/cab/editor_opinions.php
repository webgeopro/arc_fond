<?if ($boolEdit): #Редактирование ?>
<script language="javascript">$("document").ready(function () {
    $(".aDbfOpinionsDel").click(function() {
        if (confirm('Вы действительно хотите удалить отзыв?')) {
            var divOpinion = "#divDbfOpinions_"+this.name;
            $.post("/cab/delete", {
                'element_id': "opinions::" + this.name,
                'cnt': 1,
                },function(data){
                    if ('success' == data.result) {
                        $(divOpinion).remove();
                        $("#aDbfOpinionsAll").text(" "+data.cnt+" ");
                    }
                }, 'json'
            );
        }
        return false;
    });
});
</script>
<?foreach ($opinions as $op):?>
    <div style="margin: 5px 5px;" id="divDbfOpinions_<?=$op['id']?>">
        <a href="/id<?=$op['owner_id']?>"><?=Yii::app()->getModule('user')->user($op['owner_id'])->username?></a><br />
        <?=$op['body']?>
        <br /><a href="" class="aDbfOpinionsDel" name="<?=$op['id']?>">удалить</a><br class="clear" />
    </div>
<?endforeach?>  
<?else:?>
<?foreach ($opinions as $op):?>
    <div style="margin: 5px 5px;">
        <a href="/id<?=$op['owner_id']?>"><?=Yii::app()->getModule('user')->user($op['owner_id'])->username?></a><br />
        <?=$op['body']?>
    </div>
<?endforeach?> 
<?endif?>