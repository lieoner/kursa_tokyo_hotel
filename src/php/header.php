<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>tokyohotel</title>

  <?php
  require_once('src/php/connection.php');
  require_once('src/php/core.php');
  ?>
  <script src="dist/js/bundle.js"></script>


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
        <button id="login-toggler" type="button" class="btn btn-primary focus align-right">
          <span class="navbar-toggler-icon"></span>
        </button>
      </nav>
    </header>
  </section>