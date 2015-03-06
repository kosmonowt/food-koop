@section("bottomJs")
		<script type="text/javascript" src="js/{{{$controller}}}.js"></script>
		<script type="text/javascript">
		$('#tabNav a').click(function (e) {
  			e.preventDefault();
  			$(this).tab('show');
  		});
  		</script>
@stop