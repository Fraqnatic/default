<meta charset="utf8" />
<?php

//Задание 1
$name = "Алексей";
$age  = 25;
echo "Меня зовут $name <br /> Мне $age лет <br />";
unset($name);
unset($age);

//Задание 2
define("town", "Краснодар");

if (defined("town")==true) 
{
    echo town."<br/>";
}

define("town", "Сочи");

//Задание 3
$book = [
    "title"  => "Гарри Поттер и Филосовский Камень",
    "author" => "Джоан Роулинг",
    "pages"  => "615"
];

$myecho = sprintf('Недавно я прочитал книгу %s,'.
        'написанную автором %s, я осилил все %s страниц, '.
        'мне она очень понравилась.', 
        $book['title'], $book['author'], $book['pages']);

echo $myecho.'<br/>';

//Задание 4
$book1 = [
      "title1"  => "Гарри Поттер и Филосовский Камень",
      "pages1"  => 615,
      "author1" => "Джоан Роулинг"
];

$book2 = [
       "title2"  => "Метро 2034",
       "pages2"  => 588,
       "author2" => "Дмитрий Глуховский"
];
$books = [$book1, $book2];



$myecho = sprintf('Недавно я прочитал книги %s и %s, '. 
    'написанные соответственно авторами %s и %s, '.
    'я осилил в сумме %s страниц, не ожидал от себя подобного.',
        $books[0]['title1'],  $books[1]['title2'],
        $books[0]['author1'], $books[1]['author2'],
        $books[0]['pages1'] + $books[1]['pages2']);
echo $myecho;
    
