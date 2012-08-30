<h1>Новости</h1>
{foreach from=$item_list item=item}
{$item.news_date} - <a href="{$item.news_url}">{$item.news_title|escape}</a>
{$item.news_announce}
{/foreach}
<a href="/news">К списку новостей</a>
