<div class="menu">
	{foreach from=$menu_tree item=menu_item}
	<div class="level{$menu_item._depth}">
		<a{if $menu_item._selected} class="selected"{/if} href="{$menu_item.menu_url}">{$menu_item.menu_title|escape}</a>
	</div>
	{/foreach}
</div>
