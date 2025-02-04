<?php
require('db.inc.php');

$errors = [];
$submitted = false;
$weetje = "";


if (@$_POST['submit']) {
    $submitted = true;

    $naam = "";
    $email = "";
    $datum = null;
    $weetje = "";

    if (!isset($_POST['naam'])) {
        $errors[] = "Gelieve je naam en voornaam in te vullen";
    } else {
        $naam = $_POST['naam'];

        if (strlen($naam) < 1) {
            $errors[] = "Naam en voornaam is verplicht.";
        }
        if (preg_match("/[^a-zA-Z\s-]+/", $naam)) {
            $errors[] = "Je naam mag geen speciale karakters bevatten, en moet (een) spatie(s) bevatten.";
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

    if (!isset($_POST['datum'])) {
        $errors[] = "Gelieve een datum te kiezen.";
    } else {
        $datum = $_POST['datum'];
        $old_date_timestamp = strtotime($datum);
        $month = date('m', $old_date_timestamp);
        $day = date('d', $old_date_timestamp);
        $weetje = getWeetje('http://numbersapi.com/' . $month . '/' . $day . '/date');
        if (datumPicked($datum) == true) {
            $errors[] = "Datum is niet vrij. Gelieve een andere datum te kiezen.";
        }
    }

    if (count($errors) == 0) {
        $return = insertAfspraak($naam, $email, $datum);
        header('Location: form.php?message=Afspraak wordt verwerkt...');
        exit;
    }
}
// $today = date('l d-m-Y');
// print '<pre>';
// print_r($_POST);
// print '</pre>';

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
            <?php if (isset($_GET["message"])): // zit er een message in mijn GET array? 
            ?>
                <div class="p-3 text-success-emphasis bg-success-subtle border border-success-subtle rounded-3">
                    <?= $_GET["message"]; ?>
                </div>
                <p><?= $weetje; ?></p>
            <?php endif; ?>

            <label for="naam">Naam + Voornaam:</label>
            <input type="text" id="naam" name="naam" placeholder="Verboven Rudy" value="<?= @$naam ?>">
            <label for="email">E-mailadres:</label>
            <input id="email" name="email" placeholder="Jouw e-mailadres..." value="<?= @$email ?>">
            <label for="datum">Datum afspraak:</label>
            <!--<input id="datum" name="datum" type="date" /> -->
            <select id="datum" name="datum">
                <?php
                //datum van $vandaag declareren
                //forloop: twee dagen vanaf $vandaag (+2)
                //tem 100dagen verder
                //datum in date('l d F Y') format
                //=datum van $vandaag+ dagen in seconden gebruiken!!
                //w gebruiken voor dagnotatie, zondag is 0!
                //dag mag dus niet 0 of 6 zijn
                $vandaag = time();
                for ($i = 2; $i <= 100; $i++) {
                    $datum = date('l d F Y', $vandaag + (86400 * $i));
                    $dag = date('w', $vandaag + (86400 * $i));
                    if ($dag != 0 && $dag != 6) {
                ?>
                        <option value="<?= $datum; ?>"><?= $datum; ?></option>
                <?php
                    }
                }
                ?>
            </select>
            <button type="submit" value="submit" name="submit">Indienen</button>
        </form>
    </section>
</body>

</html>