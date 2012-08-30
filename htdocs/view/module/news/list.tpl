<h1>Новости</h1>
{foreach from=$item_list item=item}
<h3>{$item.news_date} - {$item.news_title|escape}</h3>
{$item.news_announce}
<a href="{$item.news_url}">Далее</a>
{/foreach}
{$pages}