<?php

namespace techmap;

include_once __DIR__.'/vendor/autoload.php';
use techmap\classes\Address;
use techmap\classes\Data;
use techmap\classes\Files;
use techmap\classes\HTML;
use techmap\classes\MySQL;
use techmap\classes\Security;
use techmap\classes\Settings;
use techmap\classes\TechmapTable;
use techmap\classes\User;

// Контроллер.

// ВХОДНЫЕ ДАННЫЕ.
// Получаем массив настроек.
$SettingsIni = Settings::getSettingsIniArray();
$cookieTime = $SettingsIni['cookietime'];
$cookieName = $SettingsIni['cookiename'];

// Получаем общие данные (действие).
$action=Data::getData('action');

// Создание, редактирование или удаление пользователя
if ($action=='user_add' or $action=='user_edit' or $action=='user_delete')
{
// Получаем данные.
$id=Data::postData('id');
$login=Data::postData('login');
$password=Data::postData('password');
$user_role=Data::postData('user_role');
$user_surname=Data::postData('user_surname');
$user_name=Data::postData('user_name');
$user_patronymic=Data::postData('user_patronymic');
$user_description=Data::postData('user_description');
//TODO тут проверка данных
}

// Создание, редактирование или удаление адреса
if ($action=='address_add' or $action=='address_edit' or $action=='address_delete' or $action=='table_update')
{
// Получаем данные.
$id=Data::postData('id');
$usersIdArray=Data::postData('users_id');
$field1=Data::postData('field1');
//Создаем строку из id
if ($usersIdArray != NULL) $users_id=implode(',', $usersIdArray);
//$users_id=$users_id.',';
//TODO тут проверка данных
}


// Экспорт данных
if ($action=='excel_export')
{
$checkboxIdArray=Data::postData('checkbox_id');
//Создаем строку из id
//if ($checkboxIdArray != NULL) $checkbox_id=implode(',', $checkboxIdArray);
}
// КОНЕЦ ВХОДНЫХ ДАННЫХ





// ВХОД ПОЛЬЗОВАТЕЛЯ В СИСТЕМУ.
if ($action=='login')
{
// Получаем данные.
$login=Data::postData('login');
$password=Data::postData('password');

// Проверка логина/пароля.
$UserLoginArray = User::checkUserLoginPassword($login, $password);
$UserLoginArrayResult = $UserLoginArray[0];
$UserLoginArrayData = $UserLoginArray[1];


// Если логин/пароль верный.
if ($UserLoginArrayResult == 'correct') {
	
	
//Код для демонстрации - обновляем базу, заполняем её типовыми данными
//Импорт БД из файла demo_base.sql
$filename=__DIR__.'/demo_base.sql';
// Temporary variable, used to store current query
$templine = '';
// Read in entire file
$lines = file($filename);
// Loop through each line
foreach ($lines as $line)
{
// Skip it if it's a comment
if (substr($line, 0, 2) == '--' || $line == '')
continue;
// Add this line to the current segment
$templine .= $line;
// If it has a semicolon at the end, it's the end of the query
if (substr(trim($line), -1, 1) == ';')
{
	


MySQL::mySQLQuery($templine);
//mysql_query($templine) or print('Error performing query \'<strong>' . $templine . '\': ' . mysql_error() . '<br /><br />');
// Reset temp variable to empty
$templine = '';
}
}
//Конец кода для демонстрации
	
	
	
$userId = $UserLoginArrayData;
User::setUserToken($userId);
header("Location: index.php?page=table&page_number=1");
exit;
}


if ($UserLoginArrayData == 'incorrect_symbols_in_login_or_password') $messageText = 'В логине или пароле использованы недопустимые символы.';
if ($UserLoginArrayData == 'incorrect_login_or_password') $messageText = 'Неверный логин/пароль.';

if ($UserLoginArrayResult == 'incorrect')
{
$buttonHref='/';
echo HTML::showInformationPage($messageText, $buttonHref);
exit;
}

exit;
}



// ВЫХОД ПОЛЬЗОВАТЕЛЯ ИЗ СИСТЕМЫ.
if ($action=='logout')
{
setcookie($cookieName, '', 0, "/");
header('Location: index.php');
exit;
}





// ВСЕ ДЕЙСТВИЯ НИЖЕ ДОСТУПНЫ ТОЛЬКО АВТОРИЗИРОВАННЫМ ПОЛЬЗОВАТЕЛЯМ
Security::accessRegisteredUsersOnly();






