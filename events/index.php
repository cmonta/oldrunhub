<?php

include_once $_SERVER['DOCUMENT_ROOT'] .  '/includes/magicquotes.inc.php';

// Vérifie si l'utilisateur a les habilitations

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/access.inc.php';

if (!userIsLoggedIn())
{
  include '../login.html.php';
  exit();
}

if (!userHasRole('Content Editor'))
{
  $error = 'Only Content Editors may access this page.';
  include '../accessdenied.html.php';
  exit();
}


//Affiche le formulaire pour ajouter un event

if (isset($_GET['add']))
{
  $pageTitle = 'Nouvel évènement';
  $action = 'addform';
  $location = '';
  $distance = '';
  $date = '';
  $id = '';
  $button = 'Ajout évènement';

  include 'form.html.php';
  exit();
}

// Traite l'action du formulaire, ajoute un event

if (isset($_GET['addform']))
{
  include $_SERVER['DOCUMENT_ROOT'] . '/includes/dbrunhub.inc.php';

  try
  {
    $sql = 'INSERT INTO event SET location = :location, distance = :distance, date = :date';
    $s = $pdo->prepare($sql);
    $s->bindvalue(':location', $_POST['location']);
    $s->bindvalue(':distance', $_POST['distance']);
    $s->bindvalue(':date', $_POST['date']);
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

// Charge la page pour éditer les evenements


if (isset($_POST['action']) and $_POST['action'] == 'Edit')
{
  include $_SERVER['DOCUMENT_ROOT'] . '/includes/dbrunhub.inc.php';

  try
  {
    $sql = 'SELECT id, location, distance, date FROM event WHERE id = :id';
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

  $pageTitle = 'Editer évènenemt';
  $action = 'editform';
  $location = $row['location'];
  $distance = $row['distance'];
  $date = $row['date'];
  $id = $row['id'];
  $button = 'Mettre à jour';

  include 'form.html.php';
  exit();

}

// Edite les events


if (isset($_GET['editform']))
{
  include $_SERVER['DOCUMENT_ROOT'] . '/includes/dbrunhub.inc.php';

  try
  {
    $sql = 'UPDATE event SET location = :location, distance = :distance, date = :date WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->bindValue(':location', $_POST['location']);
    $s->bindValue(':distance', $_POST['distance']);
    $s->bindValue(':date', $_POST['date']);
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
// supprimer les eventmember avec le eventid présent
// ensuite supprimer le event

if (isset($_POST['action']) and $_POST['action'] == 'Delete')
{
  include $_SERVER['DOCUMENT_ROOT'] . '/includes/dbrunhub.inc.php';

  // Get and delete records in eventmember where event id is present
  try
  {
    $sql = 'DELETE FROM eventmember WHERE eventid = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error deleting  event\'s members.';
    include 'error.html.php';
    exit();
  }

  // Delete the event
  try
  {
    $sql = 'DELETE FROM event WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error deleting event.';
    include 'error.html.php';
    exit();
  }

  header('Location: .');
  exit();
}


// Affiche la liste des events

include $_SERVER['DOCUMENT_ROOT'] . '/includes/dbrunhub.inc.php';

try
{
  $result = $pdo->query('SELECT id, location, distance, date FROM event');
}
catch (PDOException $e)
{
  $error = 'Error fetching members from the database!';
  include 'error.html.php';
  exit();
}

foreach ($result as $row)
{
  $events[] = array('id' => $row['id'], 'location' => $row['location'], 'distance' => $row['distance'], 'date' => $row['date']);
}



include 'events.html.php';

