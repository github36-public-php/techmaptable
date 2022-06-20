<?php
//ФАЙЛ С ДЕЙСТВИЯМИ

//Выводим только ошибки
//error_reporting(E_ALL);

//Путь к корневой директории для данного файла
$path_to_root_dir['actions']='';

//Подключение к БД
include ($path_to_root_dir['actions'].'mysql.php');

//Все данные о пользователе
include ($path_to_root_dir['actions'].'common/all_about_user.php');

//Подключаем функции
include ($path_to_root_dir['actions'].'common/functions.php');

//Информация о времени
include ($path_to_root_dir['actions'].'common/all_about_time.php');



//ДЕЙСТВИЯ НИЖЕ ДОСТУПНЫ ЛЮБЫМ ПОЛЬЗОВАТЕЛЯМ

//АВТОРИЗАЦИЯ ПОЛЬЗОВАТЕЛЯ
if (isset($_GET['login']))
{
	
	
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
// Perform the query
mysql_query($templine) or print('Error performing query \'<strong>' . $templine . '\': ' . mysql_error() . '<br /><br />');
// Reset temp variable to empty
$templine = '';
}
}
//Конец кода для демонстрации



$login=(isset($_POST['login'])) ? mysql_real_escape_string($_POST['login']) : '';
$password=(isset($_POST['password'])) ? mysql_real_escape_string($_POST['password']) : '';
$password=md5(md5($password));

$select_result=mysql_query("SELECT `password` FROM `users` WHERE `login`='$login' LIMIT 1");
$row=mysql_fetch_assoc($select_result);
$password_from_base=$row['password'];

//Проверка login
if (preg_match( '/[^0-9a-zA-Zа-яА-Я\-\+=!\.,()\s]/u', $login ))  
{header('Location: index.php?message=username_prohibit_symbols');exit;}


//Если пароль верный
if ($password_from_base==$password)
{


//Если есть кука - удаляем её
if (isset($_COOKIE['token']) )
setcookie('token', '', 0, "/");

//Ставим куку
//Создаём токен длинной 80 символов
$token=generate_random_string(80);
$time=31536000; // Ставим куку на год
setcookie('token', $token, time()+$time,"/");

//Обновляем данные пользователя
mysql_query("UPDATE `users` SET token='$token' WHERE login='$login'");

//После установки куки мы снова получаем данные о пользователе.
//Теперь он залогинен и мы можем узнать его разрешения, для переадресации на нужную страницу
include ($path_to_root_dir['actions'].'common/all_about_user.php');

//Переадресация
if ($all_about_user_array['schedule_page_permission']<>'no_access')
{header('Location: admin/admin.php?schedule&schedule_list&date_1='.$all_about_time['current_date'].'&date_2='.$all_about_time['current_date'].'&doctor_specialty_id=all&doctor_id=all'); exit;}
if ($all_about_user_array['doctors_page_permission']<>'no_access')
{header('Location: admin/admin.php?doctors&doctors_list'); exit;}
if ($all_about_user_array['services_page_permission']<>'no_access')
{header('Location: admin/admin.php?services&services_list'); exit;}
if ($all_about_user_array['patients_page_permission']<>'no_access')
{header('Location: admin/admin.php?patients&patients_list'); exit;}
if ($all_about_user_array['administration_page_permission']<>'no_access')
{header('Location: admin/admin.php?administration&administration_users'); exit;}

}
else //Иначе - если пароль не верный
{
//Переадресация на страницу авторизации
header('Location:index.php?message=login_or_password_error');
}
exit;
}


//ВЫХОД ПОЛЬЗОВАТЕЛЯ
if (isset($_GET['logout']))
{
//Если пользователь залогинен
if ($all_about_user_array['logged']=='1')
{
//Обновляем (удаляем) токен
mysql_query("UPDATE `users` SET token=''  WHERE id='".$all_about_user_array['id']."'");
//Удаляем куку
setcookie('token', '', 0, "/");
//Переадресация 
header('Location:'.$path_to_root_dir['actions'].'index.php');
exit;
}
}





//ДЕЙСТВИЯ НИЖЕ ДОСТУПНЫ АВТОРИЗИРОВАННЫМ ПОЛЬЗОВАТЕЛЯМ
if ($all_about_user_array['logged']<>'1') {echo'Для выполнения действия нужно войти в систему.'; exit;}


