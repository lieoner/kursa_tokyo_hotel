<? session_start(); ?>
<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>tokyohotel</title>

  <?php
  require_once('src/php/connection.php');
  require_once('src/php/core.php');
  ?>

  <script src="dist/js/admin-bundle.js"></script>

</head>

<body>
  <section id="header">
    <header>
      <nav class="navbar navbar-expand-lg navbar-dark bg-primary">

        <a class="navbar-brand" href="http://kursa/">
          <div>
            <picture>
              <source srcset="src/image/dab.webp" type="image/webp">
              <img src="src/image/dab.png">
            </picture>
          </div>
          <div><span>tokyohotel</span></div>
          <div>
            <picture>
              <source srcset="src/image/dab.webp" type="image/webp">
              <img class="mirrorX" src="src/image/dab.png">
            </picture>
          </div>
        </a>

        <span>ADMINKA</span>
      </nav>
    </header>
  </section>