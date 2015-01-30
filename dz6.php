<?php
header('Content-type: text/html; charset=utf-8');

session_start();

define('thisPage', 'dz6.php');

// Храним id объявлений чтобы не плодить дупликаты
$id = [];

// Храним объявление для заполнения формы (режим редактирования)
$adv = [];

// Редактирование объявления
if(isset($_GET['edit']) && $_GET['edit'] == true)
{
    foreach ($_SESSION['history'] as $key => $value)
    {
        if ($value['id'] == $_GET['id']){
           $adv = $value;
           break;
        }
    }
}

?>

<form  method="post">
    <label>
        <input type="radio" name="private" value="1"
           <?= (isset($adv['private']) && $adv['private'] == '1' )
                ? 'checked': (!isset($adv['private'])) ? 'checked':'' 
            ?> 
        >Частное лицо
    </label> 
    
    <label>
        <input type="radio" name="private" value="0"
            <?= (isset($adv['private']) && $adv['private'] == '0' ) 
                ? 'checked':'' 
             ?>   
        >Компания
    </label> 
    <br/>
    
    <label for="fld_seller_name"><b id="your-name">Ваше имя</b></label> 
        <input type="text" maxlength="40" name="seller_name" 
               id="fld_seller_name"
               <?= (isset($adv['seller_name']) && trim($adv['seller_name']) != '' ) 
                ? 'value="'.$adv['seller_name'].'"':'value=""'
                ?>        
        >
    <br/>
    
    <label for="fld_manager"><b>Контактное лицо</b></label> 
        <input type="text" maxlength="40"  name="manager" id="fld_manager"
               <?= (isset($adv['manager']) && trim($adv['manager']) != '' ) 
                ? 'value="'.$adv['manager'].'"':'value=""'
                ?>
        >
        <em>&nbsp;&nbsp;необязательно</em><br/>
        
   <label for="fld_email">Электронная почта</label>
        <input type="text" name="email" id="fld_email"
               <?= (isset($adv['email']) && trim($adv['email']) != '' ) 
                ? 'value="'.$adv['email'].'"':'value=""'
                ?>
        >
    <br/>
    
    <label for="allow_mails"> 
        <input type="checkbox" value="1" name="allow_mails" id="allow_mails"
               <?= (isset($adv['allow_mails']) && $adv['allow_mails'] == '1' )
                ? 'checked': '' 
                ?> 
        >
        <span>Я не хочу получать вопросы по объявлению по e-mail</span> 
    </label>
    <br/>
    
    <label id="fld_phone_label" for="fld_phone">Номер телефона</label> 
        <input type="text" name="phone" id="fld_phone"
               <?= (isset($adv['phone']) && trim($adv['phone']) != '' ) 
                ? 'value="'.$adv['phone'].'"':'value=""'
                ?>
        >
    <br/>
    
    <label for="region">Город</label>  
    
    <?php
    // Вывод Города
    getCityList($adv);
    
    // Вывод Категории
    getCategoryList($adv);
    
    ?>  
    
    <label>Выберите параметры</label> <br/>
    <label for="fld_title">Название объявления</label> 
        <input type="text" maxlength="50" name="title" id="fld_title"
               <?= (isset($adv['title']) && trim($adv['title']) != '' ) 
                ? 'value="'.$adv['title'].'"':'value=""'
                ?>
        >
    <br/>
    
    <!-- ОПИСАНИЕ ОБЪЯВЛЕНИЯ -->
    <label for="fld_description" id="js-description-label">Описание объявления</label> 
        <?php
              if(isset($adv['description']) && trim($adv['description']) != '' ) 
              {
                  
                  echo sprintf('<textarea name="description" '.
                          'id="fld_description">%s</textarea>',
                          $adv['description']);
              } 
              else
              {
                  echo '<textarea name="description" '.
                          'id="fld_description"></textarea>';
              }
            ?>
        
    <br/>
    <label id="price_lbl" for="fld_price">Цена</label> 
        <input type="text" maxlength="9" name="price" id="fld_price"
               <?= (isset($adv['price']) && trim($adv['price']) != '' ) 
                ? 'value="'.$adv['price'].'"':'value="0"'
                ?>
        >&nbsp;
            <span id="fld_price_title">руб.</span> 
                <a id="js-price-link" href="/info/pravilnye_ceny?plain">
                    <span>Правильно указывайте цену</span>
                </a>
    <!-- Передадим id в обработчик для сохранения измененного объявления -->
    <?=(isset($_GET['id'])) ? '<input type="hidden" name="id" value="'.
                    $_GET['id'].'"><br/>' : '<br/>'
    ?>
    <input type="submit" id="form_submit" name="main_form_submit"
           <?php echo (!isset($_GET['id']) ?
                   'value="Добавить"' : 'value="Редактировать"')?>
    > 
