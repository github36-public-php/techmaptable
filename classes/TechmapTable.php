<?php
include_once __DIR__.'/MySQL.php';
include_once __DIR__.'/User.php';

class TechmapTable
{

// Получить количество строк в таблице.
static function GetTechmapTableFieldCount() {
$query = "SELECT * FROM `techmaptable` ";
$queryResult = MySQL::MySQLQuery($query);
$field_count = $queryResult->field_count;
return $field_count;
}



// Проверить права пользователя на доступ к строке таблицы.
// (Поиск id пользователя в строке users_id .)
static function CheckTechmapTableUserRights($TechmapTableId, $userId) {
$result = false; // По умолчанию.
$query = "SELECT `users_id` FROM `techmaptable` WHERE `id` = '$TechmapTableId' LIMIT 1";
$queryResult = MySQL::MySQLQuery($query);
$num_rows = $queryResult->num_rows;
if ($num_rows !=0)
{
$queryResultArray = $queryResult->fetch_assoc();
$users_id = $queryResultArray['users_id'];
if ($queryResultArray != NULL)
{
$UsersIdArray = explode(",", $users_id);
foreach ($UsersIdArray as $UsersIdElement)
{
if ($userId == $UsersIdElement) {$result = true; break;}
}
}
}
return $result;
}




// Обновить данные в таблице
static function UpdateTechmapTable($field, $td_text, $id)
{
$query = "UPDATE `techmaptable` SET $field='$td_text' WHERE id='$id'";
$queryResult = MySQL::MySQLQuery($query);
return $queryResult;
}






// Получить все данные из таблицы исходя из указанных id (строка вида 1,2,3).
static function GetTechmapTableDataFromIds($TechmapTableIdArray) {
$TableDataArray = array();	

if ($TechmapTableIdArray != NULL ) 
{
foreach ($TechmapTableIdArray as $TechmapTableIdArrayElement)
{
$query = "SELECT * FROM `techmaptable` WHERE `id` = $TechmapTableIdArrayElement";
$queryResult = MySQL::MySQLQuery($query);
$num_rows = $queryResult->num_rows;	
if ($num_rows!=0)
{
$queryResultArray = $queryResult->fetch_assoc();
array_push($TableDataArray,$queryResultArray);	
}
}
return $TableDataArray;
}
else return '';

}















}
?>