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
    <!-- 
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
 -->
    <div class='admin-buttons col-12'>
      <div class="row">
        <div class="col-12 text-center register">
          <button type="button" class="btn btn-outline-primary btn-lg">Регистрация</button>
        </div>

        <div class="col-12 text-center payment">
          <button type="button" class="btn btn-outline-primary btn-lg">Оплата</button>
        </div>

        <div class="col-12 text-center statistics">
          <button type="button" class="btn btn-outline-primary btn-lg">Статистика</button>
        </div>

        <div class="col-12 text-center logs">
          <button type="button" class="btn btn-outline-primary btn-lg">Логи</button>
        </div>
      </div>
    </div>

    <div class="col-12 register-content">
      <div class="row justify-content-center">

        <label for="table-book-number" class="col-2 col-form-label text-right">Номер брони:</label>
        <div id="find-book-table" class="col-8 row">
          <input type="text" class="form-control col-10 book-number" id="table-book-number" name="book-number" placeholder="000 000 000" autofocus>
          <button type="button" class="btn btn-secondary btn-sm col-1 book-table-refresh">↻</button>
        </div>

        <div class="book-confirmed alert alert-dismissible alert-success">
          <strong>Заселение подтверждено</strong>
        </div>
        <?
        $nearest_bookings = $con->findNearestBooking();
        function convert_sqlDate_to_normalDate($sql_date)
        {
          return date('d.m.Y', strtotime($sql_date));
        }
        ?>
        <table class="table table-hover book-table">
          <thead>
            <tr>
              <th scope="col">Номер брони</th>
              <th scope="col">Заезд</th>
              <th scope="col">Отъезд</th>
              <th scope="col">Номер</th>
              <th scope="col">Кол-во дней</th>
              <th scope="col">Итоговая цена</th>
            </tr>
          </thead>
          <tbody>

          </tbody>
        </table>

        <div class="modal">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Подтвердить заселение</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary">Да</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

    <div class="col-12 logs-content">
      <div class="row justify-content-center">
        asfsafs
      </div>
    </div>

  </div>
</div>