//Принимаем данные для всех модулей, доступных авторизированным пользователям
if (array_key_exists('date_1', $_POST)) $date_1=mysql_real_escape_string($_POST['date_1']);
if (array_key_exists('date_2', $_POST)) $date_2=mysql_real_escape_string($_POST['date_2']);
if (array_key_exists('date_begin', $_POST)) $date_begin=mysql_real_escape_string($_POST['date_begin']);
if (array_key_exists('date_end', $_POST)) $date_end=mysql_real_escape_string($_POST['date_end']);
if (array_key_exists('get_path_parametrs', $_POST)) $get_path_parametrs=mysql_real_escape_string($_POST['get_path_parametrs']);
if (array_key_exists('schedule_date', $_POST)) $schedule_date=mysql_real_escape_string($_POST['schedule_date']);
if (array_key_exists('schedule_mysql_date', $_POST)) $schedule_mysql_date=mysql_real_escape_string($_POST['schedule_mysql_date']);
if (array_key_exists('doctor_id', $_POST)) $doctor_id=mysql_real_escape_string($_POST['doctor_id']);
if (array_key_exists('doctor_room', $_POST)) $doctor_room=mysql_real_escape_string($_POST['doctor_room']);
if (array_key_exists('id', $_POST)) $id=mysql_real_escape_string($_POST['id']);
if (array_key_exists('confirmation', $_POST)) $confirmation=mysql_real_escape_string($_POST['confirmation']);
if (array_key_exists('hours', $_POST)) $hours=mysql_real_escape_string($_POST['hours']);
if (array_key_exists('minutes', $_POST)) $minutes=mysql_real_escape_string($_POST['minutes']);
if (array_key_exists('service_id', $_POST)) $service_id=mysql_real_escape_string($_POST['service_id']);
if (array_key_exists('autocomplete_hidden_input_1', $_POST)) $autocomplete_hidden_input_1=mysql_real_escape_string($_POST['autocomplete_hidden_input_1']);
if (array_key_exists('autocomplete_hidden_input_2', $_POST)) $autocomplete_hidden_input_2=mysql_real_escape_string($_POST['autocomplete_hidden_input_2']);
if (array_key_exists('autocomplete_hidden_input_3', $_POST)) $autocomplete_hidden_input_3=mysql_real_escape_string($_POST['autocomplete_hidden_input_3']);
if (array_key_exists('autocomplete_hidden_input_4', $_POST)) $autocomplete_hidden_input_4=mysql_real_escape_string($_POST['autocomplete_hidden_input_4']);
if (array_key_exists('schedule_id', $_POST)) $schedule_id=mysql_real_escape_string($_POST['schedule_id']);
if (array_key_exists('schedule_date_1', $_POST)) $schedule_date_1=mysql_real_escape_string($_POST['schedule_date_1']);
if (array_key_exists('schedule_date_2', $_POST)) $schedule_date_2=mysql_real_escape_string($_POST['schedule_date_2']);
if (array_key_exists('company_name', $_POST)) $company_name=mysql_real_escape_string($_POST['company_name']);
if (array_key_exists('contract_number', $_POST)) $contract_number=mysql_real_escape_string($_POST['contract_number']);
if (array_key_exists('surname', $_POST)) $surname=mysql_real_escape_string($_POST['surname']);
if (array_key_exists('name', $_POST)) $name=mysql_real_escape_string($_POST['name']);
if (array_key_exists('patronymic', $_POST)) $patronymic=mysql_real_escape_string($_POST['patronymic']);
if (array_key_exists('doctor_specialty_id', $_POST)) $doctor_specialty_id=mysql_real_escape_string($_POST['doctor_specialty_id']);
if (array_key_exists('specialty_name', $_POST)) $specialty_name=mysql_real_escape_string($_POST['specialty_name']);
if (array_key_exists('department_name', $_POST)) $department_name=mysql_real_escape_string($_POST['department_name']);
if (array_key_exists('company_id', $_POST)) $company_id=mysql_real_escape_string($_POST['company_id']);
if (array_key_exists('date_of_birth', $_POST)) $date_of_birth=mysql_real_escape_string($_POST['date_of_birth']);
if (array_key_exists('policy_number', $_POST)) $policy_number=mysql_real_escape_string($_POST['policy_number']);
if (array_key_exists('policy_date_begin', $_POST)) $policy_date_begin=mysql_real_escape_string($_POST['policy_date_begin']);
if (array_key_exists('policy_date_end', $_POST)) $policy_date_end=mysql_real_escape_string($_POST['policy_date_end']);
if (array_key_exists('service_name', $_POST)) $service_name=mysql_real_escape_string($_POST['service_name']);
if (array_key_exists('service_code', $_POST)) $service_code=mysql_real_escape_string($_POST['service_code']);
if (array_key_exists('service_cost', $_POST)) $service_cost=mysql_real_escape_string($_POST['service_cost']);
if (array_key_exists('service_date_begin', $_POST)) $service_date_begin=mysql_real_escape_string($_POST['service_date_begin']);
if (array_key_exists('service_date_end', $_POST)) $service_date_end=mysql_real_escape_string($_POST['service_date_end']);
if (array_key_exists('group_name', $_POST)) $group_name=mysql_real_escape_string($_POST['group_name']);
if (array_key_exists('schedule_page_permission', $_POST)) $schedule_page_permission=mysql_real_escape_string($_POST['schedule_page_permission']);
if (array_key_exists('doctors_page_permission', $_POST)) $doctors_page_permission=mysql_real_escape_string($_POST['doctors_page_permission']);
if (array_key_exists('services_page_permission', $_POST)) $services_page_permission=mysql_real_escape_string($_POST['services_page_permission']);
if (array_key_exists('patients_page_permission', $_POST)) $patients_page_permission=mysql_real_escape_string($_POST['patients_page_permission']);
if (array_key_exists('administration_page_permission', $_POST)) $administration_page_permission=mysql_real_escape_string($_POST['administration_page_permission']);
if (array_key_exists('position', $_POST)) $position=mysql_real_escape_string($_POST['position']);
if (array_key_exists('group_id', $_POST)) $group_id=mysql_real_escape_string($_POST['group_id']);
if (array_key_exists('login', $_POST)) $login=mysql_real_escape_string($_POST['login']);
if (array_key_exists('password', $_POST)) $password=mysql_real_escape_string($_POST['password']);
if (array_key_exists('new_password', $_POST)) $new_password=mysql_real_escape_string($_POST['new_password']);

if (array_key_exists('financing', $_POST)) $financing=mysql_real_escape_string($_POST['financing']);
if (array_key_exists('execution_status', $_POST)) $execution_status=mysql_real_escape_string($_POST['execution_status']);
if (array_key_exists('financing_confirmation', $_POST)) $financing_confirmation=mysql_real_escape_string($_POST['financing_confirmation']);
if (array_key_exists('hidden_input', $_POST)) $hidden_input=mysql_real_escape_string($_POST['hidden_input']);

if (array_key_exists('referral_date', $_POST)) $referral_date=mysql_real_escape_string($_POST['referral_date']);
if (array_key_exists('referral_mysql_date', $_POST)) $referral_mysql_date=mysql_real_escape_string($_POST['referral_mysql_date']);
if (array_key_exists('medical_card_number', $_POST)) $medical_card_number=mysql_real_escape_string($_POST['medical_card_number']);
if (array_key_exists('diagnosis_mkb', $_POST)) $diagnosis_mkb=mysql_real_escape_string($_POST['diagnosis_mkb']);
if (array_key_exists('warranty_letter_number', $_POST)) $warranty_letter_number=mysql_real_escape_string($_POST['warranty_letter_number']);

//Тут проверка данных



