<section id="uslugi">
  <div class="container">
    <div class="row">
      <div class="title col-12">
        <h1>Мы вам даем:</h1>
      </div>
      <div class="uslugi-body row col-12 text-center">
        <div id="eat-title" class="col-sm-3"><a>Питание</a></div>
        <div id="parkur-title" class="col-sm-3"><a>Паркур</a></div>
        <div id="bar-title" class="col-sm-3"><a>Бар</a></div>
        <div id="pool-title" class="col-sm-3"><a>Бассейн</a></div>
      </div>
      <div id="uslugi-data" class="col-12">
        <div id="eat" class="owl-carousel">
          <?php
          $eats = $con->getEat();
          foreach ($eats as $eat) {
            echo '<div class="card border-secondary mb-3">
                      <div class="card-body">
                        <img src="' . $eat['simgpath'] . '" class="pimg" title="' . $eat['sname'] . '" />
                      </div>
                      <div class="card-header row"><div class="col-9">' . $eat['sname'] . '</div><div class="col-3">' . $eat['scost'] . 'р</div></div>
                    </div>';
          }
          ?>
        </div>
        <div id="bar" class="col-12">
          <div class="card border-secondary mb-3">
            <div class="card-img-top">
              <picture>
                <source srcset="src/image/bar.webp" type="image/webp">
                <img src="src/image/bar.png">
              </picture>
            </div>
            <div class="card-header">Бар</div>
            <div class="card-body">
              <h4 class="card-title">Лучшие напитки</h4>
              <p class="card-text">
                <ul class="list-group">
                  <li class="list-group-item  d-flex justify-content-between align-items-center">Вода</li>
                </ul>
              </p>
            </div>
          </div>
        </div>
        <div id="parkur" class="col-12">
          <div class="card border-secondary mb-3">
            <div class="card-img-top">
              <picture>
                <source srcset="src/image/works.webp" type="image/webp">
                <img src="src/image/works.png">
              </picture>
            </div>
            <div class="card-header">Паркур</div>
            <div class="card-body">
              <h4 class="card-title"></h4>
              <p class="card-text">(стройка)</p>
            </div>
          </div>
        </div>
        <div id="pool" class="col-12">
          <div class="card border-secondary mb-3">
            <div class="card-img-top">
              <picture>
                <source srcset="src/image/pool.webp" type="image/webp">
                <img src="src/image/pool.png">
              </picture>
            </div>
            <div class="card-header">Бассейн</div>
            <div class="card-body">
              <h4 class="card-title">Да, у моря</h4>
              <p class="card-text">можно прыгать сальтуксой или бомбочкой</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row dengi">
      <div class="title col-12">
        <h1>Вы нам даете:</h1>
      </div>
      <div class="uslugi-body row col-12 text-center justify-content-center">
        <div class="col-12">
          <a class="star" title="немного">Деньги*</a>
        </div>
        <div class="alert alert-dismissible alert-info ">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <strong>Немного</strong>
        </div>
      </div>
    </div>
  </div>
</section>