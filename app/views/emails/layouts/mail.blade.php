{{-- This is the main template of an email --}}
<!DOCTYPE html>
<html lang="de-DE">
	<head>
		<meta charset="utf-8">
		@yield("head")
	</head>
	<body>
		@yield("mailHeader")
		<div class="content">
			@yield("content")
		</div>
		@yield("mailFooter")
	</body>
</html>