//ДЕЙСТВИЯ ДЛЯ МОДУЛЯ - АДМИНИСТРИРОВАНИЕ СИСТЕМЫ
//Доступно только для пользователей с полным доступом к данному модулю
if ($all_about_user_array['administration_page_permission']=='full_access')
{

//СОЗДАНИЕ НОВОЙ ГРУППЫ
if (isset($_GET['add_group']))
{
//Принимаем данные выше

//Смотрим, есть ли группа с таким именем в нашей БД
$result=mysql_query("SELECT `group_name` FROM `groups` WHERE `group_name`='$group_name' LIMIT 1");
$select_amt= mysql_num_rows($result);
//Если нашли группу с таким именем, то не создаем её. Выводим сообщение.
if ($select_amt<>0)
{
header('Location:admin/admin.php?administration&administration_groups&message=group_exist');
exit;
}
//Если нет, то создаем такую группу
mysql_query("INSERT INTO `groups`  (`group_name`,`schedule_page_permission`,`doctors_page_permission`,`services_page_permission`,`patients_page_permission`,`administration_page_permission`) VALUES ('$group_name','$schedule_page_permission','$doctors_page_permission','$services_page_permission','$patients_page_permission','$administration_page_permission')");
header('Location:admin/admin.php?administration&administration_groups');
exit;
}


//РЕДАКТИРОВАНИЕ ГРУППЫ
if (isset($_GET['edit_group']))
{
//Принимаем данные выше

//Обновляем запись
mysql_query("UPDATE `groups` SET group_name='$group_name',schedule_page_permission='$schedule_page_permission',doctors_page_permission='$doctors_page_permission',services_page_permission='$services_page_permission',patients_page_permission='$patients_page_permission',administration_page_permission='$administration_page_permission' WHERE id='$id'");
header('Location:admin/admin.php?administration&administration_groups');
exit;
}


//УДАЛЕНИЕ ГРУППЫ
if (isset($_GET['delete_group']))
{
//Принимаем данные выше

//Если нет подтверждения, то не удаляем группу
if ($confirmation<>'удаление')
{
header('Location:admin/admin.php?administration&administration_groups&message=no_delete_confirmation');
exit;
}

//Удаляем запись (группу)
mysql_query("DELETE FROM `groups` WHERE id='$id'");

//Тут удаление всех связанных с группой объектов

header('Location:admin/admin.php?administration&administration_groups');
exit;
}



//СОЗДАНИЕ НОВОГО ПОЛЬЗОВАТЕЛЯ
if (isset($_GET['add_user']))
{
//Принимаем данные выше

//Смотрим, есть ли пользователь с таким логином в нашей БД
$result=mysql_query("SELECT `id` FROM `users` WHERE `login`='$login' LIMIT 1");
$select_amt= mysql_num_rows($result);
//Если нашли - то не создаем пользователя. Выводим сообщение.
if ($select_amt<>0)
{
header('Location:admin/admin.php?administration&administration_users&message=user_exist');
exit;
}
//Если нет, то создаем пользователя

//Шифруем пароль
$password=md5(md5($password));

mysql_query("INSERT INTO `users`  (`surname`,`name`,`patronymic`,`position`,`group_id`,`login`,`password`) VALUES ('$surname','$name','$patronymic','$position','$group_id','$login','$password')");
header('Location:admin/admin.php?administration&administration_users');
exit;
}




//РЕДАКТИРОВАНИЕ ПОЛЬЗОВАТЕЛЯ
if (isset($_GET['edit_user']))
{
//Принимаем данные выше


//Тут проверка данных

//Обновляем запись
mysql_query("UPDATE `users` SET surname='$surname',name='$name',patronymic='$patronymic',position='$position',group_id='$group_id',login='$login' WHERE id='$id'");
//Обновляем пароль, если нужно
if ($new_password<>'')
{
$new_password=md5(md5($new_password));
mysql_query("UPDATE `users` SET password='$new_password' WHERE id='$id'");
}

header('Location:admin/admin.php?administration&administration_users');
exit;
}




//УДАЛЕНИЕ ПОЛЬЗОВАТЕЛЯ
if (isset($_GET['delete_user']))
{
//Принимаем данные выше


//Тут проверка данных

//Если нет подтверждения, то не удаляем группу
if ($confirmation<>'удаление')
{
header('Location:admin/admin.php?administration&administration_users&message=no_delete_confirmation');
exit;
}

//Удаляем запись
mysql_query("DELETE FROM `users` WHERE id='$id'");

//Тут удаление всех связанных объектов

header('Location:admin/admin.php?administration&administration_users');
exit;
}

}
//КОНЕЦ ДЕЙСТВИЙ ДЛЯ МОДУЛЯ - АДМИНИСТРИРОВАНИЕ СИСТЕМЫ






//ДЕЙСТВИЯ ДЛЯ МОДУЛЯ - ВРАЧИ
//Доступно только для пользователей с полным доступом к данному модулю
if ($all_about_user_array['doctors_page_permission']=='full_access')
{

//СОЗДАНИЕ НОВОЙ СПЕЦИАЛЬНОСТИ ВРАЧА
if (isset($_GET['add_doctor_specialty']))
{
//Принимаем данные выше

//Смотрим, есть ли пользователь с таким логином в нашей БД
$result=mysql_query("SELECT `id` FROM `doctors_specialty` WHERE `specialty_name`='$specialty_name' LIMIT 1");
$select_amt= mysql_num_rows($result);
//Если нашли - то не создаем пользователя. Выводим сообщение.
if ($select_amt<>0)
{
header('Location:admin/admin.php?doctors&doctors_specialty&message=specialty_exist');
exit;
}
//Если нет, то создаем специальность

mysql_query("INSERT INTO `doctors_specialty` (`specialty_name`) VALUES ('$specialty_name')");
header('Location:admin/admin.php?doctors&doctors_specialty');
exit;
}



//РЕДАКТИРОВАНИЕ СПЕЦИАЛЬНОСТИ ВРАЧА
if (isset($_GET['edit_doctor_specialty']))
{
//Принимаем данные выше

//Обновляем запись
mysql_query("UPDATE `doctors_specialty` SET specialty_name='$specialty_name' WHERE id='$id'");
header('Location:admin/admin.php?doctors&doctors_specialty');
exit;
}


//УДАЛЕНИЕ СПЕЦИАЛЬНОСТИ ВРАЧА
if (isset($_GET['delete_doctor_specialty']))
{
//Принимаем данные выше

//Если нет подтверждения, то не удаляем
if ($confirmation<>'удаление')
{
header('Location:admin/admin.php?doctors&doctors_specialty&message=no_delete_confirmation');
exit;
}

//Удаляем специальность врача
mysql_query("DELETE FROM `doctors_specialty` WHERE id='$id'");

//Удаляем все объекты, связанные с данным врачом
//Удаляем всех врачей с этой специальностью
mysql_query("DELETE FROM `doctors` WHERE doctor_specialty_id='$id'");
//Удаляем все услуги
mysql_query("DELETE FROM `doctors_service` WHERE doctor_specialty_id='$id'");
//Удаляем все элементы расписаний
$result=mysql_query("SELECT * FROM `schedules` WHERE `doctor_specialty_id`='$id'");
$select_amt = mysql_num_rows($result);
for($select_amt; $select_amt>0; $select_amt--)
{
$row=mysql_fetch_assoc($result); 
$schedule_id=$row['id'];
mysql_query("DELETE FROM `schedule_elements` WHERE schedule_id='$schedule_id'");
}
//Удаляем все расписания
mysql_query("DELETE FROM `schedules` WHERE doctor_id='$id'");

header('Location:admin/admin.php?doctors&doctors_specialty');
exit;
}




//ДОБАВЛЕНИЕ НОВОГО ВРАЧА
if (isset($_GET['add_doctor']))
{
//Принимаем данные выше

//Смотрим, есть ли пользователь с такими данными в нашей БД
$result=mysql_query("SELECT `id` FROM `doctors` WHERE `name`='$name' AND `surname`='$surname' AND `patronymic`='$patronymic' AND `doctor_specialty_id`='$doctor_specialty_id' LIMIT 1");
$select_amt= mysql_num_rows($result);
//Если нашли - то не создаем врача. Выводим сообщение.
if ($select_amt<>0)
{
header('Location:admin/admin.php?doctors&doctors_list&message=doctor_exist');
exit;
}
//Если нет, то создаем врача
mysql_query("INSERT INTO `doctors` (`name`,`surname`,`patronymic`,`doctor_specialty_id`) VALUES ('$name','$surname','$patronymic','$doctor_specialty_id')");
header('Location:admin/admin.php?doctors&doctors_list');
exit;
}



//РЕДАКТИРОВАНИЕ ВРАЧА
if (isset($_GET['edit_doctor']))
{
//Принимаем данные выше

//Обновляем запись
mysql_query("UPDATE `doctors` SET surname='$surname', name='$name', patronymic='$patronymic', doctor_specialty_id='$doctor_specialty_id' WHERE id='$id'");
header('Location:admin/admin.php?doctors&doctors_list');
exit;
}



//УДАЛЕНИЕ ВРАЧА
if (isset($_GET['delete_doctor']))
{
//Принимаем данные выше

//Если нет подтверждения, то не удаляем группу
if ($confirmation<>'удаление')
{
header('Location:admin/admin.php?doctors&doctors_list&message=no_delete_confirmation');
exit;
}

//Удаляем запись (врача)
mysql_query("DELETE FROM `doctors` WHERE id='$id'");

//Удаляем все объекты, связанные с данным врачом
//Удаляем все элементы расписаний
$result=mysql_query("SELECT * FROM `schedules` WHERE `doctor_id`='$id'");
$select_amt = mysql_num_rows($result);
for($select_amt; $select_amt>0; $select_amt--)
{
$row=mysql_fetch_assoc($result); 
$schedule_id=$row['id'];
mysql_query("DELETE FROM `schedule_elements` WHERE schedule_id='$schedule_id'");
}
//Удаляем все расписания
mysql_query("DELETE FROM `schedules` WHERE doctor_id='$id'");

header('Location:admin/admin.php?doctors&doctors_list');
exit;
}




//СОЗДАНИЕ НОВОГО ОТДЕЛЕНИЯ
if (isset($_GET['add_doctor_department']))
{
//Принимаем данные выше

//Смотрим, есть ли пользователь такая специальность
$result=mysql_query("SELECT `id` FROM `doctors_department` WHERE `department_name`='$department_name' LIMIT 1");
$select_amt= mysql_num_rows($result);
//Если нашли - то не создаем пользователя. Выводим сообщение.
if ($select_amt<>0)
{
header('Location:admin/admin.php?doctors&doctors_department&message=department_exist');
exit;
}
//Если нет, то создаем специальность

mysql_query("INSERT INTO `doctors_department` (`department_name`) VALUES ('$department_name')");
header('Location:admin/admin.php?doctors&doctors_department');
exit;
}



//РЕДАКТИРОВАНИЕ ОТДЕЛЕНИЯ
if (isset($_GET['edit_doctor_department']))
{
//Принимаем данные выше

//Обновляем запись
mysql_query("UPDATE `doctors_department` SET department_name='$department_name' WHERE id='$id'");
header('Location:admin/admin.php?doctors&doctors_department');
exit;
}


//УДАЛЕНИЕ ОТДЕЛЕНИЯ
if (isset($_GET['delete_doctor_department']))
{
//Принимаем данные выше

//Если нет подтверждения, то не удаляем
if ($confirmation<>'удаление')
{
header('Location:admin/admin.php?doctors&doctors_department&message=no_delete_confirmation');
exit;
}
//Удаляем отделение
//Пока нет связанных с ним объектов. Скорее всего эти объекты не нужно удалять.
mysql_query("DELETE FROM `doctors_department` WHERE id='$id'");

header('Location:admin/admin.php?doctors&doctors_department');
exit;
}




//ДОБАВЛЕНИЕ НОВОЙ МЕДСЕСТРЫ
if (isset($_GET['add_nurse']))
{
//Принимаем данные выше
$doctors_department_id=$autocomplete_hidden_input_1;
//Смотрим, есть ли такая запись в нашей БД
$result=mysql_query("SELECT `id` FROM `nurses` WHERE `name`='$name' AND `surname`='$surname' AND `patronymic`='$patronymic' AND `doctors_department_id`='$doctors_department_id' LIMIT 1");
$select_amt= mysql_num_rows($result);
//Если нашли - то не создаем запись. Выводим сообщение.
if ($select_amt<>0)
{
header('Location:admin/admin.php?doctors&doctors_nurse&message=nurse_exist');
exit;
}
//Если нет, то создаем запись
mysql_query("INSERT INTO `nurses` (`name`,`surname`,`patronymic`,`doctors_department_id`) VALUES ('$name','$surname','$patronymic','$doctors_department_id')");
header('Location:admin/admin.php?doctors&doctors_nurse');
exit;
}



//РЕДАКТИРОВАНИЕ ВРАЧА
if (isset($_GET['edit_nurse']))
{
//Принимаем данные выше
$doctors_department_id=$autocomplete_hidden_input_1;
//Обновляем запись
mysql_query("UPDATE `nurses` SET surname='$surname', name='$name', patronymic='$patronymic', doctors_department_id='$doctors_department_id' WHERE id='$id'");
header('Location:admin/admin.php?doctors&doctors_nurse');
exit;
}



//УДАЛЕНИЕ ВРАЧА
if (isset($_GET['delete_nurse']))
{
//Принимаем данные выше

//Если нет подтверждения, то не удаляем группу
if ($confirmation<>'удаление')
{
header('Location:admin/admin.php?doctors&doctors_nurse&message=no_delete_confirmation');
exit;
}

//Удаляем запись
mysql_query("DELETE FROM `nurses` WHERE id='$id'");

header('Location:admin/admin.php?doctors&doctors_nurse');
exit;
}








}
//КОНЕЦ ДЕЙСТВИЙ ДЛЯ МОДУЛЯ - ВРАЧИ






