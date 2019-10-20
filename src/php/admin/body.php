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
<div id="main" class="container">
  <div class="row justify-content-center">
    <div class="col-12 text-center message">
      <legend>Вы авторизированы</legend>
    </div>
  </div>
</div>