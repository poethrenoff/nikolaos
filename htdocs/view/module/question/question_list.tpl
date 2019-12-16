<script type="text/javascript">question_init()</script>

<h1>Гостевая</h1>
<form action="{$form_url}" method="post" class="question_form">
	<h3>Написать свое сообщение</h3>
	<input type="text" name="question_author" value="{$smarty.post.question_author|escape}" class="text" comment="Впишите свое имя" /><br/>
{if isset($error.question_author)}
	<div class="error">{$error.question_author|escape}</div>
{/if}
	<input type="text" name="question_email" value="{$smarty.post.question_email|escape}" class="text" comment="Оставьте свой email" /><br/>
{if isset($error.question_email)}
	<div class="error">{$error.question_email|escape}</div>
{/if}
	<textarea rows="5" cols="50" name="question_content" comment="А здесь напишите собственно, что вы хотели сказать">{$smarty.post.question_content|escape}</textarea><br/>
{if isset($error.question_content)}
	<div class="error">{$error.question_content|escape}</div>
{/if}
	<div class="captcha">
		<script src='https://www.google.com/recaptcha/api.js'></script>
		<div class="g-recaptcha" data-sitekey="{$recaptcha_public}"></div>
	</div>
	<div class="submit">
		<input type="submit" value="Отправить" class="button" />
	</div>
	<div class="image">
		&nbsp;
	</div>
{if isset($error.captcha_value)}
	<div class="error">{$error.captcha_value|escape}</div>
{/if}
	<input type="hidden" name="action" value="question"/>
</form>

<h3>Последние сообщения, оставленные в гостевой</h3>

{foreach item=question_item from=$question_list name=question_list}
<div class="question">
	<div class="question_title">
		{$question_item.question_date} - <b>{$question_item.question_author|escape}</b> пишет:
	</div>
	<div class="question_content">
		{$question_item.question_content|escape|nl2br}
	</div>
{if $question_item.question_answer}
	<div class="question_answer">
		{$question_item.question_answer|escape|nl2br}
	</div>
{/if}
</div>
{/foreach}

{$pages}
