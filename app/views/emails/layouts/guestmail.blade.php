{{-- This is the template for all Mails sent to guests of the page (not logged-in members) --}}
@section("head")

@stop

@section("mailHeader")
<h2>@yield("heading")</h2>
@stop

@section("mailFooter")
<footer>Diese E-Mail wurde automatisch von biokiste.org versandt.</footer>
@stop