<script language="javascript">
$("document").ready(function() {
    setInterval(getUnreadMessages, 20000);
    function getUnreadMessages()
    {
        $("#aDbfMessagesUnread").load('/cab/getCntMessages');
    }
});
</script>
<div id="divDbfMessage">
    непр. [<a href="" id="aDbfMessagesUnread" name="unread" title="Показать непрочитанные сообщения" class="<?=$cntUnread?'blink':'';?>"> <?=$cntUnread?> </a>]
    избр. [<a href="" id="aDbfMessagesFavorite" name="favorite" title="Показать избранные сообщения"> <?=$cntFavorite?> </a>]
    все [<a href="" id="aDbfMessagesAll" name="all" title="Показать все сообщения"> <?=$cntAll?> </a>]
    <a href="" id="aDbfMessagesHide" name="all" title="Скрыть сообщения"> скрыть </a>
</div>

<div id="divDbfMessagesList"></div>  