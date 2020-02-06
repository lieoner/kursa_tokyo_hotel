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
          <button type="button" class="btn btn-outline-primary btn-lg">Регистрация</button>
        </div>

        <div class="col-12 text-center service">
          <button type="button" class="btn btn-outline-primary btn-lg">Услуги</button>
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

        <div class="col-12 book-confirmed alert alert-dismissible alert-success">
          <strong>Заселение подтверждено</strong>
        </div>
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
        <table class="table table-hover logs-table">
          <thead>
            <tr>
              <th scope="col" class="operation-label">Операция
                <div class="dropdown-menu">
                  <? $operations = $con->getOperationTypes();
                  ?>
                  <a class="dropdown-item" href="#" data-operation-id=<?= 'all' ?>>Все</a>
                  <?
                  foreach ($operations as $operation) {
                  ?>
                    <a class="dropdown-item" href="#" data-operation-id=<?= $operation['IDop'] ?>><?= $operation['opName'] ?></a>
                  <?
                  } ?>
                </div>
              </th>
              <th scope="col">Сообщение</th>
              <th scope="col">Инициатор</th>
              <th scope="col">Дата</th>
            </tr>
          </thead>
          <tbody>

          </tbody>
        </table>
      </div>
    </div>

    <div class="col-12 service-content">

      <div class="service-roomNumber" style="margin-top:20px;">
        <div class="row justify-content-center">
          <div id="find-service-table" class="col-8 row">
            <input type="text" class="form-control col-6 book-number" id="service-table-book_number" name="book-number" placeholder="000 000 000" autofocus>
            <div class="btn-group sbStatusGroup col-2" role="group">
              <button id="sbStatusBtnGroup" type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Статус услуги</button>
              <div class="dropdown-menu" aria-labelledby="sbStatusBtnGroup" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 48px, 0px);">
                <a class="dropdown-item active" href="#" data-query-status="all">Все</a>
                <a class="dropdown-item" href="#" data-query-status="0">Заявки</a>
                <a class="dropdown-item" href="#" data-query-status="1">Предоставленные</a>
              </div>
            </div>
            <button type="button" class="btn btn-secondary btn-sm col-1 service-table-refresh">↻</button>
            <button type="button" class="btn btn-primary btn-sm col-2 service-table-confirmSolve">Подтвердить выполнение</button>
          </div>
        </div>
      </div>

      <div class="modal">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Подтвердить предоставление услуг</h5>
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

      <div class="service-table" style="margin-top:20px;">
        <table class="table table-hover">
          <thead>
            <tr>
              <th scope="col">№ брони</th>
              <th scope="col">№ комнаты</th>
              <th scope="col">Активных заявок</th>
              <th scope="col">Заявок всего</th>
              <th scope="col">Стоимость</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>

    </div>

    <div class="col-12 payment-content">
      <div class="row justify-content-center">

        <label for="table-payment-number" class="col-2 col-form-label text-right">Номер брони:</label>
        <div id="find-payment-table" class="col-8 row">
          <input type="text" class="form-control col-10 book-number" id="table-payment-number" name="payment-number" placeholder="000 000 000" autofocus autocomplete="off">
          <button type="button" class="btn btn-secondary btn-sm col-1 payment-table-refresh">↻</button>
        </div>


        <div class="col-12 payment-confirmed alert alert-dismissible alert-success">
          <strong>Оплата подтверждена</strong>
        </div>

        <table class="table table-hover payment-table">
          <thead>
            <tr>
              <th scope="col">Номер брони</th>
              <th scope="col">Номер</th>
              <th scope="col">Кол-во дней</th>
              <th scope="col">Услуги</th>
              <th scope="col">Проживание</th>
              <th scope="col">Итого к оплате</th>
            </tr>
          </thead>
          <tbody>

          </tbody>
        </table>

        <div class="modal">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Оплата</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary">Подтвердить</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

    <div class="col-12 statistic-content">
      <div class="row justify-content-center">
        <div class="btn-group-vertical graphtype col-12">
          <button type="button" class="btn btn-outline-primary btn-lg top-service-btn">Самая популярная услуга</button>
          <button type="button" class="btn btn-outline-primary btn-lg profit-btn">Доход за период</button>
          <button type="button" class="btn btn-outline-primary btn-lg top-number-btn">Самый популярный класс номеров</button>
        </div>
        <div class="jumbotron">
          <div class="col-12"><button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button></div>
          <div class="graph col-12">
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
              <label class="btn btn-primary btn-sm">
                <input type="radio" name="options" id="day" autocomplete="off"> неделя
              </label>
              <label class="btn btn-primary btn-sm">
                <input type="radio" name="options" id="month" autocomplete="off"> месяц
              </label>
              <label class="btn btn-primary active btn-sm">
                <input type="radio" name="options" id="year" autocomplete="off" checked=""> год
              </label>
            </div>
            <canvas id="chart" width="1000" height="600"></canvas>
          </div>
        </div>

      </div>
    </div>

  </div>
</div>