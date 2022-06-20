<?php
/*
Этот файл - форма входа в админцентр.
*/

//Путь к корневой директории для данного файла
$path_to_root_dir['index']='';

//Верх страницы
include ($path_to_root_dir['index'].'common/page_header.php');

//Показ сообщений пользователю
include ($path_to_root_dir['index'].'common/messages.php');

//Если ошибка - выводим сообщение об ошибке
if (isset($_GET['login_or_password_error']))
{
}
?>


<div class="help_container">

Это демо информационной системы медицинского назначения.<br>
После авторизации, все внесенные ранее изменения будут удалены.<br>
Все данные в системе (врачи, пациенты) не настоящие.<br><br>
Логин администратора admin, пароль 123.<br>
Логин пользователя user, пароль 123.<br>

</div>

<form action="actions.php?login" method="POST">
<div class="login_window">
<div class="login_window_title_text">Вход в систему</div>
<div class="form_captions">Логин
<input value="admin" type="text" name="login" size="20" class="form_field" style="margin-left:20px;">
</div>
<div class="form_captions">Пароль
<input value="123" type="password" name="password" size="20" class="form_field" style="margin-left:10px;">
</div>
<input type="submit" value="Вход" class="enter_button" style="margin-left:180px;">
</div>
</form>



<?php
//Низ страницы
include ($path_to_root_dir['index'].'common/page_footer.php');
?>