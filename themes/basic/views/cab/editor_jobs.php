<script language="javascript">$("document").ready(function () {
    $('.aEdit').click(function() {
//        alert('-'+this.id+'-');
        $("#divJobs_"+this.id).load("/cab/getField",{
            type_id: this.name,
            form_edit: true,
        });
        return false;
    });
    /*$('.aAdd').click(function() {
        $("#divJobs_"+this.id).load("/cab/getField",{
            type_id: this.name,
            form_edit: true,
        });
        return false;
    });*/
    $(".aDel").click(function() {
        if (confirm('Удалить статью?')) {
            var divJobs = "#divJobs_"+this.id;
            $.post("/cab/delete", { 
                'element_id' : $(this).attr('name'),
                }, function(res) {
                    if (res == 'success') $(divJobs).remove();
            });
        }
        return false;
    });
});</script>

<div id="divJobs_0" class="divJobs">
    <a href="" class="aEdit aAdd" name="job_editor::new" id="0">Добавить новую вакансию</a>
</div>

<?foreach ($post as $job):?>
<br style="clear:both;" />

<?if ($dataTemp != $job['data']):
    $dataTemp = $job['data'];?>
    <div class="divData"><?=$job['userDate']?>&nbsp;&nbsp;&nbsp;</div>
<?endif;?>
<div class="divJobs b-row" id="divJobs_<?=$job['id']?>">
<div class="b-row_edit">
    <a class="b-button b-button_right aEdit" name="job_editor::<?=$job['id']?>" id="<?=$job['id']?>">Изменить</a>
    <div class="b-row__title">
	    <?=$job['fk_spec']['name']?>
	    <span class="b-row__salary"><?=$job['salary']?> руб.</span>
	</div>
    <a class="b-button b-button_right aDel aDelJob" id="<?=$job['id']?>" name="jobs::<?=$job['id']?>">удалить</a>
    
    <div class="b-row__body">
    	<span><?=$job['fk_towns']['name']?></span><br/>
	    <span>Опыт: <?=Jobs::model()->exp[$job['exp']]?></span>,
	    <span>пол: <?=Jobs::model()->male[$job['male']]?></span>,
	    <span>от <?=$job['ageTill']?"{$job['ageFrom']} до {$job['ageTill']}":$job['ageFrom']?> лет</span><br/>
	    <span>Образование <?=Jobs::model()->education[$job['education']]?></span>
    </div>
    <div class="b-row__body"><?=$job['comment']?></div>
<!--    <span class="b-row__separator">&nbsp;</span>-->
</div>
</div>
<?php /*?>
<div id="divJobs_<?=$job['id']?>" class="divJobs" style="padding:4px 10px;">
    <span><?=$job['fk_towns']['name']?></span>
    <span style="float:right;"><?=$job['salary']?> руб.</span><br style="clear:both;" />
    <span><?=$job['fk_spec']['name']?></span><br style="clear:both;" />
    <span><?=Jobs::model()->exp[$job['exp']]?></span>
    <span><?=Jobs::model()->male[$job['male']]?></span> 
    <span>от <?=$job['ageTill']?"{$job['ageFrom']} до {$job['ageTill']}":$job['ageFrom']?> лет</span>
    <span><?=Jobs::model()->education[$job['education']]?></span>
    <br style="clear:both;" />
    <span><?=$job['comment']?></span>
    <span style="float: right;">
        <a href="" class="aEdit" name="job_editor::<?=$job['id']?>" id="<?=$job['id']?>">Изменить</a> 
        <a href="" class="aDel" name="jobs::<?=$job['id']?>" id="<?=$job['id']?>">Удалить</a>
    </span>
</div>
*/?>
<?endforeach;?>