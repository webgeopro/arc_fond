<div id="tabsJ">
    <input type="hidden" id="pID" value="<?=$pageID?>" />
<!--    <a href="" id="aLeft" style="float: left; margin-top: 10px;"><<<</a>-->
	<a href="" id="aLeft">&nbsp;</a>
    <?if (is_array($tabs)): foreach($tabs as $i=>$tab):
        $cntLetters += strlen($tab['title']);
        if ( $cntLimit <= $cntLetters ): $cntLetters=0; $cntUl++; #if ( !($cnt % 3) and $cnt ): $cntUl++;?>
        </ul><ul class="items item_hidden" id="ul_<?=$cntUl?>" cntUl="<?=$cntUl?>">
        <?elseif (0 == $cnt): $cntUl++;?>
        <input type="hidden" id="ulMin" value="<?=$cntUl?>" />
        <ul class="items item_selected" id="ul_<?=$cntUl?>" cntUl="<?=$cntUl?>"> 
        <?endif;?>
            <li>
            <?if (!empty($tab['userTabID'])):?>
                <a id="tabs_<?=$tab['id']?>" href="" pageURL="<?=$tab['url']?>" userTabID="<?=$tab['userTabID']?>">
            <?elseif (!empty($tab['id'])):?>
                <a id="<?=$tab['id']?>" href="" pageURL="<?=$tab['url']?>">
            <?else:?>
                <a id="tabJ<?=($i+1)?>" href="" pageURL="<?=$tab['url']?>">
            <?endif;?>
                    <span><?=$tab['title']?></span>
                </a>
            </li>
        <?$cnt++; endforeach;endif;?>
    </ul>
    <?if ( (!Yii::app()->user->isGuest) and ($pageID == Yii::app()->user->getId()) ):?>
    <a href="" id="aNewTab" title="Новая закладка">+&nbsp;</a>
    <?endif;?>
<!--    <a href="" id="aRight" style="float: right;">>>></a>-->
    <a href="" id="aRight">&nbsp;</a>
    <input type="hidden" id="ulMax" value="<?=$cntUl?>" />
</div>
<div id="preloader"><?=CHtml::image(Yii::app()->theme->baseUrl.'/images/ajax_loader.gif') ?>Загрузка...</div>
<div id="tabcontent">
    <?#Текст, видимый при первой загрузке. Возможна справочная информация.?>
    <?=$firstContent?>
</div>
<div id="divNewTab">
    <label>Имя закладки:</label><input id="inpNewTab" /><br style="clear:both;" />
    <a href="" id="aNewTabCancel" class="b-button aCancel">отмена</a>
    <a href="" id="aNewTabAdd" class="b-button aAdd">добавить</a>
</div>    
<script type="text/javascript">
    var cntUl = <?=$cntUl?$cntUl:0;?>;
    if (1 == cntUl) {
        $("#aRight").addClass('a_disabled')
    }
    $("#aLeft").addClass('a_disabled')	
</script>