<section id="catalog">
  <div class="container card border-secondary mb-3">
    <div class="grid owl-carousel">
      <?php
      usort($room_types, function ($a, $b) {
        return strcmp($a["r_typeImageDir"], $b["r_typeImageDir"]);
      });
      foreach ($room_types as $room_type) {
        $directory = 'src/image/rooms/' . $room_type['r_typeImageDir'];
        $allowed_types = array("jpg", "png", "gif", "webp");
        $file_parts = array();
        $ext = "";
        $title = "";
        $i = 0;
        $dir_handle = @opendir($directory) or die("Ошибка при открытии папки !!!"); ?>
        <div class="grid-content row align-items-center">
          <div class="grid-main col-9">
            <? $img_list = array();
              while ($file = readdir($dir_handle)) {
                if ($file == "." || $file == "..") continue;
                $file_parts = explode(".", $file);
                $ext = strtolower(array_pop($file_parts));

                if (in_array($ext, $allowed_types)) {
                  $img = 'src="' . $directory . '/' . $file . '" num="' . $i . '"';
                  echo '<img ' . $img . ' class="grid-image-big" title="' . $room_type['r_typeName'] . '"/>';
                  $img_list[] = $img;
                  $i++;
                }
              } ?>
          </div>
          <div class="grid-nav col-3 ">
            <? foreach ($img_list as $img) {
                ?>
              <div class="row">
                <? echo '<img ' . $img . ' class="grid-image-small"/>'; ?>
              </div>
            <? } ?>
          </div>
          <? closedir($dir_handle) ?>

          <div class="grid-desc col-12 border-secondary">
            <div class="card-header col-12"><? echo $room_type['r_typeName'] ?></div>
            <div class="card-body row col-12">
              <div id="desc" class="desc col-lg-6 col-md-12 col-12">
                <p class="card-text"><? echo $room_type['r_typeDesc'] ?></p>
              </div>
              <div id="price" class="price col-lg-3 col-md-9 col-6 text-right">
                <h4 class="card-title col"><? echo $room_type['r_typeCost'] ?></h4>
                <h4 class="card-title col">Рублей</h4>
              </div>
              <div id="to-book" class="col-lg-3 col-md-3 col-6 text-center">
                <button type="button" class="btn btn-primary" data-roomtype-id="<? echo $room_type['IDrt'] ?>">Забронировать</button>
              </div>
            </div>
          </div>
        </div>
      <? } ?>
    </div>
  </div>

  <div class="booked alert alert-dismissible alert-success">
    <strong>Успешно забронироано, проверьте почту</strong>
  </div>

  <div class="modal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Забронировать</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="POST" id="book-form">
          <div class="modal-body">
            <div class="form-group">
              <fieldset id="f1">
                <div class="input-daterange input-group">
                  <div class="row col-12">
                    <div class="col-md-6 col-12">
                      <label class="control-label" for="dateFirst">Дата заезда</label>
                      <input type="text" class="input-sm form-control" id="dateFirst" name="start" autocomplete="off" />
                    </div>
                    <div class="col-md-6 col-12">
                      <label class="control-label" for="dateSecond">Дата отъезда</label>
                      <input type="text" class="input-sm form-control" id="dateSecond" name="end" autocomplete="off" />
                    </div>
                  </div>
                </div>
              </fieldset>
            </div>
          </div>
          <div class="modal-footer">
            <input type="submit" class="btn btn-primary" value="Принять" />
            <div id="room-check">
              <span>Проверяем...</span>
              <img class="rot" src="src/image/findrat.webp">
            </div>
            <div class="alert alert-dismissible alert-success">
              <strong>Комната свободна! Заполни поля выше и</strong>
              <a href="#catalog" onclick="alert('ну ты чего, это не кнопка же ну')" class="alert-link">можешь залетать</a>.
              <button type="button" class="btn btn-primary" disabled>Вперед >></button>
            </div>
            <div class="alert alert-dismissible alert-info">
              <strong>Не, комната занята</strong> выбирай другую дату, либо комнату
            </div>
            <div class="alert alert-dismissible alert-danger">
              <strong>Так не пойдет</strong> вводи что просят
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>