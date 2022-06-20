<?php
include_once __DIR__.'/classes/classes.php';

// Контроллер.

// ВХОДНЫЕ ДАННЫЕ.
// Получаем массив настроек.
$SettingsIni = Settings::GetSettingsIniArray();
$cookieTime = $SettingsIni['cookietime'];
$cookieName = $SettingsIni['cookiename'];

// Получаем общие данные (действие).
$action=Data::GET('action');

// Создание, редактирование или удаление пользователя
if ($action=='user_add' or $action=='user_edit' or $action=='user_delete')
{
// Получаем данные.
$id=Data::POST('id');
$login=Data::POST('login');
$password=Data::POST('password');
$user_role=Data::POST('user_role');
$user_surname=Data::POST('user_surname');
$user_name=Data::POST('user_name');
$user_patronymic=Data::POST('user_patronymic');
$user_description=Data::POST('user_description');
//TODO тут проверка данных
}

// Создание, редактирование или удаление адреса
if ($action=='address_add' or $action=='address_edit' or $action=='address_delete' or $action=='table_update')
{
// Получаем данные.
$id=Data::POST('id');
$usersIdArray=Data::POST('users_id');
$field1=Data::POST('field1');
//Создаем строку из id
if ($usersIdArray != NULL) $users_id=implode(',', $usersIdArray);
//$users_id=$users_id.',';
//TODO тут проверка данных
}


// Экспорт данных
if ($action=='excel_export')
{
$checkboxIdArray=Data::POST('checkbox_id');
//Создаем строку из id
//if ($checkboxIdArray != NULL) $checkbox_id=implode(',', $checkboxIdArray);
}
// КОНЕЦ ВХОДНЫХ ДАННЫХ





// ВХОД ПОЛЬЗОВАТЕЛЯ В СИСТЕМУ.
if ($action=='login')
{
// Получаем данные.
$login=Data::POST('login');
$password=Data::POST('password');

// Проверка логина/пароля.
$UserLoginArray = User::CheckUserLoginPassword($login, $password);
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
	


MySQL::MySQLQuery($templine);
//mysql_query($templine) or print('Error performing query \'<strong>' . $templine . '\': ' . mysql_error() . '<br /><br />');
// Reset temp variable to empty
$templine = '';
}
}
//Конец кода для демонстрации
	
	
	
$userId = $UserLoginArrayData;
User::SetUserToken($userId);
header("Location: index.php?page=table&page_number=1");
exit;
}


if ($UserLoginArrayData == 'incorrect_symbols_in_login_or_password') $messageText = 'В логине или пароле использованы недопустимые символы.';
if ($UserLoginArrayData == 'incorrect_login_or_password') $messageText = 'Неверный логин/пароль.';

if ($UserLoginArrayResult == 'incorrect')
{
$buttonHref='/';
echo HTML::ShowInformationPage($messageText, $buttonHref);
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
Security::AccessRegisteredUsersOnly();






// Сохранение (обновление) таблицы.
// Передача всех параметров происходит методом POST из js скрипта
if ($action=='table_update')
{
// Получаем данные.
$id=Data::POST('id');
$field=Data::POST('field');
$td_text=Data::POST('td_text');


$methodResult = TechmapTable::UpdateTechmapTable($field, $td_text, $id);
//header нет перехода т.к. действие выполняется черех ajax.
exit;
}







// ВСЕ ДЕЙСТВИЯ НИЖЕ ДОСТУПНЫ ТОЛЬКО АДМИНИСТРАТОРАМ
Security::AccessAdminOnly();






// Создание пользователя.
if ($action=='user_add')
{
$methodResult = User::CreateNewUser($login, $password, $user_role, $user_name, $user_surname, $user_patronymic, $user_description);
if ($methodResult=='incorrect_symbols_in_login_or_password') {echo HTML::ShowInformationPage('В логине/пароле могут быть только английские буквы и цифры.', $_SERVER['HTTP_REFERER']);exit;}
if ($methodResult=='login_cannot_be_empty') {echo HTML::ShowInformationPage('Логин не может быть пустым.', $_SERVER['HTTP_REFERER']);exit;}	
if ($methodResult=='password_cannot_be_empty') {echo HTML::ShowInformationPage('Пароль не может быть пустым.', $_SERVER['HTTP_REFERER']);exit;}	
if ($methodResult=='user_exist') {echo HTML::ShowInformationPage('Такой пользователь уже существует.', $_SERVER['HTTP_REFERER']);exit;}	
header('Location: index.php?page=admin&section=users');
exit;
}


// Редактирование пользователя.
if ($action=='user_edit')
{
$methodResult = User::UpdateUser($id, $login, $password, $user_role, $user_name, $user_surname, $user_patronymic, $user_description);
if ($methodResult=='incorrect_symbols_in_login_or_password') {echo HTML::ShowInformationPage('В логине/пароле могут быть только английские буквы и цифры.', $_SERVER['HTTP_REFERER']);exit;}
if ($methodResult=='login_cannot_be_empty') {echo HTML::ShowInformationPage('Логин не может быть пустым.', $_SERVER['HTTP_REFERER']);exit;}	
if ($methodResult=='user_does_not_exist') {echo HTML::ShowInformationPage('Такой пользователь не существует.', $_SERVER['HTTP_REFERER']);exit;}	
header('Location: index.php?page=admin&section=users');
exit;
}

// Удаление пользователя.
if ($action=='user_delete')
{
// Получаем данные.
$id=Data::GET('id');
$methodResult = User::DeleteUser($id);
if ($methodResult=='user_does_not_exist') {echo HTML::ShowInformationPage('Такой пользователь не существует.', $_SERVER['HTTP_REFERER']);exit;}	
header('Location: index.php?page=admin&section=users');
exit;
}



// Создание адреса.
if ($action=='address_add')
{
$methodResult = Address::CreateNewAddress($users_id, $field1);
if ($methodResult=='no_field1') {echo HTML::ShowInformationPage('Введите название адреса.', $_SERVER['HTTP_REFERER']);exit;}
header('Location: index.php?page=admin&section=address');
exit;
}


// Редактирование адреса.
if ($action=='address_edit')
{
$methodResult = Address::EditAddress($id, $users_id, $field1);
if ($methodResult=='no_field1') {echo HTML::ShowInformationPage('Введите название адреса.', $_SERVER['HTTP_REFERER']);exit;}
if ($methodResult=='address_does_not_exist') {echo HTML::ShowInformationPage('Такой адрес не существует.', $_SERVER['HTTP_REFERER']);exit;}
header('Location: index.php?page=admin&section=address');
exit;
}


// Удаление адреса.
if ($action=='address_delete')
{
// Получаем данные.
$id=Data::GET('id');
$methodResult = Address::DeleteAddress($id);
if ($methodResult=='address_does_not_exist') {echo HTML::ShowInformationPage('Такой адрес не существует.', $_SERVER['HTTP_REFERER']);exit;}
header('Location: index.php?page=admin&section=address');
exit;
}








// Экспорт данных
if ($action=='excel_export')
{
$pathToUserFile = Files::ExcelExport($checkboxIdArray);
//var_dump($url);
// Действие выполняется черех ajax - отдаем назад ссылку.	
if ($pathToUserFile !='') $url = '<a href ="'.$pathToUserFile.'">Скачать Excel файл</a>'; else 
$url = 'Для создания Excel файла выберите один или несколько адресов.';
echo $url;
exit;
}
























?>