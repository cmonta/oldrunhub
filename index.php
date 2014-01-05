<?php

include_once $_SERVER['DOCUMENT_ROOT'] .  '/includes/magicquotes.inc.php';

// Affiche la liste des events et des participants

include $_SERVER['DOCUMENT_ROOT'] . '/includes/dbrunhub.inc.php';

try
{
  $result = $pdo->query('SELECT e.id as eventid, e.location as eventlocation, e.distance as eventdistance, count(em.memberid) as count FROM eventmember as em RIGHT JOIN event as e ON em.eventid=e.id LEFT JOIN member as m ON (m.id=em.memberid) GROUP BY em.eventid ORDER BY count DESC;');
}

catch (PDOException $e)
{
  $error = 'Error fetching events and members from the database!';
  include 'error.html.php';
  exit();
}

foreach ($result as $row)
{
  $eventsmembers[] = array('eventid' => $row['eventid'], 'eventlocation' => $row['eventlocation'], 'eventdistance' => $row['eventdistance'], 'count' => $row['count']);
}


include 'frontpage.html.php';
