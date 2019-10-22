<div id="auth" class="container active">
  <div class="row justify-content-center">
    <div id="login-form">
      <form>
        <fieldset>
          <legend>Авторизация</legend>
          <div class="form-group row">
            <div class="col-12">
              <label for="login" class="col-12 col-form-label">Логин</label>
              <input type="text" class="form-control" id="login" name="login">
            </div>
            <div class="col-12">
              <label for="pass" class="col-12 col-form-label">Пароль</label>
              <input type="password" class="form-control" id="pass" name="pass" autocomplete>
            </div>
          </div>
          <button type="submit" class="btn btn-primary">Войти</button>
        </fieldset>
        <div class="valid-feedback">Почти, готово.</div>
        <div class="invalid-feedback">Ошибка в пароле или логине. Уходи, хакер.</div>
      </form>
    </div>
  </div>
</div>
<div id="admin-main" class="container">
  <div class="row justify-content-center">

    <div class="col-12 text-center is_auth">
      <legend>Вы авторизированы</legend>
    </div>

    <div class='admin-buttons col-12'>
      <div class="row">
        <div class="col-12 text-center register">
          <button type="button" class="btn btn-outline-primary btn-lg">Регистрация </button>
        </div>

        <div class="col-12 text-center payment">
          <button type="button" class="btn btn-outline-primary btn-lg">Оплата</button>
        </div>

        <div class="col-12 text-center statistics">
          <button type="button" class="btn btn-outline-primary btn-lg">Статистика</button>
        </div>
      </div>
    </div>

    <div class="col-12 booking_confirm">
      <div class="row justify-content-center">
        <div id="find-book-form" class="col-12">
          <form>
            <fieldset>
              <div class="row justify-content-center">
                <label for="book-number" class="col-2 col-form-label text-right">Номер брони:</label>
                <div class="col-4">
                  <input type="text" class="form-control col-12" id="book-number" name="book-number" placeholder="000 000 000">
                  <div class="invalid-feedback">Слишком не длинный номер.</div>
                </div>
                <div class="col-2">
                  <button type="submit" class="btn btn-primary">Найти</button>
                </div>
              </div>
            </fieldset>
          </form>
        </div>
      </div>
    </div>

  </div>
</div>