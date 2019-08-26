<section id="catalog">
  <? require_once('src/php/modal.php') ?>
  <div class="container card border-secondary mb-3">
    <div class="grid owl-carousel">
      <?php
      usort($room_types, function ($a, $b) {
        return strcmp($a["r_typeImageDir"], $b["r_typeImageDir"]);
      });
      foreach ($room_types as $room_type) {
        $directory = 'src/image/rooms/' . $room_type['r_typeImageDir'];
        $allowed_types = array("jpg", "png", "gif");
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
</section>