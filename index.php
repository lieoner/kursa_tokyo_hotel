<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Главная tokyohotel.ru</title>
    <script src="dist/js/bundle.js"></script>
</head>

<body>
    <section id="header">
        <header>
            <nav class="navbar navbar-expand-lg navbar-dark bg-primary justify-content-center">
                <a class="navbar-brand" href="http://kursa/">
                    <div><img src="src/image/dab.png"></div>
                    <div><span>tokyohotel</span></div>
                    <div><img class="mirrorX" src="src/image/dab.png"></div>
                </a>
            </nav>
        </header>
    </section>
    <section id="overviewer">
        <div class="container-fluid overview">
            <div class="content row align-items-start justify-content-center">
                <div class="main text-center">
                    <h2 class="main-title">
                        <span>Подберите номер
                            в единственной гостиннице</span>
                        <strong>dead inside</strong>
                        <small>Номера для всех</small>
                    </h2>
                    <button type="button" class="btn btn-secondary">Подобрать номер</button>
                </div>
            </div>
        </div>
    </section>
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
                <div id="eat" class="owl-carousel">
                    <?php
                    $directory = "src/image/eat";    // Папка с изображениями
                    $allowed_types = array("jpg", "png", "gif");  //разрешеные типы изображений
                    $file_parts = array();
                    $ext = "";
                    $title = "";
                    $i = 0;
                    //пробуем открыть папку
                    $dir_handle = @opendir($directory) or die("Ошибка при открытии папки !!!");
                    while ($file = readdir($dir_handle)) {
                        if ($file == "." || $file == "..") continue;
                        $file_parts = explode(".", $file);
                        $ext = strtolower(array_pop($file_parts));


                        if (in_array($ext, $allowed_types)) {
                            echo '<div class="card border-secondary mb-3"><div class="card-body"><img src="' . $directory . '/' . $file . '" class="pimg" title="' . $file . '" /></div><div class="card-header">' . $file . '</div></div>';
                            $i++;
                        }
                    }
                    closedir($dir_handle);
                    ?>
                </div>
                <div id="bar" class="col-12">
                    <div class="card border-secondary mb-3">
                        <div class="first card-body"><img src="src/image/bar.png" /></div>
                        <div class="card-header">Бар</div>
                        <div class="second card-body">
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
                        <div class="first card-body"><img src="src/image/works.png" /></div>
                        <div class="card-header">Паркур</div>
                        <div class="second card-body">
                            <h4 class="card-title"></h4>
                            <p class="card-text">(стройка)</p>
                        </div>
                    </div>
                </div>
                <div id="pool" class="col-12">
                    <div class="card border-secondary mb-3">
                        <div class="first card-body"><img src="src/image/pool.png" /></div>
                        <div class="card-header">Бассейн</div>
                        <div class="second card-body">
                            <h4 class="card-title">Да, у моря</h4>
                            <p class="card-text">можно прыгать сальтуксой или бомбочкой</p>
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
    <section id="catalog">
        <div class="container card border-secondary mb-3">
            <div class="grid owl-carousel">
                <?php
                for ($j = 1; $j < 5; $j++) {
                    $directory = 'src/image/rooms/' . $j;
                    $allowed_types = array("jpg", "png", "gif");
                    $file_parts = array();
                    $ext = "";
                    $title = "";
                    $i = 0;
                    $dir_handle = @opendir($directory) or die("Ошибка при открытии папки !!!");
                    ?>
                <div class="grid-content row align-items-center">
                    <div class="grid-main col-9">
                        <?php
                            $img_list = array();
                            while ($file = readdir($dir_handle)) {
                                if ($file == "." || $file == "..") continue;
                                $file_parts = explode(".", $file);
                                $ext = strtolower(array_pop($file_parts));

                                if (in_array($ext, $allowed_types)) {
                                    $img = 'src="' . $directory . '/' . $file . '" num="' . $i . '"';
                                    echo '<img ' . $img . ' class="grid-image-big"/>';
                                    $img_list[] = $img;
                                    $i++;
                                }
                            }
                            ?>
                    </div>
                    <div class="grid-nav col-3 ">
                        <?php
                            foreach ($img_list as $img) {
                                echo '<div class="row"><img ' . $img . ' class="grid-image-small"/></div>';
                            }
                            ?>
                    </div>
                </div>

                <?php
                    closedir($dir_handle);
                }
                ?>
            </div>
        </div>
    </section>
</body>

</html>