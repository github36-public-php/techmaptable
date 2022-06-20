<?php
include_once __DIR__.'/User.php';

// Получить информацию, переданную методом GET.
class Data
{

static function GET($parameterName) {
$data=$_GET[$parameterName];
//TODO Тут фильтрация данных
return $data;
}

static function POST($parameterName) {
$data=$_POST[$parameterName];
//TODO Тут фильтрация данных
return $data;
}




// Удалить первые колонки из массива
static function RemoveColumnsFromArray($Array, $columnsNumber)
{
$NewArray=array();	
foreach ($Array as $ArrayElement)
{
array_push($NewArray,array_slice($ArrayElement, $columnsNumber));  
}
return $NewArray;
}

























}

?>