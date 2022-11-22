<?php

namespace techmap\classes;

include_once __DIR__.'/../vendor/autoload.php';
use techmap\classes\MySQL;

class Address
{


// Проверка существования адреса.
    static function addressIdExist($id)
    {
        $query = "SELECT `id` FROM `techmaptable` WHERE `id` = '$id' LIMIT 1";
        $queryResult = MySQL::mySQLQuery($query);
        $result = $queryResult->num_rows;
        return $result;
    }

// Показать список адресов.
    static function getAddressList()
    {
        $query = "SELECT `id`,`field1` FROM `techmaptable` ORDER BY id DESC";
        $queryResult = MySQL::mySQLQuery($query);
        $num_rows = $queryResult->num_rows;
        if ($num_rows != 0) {
            while ($row = $queryResult->fetch_assoc()) {
                $AddressListArray[] = array(
                    'id' => $row['id'],
                    'field1' => $row['field1'],
                );
            }
            return $AddressListArray;
        } else
            return 'no_data';
    }


// Получить запись из таблицы адресов с указанным id.
    static function getAddressRow($id)
    {
        $query = "SELECT * FROM `techmaptable` WHERE `id` = '$id' LIMIT 1";
        $queryResult = MySQL::mySQLQuery($query);
        $num_rows = $queryResult->num_rows;
        if ($num_rows != 0) {
            while ($row = $queryResult->fetch_assoc()) {
                $ResultArray[] = $row;
            };
            $AddressListArray = $ResultArray[0];
            return $AddressListArray;
        } else
            return 'no_data';
    }


// Создать новый адрес ($users_id может быть пустой - доступ для администраторов)
    static function createNewAddress($users_id = '', $field1)
    {
        if ($field1 == '') return 'no_field1';
        $query = "INSERT INTO techmaptable (users_id, field1) VALUES ('$users_id', '$field1')";
        $queryResult = MySQL::mySQLQuery($query);
        if ($queryResult == 1) return 'address_created';
    }


// Редактировать адрес ($users_id может быть пустой - доступ для администраторов)
    static function editAddress($id, $users_id = '', $field1)
    {
        if ($field1 == '') return 'no_field1';
        $addressIdExist = self::addressIdExist($id);
        if ($addressIdExist != 1) return 'address_does_not_exist';
        $query = "UPDATE `techmaptable` SET users_id='$users_id', field1='$field1' WHERE id='$id'";
        $queryResult = MySQL::mySQLQuery($query);
        if ($queryResult == 1) return 'address_updated';
    }


// Удалить адрес с указанным id.
    static function deleteAddress($id)
    {
        $addressIdExist = self::addressIdExist($id);
        if ($addressIdExist != 1) return 'address_does_not_exist';
        $query = "DELETE FROM `techmaptable` WHERE id='$id'";
        $queryResult = MySQL::mySQLQuery($query);
        if ($queryResult == 1) return 'address_deleted';
    }


}