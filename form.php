<?php
require('db.inc.php');

$errors = [];
$submitted = false;

if (@$_POST['submit']) {
    $submitted = true;

    $naam = "";
    $email = "";
    $datum = "";

    if (!isset($_POST['naam'])) {
        $errors[] = "Gelieve je naam en voornaam in te vullen";
    } else {
        $naam = $_POST['naam'];

        if (strlen($naam) < 1) {
            $errors[] = "Naam en voornaam is verplicht.";
        }
        if (preg_match("/[^a-zA-Z]+(?:[\s.]+[a-zA-Z]+)*$/", $naam)) {
            $errors[] = "Je volledige naam mag geen speciale karakters bevatten en moet bestaan uit minstens twee woorden.";
        }
    }
    if (!isset($_POST['email'])) {
        $errors[] = "Gelieve je e-mailadres in te vullen";
    } else {
        $email = $_POST['email'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "E-mailadres klopt niet.";
        }
    }

    if (count($errors) == 0) { // er werden geen fouten geregistreerd tijdens validatie
        $return = insertAfspraak($naam, $email, $datum);
        header("Location: index.php?message=Afspraak wordt aangevraagd...");
        exit;
    }
}
$today = date('d-m-Y');
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aanvraagformulier</title>
    <link rel="stylesheet" href="https://unpkg.com/mvp.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <section>
        <form method="post" action="form.php">
            <header>
                <h2>Afspraak maken</h2>
            </header>
            <?php if (count($errors) > 0): ?>
                <div class="alert alert-danger" role="alert">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <label for="naam">Naam + Voornaam:</label>
            <input type="text" id="naam" name="naam" placeholder="Verboven Rudy">
            <label for="email">E-mailadres:</label>
            <input id="email" name="email" placeholder="Jouw e-mailadres...">
            <label for="datum">Datum afspraak:</label>
            <input id="datum" name="datum" type="date" />
            <select id="datum">
                <option value="test"><?= date($today, strtotime(' -1 day')); ?></option>
                <option value="test"><?= date($today, strtotime(' +1 day')); ?></option>
                <option value="datum1">donderdag 6 februari 2025</option>
                <option value="datum2">vrijdag 7 februari 2025</option>
                <option value="datum3">maandag 10 februari 2025</option>
                <option value="datum4">dinsdag 11 februari 2025</option>
                <option value="datum5">woensdag 12 februari 2025</option>
            </select>
            <button type="submit" value="submit" name="submit">Indienen</button>
        </form>
    </section>
</body>

</html>