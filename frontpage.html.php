<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/helpers.inc.php';
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>RunHub</title>
  </head>
  <body>
    <h1>Runhub management</h1>
    <ul>
      <li><a href="members/">Manage members</a></li>
      <li><a href="events/">Manage events</a></li>
    </ul>

    <p>Evenements et participants</p>

    <div>

      <table border ="1">
	<tr>
	  <th>Event id</th>
	  <th>Event location</th>
	  <th>Event distance</th>
	  <th>Number of participants</th>
	  <?php  foreach ($eventsmembers as $eventmember) { ?>
	    <tr>
	      <td><?php htmlout($eventmember['eventid']); ?></td>
	      <td><?php htmlout($eventmember['eventlocation']); ?></td>
	      <td><?php htmlout($eventmember['eventdistance']); ?></td>
	      <td><?php htmlout($eventmember['count']); ?></td>
	    </tr>
	  <?php } ?>
      </table>

    </div>

  </body>
</html>