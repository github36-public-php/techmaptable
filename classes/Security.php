<?php
include_once __DIR__.'/User.php';

class Security
{
	
// Генерация хеша пароля пользователя (для гарантированной уникальности передаем id пользователя).
static function createUserToken($userId) {
$userToken = uniqid($userId);
return $userToken;
}

// Проверка на латиннские буквы и символы.
static function onlyDigitsAndEngLetters($string) {
if (!preg_match('/[^A-Za-z0-9]/', $string)) return true; else return false;
}

// Доступ только для зарегистрированных пользователей.
static function accessRegisteredUsersOnly() {
$userStatus = User::getUserStatus();
if ($userStatus != 'authorized')
{
header("Location: /");
exit;
}
}

// Доступ только для администраторов.
static function accessAdminOnly() {
$UserDataArray = User::getAllUserDataBasedOnUserCookie();
$userRole = $UserDataArray['user_role'];
if ($userRole != 'Администратор')
{
echo 'This is only available for administrators.';
exit;
}
}



}

