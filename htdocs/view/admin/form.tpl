<form id="card" action="{$form_url}" method="post" enctype="multipart/form-data" onsubmit="return form_submit( this )">
	<table class="record">
		<tr>
			<td colspan="2" align="left">
				<b>{$record_title}</b><br/>{$action_title}
			</td>
		</tr>
		<tr>
			<td colspan="2" align="right">
				<input type="button" value="Вернуться" class="button" onclick="location.href = '{$back_url}'"/>
			</td>
		</tr>
{foreach from=$fields key=name item=field}
		<tr>
			<td class="title">
				{$field.title}{if $field.require}<span class="require">*</span>{/if}:
			</td>
			<td class="field">
{if $field.type === 'boolean' || $field.type === 'active' || $field.type === 'default'}
				<input type="checkbox" name="{$name}" value="1" class="check" errors="{$field.errors}"{if $field.value === "1"} checked="checked"{/if}/>
{elseif $field.type === 'select' || $field.type === 'table' || $field.type === 'parent'}
				<select name="{$name}" class="list" errors="{$field.errors}">
					<option value=""/>
{foreach from=$field.values item=option}
					<option value="{$option.value}"{if $option.value === $field.value} selected="selected"{/if}>{section name=offset start=0 loop=$option._depth}&nbsp;&nbsp;&nbsp;{/section}{$option.title}</option>
{/foreach}
				</select>
{elseif $field.type === 'date' || $field.type === 'datetime'}
				<table class="date">
					<tr>
						<td>
							<input type="text" name="{$name}" value="{$field.value}" class="date" errors="{$field.errors}"/>
						</td>
						<td>
							<a href="" onclick="Calendar.show( document.forms['card']['{$name}'], this, '{if $field.type === 'date'}short{else}long{/if}' ); return false">
								<img src="/admin/image/calendar/calendar.gif" alt=""/>
							</a>
						</td>
						<td>
							<a href="" onclick="Calendar.now( document.forms['card']['{$name}'], '{if $field.type === 'date'}short{else}long{/if}' ); return false">
								<img src="/admin/image/calendar/check.gif" alt=""/>
							</a>
						</td>
					</tr>
				</table>
{elseif $field.type === 'image' || $field.type === 'file'}
				<table class="file">
					<tr>
						<td>
							<input type="file" name="{$name}_file" class="file" size="70%"/>
						</td>
					</tr>
					<tr>
						<td>
							<input type="text" name="{$name}" value="{$field.value}" class="link"/>
						</td>
					</tr>
				</table>
{elseif $field.type === 'text'}
{if $field.translate}
{foreach from=$lang_list item=lang}
				<div class="lang">{$lang.lang_name}</div>
				<textarea name="{$name}[{$lang.lang_id}]" cols="" rows=""{if $field.editor} class="ckeditor"{/if} errors="{$field.errors}">{$field.value[$lang.lang_id]}</textarea>
				<div class="padding"></div>
{/foreach}
{else}
				<textarea name="{$name}" cols="" rows=""{if $field.editor} class="ckeditor"{/if} errors="{$field.errors}">{$field.value}</textarea>
{/if}
{else}
{if $field.translate}
{foreach from=$lang_list item=lang}
				<div class="lang">{$lang.lang_name}</div>
				<input type="text" name="{$name}[{$lang.lang_id}]" value="{$field.value[$lang.lang_id]}" class="text" errors="{$field.errors}"/>
				<div class="padding"></div>
{/foreach}
{else}
				<input type="text" name="{$name}" value="{$field.value}" class="text" errors="{$field.errors}"/>
{/if}
{/if}
			</td>
		</tr>
{/foreach}
		<tr>
			<td colspan="2">
				<input type="submit" value="Сохранить" class="button"/>
			</td>
		</tr>
	</table>
</form>
{if $scripts}
<script type="text/javascript">
$(function(){ldelim}
{foreach from=$scripts key=function_name item=function_params}
	{$function_name}({$function_params});
{/foreach}
{rdelim});
</script>
{/if}
