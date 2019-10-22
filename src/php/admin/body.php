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

    <div class="col-12 booking_confirm">
      <div class="row justify-content-center">
        <?
        /* 
        array (size=7)
          0 => 
            array (size=6)
              'bookNumber' => string '675036040' (length=9)
              'comingDate' => string '2019-10-16 00:00:00' (length=19)
              'outDate' => string '2019-11-10 00:00:00' (length=19)
              'roomNumber' => int 10
              'totalCost' => float 1500
              'totalDaysCount' => int 25 
        */
        $nearest_bookings = $con->findNearestBooking();

        function convert_sqlDate_to_normalDate($sql_date)
        {
          return date('d.m.Y', strtotime($sql_date));
        }
        ?>
        <table class="table table-hover">
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
            <? foreach ($nearest_bookings as $key => $booking) {
              ?>
              <tr>
                <td><?= $booking['bookNumber'] ?></td>
                <td><?= convert_sqlDate_to_normalDate($booking['comingDate']) ?></td>
                <td><?= convert_sqlDate_to_normalDate($booking['outDate']) ?></td>
                <td><?= $booking['roomNumber'] ?></td>
                <td><?= $booking['totalDaysCount'] ?></td>
                <td><?= $booking['totalCost'] ?></td>
              </tr>
            <? } ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>