<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
	<head>
		<title>{$meta_title|escape}</title>
		<meta name="keywords" content="{$meta_keywords|escape}"/> 
		<meta name="description" content="{$meta_description|escape}"/> 
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" type="text/css" href="/style/index.css"/>
		<script type="text/javascript" src="/script/jquery.js"></script>
		<script type="text/javascript" src="/script/interface.js"></script>
	</head>
	<body>
		<div class="main">
			<div class="outer">
				<div class="head">
{$bless} 
				</div>
				<div class="back">
					<a href="/">
						<img src="/image/space.gif" />
					</a>
				</div>
				<div class="inner">
					<div class="content">
						<table class="content">
							<tr>
								<td class="menu">
{$menu} 
								</td>
								<td class="content">
{$content} 
{$news} 
								</td>
								<td class="info">
{$info} 
								</td>
							</tr>
						</table>
					</div>
				</div>
				<div class="contact">
{$contact}
				</div>
				<div class="design">
					<a href="https://www.free-lance.ru/users/Mayber/" target="_blank">Дизайн - May Ber</a>
				</div>
			</div>
		</div>
	</body>
</html>
