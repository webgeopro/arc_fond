<?if (0 < count($opinions)):?>
    <form id="formMessageSend" name="formMessageSend" action="/cab/sendMessage" method="post">
        <div id="divDbfMessageError" style="color:red;"></div>
        <div id="divDbfOpinionsBox">
            <div id="aDbfOpinionsPrev">
                <a class="aScroll">
                    <img src="<?=Yii::app()->theme->baseUrl?>/images/cab/arrow_tri_l.png" width="11" height="22" />
                </a>
            </div>
            <div id="divDbfOpinions">
                <ul>
                    <?foreach($opinions as $op):?>
                    <li><?=$op['body']?></li>
                    <?endforeach?>
                </ul>
            </div>
            <div id="aDbfOpinionsNext">
                <a class="aScroll">
                    <img src="<?=Yii::app()->theme->baseUrl?>/images/cab/arrow_tri_r.png" width="11" height="22" />
                </a>
            </div>
        </div>
        <input type="hidden" name="pID" value="<?=$pageID?>" />
    </form>
    <div id="divDbfOpinionsView">
        <a href="" id="aDbfOpinionsAll" class="aFormSend" name="<?=$pageID?>">смотреть все [ <?=$cntOpinion?> ]</a>
    </div>
<?/*Количество символов (--<span id="spCntLetters"><?=$maxLetters?></span>):
<textarea id="areaDBFMessageBody" name="messageBody" <?#title="Оставьте обратный адрес в тексте сообщения."?>></textarea>
-->
<table id="tblDbfOpinions" cellpadding="0" cellspacing="0"><tr>
    <td></td>
    <td id="tdDbfOpinions"></td>
    <td><a class="aScroll" id="aDbfOpinionsNext">&nbsp;>&nbsp;</a></td>
</tr></table>
<span style="float:right;">смотреть все [<a href="" id="aDbfOpinionsAll" name="<?=$pageID?>"> <?=$cntOpinion?> </a>]</span>
*/?>
<?else:?>
<script language="javascript">$(document).ready(function() {
    $("#divOpinions").css('display','none');
});
</script>
<?endif?>