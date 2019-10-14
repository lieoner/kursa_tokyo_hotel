<section>
  <?php
  User::loadUser();
  ?>
  <div class="container-fluid profile">
    <h2 class="text-center">Профиль № <?= User::getBookNumber() ?></h2>
    <div class="row">
      <div class="col-12 col-md-3">
        <div class="list-group">
          <div class="list-group-item label">
            Личный кабинет
          </div>
          <a href="#" class="list-group-item list-group-item-action active" data-val="1">Профиль
          </a>
          <a href="#" class="list-group-item list-group-item-action" data-val="2">Информация о брони
          </a>
        </div>
      </div>
      <div class="col-12 col-md-9">
        <div class="item-data active col-12" data-val="1">
          <div class="static-data">
            <ul>
              <li class="row">
                <div class="col-12 col-md-2">Имя</div>
                <div class="col-12 col-md-6 static-uname"><?= User::getUserName() ?></div>
              </li>
              <li class="row">
                <div class="col-12 col-md-2">Фамилия</div>
                <div class="col-12 col-md-6 static-ufam"><?= User::getUserFam() ?></div>
              </li>
              <li class="row">
                <div class="col-12 col-md-2">Телефон</div>
                <div class="col-12 col-md-6 static-uphone"><?= User::getUserPhone() ?></div>
              </li>
            </ul>
            <button type="button" class="btn btn-primary edit-user">Изменить</button>
          </div>
          <div class="edit-data">
            <form>
              <fieldset>
                <legend>Редактирование</legend>
                <div class="form-group row">
                  <div class="col-12">
                    <label for="uname" class="col-12 col-form-label">Имя</label>
                    <input type="text" class="form-control" id="uname" name="uname" value="<?= User::getUserName() ?>" data-uid="<?= User::getUserID() ?>">
                  </div>
                  <div class="col-12">
                    <label for="ufam" class="col-12 col-form-label">Фамилия</label>
                    <input type="text" class="form-control" id="ufam" name="ufam" value="<?= User::getUserFam() ?>">
                  </div>
                  <div class="col-12">
                    <label for="uphone" class="col-12 col-form-label">Телефон</label>
                    <input type="text" class="form-control" id="uphone" name="uphone" value="<?= User::getUserPhone() ?>">
                  </div>
                </div>
                <button type="submit" class="btn btn-primary">Сохранить</button>
              </fieldset>
            </form>
          </div>
        </div>
        <div class="item-data" data-val="2">
          <div class="booking-data">
            <ul>
              <li class="row">
                <div class="col-12 col-md-2">Статус брони</div>
                <div class="col-12 col-md-6 book-status"><?= User::getBookingData()['status']; ?></div>
              </li>
              <li class="row">
                <div class="col-12 col-md-2">Дата заезда</div>
                <div class="col-12 col-md-6 book-begin"><? $date =  explode(' ', User::getBookingData()['comingDate']);
                                                        $date = explode('-', $date[0]);
                                                        echo $date[2] . '.' . $date[1] . '.' . $date[0] ?></div>
              </li>
              <li class="row">
                <div class="col-12 col-md-2">Дата отъезда</div>
                <div class="col-12 col-md-6 book-end"><? $date =  explode(' ', User::getBookingData()['outDate']);
                                                      $date = explode('-', $date[0]);
                                                      echo $date[2] . '.' . $date[1] . '.' . $date[0] ?></div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>