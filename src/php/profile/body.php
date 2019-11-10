<section>
  <?php
  User::loadUser();
  ?>
  <div class="container profile">
    <h2 class="text-center">Профиль № <?= User::getBookNumber() ?></h2>
    <div class="row">

      <div class="col-12 col-md-3">
        <div class="list-group">
          <div class="list-group-item label">
            Личный кабинет
          </div>
          <a href="#" class="list-group-item list-group-item-action" data-val="1">Профиль
          </a>
          <a href="#" class="list-group-item list-group-item-action" data-val="2">Информация о брони
          </a>
          <a href="#" class="list-group-item list-group-item-action active" data-val="3">Доп. услуги
          </a>
        </div>

        <div class="total-service card border-primary mb-3">
          <div class="card-header ">
            <div class="row">
              <div class="col-6">Выбранные услуги</div>
              <div class="col-6">
                <button type="button" class="cart-confirm btn btn-primary btn-sm">Подтвердить</button>
              </div>
            </div>
          </div>
          <ul class="list-group list-group-flush"></ul>
        </div>

        <div class="total-offer card border-secondary mb-3">
          <div class="card-header">Предварительный счет</div>
          <div class="card-body">
            <h6 class="card-title">Проживание</h6>
            <p class="card-text"><span class="living-cost">0</span><span> руб.</span></p>
            <h6 class="card-title">Услуги</h6>
            <p class="card-text"><span class="service-cost">0</span><span> руб.</span></p>
            <p class="card-text text-right"> <a href="#" class="show_cost_info btn btn-primary btn-sm">Подробнее</a></p>
          </div>

          <div class="modal cost_info">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Предварительный счет</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-12">
                      <div class="row">
                        <div class="col-6 h5">Проживание</div>
                        <div class="living-days col-2">0</div>
                        <div class="living-cost col-2 text-right h5">0</div>
                        <div class="col-2"> руб.</div>
                      </div>
                    </div>
                    <div class="col-12" style="margin-top:20px;">
                      <div class="row service-more">
                        <div class="col-6 h5">Услуги</div>
                        <div class="col-2"></div>
                        <div class="service-cost col-2 text-right h5">0</div>
                        <div class="col-2"> руб.</div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <div class="col-12">
                    <h5>Итого</h5>
                    <div class=" row">
                      <div class="total-cost col-9 h1 text-right">0</div>
                      <div class="col-3 h3">руб.</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="card-footer text-muted">
            <h5 class="card-title">Итого</h5>
            <p class="card-text"><span class="total-cost">0</span><span> руб.</span></p>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-9">

        <div class="item-data col-12" data-val="1">
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
                <div class="col-12 col-md-6 book-begin"><?= date('d.m.Y', strtotime(User::getBookingData()['comingDate'])); ?></div>
              </li>
              <li class="row">
                <div class="col-12 col-md-2">Дата отъезда</div>
                <div class="col-12 col-md-6 book-end"><?= date('d.m.Y', strtotime(User::getBookingData()['outDate'])); ?></div>
              </li>
            </ul>
          </div>
        </div>

        <? if (User::getBookingData()['bool_status'] == 1) {
          ?>
          <div class="item-data active" data-val="3">
            <div class="service-data col-12">

              <div class="service-btn row">
                <button type="button" class="btn btn-outline-primary col-3 btn-lg" data-type="eat">Еда/Вода<br>(в номер)</button>
                <button type="button" class="btn btn-outline-primary col-3 btn-lg" data-type="travel">Экскурсии</button>
                <button type="button" class="btn btn-outline-primary col-3 btn-lg " data-type="other">Другое</button>
              </div>

              <div class="service-content row">
                <div class="col-12 eat">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th scope="col">Превью</th>
                        <th scope="col">Название</th>
                        <th scope="col">Кол-во</th>
                        <th scope="col">Цена</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $eats = $con->getEat();
                        foreach ($eats as $eat) {
                          ?>
                        <tr data-service-id=<?= $eat['IDs'] ?>>
                          <td>
                            <img class="col-9" src="<?= $eat['simgpath'] ?>" class="pimg" title="<?= $eat['sname'] ?>" />
                          </td>
                          <td>
                            <p class="text-primary"> <?= $eat['sname'] ?></p>
                          </td>
                          <td>
                            <div class="btn-group" role="group">
                              <button type="button" class="btn btn-sm btn-outline-primary col-4 sub-cnt">-</button>
                              <input class="form-control form-control-sm col-4 text-center cnt" type="text" value=1 readonly="">
                              <button type="button" class="btn btn-sm btn-outline-primary col-4 inc-cnt">+</button>
                            </div>
                          </td>
                          <td>
                            <p class="text-primary"> <?= $eat['scost'] ?> руб.</p>
                          </td>
                        </tr>
                      <?
                        }
                        ?>
                    </tbody>
                  </table>
                </div>

                <div class="col-12 travel">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th scope="col">Превью</th>
                        <th scope="col">Название</th>
                        <th scope="col">Кол-во</th>
                        <th scope="col">Цена</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $travels = $con->getTravel();
                        foreach ($travels as $travel) {
                          ?>
                        <tr data-service-id=<?= $travel['IDs'] ?>>
                          <td>
                            <img class="col-9" src="<?= $travel['simgpath'] ?>" class="pimg" title="<?= $travel['sname'] ?>" />
                          </td>
                          <td class="label">
                            <p class="text-primary"> <?= $travel['sname'] ?></p>
                          </td>
                          <td>
                            <div class="btn-group" role="group">
                              <button type="button" class="btn btn-sm btn-outline-primary col-4 sub-cnt">-</button>
                              <input class="form-control form-control-sm col-4 text-center cnt" type="text" value=1 readonly="">
                              <button type="button" class="btn btn-sm btn-outline-primary col-4 inc-cnt">+</button>
                            </div>
                          </td>
                          <td>
                            <p class="text-primary"> <?= $travel['scost'] ?> руб.</p>
                          </td>
                        </tr>
                      <?
                        }
                        ?>
                    </tbody>
                  </table>
                </div>

                <div class="col-12 other">Менеджеры ведут разработку новых услуг, скоро будут...</div>
              </div>

            </div>
          </div>
        <? } ?>
      </div>
    </div>
  </div>
</section>