//ДЕЙСТВИЯ ДЛЯ МОДУЛЯ - ПАЦИЕНТЫ
//Доступно только для пользователей с полным доступом к данному модулю
if ($all_about_user_array['patients_page_permission']=='full_access')
{

//СОЗДАНИЕ НОВОГО ПАЦИЕНТА
if (isset($_GET['add_patient']))
{
add_patient($surname,$name,$patronymic,$date_of_birth,$company_id,$policy_number,$policy_date_begin,$policy_date_end,'patients');
header('Location:admin/admin.php?patients&patients_list');
exit;
}



//РЕДАКТИРОВАНИЕ ПАЦИЕНТА
if (isset($_GET['edit_patient']))
{
//Принимаем данные выше

//Преобразуем дату в mysql дату
$date_of_birth=date_to_mysql_date($date_of_birth);
$policy_date_begin=date_to_mysql_date($policy_date_begin);
$policy_date_end=date_to_mysql_date($policy_date_end);

//Обновляем запись
mysql_query("UPDATE `patients` SET surname='$surname', name='$name', patronymic='$patronymic', date_of_birth='$date_of_birth', company_id='$company_id', policy_number='$policy_number', policy_date_begin='$policy_date_begin', policy_date_end='$policy_date_end' WHERE id='$id'");
header('Location:admin/admin.php?patients&patients_list');
exit;
}



//УДАЛЕНИЕ ПАЦИЕНТА
if (isset($_GET['delete_patient']))
{
//Принимаем данные выше

//Если нет подтверждения, то не удаляем группу
if ($confirmation<>'удаление')
{
header('Location:admin/admin.php?patients&patients_list&message=no_delete_confirmation');
exit;
}

//Удаляем запись (пациента)
mysql_query("DELETE FROM `patients` WHERE id='$id'");

//Удаляем все объекты, связанные с данным пациентом
//Мы удаляем, а не отчищаем т.к. у пациента (будет) много свойств, что повлечет создание многих связей
mysql_query("DELETE FROM `schedule_elements` WHERE patient_id='$id'");


header('Location:admin/admin.php?patients&patients_list');
exit;
}

}
//КОНЕЦ ДЕЙСТВИЙ ДЛЯ МОДУЛЯ - ПАЦИЕНТЫ






