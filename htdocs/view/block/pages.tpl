{if $count > $by_page}
{if $prev_page}
<a href="{$prev_page.link|escape}">&lt;</a>
{else}
<b>&lt;</b>
{/if}
{foreach from=$pages item=page}
{if $page.link}
<a href="{$page.link|escape}">{$page.number|default:"&hellip;"}</a>
{else}
<b>{$page.number}</b>
{/if}
{/foreach}
{if $next_page}
<a href="{$next_page.link|escape}">&gt;</a>
{else}
<b>&gt;</b>
{/if}
{/if}
