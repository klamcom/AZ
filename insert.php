<?php
require "inc/db-connect.php";

if (!empty($_POST)) {

    $kunde = '';
    if (isset($_POST['kunde'])) {
        $kunde = $_POST['kunde'];

    }
    $datumstart = '';
    if (isset($_POST['datumstart'])) {
        $datumstart = $_POST['datumstart'];
    }

    $datumende = '';
    if (isset($_POST['datumende'])) {
        $datumende = $_POST['datumende'];
    }

    $taetigkeiten = '';
    if (isset($_POST['taetigkeiten'])) {
        $taetigkeiten = $_POST['taetigkeiten'];
    }


    if (!empty($kunde) && !empty($datumstart) && !empty($datumende) && !empty($taetigkeiten)) {
        
            $stmt = $pdo->prepare('INSERT INTO tblzeiten (fkKunde, Start, Ende, fkTaetigkeit) VALUES (:kunde, :datumstart, :datumende, :taetigkeiten)'); 
    
            $stmt->bindValue('kunde', $kunde);
            $stmt->bindValue('datumstart', $datumstart);
            $stmt->bindValue('datumende', $datumende);
            $stmt->bindValue('taetigkeiten', $taetigkeiten);
            $stmt->execute();
    
        }
      
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }
    ?>

