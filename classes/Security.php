<?php
include_once __DIR__.'/User.php';

class Security
{
	
// Генерация хеша пароля пользователя (для гарантированной уникальности передаем id пользователя).
static function CreateUserToken($userId) {
$userToken = uniqid($userId);
return $userToken;
}

// Проверка на латиннские буквы и символы.
static function OnlyDigitsAndEngLetters($string) {
if (!preg_match('/[^A-Za-z0-9]/', $string)) return true; else return false;
}

// Доступ только для зарегистрированных пользователей.
static function AccessRegisteredUsersOnly() {
$userStatus = User::GetUserStatus();
if ($userStatus != 'authorized')
{
header("Location: /");
exit;
}
}

// Доступ только для администраторов.
static function AccessAdminOnly() {
$UserDataArray = User::GetAllUserDataBasedOnUserCookie();
$userRole = $UserDataArray['user_role'];
if ($userRole != 'Администратор')
{
echo 'This is only available for administrators.';
exit;
}
}






}

?>