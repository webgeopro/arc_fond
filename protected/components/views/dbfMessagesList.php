<script language="javascript">$("document").ready(function() {
    $(".aDbfMessageBodyRead").click(function(){
        var tdBody = "#tdDbfMessageBody_"+this.name;
        if ($(this).is(".unread")) { 
            $(this).removeClass('messageRed');
            if ($(tdBody+":first").is(":hidden")) {
                $.post("/cab/setMessage", { messageStatus: "read::"+this.name },
                    function(data){
                        if ('success' == data.result) {
                            $("#aDbfMessagesUnread").text(' '+data.cnt+' ');
                        }
                    }, "json"
                );
            }
        }
        $(tdBody).toggle();
        return false;
    });
    $(".aDbfMessageBodyFavorite").click(function(){
        var tdBody = "#tdDbfMessageBody_"+this.name;
        //if ($(this).is(".messageFavorite")) { 
            $.post("/cab/setMessage", { messageStatus: "favorite::"+this.name },
                function(data){
                    if ('success' == data.result) {
                        $("#aDbfMessagesFavorite").text(' '+data.cnt+' ');
                    }
                }, "json"
            );
        //}
        $(this).toggleClass('messageFavorite');
        return false;
    });
});</script>
<div id="divDbfMessagesList">
    <table cellpadding="0" cellspacing="0">
    <?foreach($messages as $m):?>
        <tr>
            <td class="tdDbfMessageAuthor">
	            <div class="b-messages__author">
	            <?if ($m['owner_id']):?>
	                <a href="/user/id<?=$m['owner_id']?>"><?=Yii::app()->getModule('user')->user($m['owner_id'])->username;?></a>
	            <?else:?>
	                Аноним
	            <?endif?>
	            </div>
	            <div class="b-messages__date"><?=$m['data']?></div>
            </td>
            <td class="tdDbfMessageDate">
                <a href="" class="aDbfMessageBodyRead <?=(($unread == $m['status']) or ($favoriteUnread == $m['status']))?'unread messageRed':'';?>" name="<?=$m['id']?>">
                    читать
                </a> &nbsp;
                <?if ($favorite == $m['status'] or $favoriteUnread == $m['status']):?>
                <a href="" class="aDbfMessageBodyFavorite messageFavorite" name="<?=$m['id']?>" title="Удалить из избранного">&#x2605;
<!--                    x-->
                </a>
                <?else:?>
                <a href="" class="aDbfMessageBodyFavorite" name="<?=$m['id']?>" title="Добавить в избранное">&#x2605;
<!--                    +-->
                </a>
                <?endif;?>
            </td>
        </tr>
        <tr class="tdDbfMessageBody" id="tdDbfMessageBody_<?=$m['id']?>">
            <td colspan="2"><?=$m['body']?></td>
        </tr>
    <?endforeach?>
    </table>
</div>