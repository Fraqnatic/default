<meta charset="utf8" />
<?php

// Формируем массив с произвольными датами
$date = [
      rand(1, time()),
      rand(1, time()),
      rand(1, time()),
      rand(1, time()),
      rand(1, time()),
];
echo $date[0];
echo '<br/>';
// Ищем минимальный день среди всех дат
echo 'Минимальный день: ';
echo min(date('d', $date[0]),
         date('d', $date[1]),
         date('d', $date[2]),
         date('d', $date[3]),
         date('d', $date[4])
    );
echo '<br/>';

// Ищем максимальный месяц среди всех дат
echo 'Максимальный месяц: ';
echo max(date('m', $date[0]),
         date('m', $date[1]),
         date('m', $date[2]),
         date('m', $date[3]),
         date('m', $date[4])
    );
echo '<br/>';

sort($date);

// Вытаскиваем последний элемент массива и ложим в переменную
$selected = array_pop($date);

// Выводим дату в читабельном формате
echo date('d.m.Y H:i:s', $selected);
echo "<br/>";

date_default_timezone_set('America/New_York');
echo date_default_timezone_get();

?>