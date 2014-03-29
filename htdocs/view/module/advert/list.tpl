<h1>Объявления</h1>
{foreach from=$item_list item=item}
<h3>{$item.advert_date} - {$item.advert_title|escape}</h3>
{$item.advert_content}
{/foreach}
