{{-- This is the template for all Mails sent to users (with a user account) --}}
@section("head")

@stop

@section("mailHeader")
<h2>@yield("heading")</h2>
<p>Hallo {{$userName}},</p>
@stop

@section("mailFooter")
<p>Liebe Grüße, Deine Biokiste</p>
<footer>Diese E-Mail wurde automatisch von biokiste.org versandt.</footer>
@stop