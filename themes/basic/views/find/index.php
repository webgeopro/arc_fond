<h1><?#=$this->id . '/' . $this->action->id?></h1>
<h1>Страница поиска</h1>
<div id="divFindBreadcrumbs">
    <h2>Хлебные крошки</h2>
</div>
<div id="divFindSubCats">
    <h2>Блок подуровней</h2>
    <table id="tabFindSubCats">
        <?foreach ($data['subcats'] as $sub):?>
            <tr>
            <?foreach ($sub as $subSub):?>
                <td><?=$subSub[0]?> :: <?=$subSub[1]?></td>
            <?endforeach;?>
            </tr>
        <?endforeach;?>
    </table>
</div>
<div id="divFindContent">
    <h2>Блок Результатов поиска</h2>
    <table id="tabFindSubCats">
        
    </table>
</div>
<?=CVarDumper::dump($data);?>
<br /><br />
Tag = <?=$tag?>