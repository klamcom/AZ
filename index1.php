<?php require "inc/db-connect.php"; ?>


<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Arbeitszeitaufzeichnung</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">    
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>
<body>
    <?php
        $stmt = $pdo->prepare('SELECT * FROM tblzeiten INNER JOIN tblkunden ON tblzeiten.fkKunde = tblkunden.KundenID INNER JOIN tbltaetigkeit ON tblzeiten.fkTaetigkeit = tbltaetigkeit.TaetigkeitID'); 
        $stmt->execute();  

        $stmt_kunden = $pdo->prepare("SELECT * FROM tblKunden");
        $stmt_kunden->execute();
        $kunden = $stmt_kunden->fetchAll(PDO::FETCH_ASSOC);

        $stmt_taetigkeiten = $pdo->prepare("SELECT * FROM tbltaetigkeit");
        $stmt_taetigkeiten->execute();
        $taetigkeiten = $stmt_taetigkeiten->fetchAll(PDO::FETCH_ASSOC);

    ?>

    <div class="container mt-3">
        <form action="" method="POST" class="mb-5">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="kundeFilter" class="form-label">Kunde</label>
                    <select class="form-select" id="kundeFilter" name="kundeFilter">
                        <option value="">Alle</option>
                        <?php foreach ($kunden as $kunde): ?>
                            <option value="<?php echo $kunde['KundenID']; ?>"><?php echo $kunde['KundenName']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="startDatum" class="form-label">Startdatum</label>
                    <input type="date" class="form-control" id="startDatum" name="startDatum">
                </div>
                <div class="col-md-4">
                    <label for="endeDatum" class="form-label">Enddatum</label>
                    <input type="date" class="form-control" id="endeDatum" name="endeDatum">
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Filtern</button>
        </form>
    </div>

    <?php
          if (isset($_POST['kundeFilter'])) {
            $kundenFilter = $_POST['kundeFilter'];
          } else {
              $kundenFilter = '';
          }
        
        if (isset($_POST['startDatum'])) {
            $startDatum = $_POST['startDatum'];
          } else {
              $startDatum = '';
          }
        
        if (isset($_POST['endeDatum'])) {
            $endeDatum = $_POST['endeDatum'];
          } else {
              $endeDatum = '';
          }

          $whereConditions = [];
          $params = [];

                if (!empty($kundenFilter)) {
                    $whereConditions[] = "tblzeiten.fkKunde = :kundeFilter";
                    $params[':kundeFilter'] = $kundenFilter;
                }

                if (!empty($startDatum) && !empty($endeDatum)) {
                    $whereConditions[] = "tblzeiten.Start BETWEEN :startDatum AND :endeDatum";
                    $params[':startDatum'] = $startDatum;
                    $params[':endeDatum'] = $endeDatum;
                } elseif (!empty($startDatum)) {
                    $whereConditions[] = "tblzeiten.Start >= :startDatum";
                    $params[':startDatum'] = $startDatum;
                } elseif (!empty($endeDatum)) {
                    $whereConditions[] = "tblzeiten.Ende <= :endeDatum";
                    $params[':endeDatum'] = $endeDatum;
                }

                $whereSql = !empty($whereConditions) ? ' WHERE ' . implode(' AND ', $whereConditions) : '';

                $query = "SELECT * FROM tblzeiten INNER JOIN tblkunden ON tblzeiten.fkKunde = tblkunden.KundenID INNER JOIN tbltaetigkeit ON tblzeiten.fkTaetigkeit = tbltaetigkeit.TaetigkeitID" . $whereSql;
                $stmt = $pdo->prepare($query);
                $stmt->execute($params);

    ?>

    <div class="container mt-5">
        <h2>Arbeitszeitaufzeichnung</h2>
        <table class="table table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ZeitenID</th>
                    <th>Kunde</th>
                    <th>Start</th>
                    <th>Ende</th>
                    <th>Taetigkeit</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo $row['ZeitenID']; ?></td>
                        <td><?php echo $row['KundenName']; ?></td>
                        <td><?php echo date("d.m.Y  H:i", strtotime($row['Start'])); ?></td>
                        <td><?php echo date("d.m.Y  H:i", strtotime($row['Ende'])) ?></td>
                        <td><?php echo $row['Taetigkeit']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Button zum aufrufen vom Modal -->
        <button type="button" class="btn btn-primary mt-5" data-bs-toggle="modal" data-bs-target="#beispielModal">
        Eintrag hinzufügen
        </button>
    </div>

    <div class="container mt-5">
    <a href="index3.php" class="btn btn-primary">Startseite mit Cards</a>
    </div>




<!-- Modal -->
<div class="modal fade" id="beispielModal" tabindex="-1" aria-labelledby="beispielModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="beispielModalLabel">Neuen Eintrag hinzufügen</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        </button>
      </div>
      <form action="insert.php" method="post">
        <div class="modal-body">
        <div class="mb-3">
            <label for="kunde" class="form-label">Kunde</label>
            <select class="form-select" id="kunde" name="kunde">
            <?php foreach ($kunden as $kunde): ?>
                <option value="<?php echo $kunde['KundenID']; ?>"><?php echo $kunde['KundenName']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
          <div class="form-group">
            <label for="datumstart">Start Datum/Zeit</label>
            <input type="datetime-local" class="form-control" id="datumstart" name="datumstart" required>
          </div>
          <div class="form-group">
            <label for="datumende">Ende Datum/Zeit</label>
            <input type="datetime-local" class="form-control" id="datumende" name="datumende" required>
          </div>
        <div class="mb-3">
            <label for="taetigkeiten" class="form-label">Tätigkeit</label>
            <select class="form-select" id="taetigkeiten" name="taetigkeiten">
            <?php foreach ($taetigkeiten as $taetigkeit): ?>
                <option value="<?php echo $taetigkeit['TaetigkeitID']; ?>"><?php echo $taetigkeit['Taetigkeit']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Schließen</button>
          <button type="submit" class="btn btn-primary">Speichern</button>
        </div>
      </form>
    </div>
  </div>
  </div>



<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>


</body>
</html>
