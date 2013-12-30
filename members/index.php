
<?php

include_once $_SERVER['DOCUMENT_ROOT'] .  '/includes/magicquotes.inc.php';

// Vérifie si l'utilisateur a les habilitations

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/access.inc.php';

if (!userIsLoggedIn())
{
  include '../login.html.php';
  exit();
}

if (!userHasRole('Account Administrator'))
{
  $error = 'Only Account Administrators may access this page.';
  include '../accessdenied.html.php';
  exit();
}


// Affiche le formulaire pour ajouter un membre

if (isset($_GET['add']))
{
  $pageTitle = 'Nouveau membre';
  $action = 'addform';
  $name = '';
  $email = '';
  $id = '';
  $button = 'Ajout membre';

  include 'form.html.php';
  exit();
}

// Traite l'action du formulaire, ajoute un nouveau membre

if (isset($_GET['addform']))
{
  include $_SERVER['DOCUMENT_ROOT'] . '/includes/dbrunhub.inc.php';

  try
  {
    $sql = 'INSERT INTO member SET name = :name, email = :email';
    $s = $pdo->prepare($sql);
    $s->bindvalue(':name', $_POST['name']);
    $s->bindvalue(':email', $_POST['email']);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error adding submitted member.';
    include 'error.html.php';
    exit();
  }

  // renvoie à la page d'accueil
  header('Location: .');
  exit();

}


// Charge la page pour éditer les membres

if (isset($_POST['action']) and $_POST['action'] == 'Edit')
{
  include $_SERVER['DOCUMENT_ROOT'] . '/includes/dbrunhub.inc.php';

  try
  {
    $sql = 'SELECT id, name, email FROM member WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching member details.';
    include 'error.html.php';
    exit();
  }

  $row = $s->fetch();

  $pageTitle = 'Editer membre';
  $action = 'editform';
  $name = $row['name'];
  $email = $row['email'];
  $id = $row['id'];
  $button = 'Mettre à jour';

  include 'form.html.php';
  exit();

}

// Edite les membres

if (isset($_GET['editform']))
{
  include $_SERVER['DOCUMENT_ROOT'] . '/includes/dbrunhub.inc.php';

  try
  {
    $sql = 'UPDATE member SET name = :name, email = :email WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->bindValue(':name', $_POST['name']);
    $s->bindValue(':email', $_POST['email']);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error updating submitted member!';
    include 'error.html.php';
    exit();
  }

  header('Location: .');
  exit();

}

// Gérer la suppression
// supprimer les eventmember avec le memberid présent
// ensuite spprimer le member
// il faudra vérifier si les events sont affectés à un member (à voir)

if (isset($_POST['action']) and $_POST['action'] == 'Delete')
{
  include $_SERVER['DOCUMENT_ROOT'] . '/includes/dbrunhub.inc.php';

  // Get and delete records in eventmember where member id is present
  try
  {
    $sql = 'DELETE FROM eventmember WHERE memberid = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error deleting member events.';
    include 'error.html.php';
    exit();
  }

  // Delete the member
  try
  {
    $sql = 'DELETE FROM member WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error deleting member.';
    include 'error.html.php';
    exit();
  }

  header('Location: .');
  exit();
}




// Affiche la liste des membres

include $_SERVER['DOCUMENT_ROOT'] . '/includes/dbrunhub.inc.php';

try
{
  $result = $pdo->query('SELECT id, name, email FROM member');
}
catch (PDOException $e)
{
  $error = 'Error fetching members from the database!';
  include 'error.html.php';
  exit();
}

foreach ($result as $row)
{
  $members[] = array('id' => $row['id'], 'name' => $row['name'], 'email' => $row['email']);
}



include 'members.html.php';

