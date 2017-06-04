<?php

require __DIR__.'/vendor/autoload.php';

$api = new \Yandex\Geo\Api();

if (isset($_POST['adress'])) {
    $adress = $_POST['adress'];
    $api->setQuery($adress);
} else {
    $adress = "";
}

// Настройка фильтров
$api
    ->setLimit(100) // кол-во результатов
    ->setLang(\Yandex\Geo\Api::LANG_US) // локаль ответа
    ->load();

$response = $api->getResponse();

// Список найденных точек
$collection = $response->getList();

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-cache">
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
    <link rel="shortcut icon" href="image/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>GEO</title>
</head>
<body>
<header class="header-container">
    <ul class="header-container__menu clearfix">
        <li class="header-container__menu__item"><img src="image/logo.png" width="40" height="40"></li>
        <li class="header-container__menu__item"><a class="header-container-link logo-link" href="index.php">GEO</a></li>
        <li class="header-container__menu__item"><a class="header-container-link" href="index.php">Main</a></li>
        <li class="header-container__menu__item"><a class="header-container-link" href="index.php">About</a></li>
    </ul>
</header>

<div class="main-container-fieldset__search-form-container">
    <div class="main-container-fieldset__search-form-container-center">
        <form method="POST" action="index.php" class="main-container-fieldset__search-form">
            <input class="main-container-fieldset__input-text" type="text" name="adress" placeholder="Введите адрес" value="<?= $adress; ?>">
            <input class="button" type="submit" value="Найти адрес">
        </form>
    </div>
</div>

<div class="main-container-fieldset">
<?php if (isset($_POST['adress'])) { ?>

    <ul class="main-container-fieldset__adresses">
        <?php foreach ($collection as $item) { ?>
            <li class="main-container-fieldset__adresses__item"><a class="main-container-fieldset__adresses__item-link" href="index.php?latitude=<?= $item->getLatitude(); ?>&longitude=<?= $item->getLongitude(); ?>"><?= $item->getAddress(); ?></a></li>
        <?php } ?>
    </ul>

<?php } ?>

</div>

<div id="map" class="main-container-fieldset__map"></div>
</body>
</html>

<script type="text/javascript">
    ymaps.ready(init);
    var myMap,
        myPlacemark;

    function init(){
        myMap = new ymaps.Map("map", {
            center: [<?= $_GET['latitude']; ?>, <?= $_GET['longitude']; ?>],
            zoom: 11
        });

        myPlacemark = new ymaps.Placemark([<?= $_GET['latitude']; ?>, <?= $_GET['longitude']; ?>]);

        myMap.geoObjects.add(myPlacemark);
    }
</script>

