<?php
class Settings
{

// Чтение ini файла настроек.
static function getSettingsIniArray() {
$pathToIniFile = $_SERVER['DOCUMENT_ROOT'].'/configuration.ini';
$IniArray = parse_ini_file($pathToIniFile);
return $IniArray;
}

}
