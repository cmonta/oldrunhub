<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/helpers.inc.php';
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Events list</title>
  </head>
  <body>
    <h1>Liste des évènements</h1>

    <p><a href="?add">Ajouter un évènement</a></p>

    <p>Evènements enregistrés</p>

    <?php foreach ($events as $event) { ?>

      <li>
	<form action="" method="post">
	  <div>
	    <td><?php htmlout($event['id']); ?></td>
	    <td><?php htmlout($event['location']); ?></td>
	    <td><?php htmlout($event['distance']); ?></td>
	    <td><?php htmlout($event['date']); ?></td>

	    <td>
	      <input type="hidden" name="id" value="<?php echo $event['id']; ?>">
	      <input type="submit" name="action" value="Edit">
	      <input type="submit" name="action" value="Delete">
	    </td>

	  </div>
	</form>
      </li>

    <?php } ?>
      <p><a href="..">Retournez à la page d’accueil</a></p>
      <?php include '../logout.inc.html.php'; ?>

  </body>
</html>