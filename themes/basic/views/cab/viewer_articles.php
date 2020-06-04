<script language="javascript">
$("document").ready(function () {
    
});
</script>
<?foreach ($post as $art):?>
<?if ($dataTemp != $art['data']):
    $dataTemp = $art['data'];?>
    <div class="divData"><?=$art['userDate']?>&nbsp;&nbsp;&nbsp;</div>
<?endif;?>
<div class="divArticles b-row" id="divArticles_<?=$art['id']?>">
    <div class="b-row__title"><?=$art['title']?></div>
    <div class="b-row__tag"><?=$art['rubricator']['title']?></div>
    <div class="b-row__body"><?=strip_tags(substr($art['descr'],0,300))?></div>
    <?if($art['author']) {?><div class="b-row__author">Автор статьи: <span><?=$art['author']?></span></div><?}?>
    <span id="spFiles_<?=$art['id']?>"></span>
    <?/*foreach($art['source'] as $file):?>
        файл  
    <?endforeach;*/?>
</div>
<?endforeach;?>