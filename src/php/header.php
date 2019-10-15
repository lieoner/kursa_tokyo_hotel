<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>tokyohotel</title>

  <?php
  require_once('connection.php');
  require_once('core.php');
  require_once('profile/user.php');
  ?>
  <script src="dist/js/index-bundle.js"></script>


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
        <div id="login-form">
          <form>
            <fieldset>
              <legend>Авторизация</legend>
              <div class="form-group row">
                <div class="col-12">
                  <label for="login" class="col-12 col-form-label">Номер брони</label>
                  <input type="text" class="form-control" id="login" name="login" placeholder="000 000 000">
                  <div id='short-login-feedback' class="invalid-feedback">Мало букав, введи всё</div>
                </div>
                <div class="col-12">
                  <label for="pass" class="col-12 col-form-label">Пароль</label>
                  <input type="password" class="form-control" id="pass" name="pass" autocomplete>
                  <div id='short-pass-feedback' class="invalid-feedback">Там не столько букв было, вводи как написано</div>
                </div>
              </div>
              <button type="submit" class="btn btn-primary">Войти</button>
            </fieldset>
          </form>
        </div>
      </nav>
    </header>
  </section>