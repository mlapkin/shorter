<!doctype html>
<html>
<head>
	<meta charset="utf-8"/>
	<link href="/files/css/testtask-styles.css?data=26.07.2012" rel="stylesheet">
	<title>XIAG test task</title>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
</head>
<body>
<div class="content">
	<header>URL shortener</header>

	<?php if ($this->error): ?>
	<div class="error"><?=$this->error?></div>
	<?php endif; ?>

	<form method="post" id="form">
		<table>
			<tr>
				<th>Long URL</th>
				<th>Short URL</th>
			</tr>
			<tr>
				<td>
					<input type="url" name="url">
					<input type="submit" value="Do!">
				</td>
				<td id="result"></td>
			</tr>
		</table>
	</form>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$('#form').submit(function(e){
			$('#result').html('');
			$.ajax(
				{
					url : this.action ? this.action : window.location,
					type: 'POST',
					data : $(this).serializeArray(),
					success: function(data)
					{
						if (data.result)
						{
							var link = document.createElement('a');
							link.setAttribute('href', data.url);
							link.setAttribute('target', '_blank');
							link.appendChild(document.createTextNode(data.url));
							$('#result').html(link);
						}
						else if (data.message)
						{
							$('#result').html(data.message);
						}
					},
					error: function()
					{
						alert('Failed to shorten URL, try again');
					}
				});
			e.preventDefault();
		});	});
</script>
</body>
</html>