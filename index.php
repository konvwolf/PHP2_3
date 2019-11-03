<?php

include ('.'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'const.php'); // константы сайта
include (ENGINE_PATH.'func.php'); // функции сайта
require_once SITE_ROOT.'vendor'.DIRECTORY_SEPARATOR.'autoload.php'; // автолоадер Composer, Symphony и Twig

$loader = new Twig_Loader_Filesystem(TEMPLATES_PATH); // объект Twig с папкой шаблонов
$twig = new Twig_Environment($loader, // объект Twig с путем до папки кэширования отрендеренных шаблонов
    [
        'cache' => CACHE_PATH
    ]);

// Массив с названиями файлов стилей
$styles = [];
foreach (scanDir4Files(STYLES_PATH) as $file) {
    array_push ($styles, $file);
}

// Массив с названиями файлов JS-скриптов
$js = [];
foreach (scanDir4Files(JS_PATH) as $file) {
    array_push ($js, $file);
}

// Инкремент счетчика просмотра картинок в базе данных
if ($picID = $_POST['picID']) {
    updateSQLtable(PHOTOS, 'counter', 'counter + 1', "WHERE id = $picID");
}

// Галлерея
$gallery = getSQLdata (PHOTOS, '*');

// Комментарии
$comments = getSQLdata (COMMENTS, '*', 'ORDER BY comment_id DESC LIMIT 10');

// Вывод

echo $twig->render('index.html',
    [
        'title' => 'Автомобильная фотогалерея',
        'styles' => $styles,
        'js' => $js,
        'gallery' => $gallery,
        'comments' => $comments
    ]);