//ДЕЙСТВИЯ ДЛЯ МОДУЛЯ - УСЛУГИ
//Доступно только для пользователей с полным доступом к данному модулю
if ($all_about_user_array['services_page_permission']=='full_access')
{

//ДОБАВЛЕНИЕ НОВОЙ УСЛУГИ
if (isset($_GET['add_service']))
{
//Принимаем данные выше

//Для удобства - в цене можно использовать пробел или запятую вместо точки
$service_cost=str_replace(' ','.',$service_cost);
$service_cost=str_replace(',','.',$service_cost);

//Смотрим, есть ли такая услуга в нашей БД
$result=mysql_query("SELECT `id` FROM `doctors_service` WHERE `service_name`='$service_name' AND `service_code`='$service_code' AND `doctor_specialty_id`='$doctor_specialty_id' LIMIT 1");
$select_amt= mysql_num_rows($result);
//Если нашли - то не создаем запись. Выводим сообщение.
if ($select_amt<>0)
{
header('Location:admin/admin.php?services&services_list&message=service_exist');
exit;
}
//Если нет, то создаем услугу

//Преобразуем дату в mysql дату
$service_date_begin=date_to_mysql_date($service_date_begin);
$service_date_end=date_to_mysql_date($service_date_end);

mysql_query("INSERT INTO `doctors_service` (`service_name`,`service_code`,`service_cost`,`service_date_begin`,`service_date_end`,`doctor_specialty_id`) VALUES ('$service_name','$service_code','$service_cost','$service_date_begin','$service_date_end','$doctor_specialty_id')") or die (mysql_error());


header('Location:admin/admin.php?services&services_list');
exit;
}



//РЕДАКТИРОВАНИЕ УСЛУГИ
if (isset($_GET['edit_service']))
{
//Принимаем данные выше

//Для удобства - в цене можно использовать пробел или запятую вместо точки
$service_cost=str_replace(' ','.',$service_cost);
$service_cost=str_replace(',','.',$service_cost);

//Преобразуем дату в mysql дату
$service_date_begin=date_to_mysql_date($service_date_begin);
$service_date_end=date_to_mysql_date($service_date_end);

//Обновляем запись
mysql_query("UPDATE `doctors_service` SET service_name='$service_name', service_code='$service_code', service_cost='$service_cost', service_date_begin='$service_date_begin', service_date_end='$service_date_end', doctor_specialty_id='$doctor_specialty_id' WHERE id='$id'");
header('Location:admin/admin.php?services&services_list');
exit;
}



//УДАЛЕНИЕ УСЛУГИ
if (isset($_GET['delete_service']))
{
//Принимаем данные выше

//Если нет подтверждения, то не удаляем
if ($confirmation<>'удаление')
{
header('Location:admin/admin.php?services&services_list&message=no_delete_confirmation');
exit;
}

//Удаляем запись
mysql_query("DELETE FROM `doctors_service` WHERE id='$id'");

//Отчищаем все объекты, связанные с данной услугой
//Мы можем не удалять, а отчищать т.к. у услуги нет связей и свойств
mysql_query("UPDATE `schedule_elements` SET service_id='0' WHERE service_id='$id'");

header('Location:admin/admin.php?services&services_list');
exit;
}

}
//КОНЕЦ ДЕЙСТВИЙ ДЛЯ МОДУЛЯ - УСЛУГИ




//ДЕЙСТВИЯ ДЛЯ МОДУЛЯ - РАСПИСАНИЕ

//Прерход по ссылке с параметрами доступен только авторизированным пользователям
if ($all_about_user_array['logged']=='1')
{

if (isset($_GET['show_data_with_parametrs']))
{
//Используем функцию из модуля functions.php и пересылаем на страницу с параметрами
$modul_name=$_GET['show_data_with_parametrs'];
$path_parametrs=get_path_parametrs('','post');
header('Location:admin/admin.php?'.$modul_name.'&'.$modul_name.'_list'.$path_parametrs);
exit;
}

}