</form>

<?php 
// Если страница открыта в результате POST запроса
// то добавляем новое объявление
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if ($_POST['main_form_submit'] == 'Добавить')
    {
        // Удаляем лишние сведения о кнопке submit
        array_pop($_POST);
        
        // Получим список ранее сгенерированных id
        if(isset($_SESSION['history']) && count($_SESSION['history'] > 0))
            getIDs($id);

        // Генерируем id 
        generateID($id);

        // Добавляем объявление в сессию
        $_SESSION['history'][date('d.m.Y H:i:s')] = $_POST;

        // Добавляем ID к объявлению
        $_SESSION['history'][date('d.m.Y H:i:s')]['id'] = $id[count($id)-1];  
    }
    else
    {
        // Удаляем лишние сведения о кнопке submit
        array_pop($_POST);
        
        // Редактирование объявления
        // Найдем объявление
        $adb_id = $_POST['id'];
        foreach ($_SESSION['history'] as $key => $value)
        {
            if($value['id'] == $adb_id)
            {
                // Внеcем правки в объявление
                $_SESSION['history'][$key] = $_POST;
                $_SESSION['history'][$key]['id'] = $adb_id;
                break;;
            }
        }
        echo '<script>window.location.href="'.thisPage.'";</script>';
    }
}

// Если страничка обновлена
if(isset($_SESSION['history']) && count($_SESSION['history']) > 0)
{
    // Имеются объявления для отображения
    echo '<p><h3>Объявления</h3></p>';
    getList();
}

// Удаление объявления
if(isset($_GET['del']) && $_GET['del'] == true)
{
    foreach ($_SESSION['history'] as $key => $value)
    {
        if ($value['id'] == $_GET['id']){
            unset($_SESSION['history'][$key]);
            break;
        }
    }
    echo '<script>window.location.href = \'' .thisPage.'\';</script>';
}

// Получение списка всех id объявлений
function getIDs(&$id)
{
    foreach ($_SESSION['history'] as $key=>$value)
    {
        $id[] = $value['id'];  
    }
}

// Генерируем новый ID
function generateID(&$id)
{
    while(true)
    {
        $temp = mt_rand(10000, 100000);
        if(!in_array($temp, $id))
        {
            array_push($id, $temp);
            break;
        }
    }    
}
    
// Получение объявлений
function getList()
{
    echo '<table>';
    $myEcho = '';
    foreach ($_SESSION['history'] as $key => $value)
    {
        // Название объявления
        $myEcho .= sprintf('<tr><td>%s</td>', $value['title']);
        
        // Изменить
        $myEcho .= sprintf('<td><a href="%s?id=%s&edit=true">Изменить</a></td>',
                           thisPage, $value['id']);
        
        // Удалить
        $myEcho .= sprintf('<td><a href="%s?id=%s&del=true">Удалить</a></td>',
                           thisPage, $value['id']);
    }
    echo $myEcho.'</table>';
}

