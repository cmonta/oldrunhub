<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/helpers.inc.php'; ?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php htmlout($pageTitle); ?></title>
  </head>
  <body>
    <h1><?php htmlout($pageTitle); ?></h1>
    <form action="?<?php htmlout($action); ?>" method="post">
      <div>
        <label for="location">Lieu: <input type="text" name="location" id="location" value="<?php htmlout($location); ?>"></label>
      </div>
      <div>
        <label for="distance">Distance: <input type="int" name="distance" id="distance" value="<?php htmlout($distance); ?>"></label>
      </div>
      <div>
        <label for="date">Date: <input type="date" name="date" id="date" value="<?php htmlout($date); ?>"></label>
      </div>
      <div>
        <input type="hidden" name="id" value="<?php htmlout($id); ?>">
        <input type="submit" value="<?php htmlout($button); ?>">
      </div>
    </form>
  </body>
</html>