//Доступно только для пользователей с полным доступом к данному модулю
if ($all_about_user_array['schedule_page_permission']=='full_access')
{


//ДОБАВЛЕНИЕ НОВОГО РАСПИСАНИЯ
if (isset($_GET['add_schedule']))
{
//Принимаем данные выше

//Узнаем id специальности врача
$doctor_specialty_id=get_doctor_specialty_id($doctor_id);
//Преобразуем дату в mysql дату
$schedule_mysql_date=date_to_mysql_date($schedule_date);

//Смотрим, есть ли уже расписание для этого врача на эту дату
if (schedule_exist($schedule_mysql_date,$doctor_id)==true)
{
header('Location:'.$_SERVER['HTTP_REFERER'].'&message=schedule_exist');
exit;
}

//Если нет, то создаем новое расписание
//Создаем запись
mysql_query("INSERT INTO `schedules` (`doctor_id`,`doctor_specialty_id`,`doctor_room`,`schedule_date`) VALUES ('$doctor_id','$doctor_specialty_id','$doctor_room','$schedule_mysql_date')");
header('Location:admin/admin.php?schedule&schedule_list&date_1='.$schedule_date.'&date_2='.$schedule_date.'&doctor_id=all'.$get_path_parametrs);
exit;
}


//ИЗМЕНЕНИЕ РАСПИСАНИЯ
if (isset($_GET['edit_schedule']))
{
//Принимаем данные выше

//Узнаем id специальности врача
$doctor_specialty_id=get_doctor_specialty_id($doctor_id);
//Преобразуем дату в mysql дату
$schedule_mysql_date=date_to_mysql_date($schedule_date);



//Обновляем запись
mysql_query("UPDATE `schedules` SET doctor_id='$doctor_id', doctor_specialty_id='$doctor_specialty_id', doctor_room='$doctor_room', schedule_date='$schedule_mysql_date' WHERE id='$id'");
//Переходим к измененному расписанию
header('Location:admin/admin.php?schedule&schedule_list&date_1='.$schedule_date.'&date_2='.$schedule_date.$get_path_parametrs.'#schedule_'.$id);
exit;
}


//УДАЛЕНИЕ РАСПИСАНИЯ
if (isset($_GET['delete_schedule']))
{
//Принимаем данные выше

//Если нет подтверждения, то не удаляем
if ($confirmation<>'удаление')
{
header('Location:'.$_SERVER['HTTP_REFERER'].'&message=no_delete_confirmation#schedule_'.$id);
exit;
}

//Удаляем запись
mysql_query("DELETE FROM `schedules` WHERE id='$id'");
//Тут удаляем все записи связанные с данным расписанием
//Удаляем запись
mysql_query("DELETE FROM `schedule_elements` WHERE schedule_id='$id'");

//Тут удаление всех связанных объектов (если ещё остались)

header('Location:admin/admin.php?schedule&schedule_list'.$get_path_parametrs);
exit;
}



//УДАЛЕНИЕ РАСПИСАНИЯ (ДЛЯ ВСЕХ ВРАЧЕЙ) ЗА ВЫБРАННУЮ ДАТУ
if (isset($_GET['delete_all_schedule']))
{
//Принимаем данные выше

//Если нет подтверждения, то не удаляем
if ($confirmation<>'удаление')
{
header('Location:'.$_SERVER['HTTP_REFERER'].'&message=no_delete_confirmation');
exit;
}

//Тут удаляем все элементы связанные с данными расписаниями
$result=mysql_query("SELECT `id` FROM `schedules` WHERE `schedule_date`='$schedule_mysql_date'");
$select_amt = mysql_num_rows($result);
for($select_amt; $select_amt>0; $select_amt--)
{
$row=mysql_fetch_assoc($result); 
$id=$row['id'];
mysql_query("DELETE FROM `schedule_elements` WHERE schedule_id='$id'");
}

//Удаляем все записи
mysql_query("DELETE FROM `schedules` WHERE schedule_date='$schedule_mysql_date'");
//Тут удаление других связанных объектов

header('Location:admin/admin.php?schedule&schedule_list'.$get_path_parametrs);
exit;
}



//ДУБЛИРОВАНИЕ РАСПИСАНИЯ (ДЛЯ ВСЕХ ВРАЧЕЙ) ЗА ВЫБРАННУЮ ДАТУ ИЛИ ДИАПАЗОН ДАТ
if (isset($_GET['duplicate_schedule']))
{
//Принимаем данные выше

$schedule_date=date_from_mysql_to_normal_date($schedule_mysql_date);
//Сравниваем даты для дублирования. Первая дата должна быть меньше второй.
if (compare_dates($schedule_date_1,$schedule_date_2)=='date_1_bigger')
{
header('Location:'.$_SERVER['HTTP_REFERER'].'&message=schedule_duplicate_date_2_bigger');
exit;
}

//Нельзя дублировать расписание на эту дату
if ($schedule_date_1==$schedule_date or $schedule_date_2==$schedule_date)
{
header('Location:'.$_SERVER['HTTP_REFERER'].'&message=schedule_duplicate_date_same_date');
exit;
}

$date_array=create_date_array($schedule_date_1,$schedule_date_2);

//Для каждой даты из массива создаем расписание и их элементы
for($i=0; $i<count($date_array); $i++)
{
//echo $date_array[$i];

//Получаем информацию о расписаниях

//Если дублируем все расписания за дату
if ($hidden_input=='duplicate_schedule') $result=mysql_query("SELECT * FROM `schedules` WHERE `schedule_date`='$schedule_mysql_date'");
//Если дублируем расписание для одного врача за дату
if ($hidden_input=='duplicate_doctor') $result=mysql_query("SELECT * FROM `schedules` WHERE `doctor_id`='$doctor_id' AND `schedule_date`='$schedule_mysql_date' LIMIT 1");
$select_amt = mysql_num_rows($result);

/*
//Отладка
$row=mysql_fetch_assoc($result); 
$id=$row['id'];
if (array_key_exists('schedule_mysql_date', $_POST)) echo"Double date <br>";
if (array_key_exists('doctor_id', $_POST)) echo"Double doctor <br>";
echo"doctor_id =$doctor_id, id=$id, select_amt=$select_amt";
exit;
*/


for($select_amt; $select_amt>0; $select_amt--)
{
$row=mysql_fetch_assoc($result); 
$id=$row['id'];
$doctor_id=$row['doctor_id'];
$doctor_specialty_id=$row['doctor_specialty_id'];
//Создаем это расписание
$date_array[$i]=date_to_mysql_date($date_array[$i]);
mysql_query("INSERT INTO `schedules`  (`doctor_id`,`doctor_specialty_id`,`schedule_date`) VALUES ('$doctor_id','$doctor_specialty_id','$date_array[$i]')");
//id этого расписания
$schedule_id=mysql_insert_id();

//Получаем информацию о элементах расписаниях
$element_result=mysql_query("SELECT * FROM `schedule_elements` WHERE `schedule_id`='$id'");
$element_select_amt = mysql_num_rows($element_result);

//Отладка
//echo"$id $element_select_amt";
//exit;

for($element_select_amt; $element_select_amt>0; $element_select_amt--)
{
$element_row=mysql_fetch_assoc($element_result); 
$receipt_minutes=$element_row['receipt_minutes'];
$receipt_hours=$element_row['receipt_hours'];
$time_in_minutes=$element_row['time_in_minutes'];
$patient_id=$element_row['patient_id'];
$service_id=$element_row['service_id'];

//Создаем запись
mysql_query("INSERT INTO `schedule_elements`  (`schedule_id`,`receipt_minutes`,`receipt_hours`,`time_in_minutes`,`patient_id`,`service_id`) VALUES ('$schedule_id','$receipt_minutes','$receipt_hours','$time_in_minutes','$patient_id','$service_id')");
}
}
}



/*
//Выводим массив
echo '<pre>',
print_r($date_array);
echo '</pre>';
echo '---';
echo count($date_array);
exit;
*/

header('Location:admin/admin.php?schedule&schedule_list'.$get_path_parametrs);
exit;
}







//ДОБАВЛЕНИЕ НОВОГО ЭЛЕМЕНТА РАСПИСАНИЯ
if (isset($_GET['add_schedule_element']))
{
//Принимаем данные выше

//Используется автозаполнение - указываем какие данные были приняты
$patient_id=$autocomplete_hidden_input_1;
$service_id=$autocomplete_hidden_input_2;


//Отладка
/* echo"patient_id=$patient_id, service_id=$service_id";
exit; */

//$fio_with_spaces=$autocomplete_search_box;


//Идентификатор пациента передается с помощью автозаполнения.
//Если пустое значение идентификатора пациента или оно не было найдено (ввели пациента которого нет) - то нет пациента.
if ($patient_id=='') $patient_id=0; 

//Пробуем определить идентификатор пациента, исходя из ФИО
//$patient_id=get_patients_id_by_fio($fio_with_spaces);


//Если значения в поле - фамилия не пустое, значит пользователь туда что то ввел и хочет создать нового пациента
//Создаем нового пациента и получаем его id из функции
if ($_POST['surname']!=null)
{
$patient_id=add_patient($surname,$name,$patronymic,$date_of_birth,$company_id,$policy_number,$policy_date_begin,$policy_date_end,'schedule');
}


//Тут проверка данных

//Тут контроль дублирующих записей

//Считаем общее количество времени в минутах. Это будет занесено в БД для дальнейшего вывода записей по времени.
$time_in_minutes=$hours*60+$minutes;

//Создаем запись
mysql_query("INSERT INTO `schedule_elements`  (`schedule_id`,`receipt_minutes`,`receipt_hours`,`time_in_minutes`,`patient_id`,`service_id`,`financing`,`execution_status`,`financing_confirmation`) VALUES ('$id','$minutes','$hours','$time_in_minutes','$patient_id','$service_id','$financing','$execution_status','$financing_confirmation')");
//Переходим к этой записи (узнаем её id сразу после вставки)
header('Location:admin/admin.php?schedule&schedule_list'.$get_path_parametrs.'&light_element_id='.mysql_insert_id().'#element_'.mysql_insert_id());
exit;
}




//ИЗМЕНЕНИЕ ЭЛЕМЕНТА РАСПИСАНИЯ
if (isset($_GET['edit_schedule_element']))
{
//Принимаем данные выше

//Используется автозаполнение - указываем какие данные были приняты
$patient_id=$autocomplete_hidden_input_1;
$service_id=$autocomplete_hidden_input_2;

//$fio_with_spaces=$autocomplete_search_box;

//echo"$patient_id";
//exit;

//Идентификатор пациента передается с помощью автозаполнения.
//Если пустое значение идентификатора пациента или оно не было найдено (ввели пациента которого нет) - то нет пациента.
if ($patient_id=='') $patient_id=0; 

//Пробуем определить идентификатор пациента, исходя из ФИО
//$patient_id=get_patients_id_by_fio($fio_with_spaces);


//Если значения в поле - фамилия не пустое, значит пользователь туда что то ввел и хочет создать нового пациента
//Создаем нового пациента и получаем его id из функции
if ($_POST['surname']!=null)
{
$patient_id=add_patient($surname,$name,$patronymic,$date_of_birth,$company_id,$policy_number,$policy_date_begin,$policy_date_end,'schedule');
}


//Проверяем есть ли в БД запись с таким id (вдруг её кто то уже удалил)
//Если не нашли - выводим сообщение
if (record_exist($id,'schedule_elements')==0)
{
header('Location:'.$_SERVER['HTTP_REFERER'].'&message=schedule_element_not_found');
exit;
}

//Тут проверка данных

//Тут проверка дублирующей записи (есть уже такой элемент рассписания), если это нужно

//Считаем общее количество времени в минутах. Это будет занесено в БД для дальнейшего вывода записей по времени.
$time_in_minutes=$hours*60+$minutes;

//Обновляем запись
mysql_query("UPDATE `schedule_elements` SET receipt_minutes='$minutes', receipt_hours='$hours', time_in_minutes='$time_in_minutes', patient_id='$patient_id', service_id='$service_id', financing='$financing', execution_status='$execution_status', financing_confirmation='$financing_confirmation' WHERE id='$id'");
//Переходим к измененному расписанию
header('Location:admin/admin.php?schedule&schedule_list'.$get_path_parametrs.'&light_element_id='.$id.'#element_'.$id);
exit;
}





//УДАЛЕНИЕ ЭЛЕМЕНТА РАСПИСАНИЯ
if (isset($_GET['delete_schedule_element']))
{
//Принимаем данные выше

//Если нет подтверждения, то не удаляем
if ($confirmation<>'удаление')
{
header('Location:'.$_SERVER['HTTP_REFERER'].'&message=no_delete_confirmation#element_'.$id);
exit;
}

//Удаляем запись
mysql_query("DELETE FROM `schedule_elements` WHERE id='$id'");
header('Location:admin/admin.php?schedule&schedule_list'.$get_path_parametrs.'#schedule_'.$schedule_id);
exit;
}


}
//КОНЕЦ ДЕЙСТВИЙ ДЛЯ МОДУЛЯ - РАСПИСАНИЕ








