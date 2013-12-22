<?php

include_once $_SERVER['DOCUMENT_ROOT'] .  '/includes/magicquotes.inc.php';

// Affiche la liste des events et des participants

include $_SERVER['DOCUMENT_ROOT'] . '/includes/dbrunhub.inc.php';

try
{
  $result = $pdo->query('SELECT event.id as eventid, event.location as eventlocation, event.distance as eventdistance, member.name as membername FROM event JOIN eventmember  ON (event.id = eventmember.eventid) JOIN member  ON (eventmember.memberid = member.id)');
}

catch (PDOException $e)
{
  $error = 'Error fetching events and members from the database!';
  include 'error.html.php';
  exit();
}

foreach ($result as $row)
{
  $eventsmembers[] = array('eventid' => $row['eventid'], 'eventlocation' => $row['eventlocation'], 'eventdistance' => $row['eventdistance'], 'membername' => $row['membername']);
}


include 'frontpage.html.php';
