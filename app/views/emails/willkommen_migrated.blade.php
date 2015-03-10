<!DOCTYPE html>
<html lang="de-DE">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Willkommen im neuen Portal der Biokiste, {{$user["firstname"]}}</h2>

		<div>
			<p>Lieber {{$user['firstname']}},<br>
			Du bekommst diese E-Mail weil Du Mitglied in einer Bestellgruppe der Biokiste bist.</p>
			<p>Dank viel Ehrenamtlicher Arbeit ist nun die Website biokiste.org auf die aktuellen Bedürfnisse und den neusten Stand der Technik aktualisiert worden.</p>
			<p><strong>Von nun an hat jedes Mitglied einer Bestellgruppe einen eigenen Benuterzugang mit speziellen Rechten.</strong> Falls Deine Mitbewohner / Partner oder andere Gemeinschaftsgefährten keine E-Mail bekommen haben müsstest Du sie noch registrieren.</p>
			<p>HIER KOMMT NOCH DIE MITGLIEDERLISTE REIN</p>
			<p>LOGGE DICH HIER EIN:</p>
			<p>Wir mussten Dein Passwort (wahrscheinlich) ändern. Es lautet nun: {{$password}}</p>
			<p>Der Login ist {{$user['email']}}.</p>
			<p>Liebe Grüße und viel Freude!</p>
			<p>Deine Biokiste</p>
		</div>
	</body>
</html>
