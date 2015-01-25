<meta charset="utf8" />
<?php

$ini_string='[игрушка мягкая мишка белый]
цена = '.  mt_rand(1, 10).';
количество заказано = '.  mt_rand(1, 10).';
осталось на складе = '.  mt_rand(0, 10).';
diskont = diskont'.  mt_rand(0, 2).';
    
[одежда детская куртка синяя синтепон]
цена = '.  mt_rand(1, 10).';
количество заказано = '.  mt_rand(1, 10).';
осталось на складе = '.  mt_rand(0, 10).';
diskont = diskont'.  mt_rand(0, 2).';
    
[игрушка детская велосипед]
цена = '.  mt_rand(1, 10).';
количество заказано = '.  mt_rand(1, 10).';
осталось на складе = '.  mt_rand(0, 10).';
diskont = diskont'.  mt_rand(0, 2).';

';
$bd = parse_ini_string($ini_string, true);


// Функция формирующая уведомления о наличии количества товара
function notice($name, $action)
{
    static $output = '<table><th align=left>Уведомление</th>';
    
    if ($action == 'add')
    {
        $output .= sprintf('<tr><td>Вы добавили большее количество товара %s, '.
                'чем имеется на складе. Мы можем выслать Вам столько, '.
                'сколько отображено в таблице выше</td></tr>', 
                "<strong>\"".$name."\"</strong>");
    }
    elseif($action == 'echo')
    {
        $output .= '</table>';
        echo $output.'<br/>';
        $output = '';
    }
}

// Функция формирующая уведомления по скидкам
function discont($goods, $param = null)
{
    static $echo = '<table><th align=left>Скидки</th>';
    global $countGoods;
    
    if($param == null)
    {
        $echo .= sprintf('<tr><td>Вы добавили '.$countGoods.
                ' единицы товара %s, '.
                'поэтому Вам предоставляется скидка 30%%. </td></tr>', 
                "<strong>\"".$goods."\"</strong>");
    }
    else 
    {
        $echo .= '</table>';
        echo $echo;
    }
}

if(count($bd) < 1) // Если количество выбранного товара = 0, ничего не выводим
{
    notice('Вы не выбрали ни одного товара');
}
else // Выводим товары один за другим
{
    $totalCount  = 0;  // Общее количество товара
    $totalAmountOne = 0; // Общая сумма считая по единице заказанного товара
    $totalAmount_clear = 0; // Общая сумма за товар без скидки
    $totalAmount = 0; // Общая сумма за товар + скидка
    
    // Шапка таблицы
    $th = sprintf('<table border=1 cellspacing = 0>'.
            '<tr><td></td><td><h3>%s</h3></td><td><h3>%s</h3></td>'.
            '<td><h3>%s</h3></td><td><h3>%s</h3></td><td><h3>%s</h3></td>'.
            '<td><h3>%s</h3></td><td><h3>%s</h3></td>',
            'Наименование товара', 'Цена за ед. товара', 
            'Цена с учетом наличия', 'Цена со скидкой', 
            'Количество', 'Осталось на складе', 'Скидка');
    echo $th;
    
    // Имеется ли повышенная скидка
    // Для вывода уведомления
    $isSpecialDiscont = false;
    
    // Для вывода уведомления о количестве товара
    $isNotice = false;
    
    // Так как мы не знаем какие товары выбраны, будем перебирать по ключу
    foreach ($bd as $key => $value)
    {
        // Проверим что заказано <= имеется на складе
        $countGoods = (int)$bd[$key]['количество заказано'];
        $allGoods   = (int)$bd[$key]['осталось на складе'];
        
        if ($countGoods > $allGoods)
        {
            $countGoods = $allGoods; 
            notice($key, 'add'); // Добавляем текст уведомления
            $isNotice = true; // Уведомление будет выведено на экран
        }
        
        // Суммируем кол-во товара для итоговой панели
        $totalCount  = $totalCount  + $countGoods; 
        
        // Инициализируем переменную скидок
        $discont = 0;
        
        //Уведомление о скидке по условию задачи (30%)
        if($key == 'игрушка детская велосипед' && $countGoods >= 3){
            discont($key); // Добавляем текст уведомления о повышенной скидке
            $isSpecialDiscont = true; // Будет добавлен текст о повыш. скидке
            $discont = 3; // Для фомирования цены с учетом этой скидки
        }
        else
            // Определяем стандартную скидку 
            // Определим цену за выбранное количество товара
            $discont = preg_replace("/[^0-9]/", '', $bd[$key]['diskont']);
        
        // Инициализиурем цену
        $price = 0;
        $discontPrice = 0;
        
        // Приводим скидку в вид, при котором можно её использовать 
        // для коррекции цены
        switch ($discont)
        {
            case 1:
                $koef = 0.1;
                break;
            case 2:
                $koef = 0.2;
                break;
            case 3:
                $koef = 0.3;
                break;
            default :
                $koef = 1;
                break;
        }
        
        // Определяем цену зная количество
        $price_one = (int)$bd[$key]['цена'];
        $price = $price_one * (int)$bd[$key]['количество заказано'];
        
        // Суммируем цены за единицу товара
        $totalAmountOne = $totalAmountOne + $price_one;
        
        // Добавляем скидку
        $discontPrice = ($koef != 1)? $price - ($price * $koef):$price;
        
        // Запоминаем общую стоимость заказанного для итоговой панели
        // Общая цена без скидки
        $totalAmount_clear = $totalAmount_clear + $price; 
        
        // Общая цена со скидкой
        $totalAmount = $totalAmount + $discontPrice;
        // Формируем информацию по покупке
        
        $row = sprintf('<tr><td></td><td>%s</td><td>%s</td><td>%s</td>'.
                '<td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>',
                $key, $price_one, $price, $discontPrice, $countGoods, 
                $bd[$key]['осталось на складе'], 
                ($discont == '0') ? '0%' : $discont.'0%' 
                );
        
        // Выводим на обозрение детальную информацию по заказанному товару
        echo $row;
    }
    
    // Итого
    $total = sprintf('<tr><td>Итого</td><td>%s</td><td>%s</td><td>%s</td>'.
            '<td>%s</td><td>%s</td></tr>',
            count($bd), 
            $totalAmountOne, $totalAmount_clear, $totalAmount, $totalCount);
    
    echo $total;
    
    echo '</table><br/>';
    
    // Если имеются уведомления - выводим
    if($isNotice) 
        echo notice(null, 'echo');
    
    // Если имеют повышенные скидки - уведомляем
    if($isSpecialDiscont)
        echo discont(null, 'get_output');
}