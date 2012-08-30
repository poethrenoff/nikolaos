<link rel="stylesheet" type="text/css" href="/script/sexy-lightbox/sexylightbox.css" />
<script type="text/javascript" src="/script/sexy-lightbox/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="/script/sexy-lightbox/sexylightbox.v2.3.jquery.js"></script>
<script type="text/javascript">photo_init()</script>

<h1>Фотогалерея</h1>
{foreach item=album_item from=$album_list name=album_list}
<div class="photo">
{if $album_item.photo_list}
	<h3>{$album_item.album_title|escape}</h3>
{if $album_item.album_comment}
	<p{if !$smarty.foreach.album_list.first} style="display: none"{/if}>{$album_item.album_comment|escape}</p>
{/if}
{foreach item=photo_item from=$album_item.photo_list}
	<div class="item"{if !$smarty.foreach.album_list.first} style="display: none"{/if}>
		<a href="{$photo_item.photo_image}" rel="sexylightbox[{$photo_item.photo_album}]"><img src="{$photo_item.photo_preview}" /></a>
	</div>
{/foreach}
{/if}
	<br class="clear" />
</div>
{/foreach}
