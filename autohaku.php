<!DOCTYPE html>
<html>
<head>
	<title>Autohaku</title>
</head>
<body>
	<h4>Syötä rekisterinumero:</h4>
	<form action="autohaku.php" method="post">
		<input type="text" name="rekisterinro" /><br><br>
		<input type="submit" name="hae" /><br><br>
	</form>

	<?php
	include("yhteys.php"); // Varmista, että tämä tiedosto sisältää oikeat tietokantayhteystiedot

	if (isset($_POST["hae"])) {
		$haettuTeksti = $_POST["rekisterinro"];

		if (!empty($haettuTeksti)) {
			// Suorita haku, jos hakukenttä ei ole tyhjä
			$hakusql = "SELECT * FROM auto WHERE rekisterinro LIKE '%" . $haettuTeksti . "%'";
		} else {
			// Näytä kaikki autot, jos hakukenttä on tyhjä
			$hakusql = "SELECT * FROM auto";
		}

		$tulokset = $yhteys->query($hakusql);

		if ($tulokset->num_rows > 0) {
			while ($rivi = $tulokset->fetch_assoc()) {
				echo "Rekisterinumero: " . $rivi["rekisterinro"] . "<br>";
				echo "Väri: " . $rivi["vari"] . "<br>";
				echo "Vuosimalli: " . $rivi["vuosimalli"] . "<br>";
				echo "Omistaja: " . $rivi["omistaja"] . "<br><br>";
			}
		} else {
			echo "Ei tuloksia";
		}
	}
	?>


	
	</body>
</html>