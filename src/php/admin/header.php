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

        <div id="back_btn" class="col-2 col-sm-1">
          <button type="button" class="btn btn-primary">←</button>
        </div>

        <a class="navbar-brand col-8 col-sm-4" href="http://kursa/">
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

        <div class="col-12 col-sm-3 text-left">
          <div>ADMINKA</div>
          <div id="is-login"></div>
        </div>
      </nav>
    </header>
  </section>