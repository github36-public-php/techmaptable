<?php
include_once __DIR__.'/MySQL.php';
include_once __DIR__.'/Settings.php';

class User
{

// Генерация хеша пароля пользователя.
static function CreateUserPasswordHash($password) {
$passwordHash = password_hash($password, PASSWORD_DEFAULT);
return $passwordHash;
}

// Проверка существования логина пользователя.
static function UserLoginExist($login) {
$query = "SELECT `id` FROM `users` WHERE `login` = '$login' LIMIT 1";
$queryResult = MySQL::MySQLQuery($query);
$result = $queryResult->num_rows;
return  $result;
}

// Проверка существования пользователя с указанным id.
static function UserIdExist($id) {
$query = "SELECT `id` FROM `users` WHERE `id` = '$id' LIMIT 1";
$queryResult = MySQL::MySQLQuery($query);
$result = $queryResult->num_rows;
return $result;
}

// Создание нового пользователя.
static function CreateNewUser($login, $password, $user_role='', $user_name='', $user_surname='', $user_patronymic='', $user_description='') {
if (Security::OnlyDigitsAndEngLetters($login) != true or Security::OnlyDigitsAndEngLetters($password) != true) return 'incorrect_symbols_in_login_or_password';
if ($login == '') return 'login_cannot_be_empty';
$userLoginExist = self::UserLoginExist($login);
if ($userLoginExist == true) return 'user_exist';
if ($password == '') return 'password_cannot_be_empty';
$password_hash = self::CreateUserPasswordHash($password);
$query = "INSERT INTO users (login, password_hash, user_role,  user_name, user_surname, user_patronymic, user_description, user_token) VALUES
('$login', '$password_hash', '$user_role', '$user_name', '$user_surname', '$user_patronymic', '$user_description', '')";
$queryResult = MySQL::MySQLQuery($query);
if ($queryResult == 1) return 'user_created';
}


// Обновление данных указанного пользователя.
static function UpdateUser($id, $login, $password, $user_role, $user_name, $user_surname, $user_patronymic, $user_description) {
if (Security::OnlyDigitsAndEngLetters($login) != true or Security::OnlyDigitsAndEngLetters($password) != true) return 'incorrect_symbols_in_login_or_password';
if ($login == '') return 'login_cannot_be_empty';
$userIdExist = self::UserIdExist($id);
if ($userIdExist == 0) return 'user_does_not_exist';
{
$password_hash = self::CreateUserPasswordHash($password);
$query = "UPDATE `users` SET login='$login', password_hash='$password_hash', user_role='$user_role', user_name='$user_name', user_surname='$user_surname', user_patronymic='$user_patronymic', user_description='$user_description' WHERE id='$id'";
}
if ($password =='') $query = "UPDATE `users` SET login='$login', user_role='$user_role', user_name='$user_name', user_surname='$user_surname', user_patronymic='$user_patronymic', user_description='$user_description' WHERE id='$id'";
$queryResult = MySQL::MySQLQuery($query);
if ($queryResult == 1) return 'user_updates';
}


// Удаление пользователя с указанным id.
static function DeleteUser($id) {
$userIdExist = self::UserIdExist($id);
if ($userIdExist == 0) return 'user_does_not_exist';
$query = "DELETE FROM `users` WHERE id='$id'";
$queryResult = MySQL::MySQLQuery($query);
if ($queryResult == 1) return 'user_deleted';
}


// Проверка логина/пароля пользователя. В случае успеха, возвращает id пользователя.
static function CheckUserLoginPassword($login, $password) {
$CheckUserResultArray=['incorrect','error']; // По умолчанию логин/пароль не верный.
if (Security::OnlyDigitsAndEngLetters($login) == false or Security::OnlyDigitsAndEngLetters($password) == false) return $CheckUserResultArray=['incorrect','incorrect_symbols_in_login_or_password'];
$query = "SELECT `id`,`password_hash` FROM `users` WHERE `login` = '$login' LIMIT 1";
$queryResult = MySQL::MySQLQuery($query);
$UserDataArray = $queryResult->fetch_assoc();
$password_hash = $UserDataArray['password_hash'];
$id = $UserDataArray['id'];
if (password_verify($password, $password_hash) == true) return $CheckUserResultArray=['correct', $id]; else return $CheckUserResultArray=['incorrect', 'incorrect_login_or_password'];
}


// Сгенерировать и установить куку и токен пользователю с указанным id (по умолчанию сроком на год).
static function SetUserToken($userId) {
// Получаем массив настроек.
$SettingsIni = Settings::GetSettingsIniArray();
$cookieName = $SettingsIni['cookiename'];
$cookieTime = $SettingsIni['cookietime'];
$userToken = Security::CreateUserToken($userId);
$cookieEndData = time() + $cookieTime;
// Принудительно устанавливаем (обновляем) куку.
setcookie($cookieName, $userToken, $cookieEndData);
//Обновляем данные пользователя.
$query = "UPDATE `users` SET user_token='$userToken' WHERE id='$userId'";
$queryResult = MySQL::MySQLQuery($query);
if ($queryResult == 1) return 'token_set'; else return 'error';
}


// Получить все данные пользователя на основе токена из cookie.
static function GetAllUserDataBasedOnToken() {
// Получаем массив настроек.
$SettingsIni = Settings::GetSettingsIniArray();
$cookieName = $SettingsIni['cookiename'];
$userTokenFromCookie = $_COOKIE[$cookieName];
$query = "SELECT * FROM `users` WHERE `user_token` = '$userTokenFromCookie' LIMIT 1";
$queryResult = MySQL::MySQLQuery($query);
$num_rows = $queryResult->num_rows;
if ($num_rows !=0)
{
$UserDataArray = $queryResult->fetch_assoc();
return $UserDataArray;
}
else
return 'no_data';
}

// Проверка статуса пользователя в системе (авторизирован или нет)
static function GetUserStatus() {
// Получаем массив настроек.
$SettingsIni = Settings::GetSettingsIniArray();
$cookieName = $SettingsIni['cookiename'];
$userStatus = 'not_authorized';
$userTokenFromCookie = $_COOKIE[$cookieName];
if (!isset($_COOKIE[$cookieName])) return 'not_authorized';
$query = "SELECT * FROM `users` WHERE `user_token` = '$userTokenFromCookie' LIMIT 1";
$queryResult = MySQL::MySQLQuery($query);
$num_rows = $queryResult->num_rows;
if ($num_rows == 1) $userStatus = 'authorized';
return $userStatus;
}



// Получить все данные пользователя на основе id.
static function GetAllUserDataBasedOnUserId($userId) {
$query = "SELECT * FROM `users` WHERE `id` = '$userId' LIMIT 1";
$queryResult = MySQL::MySQLQuery($query);
$num_rows = $queryResult->num_rows;
if ($num_rows !=0)
{
$UserDataArray = $queryResult->fetch_assoc();
return $UserDataArray;
}
else
return 'no_data';
}


// Получить все данные пользователя на основе COOKIE
static function GetAllUserDataBasedOnUserCookie() {
// Получаем массив настроек.
$SettingsIni = Settings::GetSettingsIniArray();
$cookieName = $SettingsIni['cookiename'];
$userTokenFromCookie = $_COOKIE[$cookieName];
$query = "SELECT * FROM `users` WHERE `user_token` = '$userTokenFromCookie' LIMIT 1";
$queryResult = MySQL::MySQLQuery($query);
$num_rows = $queryResult->num_rows;
if ($num_rows !=0)
{
$UserDataArray = $queryResult->fetch_assoc();
return $UserDataArray;
}
else
return 'no_data';
}


// Показать список пользователей.
static function GetUserList() {
// Получаем массив настроек.
$query = "SELECT * FROM `users` ORDER BY id DESC";
$queryResult = MySQL::MySQLQuery($query);
$num_rows = $queryResult->num_rows;
if ($num_rows !=0)
{
while ($row = $queryResult->fetch_assoc()) {
$UserListArray[] = array(
'id'=>$row['id'],
'login'=>$row['login'],
'user_role'=>$row['user_role'],
'user_name'=>$row['user_name'],
'user_surname'=>$row['user_surname'],
'user_patronymic'=>$row['user_patronymic'],
'user_description'=>$row['user_description']
);
}
return $UserListArray;
}
else
return 'no_data';
}







}

?>