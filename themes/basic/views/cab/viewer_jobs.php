<?foreach ($post as $job):?>

<?if ($dataTemp != $job['data']):
    $dataTemp = $job['data'];?>
    <div class="divData"><?=$job['userDate']?>&nbsp;&nbsp;&nbsp;</div>
<?endif;?>

<div id="divJobs_<?=$job['id']?>" class="divJobs b-row">
	<div class="b-row__title"><?=$job['fk_spec']['name']?> <span class="b-row__salary"><?=$job['salary']?> руб.</span></div>
	<div class="b-row__body">
    	<span><?=$job['fk_towns']['name']?></span><br/>
	    <span>Опыт: <?=Jobs::model()->exp[$job['exp']]?></span>,
	    <span>пол: <?=Jobs::model()->male[$job['male']]?></span>,
	    <span>от <?=$job['ageTill']?"{$job['ageFrom']} до {$job['ageTill']}":$job['ageFrom']?> лет</span><br/>
	    <span>Образование <?=Jobs::model()->education[$job['education']]?></span>
    </div>
    <div class="b-row__body"><?=$job['comment']?></div>
</div>

<?endforeach;?>