<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/helpers.inc.php';
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Members list</title>
  </head>
  <body>
    <h1>Liste des membres</h1>

    <p><a href="?add">Ajouter un membre</a></p>

    <p>Membres inscrits sur le site</p>


    <!-- Tableau à refaire -->

    <?php foreach ($members as $member) { ?>

      <li>
	<form action="" method="post">
	  <div>

	    <td> <?php htmlout($member['id']); ?></td>
	    <td> <?php htmlout($member['name']); ?></td>
	    <td> <?php htmlout($member['email']); ?></td>

	    <td>
	      <input type="hidden" name="id" value="<?php echo $member['id']; ?>">
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