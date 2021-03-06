<!doctype HTML>
<html>
	<head>
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/can.custom.js"></script>
		<script type="text/javascript" src="js/bootstrap.min.js"></script>
		<script type="text/javascript">
			var sUrl = "{{$sUrl}}";
		</script>
	    <meta charset="utf-8">
    	<title>{{{$title}}}</title>
	
	    <!-- Sets initial viewport load and disables zooming  -->
	    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
	
	    <!-- Makes your prototype chrome-less once bookmarked to your phone's home screen -->
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	
	    <!--<link href="/bower_components/bootstrap/dist/css/bootstrap-theme.min.css" rel="stylesheet"> -->
	    <link href="css/bootstrap.min.css" rel="stylesheet">
	    <link href="css/app.css" rel="stylesheet">
	    <link href="css/webfont.css" rel="stylesheet">
	    <link href="css/calendar.css" rel="stylesheet">
	</head>
	<body>
		@yield("mustacheStart")
		@yield("header")
		
		<div class="navbar-fixed-bottom">
			<div class="container" id="flashContainer">
			</div>
		</div>		
		<div class="container">
			@yield("content")
		</div>

		@yield("mustacheEnd")
		@yield("dynamicScripts")
		<script type="text/javascript" src="js/app.js"></script>
		@yield("bottomJs")
	</body>
</html>