function getCityList(&$adv)
{
    $city = <<<CITY
<select title="Выберите Ваш город" name="location_id" id="region"><br/>
    <option value="">-- Выберите город --</option>
        <option disabled="disabled">-- Города --</option>
        <option data-coords=",," value="641780">Новосибирск</option> 
        <option data-coords=",," value="641490">Барабинск</option>   
        <option data-coords=",," value="641510">Бердск</option>   
        <option data-coords=",," value="641600">Искитим</option>   
        <option data-coords=",," value="641630">Колывань</option>   
        <option data-coords=",," value="641680">Краснообск</option>   
        <option data-coords=",," value="641710">Куйбышев</option>   
        <option data-coords=",," value="641760">Мошково</option>   
        <option data-coords=",," value="641790">Обь</option>   
        <option data-coords=",," value="641800">Ордынское</option>   
        <option data-coords=",," value="641970">Черепаново</option>   
    <option id="select-region" value="0">Выбрать другой...</option> 
</select> <br/>
CITY;
    if(isset($adv['location_id']))
    {
        $search = 'value="'.$adv['location_id'].'"';
        $replace = $search . ' selected=""';
        $city = str_replace($search, $replace, $city);
    }
    echo $city;
}
function getCategoryList(&$adv)
{
    $category = <<<CATEGORY
<label for="fld_category_id">Категория</label> 
<select title="Выберите категорию объявления" 
        name="category_id" id="fld_category_id"> 
    <option value="">-- Выберите категорию --</option>
    <optgroup label="Транспорт">
        <option value="9">Автомобили с пробегом</option>
        <option value="109">Новые автомобили</option>
        <option value="14">Мотоциклы и мототехника</option>
        <option value="81">Грузовики и спецтехника</option>
        <option value="11">Водный транспорт</option>
        <option value="10">Запчасти и аксессуары</option>
    </optgroup><optgroup label="Недвижимость">
        <option value="24">Квартиры</option>
        <option value="23">Комнаты</option>
        <option value="25">Дома, дачи, коттеджи</option>
        <option value="26">Земельные участки</option>
        <option value="85">Гаражи и машиноместа</option>
        <option value="42">Коммерческая недвижимость</option>
        <option value="86">Недвижимость за рубежом</option>
    </optgroup><optgroup label="Работа">
        <option value="111">Вакансии (поиск сотрудников)</option>
        <option value="112">Резюме (поиск работы)</option>
    </optgroup>
    <optgroup label="Услуги">
        <option value="114">Предложения услуг</option>
        <option value="115">Запросы на услуги</option>
    </optgroup>
    <optgroup label="Личные вещи">
        <option value="27">Одежда, обувь, аксессуары</option>
        <option value="29">Детская одежда и обувь</option>
        <option value="30">Товары для детей и игрушки</option>
        <option value="28">Часы и украшения</option>
        <option value="88">Красота и здоровье</option>
    </optgroup><optgroup label="Для дома и дачи">
        <option value="21">Бытовая техника</option>
        <option value="20">Мебель и интерьер</option>
        <option value="87">Посуда и товары для кухни</option>
        <option value="82">Продукты питания</option>
        <option value="19">Ремонт и строительство</option>
        <option value="106">Растения</option></optgroup>
    <optgroup label="Бытовая электроника">
        <option value="32">Аудио и видео</option>
        <option value="97">Игры, приставки и программы</option>
        <option value="31">Настольные компьютеры</option>
        <option value="98">Ноутбуки</option>
        <option value="99">Оргтехника и расходники</option>
        <option value="96">Планшеты и электронные книги</option>
        <option value="84">Телефоны</option>
        <option value="101">Товары для компьютера</option>
        <option value="105">Фототехника</option></optgroup>
    <optgroup label="Хобби и отдых">
        <option value="33">Билеты и путешествия</option>
        <option value="34">Велосипеды</option>
        <option value="83">Книги и журналы</option>
        <option value="36">Коллекционирование</option>
        <option value="38">Музыкальные инструменты</option>
        <option value="102">Охота и рыбалка</option>
        <option value="39">Спорт и отдых</option>
        <option value="103">Знакомства</option></optgroup>
    <optgroup label="Животные">
        <option value="89">Собаки</option>
        <option value="90">Кошки</option>
        <option value="91">Птицы</option>
        <option value="92">Аквариум</option>
        <option value="93">Другие животные</option>
        <option value="94">Товары для животных</option>
    </optgroup><optgroup label="Для бизнеса">
        <option value="116">Готовый бизнес</option>
        <option value="40">Оборудование для бизнеса</option>
    </optgroup>
</select>
CATEGORY;
    if(isset($adv['category_id']))
    {
        $search = 'value="'.$adv['category_id'].'"';
        $replace = $search . ' selected=""';
        $category = str_replace($search, $replace, $category);
    }
    echo $category;
}

?>

