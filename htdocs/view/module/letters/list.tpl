<h1>Записки паломника</h1>
{foreach from=$item_list item=item}
<h3>{$item.letters_date} - {$item.letters_title|escape}</h3>
{$item.letters_announce}
<a href="{$item.letters_url}">Далее</a>
{/foreach}
{$pages}