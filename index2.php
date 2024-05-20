<?php require "inc/db-connect.php"; ?>


<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Modal Formular Beispiel</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">    
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>
<body>
    <?php
        $stmt = $pdo->prepare('SELECT * FROM tblzeiten'); 
        $stmt->execute();  

        $stmt_kunden = $pdo->prepare("SELECT * FROM tblKunden");
        $stmt_kunden->execute();
        $kunden = $stmt_kunden->fetchAll(PDO::FETCH_ASSOC);

        $stmt_taetigkeiten = $pdo->prepare("SELECT * FROM tbltaetigkeit");
        $stmt_taetigkeiten->execute();
        $taetigkeiten = $stmt_taetigkeiten->fetchAll(PDO::FETCH_ASSOC);

    ?>

    <div class="container mt-5">
        <h2>Beispiel Tabelle</h2>
        <table class="table table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ZeitenID</th>
                    <th>fkKunde</th>
                    <th>Start</th>
                    <th>Ende</th>
                    <th>fkTaetigkeit</th>
                    <th>Aktion</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo $row['ZeitenID']; ?></td>
                        <td><?php echo $row['fkKunde']; ?></td>
                        <td><?php echo $row['Start']; ?></td>
                        <td><?php echo $row['Ende']; ?></td>
                        <td><?php echo $row['fkTaetigkeit']; ?></td>
                        
                        <td><button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#updateModal" onclick="setUpdateData(<?php echo $row['ZeitenID']; ?>, '<?php echo $row['fkKunde']; ?>', '<?php echo $row['Start']; ?>')">Bearbeiten</button>
                        <button type="button" class="btn btn-danger btn-sm deleteButton" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" data-id="<?php echo $row['ZeitenID']; ?>">Löschen</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Button zum aufrufen vom Modal -->
        <button type="button" class="btn btn-primary mt-5" data-bs-toggle="modal" data-bs-target="#beispielModal">
        Eintrag hinzufügen
        </button>
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

<!-- Update Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="update.php" method="post">
        <div class="modal-header">
          <h5 class="modal-title" id="updateModalLabel">Eintrag aktualisieren</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="updateId" name="id">
          <div class="form-group">
            <label for="updateName">Name</label>
            <input type="text" class="form-control" id="updateName" name="name" required>
          </div>
          <div class="form-group">
            <label for="updateEmail">E-Mail</label>
            <input type="email" class="form-control" id="updateEmail" name="email" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Schließen</button>
          <button type="submit" class="btn btn-primary">Aktualisieren</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Bestätigungsmodal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Eintrag löschen</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Sind Sie sicher, dass Sie diesen Eintrag löschen möchten?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
        <button type="button" class="btn btn-danger" id="deleteBtn">Löschen</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

<script>

function setUpdateData(id, name, email) {
  document.getElementById('updateId').value = id;
  document.getElementById('updateName').value = name;
  document.getElementById('updateEmail').value = email;
}

document.addEventListener("DOMContentLoaded", function() {
  var deleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'), {
    keyboard: false
  });
  var deleteBtn = document.getElementById('deleteBtn');

  document.querySelectorAll('.deleteButton').forEach(function(button) {
    button.addEventListener('click', function(event) {
      var id = button.getAttribute('data-id');
      deleteBtn.onclick = function() {
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = 'delete.php'; // Pfad zum PHP-Skript, das den Löschvorgang verarbeitet

        var hiddenField = document.createElement('input');
        hiddenField.type = 'hidden';
        hiddenField.name = 'id';
        hiddenField.value = id;

        form.appendChild(hiddenField);
        document.body.appendChild(form);
        form.submit();
      };
    });
  });
});

</script>

</body>
</html>
