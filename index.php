<?php

include ('.'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'const.php'); // константы сайта
include (ENGINE_PATH.'func.php'); // функции сайта
require_once SITE_ROOT.'vendor'.DIRECTORY_SEPARATOR.'autoload.php'; // автолоадер Composer, Syphony и Twig

$loader = new Twig_Loader_Filesystem('./templates'); // объект Twig с папкой шаблонов
$twig = new Twig_Environment($loader, // объект Twig с путем до папки кэширования отрендеренных шаблонов
    [
        'cache' => './cache',
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
$galleryArr = getSQLdata (PHOTOS, '*');
$gallery = [];
foreach ($galleryArr as $val) {
    $arr =
        [
            'id' => $val['id'],
            'file_name' => $val['file_name'],
            'description' => $val['description'],
            'counter' => $val['counter']
        ];
    array_push ($gallery, $arr);
}

// Комментарии
$commentsArr = getSQLdata (COMMENTS, '*', 'ORDER BY comment_id DESC LIMIT 10');
$comments = [];
foreach ($commentsArr as $val) {
    $arr = 
        [
            'user_name' => $val['user_name'],
            'comment_date' => $val['comment_date'],
            'comment_text' => $val['comment_text'],
            'comment_id' => $val['comment_id']
        ];
    array_push ($comments, $arr);
}

// Вывод

echo $twig->render('index.html',
    [
        'styles' => $styles,
        'js' => $js,
        'gallery' => $gallery,
        'comments' => $comments
    ]);