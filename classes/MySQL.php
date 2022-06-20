<?php
include_once __DIR__.'/Settings.php';
class MySQL
{

// Подключение к БД.
static function ConnectToBase() {
$IniArray = Settings::GetSettingsIniArray();
$hostname = $IniArray['hostname'];
$username = $IniArray['username'];
$password = $IniArray['password'];
$base = $IniArray['base'];
$timeZone = $IniArray['timeZone'];
$mysqli = new mysqli($hostname, $username, $password, $base);
unset($hostname, $username, $password, $base);
if ($mysqli->connect_errno){echo "Error. System can not connect to the database. hostname=$hostname username=$username password=$password base=$base"; exit();}
// Настройки соединения (зависят от кодировки БД).
$mysqli->query("set character_set_client='utf8'");
$mysqli->query("set character_set_results='utf8'");
$mysqli->query("set collation_connection='utf8_general_ci'");
// Устанавливаем временную зону для mysql - если сервер не локальный (нужно для определенных функций mysql).
$query="SET time_zone = '".$timeZone."'";
$mysqli->query($query) or die ($mysqli->error);
return $mysqli;
}

// Выполнение запроса.
static function MySQLQuery($query) {
$mysqli = self::ConnectToBase();
//TODO тут проверка на sql инъекции
$queryResult = $mysqli->query($query) or die ($mysqli->error);
return  $queryResult;
}

}
?>