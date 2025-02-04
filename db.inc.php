<?php

// CONNECTIE MAKEN MET DE DB
function connectToDB()
{
    // CONNECTIE CREDENTIALS
    $db_host = '127.0.0.1';
    $db_user = 'root';
    $db_password = 'root';
    $db_db = 'phpbasis';
    $db_port = 8889;

    try {
        $db = new PDO('mysql:host=' . $db_host . '; port=' . $db_port . '; dbname=' . $db_db, $db_user, $db_password);
    } catch (PDOException $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        die();
    }
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
    return $db;
}

function insertAfspraak(String $naam, String $email, String $datum): bool|int
{
    $db = connectToDB();
    $sql = "INSERT INTO afspraken(naam, email, datum) VALUES (:naam, :email, :datum)";
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':naam' => $naam,
        ':email' => $email,
        ':datum' => $datum
    ]);
    return $db->lastInsertId();
}

function datumPicked(String $datum): bool
{
    $sql = "SELECT datum FROM afspraken WHERE datum = :datum";
    $stmt = connectToDB()->prepare($sql);
    $stmt->execute([':datum' => $datum]);
    return $stmt->fetch(PDO::FETCH_COLUMN);
}

function getWeetje(String $url)
{
    $curl_handle = curl_init();

    curl_setopt($curl_handle, CURLOPT_URL, $url);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);

    $curl_data = curl_exec($curl_handle);
    curl_close($curl_handle);

    $response = json_decode($curl_data);

    if ($response === null)
        return false;

    return $response;
}