//ДЕЙСТВИЯ ДЛЯ МОДУЛЯ - СТРАХОВЫЕ КОМПАНИИ
//Доступно только для пользователей с полным доступом к данному модулю
if ($all_about_user_array['companies_page_permission']=='full_access')
{

//СОЗДАНИЕ НОВОЙ СТРАХОВОЙ КОМПАНИИ
if (isset($_GET['add_company']))
{
//Принимаем данные выше

//Смотрим, есть ли уже иакая запись в нашей БД
$result=mysql_query("SELECT `id` FROM `companies` WHERE `company_name`='$company_name' AND `contract_number`='$contract_number' LIMIT 1");
$select_amt= mysql_num_rows($result);
//Если нашли - то не создаем. Выводим сообщение.
if ($select_amt<>0)
{
header('Location:admin/admin.php?companies&companies_list&message=company_exist');
exit;
}
//Если нет, то создаем.
mysql_query("INSERT INTO `companies` (`company_name`,`contract_number`) VALUES ('$company_name','$contract_number')");
header('Location:admin/admin.php?companies&companies_list');
exit;
}




//РЕДАКТИРОВАНИЕ СТРАХОВОЙ КОМПАНИИ
if (isset($_GET['edit_company']))
{
//Принимаем данные выше

//Обновляем запись
mysql_query("UPDATE `companies` SET company_name='$company_name', contract_number='$contract_number' WHERE id='$id'");
header('Location:admin/admin.php?companies&companies_list');
exit;
}



//УДАЛЕНИЕ СТРАХОВОЙ КОМПАНИИ
if (isset($_GET['delete_company']))
{
//Принимаем данные выше

//Если нет подтверждения, то не удаляем.
if ($confirmation<>'удаление')
{
header('Location:admin/admin.php?companies&companies_list&message=no_delete_confirmation');
exit;
}

//Удаляем запись
mysql_query("DELETE FROM `companies` WHERE id='$id'");

//Удаляем все объекты, связанные с данным пациентом


header('Location:admin/admin.php?companies&companies_list');
exit;
}

}
//КОНЕЦ ДЕЙСТВИЙ ДЛЯ МОДУЛЯ - СТРАХОВЫЕ КОМПАНИИ






//ДОБАВЛЕНИЕ НОВОГО НАПРАВЛЕНИЯ
if (isset($_GET['add_referral']))
{
//Принимаем данные выше

//Используется автозаполнение - указываем какие данные были приняты
//Получаем идентификатор пациента
$patient_id=$autocomplete_hidden_input_1;

//Преобразуем дату в mysql дату
$referral_mysql_date=date_to_mysql_date($referral_date);

//Смотрим, есть ли уже направление для этого пациента на эту дату
if (referral_exist($referral_mysql_date,$patient_id)==true)
{
header('Location:'.$_SERVER['HTTP_REFERER'].'&message=referral_exist');
exit;
}

//Если значения в поле - фамилия не пустое, значит пользователь туда что то ввел и хочет создать нового пациента
//Создаем нового пациента и получаем его id из функции
if ($_POST['surname']!=null)
{
$patient_id=add_patient($surname,$name,$patronymic,$date_of_birth,$company_id,$policy_number,$policy_date_begin,$policy_date_end,'referral');
}

//Если нет, то создаем новое направление
//Создаем запись
mysql_query("INSERT INTO `referrals` (`patient_id`,`medical_card_number`,`referral_date`) VALUES ('$patient_id','$medical_card_number','$referral_mysql_date')");
header('Location:admin/admin.php?referrals&referrals_list&date_1='.$referral_date.'&date_2='.$referral_date);
exit;
}




//ИЗМЕНЕНИЕ НАПРАВЛЕНИЯ
if (isset($_GET['edit_referral']))
{
//Принимаем данные выше

//Используется автозаполнение - указываем какие данные были приняты
//Получаем идентификатор пациента
$patient_id=$autocomplete_hidden_input_1;

//Преобразуем дату в mysql дату
$referral_mysql_date=date_to_mysql_date($referral_date);

//Смотрим, есть ли уже направление для этого пациента на эту дату
//Отключено т.к. оно есть потому что мы редактируем его
/*
if (referral_exist($referral_mysql_date,$patient_id)==true)
{
header('Location:'.$_SERVER['HTTP_REFERER'].'&message=referral_exist');
exit;
}
*/

//Если значения в поле - фамилия не пустое, значит пользователь туда что то ввел и хочет создать нового пациента
//Создаем нового пациента и получаем его id из функции
if ($_POST['surname']!=null)
{
$patient_id=add_patient($surname,$name,$patronymic,$date_of_birth,$company_id,$policy_number,$policy_date_begin,$policy_date_end,'referral');
}

//Обновляем запись
mysql_query("UPDATE `referrals` SET patient_id='$patient_id', medical_card_number='$medical_card_number', referral_date='$referral_mysql_date' WHERE id='$id'");
header('Location:admin/admin.php?referrals&referrals_list&date_1='.$referral_date.'&date_2='.$referral_date);
exit;
}




