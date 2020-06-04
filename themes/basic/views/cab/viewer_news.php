<?foreach ($post as $news):?>
<?if ($dataTemp != $news['data']):
    $dataTemp = $news['data'];?>
    <div class="divData"><?=$news['userDate']?>&nbsp;&nbsp;&nbsp;</div>
<?endif;?>
<div class="divNews b-row" id="divNews_<?=$news['id']?>">
    <div class="b-row__tag" id="news::<?=$news['id']?>::rubricator_id"><?=$news['rubricator']['title']?></div>
    <div class="b-row__title" id="news::<?=$news['id']?>::title"><?=$news['title']?></div>
    <div class="b-row__body" id="news::<?=$news['id']?>::descr"><?=$news['descr']?></div>
</div>
<?endforeach;?>