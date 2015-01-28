<?php

error_reporting(E_WARNING|E_NOTICE|E_ERROR|E_PARSE);

header('Content-type: text/html; charset=utf-8');

define('mainNews', 'dz5-2.php');

$news='Четыре новосибирские компании вошли в сотню лучших работодателей
Выставка университетов США: открой новые горизонты
Оценку «неудовлетворительно» по качеству получает каждая 5-я квартира в новостройке
Студент-изобретатель раскрыл запутанное преступление
Хоккей: «Сибирь» выстояла против «Ак Барса» в пятом матче плей-офф
Здоровое питание: вегетарианская кулинария
День святого Патрика: угощения, пивной теннис и уличные гуляния с огнем
«Красный факел» пустит публику на ночные экскурсии за кулисы и по закоулкам столетнего здания
Звезды телешоу «Голос» Наргиз Закирова и Гела Гуралиа споют в «Маяковском»';

$news = explode("\n", $news);

// Получение всех новостей
function get_all($news)
{
	foreach ($news as $key => $value) {
		echo $value.'<br/>';
	}
}

// Получение конкретной новости
function getNewsById($id)
{
	global $news;

	echo $news[$id-1].'<br/>';
	
	echo '<a href=\"'.mainNews.'\">Все новости</a>';
}


// Точка входа
// Если параметр id в запросе POST есть
if(isset($_POST['id']) && !empty($_POST['id']))
{
	$id = $_POST['id'] * 1; // Если id было отрицательным
	 
	// Проверяем чтобы номер новости был в массиве
	if ($id <= count($news))
	{
		getNewsById($id);
	}

	// Если новости в массиве нет
	else
	{
		header('HTTP/1.0 404 NOT FOUND');	
		echo '<h3>Страница с такой новостью не найдена, попробуйте по искать другую</h3><br/>';
		echo '<a href=\"'.mainNews.'\">Все новости';
	}
}

// Если id в запросе POST не был передан, выводим все новости
else
{
	get_all($news);
}
?>

<!DOCTYPE HTML>
<html>
<head></head>
<body>
	<form method="POST" action="<?php mainNews ?>">
		<p>
			<input type="text" name="id" value="" />
			<input type="submit" value="Показать" />
		</p>
	</form>
</body>
</html>

