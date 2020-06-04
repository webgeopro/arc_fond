<?foreach ($post as $dis):?>
<?if ($dataTemp != $dis['data']):
    $dataTemp = $dis['data'];?>
    <div class="divData"><?=$dis['userDate']?>&nbsp;&nbsp;&nbsp;</div>
<?endif;?>
<div class="divDiscounts  b-row" id="divDiscount_<?=$dis['id']?>">
    <div class="b-row__icon b-row__icon_discount"><?=$dis['procent']?>%</div>
    <div class="b-row__title"><?=$dis['title']?></div>
    <div class="b-row__body"><?=$dis['descr']?></div>
    <span class="b-row__separator">&nbsp;</span>    
</div>
<?endforeach;?>