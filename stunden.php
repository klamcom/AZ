<?php
require "inc/db-connect.php";

$kundeFilter = isset($_POST['kundeFilter']) ? $_POST['kundeFilter'] : '';
$startDatum = isset($_POST['startDatum']) ? $_POST['startDatum'] : '';
$endeDatum = isset($_POST['endeDatum']) ? $_POST['endeDatum'] : '';

$sql = "
    SELECT SUM(TIMESTAMPDIFF(MINUTE, tblzeiten.Start, tblzeiten.Ende)) AS GesamtMinuten
    FROM tblzeiten
    INNER JOIN tblkunden ON tblzeiten.fkKunde = tblkunden.KundenID
    WHERE 1 = 1
";

if (!empty($kundeFilter)) {
    $sql .= " AND tblzeiten.fkKunde = :kundeFilter";
}
if (!empty($startDatum)) {
    $sql .= " AND tblzeiten.Start >= :startDatum";
}
if (!empty($endeDatum)) {
    $sql .= " AND tblzeiten.Ende <= :endeDatum";
}

$stmt = $pdo->prepare($sql);

if (!empty($kundeFilter)) {
    $stmt->bindParam(':kundeFilter', $kundeFilter);
}
if (!empty($startDatum)) {
    $stmt->bindParam(':startDatum', $startDatum);
}
if (!empty($endeDatum)) {
    $stmt->bindParam(':endeDatum', $endeDatum);
}

$stmt->execute();
$ergebnis = $stmt->fetch(PDO::FETCH_ASSOC);

$gesamtStunden = 0;
if ($ergebnis['GesamtMinuten']) {
    $gesamtStunden = $ergebnis['GesamtMinuten'] / 60;
}
?>

<div class="bg-info text-white p-3 rounded">
    <?php echo number_format($gesamtStunden, 2) . " Stunden"; ?>

