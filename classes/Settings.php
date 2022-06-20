<?php
class Settings
{

// Чтение ini файла настроек.
static function GetSettingsIniArray() {
$pathToIniFile = $_SERVER['DOCUMENT_ROOT'].'/configuration.ini';
$IniArray = parse_ini_file($pathToIniFile);
return $IniArray;
}

}

?>