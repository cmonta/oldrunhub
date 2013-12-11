
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
  include $_SERVER['DOCUMENT_ROOT'] . '/includes/dbrunhub.inc.php';

  $pageTitle = 'Nouveau membre';
  $action = 'addform';
  $name = '';
  $email = '';
  $id = '';
  $button = 'Ajout membre';

  // Build the list of roles
  try
  {
    $result = $pdo->query('SELECT id, description FROM role');
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching list of roles.';
    include 'error.html.php';
    exit();
  }

  foreach ($result as $row)
  {
    $role[] = array(
      'id' => $row['id'],
      'description' => $row['description'],
      'selected' => FALSE);
  }


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

  $memberid = $pdo->lastInsertId();

  if ($_POST['password'] != '')
  {
    $password = md5($_POST['password'] . 'runhub');

    try
    {
      $sql = 'UPDATE member SET password = :password WHERE id = :id';
      $s = $pdo->prepare($sql);
      $s->bindValue(':password', $password);
      $s->bindValue(':id', $memberid);
      $s->execute();
    }
    catch (PDOException $e)
    {
      $error = 'Error setting member password.';
      include 'error.html.php';
      exit();
    }
  }

  if (isset($_POST['roles']))
  {
    foreach ($_POST['roles'] as $role)
    {
      try
      {
	$sql = 'INSERT INTO memberrole SET memberid = :memberid, roleid = :roleid';
	$s = $pdo->prepare($sql);
	$s->bindValue(':memberid', $memberid);
	$s->bindValue(':roleid', $role);
	$s->execute();
      }
      catch (PDOException $e)
      {
	$error = 'Error assigning selected role to member.';
	include 'error.html.php';
	exit();
      }
    }
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

  //Get list of roles assigned to this member
  try
  {
    $sql = 'SELECT roleid FROM memberrole WHERE memberid = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $id);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching list of assigned roles.';
    include 'error.html.php';
    exit();
  }

  $selectedRoles = array();
  foreach ($s as $row)
  {
    $selectedRoles[] = $row['roleid'];
  }

  //Build the list of all roles
  try
  {
    $result = $pdo->query('SELECT id, description FROM role');
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching list of roles.';
    include 'error.html.php';
    exit();
  }

  foreach ($result as $row)
  {
    $roles[] = array('id' => $row['id'], 'description' => $row['description'], 'selected' => in_array($row['id'], $selectedRoles));
  }

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

  if ($_POST['password'] != '')
  {
    $password = md5($_POST['password'] . 'runhub');

    try
    {
      $sql = 'UPDATE member SET password = :password WHERE id = :id';
      $s = $pdo->prepare($sql);
      $s->bindValue(':password', $password);
      $s->bindValue(':id', $_POST['id']);
      $s->execute();
    }
    catch (PDOException $e)
    {
      $error = 'Error setting member password.';
      include 'error.html.php';
      exit();
    }
  }

  try
  {
    $sql = 'DELETE FROM memberrole WHERE memberid = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error removing obsolete member role entries.';
    include 'error.html.php';
    exit();
  }

  if (isset($_POST['roles']))
  {
    foreach ($_POST['roles'] as $role)
    {
      try
      {
	$sql = 'INSERT INTO memberrole SET memberid = :memberid, roleid = :roleid';
	$s = $pdo->prepare($sql);
	$s->bindValue(':memberid', $_POST['id']);
	$s->bindValue(':roleid', $role);
	$s->execute();
      }
      catch (PDOException $e)
      {
	$error = 'Error assigning selected role to member.';
	include 'error.html.php';
	exit();
      }
    }
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

  //Delete role assignements for this member
  try
  {
    $sql = 'DELETE FROM memberrole WHERE memberid = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error removing member from roles.';
    include 'error.html.php';
    exit();
  }


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