// Сохранение (обновление) таблицы.
// Передача всех параметров происходит методом POST из js скрипта
if ($action=='table_update')
{
// Получаем данные.
$id=Data::postData('id');
$field=Data::postData('field');
$td_text=Data::postData('td_text');


$methodResult = TechmapTable::updateTechmapTable($field, $td_text, $id);
//header нет перехода т.к. действие выполняется черех ajax.
exit;
}







// ВСЕ ДЕЙСТВИЯ НИЖЕ ДОСТУПНЫ ТОЛЬКО АДМИНИСТРАТОРАМ
Security::accessAdminOnly();






// Создание пользователя.
if ($action=='user_add')
{
$methodResult = User::createNewUser($login, $password, $user_role, $user_name, $user_surname, $user_patronymic, $user_description);
if ($methodResult=='incorrect_symbols_in_login_or_password') {echo HTML::showInformationPage('В логине/пароле могут быть только английские буквы и цифры.', $_SERVER['HTTP_REFERER']);exit;}
if ($methodResult=='login_cannot_be_empty') {echo HTML::showInformationPage('Логин не может быть пустым.', $_SERVER['HTTP_REFERER']);exit;}
if ($methodResult=='password_cannot_be_empty') {echo HTML::showInformationPage('Пароль не может быть пустым.', $_SERVER['HTTP_REFERER']);exit;}
if ($methodResult=='user_exist') {echo HTML::showInformationPage('Такой пользователь уже существует.', $_SERVER['HTTP_REFERER']);exit;}
header('Location: index.php?page=admin&section=users');
exit;
}


// Редактирование пользователя.
if ($action=='user_edit')
{
$methodResult = User::updateUser($id, $login, $password, $user_role, $user_name, $user_surname, $user_patronymic, $user_description);
if ($methodResult=='incorrect_symbols_in_login_or_password') {echo HTML::showInformationPage('В логине/пароле могут быть только английские буквы и цифры.', $_SERVER['HTTP_REFERER']);exit;}
if ($methodResult=='login_cannot_be_empty') {echo HTML::showInformationPage('Логин не может быть пустым.', $_SERVER['HTTP_REFERER']);exit;}
if ($methodResult=='user_does_not_exist') {echo HTML::showInformationPage('Такой пользователь не существует.', $_SERVER['HTTP_REFERER']);exit;}
header('Location: index.php?page=admin&section=users');
exit;
}

// Удаление пользователя.
if ($action=='user_delete')
{
// Получаем данные.
$id=Data::getData('id');
$methodResult = User::deleteUser($id);
if ($methodResult=='user_does_not_exist') {echo HTML::showInformationPage('Такой пользователь не существует.', $_SERVER['HTTP_REFERER']);exit;}
header('Location: index.php?page=admin&section=users');
exit;
}



// Создание адреса.
if ($action=='address_add')
{
$methodResult = Address::createNewAddress($users_id, $field1);
if ($methodResult=='no_field1') {echo HTML::showInformationPage('Введите название адреса.', $_SERVER['HTTP_REFERER']);exit;}
header('Location: index.php?page=admin&section=address');
exit;
}


// Редактирование адреса.
if ($action=='address_edit')
{
$methodResult = Address::editAddress($id, $users_id, $field1);
if ($methodResult=='no_field1') {echo HTML::showInformationPage('Введите название адреса.', $_SERVER['HTTP_REFERER']);exit;}
if ($methodResult=='address_does_not_exist') {echo HTML::showInformationPage('Такой адрес не существует.', $_SERVER['HTTP_REFERER']);exit;}
header('Location: index.php?page=admin&section=address');
exit;
}


// Удаление адреса.
if ($action=='address_delete')
{
// Получаем данные.
$id=Data::getData('id');
$methodResult = Address::deleteAddress($id);
if ($methodResult=='address_does_not_exist') {echo HTML::showInformationPage('Такой адрес не существует.', $_SERVER['HTTP_REFERER']);exit;}
header('Location: index.php?page=admin&section=address');
exit;
}








// Экспорт данных
if ($action=='excel_export')
{
$pathToUserFile = Files::excelExport($checkboxIdArray);
//var_dump($url);
// Действие выполняется черех ajax - отдаем назад ссылку.	
if ($pathToUserFile !='') $url = '<a href ="'.$pathToUserFile.'">Скачать Excel файл</a>'; else 
$url = 'Для создания Excel файла выберите один или несколько адресов.';
echo $url;
exit;
}
























?>