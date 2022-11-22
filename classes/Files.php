<?php

namespace techmap\classes;

include_once __DIR__.'/../vendor/autoload.php';
use techmap\classes\MySQL;
use techmap\classes\TechmapTable;
use techmap\classes\User;
use techmap\classes\Data;

include_once __DIR__ . '/../addons/SimpleXLSXGen.php';

class Files
{


// TODO вынести код создания массива  в отдельный метод.
// Создаие массива для вывода.
//static function CreateTechmapTableExportArray($TechmapTableIdArray)


// Экспорт данных в Excel. Метод получает массив из id строк в таблице и возвращает путь к excel файлу.
    static function excelExport($TechmapTableIdArray)
    {
        $TableDataArray = TechmapTable::getTechmapTableDataFromIds($TechmapTableIdArray);
        if ($TableDataArray == NULL) return '';
        $UserDataArray = User::getAllUserDataBasedOnToken();
        $userId = $UserDataArray['id'];

// Создаем пути к файлу и папке.
        $pathToUserFolder = __DIR__ . '/../output_files/' . $userId;
        $pathToUserFile = $pathToUserFolder . '/excel_table.xlsx';

// Создаем папку если папка не существует.
        if (!file_exists($pathToUserFolder)) mkdir($pathToUserFolder, 0755);

// Удаляем первые 2 колонки из массива.
        $TableDataArray = Data::removeColumnsFromArray($TableDataArray, 2);

        $xlsx = \SimpleXLSXGen::fromArray($TableDataArray);
        $xlsx->saveAs($pathToUserFile);

        $url = '/output_files/' . $userId . '/excel_table.xlsx';
        return $url;
    }

}