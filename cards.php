<?php
require "inc/db-connect.php"; 

$kundenFilter = isset($_POST['kundeFilter']) ? $_POST['kundeFilter'] : '';
$startDatum = isset($_POST['startDatum']) ? $_POST['startDatum'] : '';
$endeDatum = isset($_POST['endeDatum']) ? $_POST['endeDatum'] : '';

$sql = "SELECT tblzeiten.*, tblkunden.KundenName, tblkunden.Farbe, tbltaetigkeit.Taetigkeit
          FROM tblzeiten
          INNER JOIN tblkunden ON tblzeiten.fkKunde = tblkunden.KundenID
          INNER JOIN tbltaetigkeit ON tblzeiten.fkTaetigkeit = tbltaetigkeit.TaetigkeitID";

$bedingungen = [];
$params = [];

if (!empty($kundenFilter)) {
    $bedingungen[] = "tblzeiten.fkKunde = :kundeFilter";
    $params[':kundeFilter'] = $kundenFilter;
}

if (!empty($startDatum)) {
    $bedingungen[] = "tblzeiten.Start >= :startDatum";
    $params[':startDatum'] = $startDatum;
}

if (!empty($endeDatum)) {
    $bedingungen[] = "tblzeiten.Ende <= :endeDatum";
    $params[':endeDatum'] = $endeDatum;
}

if (count($bedingungen) > 0) {
    $sql .= " WHERE " . implode(' AND ', $bedingungen);
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

echo '<div class="row">';

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<div class='col-md-4 mb-4'>";
    echo "<div class='card' style='background-color:" . $row['Farbe'] . "; color: #FFFFFF;'>";
    echo "<div class='card-body'>";
    echo "<h5 class='card-title'>" . $row['KundenName'] . "</h5>";
    echo "<p class='card-text'>Start: " . date("d.m.Y H:i", strtotime($row['Start'])) . "</p>";
    echo "<p class='card-text'>Ende: " . date("d.m.Y H:i", strtotime($row['Ende'])) . "</p>";
    echo "<p class='card-text'>Tätigkeit: " . $row['Taetigkeit'] . "</p>";
    echo "</div></div></div>";
}

echo '</div>'; 