//УДАЛЕНИЕ НАПРАВЛЕНИЯ
if (isset($_GET['delete_referral']))
{
//Принимаем данные выше
//Если нет подтверждения, то не удаляем
if ($confirmation<>'удаление')
{
header('Location:'.$_SERVER['HTTP_REFERER'].'&message=no_delete_confirmation#referral_'.$id);
exit;
}
//Удаляем запись
mysql_query("DELETE FROM `referrals` WHERE id='$id'");
//Тут удаляем все записи связанные с данным расписанием
//Удаляем записи элементов
mysql_query("DELETE FROM `referral_elements` WHERE referral_id='$id'");

//Тут удаление всех связанных объектов (если ещё остались)

header('Location:admin/admin.php?referrals&referrals_list'.$get_path_parametrs);
exit;
}


//УДАЛЕНИЕ НАПРАВЛЕНИЯ (ДЛЯ ВСЕХ ПАЦИЕНТОВ) ЗА ВЫБРАННУЮ ДАТУ
if (isset($_GET['delete_all_referrals']))
{
//Принимаем данные выше

//Если нет подтверждения, то не удаляем
if ($confirmation<>'удаление')
{
header('Location:'.$_SERVER['HTTP_REFERER'].'&message=no_delete_confirmation');
exit;
}

//Тут удаляем все элементы связанные с данными расписаниями
$result=mysql_query("SELECT `id` FROM `referrals` WHERE `referral_date`='$referral_mysql_date'");
$select_amt = mysql_num_rows($result);
for($select_amt; $select_amt>0; $select_amt--)
{
$row=mysql_fetch_assoc($result); 
$id=$row['id'];
mysql_query("DELETE FROM `referral_elements` WHERE referral_id='$id'");
}

//Удаляем все записи
mysql_query("DELETE FROM `referrals` WHERE referral_date='$referral_mysql_date'");
//Тут удаление других связанных объектов

header('Location:admin/admin.php?referrals&referrals_list'.$get_path_parametrs);
exit;
}



//ДОБАВЛЕНИЕ НОВОГО ЭЛЕМЕНТА НАПРАВЛЕНИЯ (НОВОЙ УСЛУГИ В НАПРАВЛЕНИЕ)
if (isset($_GET['add_referral_element']))
{
//Принимаем данные выше
//Используется автозаполнение - указываем какие данные были приняты
$service_id=$autocomplete_hidden_input_1;
$doctor_id=$autocomplete_hidden_input_2;
$nurse_id=$autocomplete_hidden_input_3;
$doctors_department_id=$autocomplete_hidden_input_4;
//Преобразуем дату в mysql дату
$referral_mysql_date=date_to_mysql_date($referral_date);
//Не контролируем дублирующие услуги. Пока даем возможность добавлять одинаковые услуги.
//Тут проверка данных
//Создаем запись
mysql_query("INSERT INTO `referral_elements`  (`referral_id`,`service_id`,`doctor_id`,`nurse_id`,`doctors_department_id`,`diagnosis_mkb`,`warranty_letter_number`,`referral_date`) VALUES ('$id','$service_id','$doctor_id','$nurse_id','$doctors_department_id','$diagnosis_mkb','$warranty_letter_number','$referral_mysql_date')") or die (mysql_error()); 

//Переходим к этой записи (узнаем её id сразу после вставки)
header('Location:admin/admin.php?referrals&referrals_list'.$get_path_parametrs.'&light_element_id='.mysql_insert_id().'#element_'.mysql_insert_id());
exit;
}


//ИЗМЕНЕНИЕ ЭЛЕМЕНТА НАПРАВЛЕНИЯ (НОВОЙ УСЛУГИ В НАПРАВЛЕНИЕ)
if (isset($_GET['edit_referral_element']))
{
//Принимаем данные выше
//Используется автозаполнение - указываем какие данные были приняты
$service_id=$autocomplete_hidden_input_1;
$doctor_id=$autocomplete_hidden_input_2;
$nurse_id=$autocomplete_hidden_input_3;
$doctors_department_id=$autocomplete_hidden_input_4;
//Преобразуем дату в mysql дату
$referral_mysql_date=date_to_mysql_date($referral_date);
//Не контролируем дублирующие услуги. Пока даем возможность добавлять одинаковые услуги.
//Тут проверка данных
//Обновляем запись
mysql_query("UPDATE `referral_elements` SET service_id='$service_id',doctor_id='$doctor_id',nurse_id='$nurse_id',doctors_department_id='$doctors_department_id',diagnosis_mkb='$diagnosis_mkb',warranty_letter_number='$warranty_letter_number',referral_date='$referral_mysql_date' WHERE id='$id'");
//Переходим к этой записи (узнаем её id сразу после вставки)
header('Location:admin/admin.php?referrals&referrals_list'.$get_path_parametrs.'&light_element_id='.$id.'#element_'.mysql_insert_id());
exit;
}


//УДАЛЕНИЕ ЭЛЕМЕНТА НАПРАВЛЕНИЯ 
if (isset($_GET['delete_referral_element']))
{
//Принимаем данные выше
//Если нет подтверждения, то не удаляем
if ($confirmation<>'удаление')
{
header('Location:'.$_SERVER['HTTP_REFERER'].'&message=no_delete_confirmation#referral_'.$id);
exit;
}
//Удаляем запись
mysql_query("DELETE FROM `referral_elements` WHERE id='$id'");
//Тут удаляем все записи связанные с данным расписанием

header('Location:admin/admin.php?referrals&referrals_list'.$get_path_parametrs);
exit;
}



//ОТОБРАЖЕНИЕ РЕЕСТРОВ НАПРАВЛЕНИЙ С ПАРАМЕТРАМИ
if (isset($_GET['show_register_of_direction']))
{
//Код зависит от услуги, как и услуга от кода
//Если есть хотя бы одно из этих значений, то дополняем второе
//if ($code_id<>'' and $service_id=='') $service_id=get_service_id_from_code_id($code_id);
//if ($code_id=='' and $service_id<>'') $service_id=get_code_id_from_service_id($service_id);

//header('Location:admin/admin.php?registers&register_of_direction&date_1='.$date_1.'&date_2='.$date_2.'&patient_id='.$autocomplete_hidden_input_1.'&company_id='.$autocomplete_hidden_input_2.'&code_id='.$autocomplete_hidden_input_3.'&service_id='.$autocomplete_hidden_input_4.'');

header('Location:admin/admin.php?registers&register_of_direction&date_1='.$date_1.'&date_2='.$date_2.'&patient_id='.$autocomplete_hidden_input_1.'&company_id='.$autocomplete_hidden_input_2.'&code_id='.$autocomplete_hidden_input_3.'&doctors_department_id='.$autocomplete_hidden_input_4.'');

exit;
}










?>