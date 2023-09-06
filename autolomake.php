<!DOCTYPE html>
<html>
<head>
	<title>Autolomake</title>
</head>
<body>
	<h4>Syötä auton tiedot:</h4>
	<form action="autolomake.php" method="post">
		<input type="text" name="rekisteri" placeholder="Rekisterinro"/><br><br>
		<input type="text" name="vari" placeholder="Väri"/><br><br>
		<input type="text" name="vuosimalli" placeholder="Vuosimalli"/><br><br>
		<select name="omistaja">
			<option value="281182-070W">Anne Autoilija</option>
			<!-- Lisää muita vaihtoehtoja tarvittaessa -->
		</select><br><br>
		<input type="submit" name="lisays" value="Lisää auto"><br><br>
	</form>
</body>
</html>

<?php
include("yhteys.php");

if (isset($_POST["lisays"])) {
    $rekisteri = $_POST["rekisteri"];
    $vari = $_POST["vari"];
    $vuosimalli = $_POST["vuosimalli"];
    $omistaja = $_POST["omistaja"];

    // Tarkista, onko rekisterinumero jo käytössä
    $tarkistusql = "SELECT * FROM auto WHERE rekisterinro = ?";
    $stmt = $yhteys->prepare($tarkistusql);
    $stmt->bind_param("s", $rekisteri);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Rekisterinumero on uniikki, joten lisätään auto tietokantaan
        $lisayssql = "INSERT INTO auto (rekisterinro, vari, vuosimalli, omistaja) VALUES (?, ?, ?, ?)";

        // Valmista ja suorita kysely valmistelulla lauseella
        $stmt = $yhteys->prepare($lisayssql);
        if ($stmt) {
            $stmt->bind_param("ssss", $rekisteri, $vari, $vuosimalli, $omistaja);
            $stmt->execute();
            $stmt->close();
            echo "Auto lisätty.";
        } else {
            echo "Virhe: " . $yhteys->error;
        }
    } else {
        echo "Rekisterinumero on jo käytössä.";
    }
}

$hakusql = "SELECT * FROM auto";

$tulokset = $yhteys->query($hakusql);

if ($tulokset->num_rows > 0) {
    while ($rivi = $tulokset->fetch_assoc()) {
        echo "Rekisterinumero: " . $rivi["rekisterinro"] . "<br>";
        echo "Väri: " . $rivi["vari"] . "<br>";
        echo "Vuosimalli: " . $rivi["vuosimalli"] . "<br>";
        echo "Omistaja: " . $rivi["omistaja"] . "<br> <br>";
    }
} else {
    echo "Ei tuloksia";
}

?>