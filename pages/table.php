<?php

namespace techmap\pages;

include_once __DIR__ . '/../vendor/autoload.php';

use techmap\classes\HTML;
use techmap\classes\MySQL;
use techmap\classes\Security;
use techmap\classes\User;

// Скрипт доступен только авторизированным пользователям.
Security::accessRegisteredUsersOnly();

// Получаем данные.
// Получаем id пользователя.
$UserDataArray = User::getAllUserDataBasedOnToken();
$userId = $UserDataArray['id'];
$userRole = $UserDataArray['user_role'];


if ($userRole == 'Администратор') $sqlString = ''; else $sqlString = "WHERE FIND_IN_SET('$userId', `users_id`) > 0";

// Показ таблицы.
$query = "SELECT * FROM `techmaptable` $sqlString";
$queryResult = MySQL::mySQLQuery($query);
$num_rows = $queryResult->num_rows;
$field_count = $queryResult->field_count;
$tableFieldCount = $field_count - 2; // Количество колонок в отображаемой таблице (2 служебные)

$TechmapTableRowsArray = array();
while ($row = $queryResult->fetch_assoc()) {
    $id = $row['id'];
    array_push($TechmapTableRowsArray, '<tr>');

    array_push($TechmapTableRowsArray, '<td class="checkbox_cell"><input type="checkbox" value="' . $id . '"></td>');

    for ($tableFieldNumber = 1; $tableFieldNumber <= $tableFieldCount; $tableFieldNumber++) {
        if ($tableFieldNumber == 1 and $userRole != 'Администратор') $contenteditable = ''; else $contenteditable = 'contenteditable';
        $fieldValue = $row['field' . $tableFieldNumber];
        $data_field = 'field' . $tableFieldNumber;
        array_push($TechmapTableRowsArray, '<td ' . $contenteditable . ' class="edit_cell" data-id="' . $id . '" data-field="' . $data_field . '">' . $fieldValue);
        array_push($TechmapTableRowsArray, '</td>');
    }


    array_push($TechmapTableRowsArray, '</tr>');
}

echo HTML::showTechmapTable($TechmapTableRowsArray);


?>
