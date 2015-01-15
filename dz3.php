<meta charset="utf8" />
<?php

// Формируем массив с произвольными датами
$date = [
    date('d.m.Y', rand(time(), 1)),
    date('d.m.Y', rand(time(), 1)),
    date('d.m.Y', rand(time(), 1)),
    date('d.m.Y', rand(time(), 1)),
    date('d.m.Y', rand(time(), 1)),
];

// Ищем минимальный день среди всех дат
echo 'Минимальный день: ';
echo min(substr($date[0], 0, 2),
         substr($date[1], 0, 2),
         substr($date[2], 0, 2),
         substr($date[3], 0, 2),
         substr($date[4], 0, 2)
    );
echo '<br/>';

// Ищем максимальный месяц среди всех дат
echo 'Максимальный месяц: ';
echo max(substr($date[0], 3, 2),
         substr($date[1], 3, 2),
         substr($date[2], 3, 2),
         substr($date[3], 3, 2),
         substr($date[4], 3, 2)
    );
echo '<br/>';

// Переводим даты из строкового формата в юниксовую метку числового
// для того, чтобы можно было эти даты отсортировать
$date[0] = mktime(0, 0, 0, substr($date[0], 3, 2),
                           substr($date[0], 0, 2),
                           substr($date[0], 6, 4));

$date[1] = mktime(0, 0, 0, substr($date[1], 3, 2),
                           substr($date[1], 0, 2),
                           substr($date[1], 6, 4));

$date[2] = mktime(0, 0, 0, substr($date[2], 3, 2),
                           substr($date[2], 0, 2),
                           substr($date[2], 6, 4));

$date[3] = mktime(0, 0, 0, substr($date[3], 3, 2),
                           substr($date[3], 0, 2),
                           substr($date[3], 6, 4));

$date[4] = mktime(0, 0, 0, substr($date[4], 3, 2),
                           substr($date[4], 0, 2),
                           substr($date[4], 6, 4));
sort($date);

// Вытаскиваем последний элемент массива и ложим в переменную
$selected = array_pop($date);

// Выводим дату в читабельном формате
echo date('d.m.Y H:i:s', $selected);
echo "<br/>";

date_default_timezone_set('America/New_York');
echo date_default_timezone_get();

?>