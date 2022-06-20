<?php
class HTML
{

// Получить код.
static function GetHTMLCode($filePath) {
ob_start();
include $filePath;
return ob_get_clean();
}

// Получить код из шаблона.
static function GetTemplateCode($templateFileName, $templateArgumentsArray) {
$templateFullPath=$_SERVER['DOCUMENT_ROOT'].'/templates/'.$templateFileName;
ob_start();
include $templateFullPath;
return ob_get_clean();
}

// Код HTML страницы. +
static function ShowHTMLPage($htmlPageTitle='', $htmlPageСontent='') {
return self::GetTemplateCode('ShowHTMLPage.html', array($htmlPageTitle, $htmlPageСontent));
}

// Код формы авторизации. + 
static function ShowAuthorizationWindow($formAction='') {
return self::GetTemplateCode('ShowAuthorizationWindow.html', array($formAction));
}

// Код окна. +
static function ShowWindow($windowTitle='', $windowContent='', $windowFooter='') {
return self::GetTemplateCode('ShowWindow.html', array($windowTitle, $windowContent, $windowFooter));
}

// Код меню. +
static function ShowMenu($menuArray, $menuCode='') {
return self::GetTemplateCode('ShowMenu.html', array($menuArray, $menuCode));
}

// Код формы поиска в меню. +
static function ShowMenuFindForm($inputClass, $outputClass) {
return self::GetTemplateCode('ShowMenuFindForm.html', array($inputClass, $outputClass, $tableName));
}


// Код панели пользователя. +
static function ShowUserPanelMarkup($userInformation='', $userButtons='') {
return self::GetTemplateCode('ShowUserPanelMarkup.html', array($userInformation, $userButtons));
}

// Код панели. +
static function ShowPanelMarkup($panelClass='', $panelMenu='', $panelСontent='') {
return self::GetTemplateCode('ShowPanelMarkup.html', array($panelClass, $panelMenu, $panelСontent));
}


// Код кнопки в окне. +
static function ShowWindowButton($buttonHref='#', $buttonClass='', $buttonText='') {
return self::GetTemplateCode('ShowWindowButton.html', array($buttonHref, $buttonClass, $buttonText));
}


// Код кнопки. +
static function ShowButton($buttonHref='#', $buttonClass='', $buttonText='') {
return self::GetTemplateCode('ShowButton.html', array($buttonHref, $buttonClass, $buttonText));
}


// Показать окно с сообщением. +
static function ShowInformationPage($messageText='', $buttonHref='#') {
$windowTitle='Сообщение';
$windowContent=$messageText;
$windowFooter=self::ShowWindowButton($buttonHref, '', 'Ok');
$htmlPageСontent=self::ShowWindow($windowTitle, $windowContent, $windowFooter);
$htmlOutput = self::ShowHTMLPage($windowTitle, $htmlPageСontent);
return $htmlOutput;
}


// Показать окно с авторизации. +
static function ShowAuthorizationPage($formAction='') {
$windowTitle='Авторизация';
$htmlPageСontent=self::ShowAuthorizationWindow($formAction);
$htmlOutput = self::ShowHTMLPage($windowTitle, $htmlPageСontent);
return $htmlOutput;
}

// Код типовой страницы. +
static function ShowPageMarkup($pageMenu, $pageUserPanel='', $pageСontent='') {
return self::GetTemplateCode('ShowPageMarkup.html', array($pageMenu, $pageUserPanel, $pageСontent));
}

// Показать типовую страницу. +
static function ShowPage($windowTitle='Страница',$pageMenu, $pageUserPanel, $pageСontent) {
$htmlPageСontent = self::ShowPageMarkup($pageMenu, $pageUserPanel, $pageСontent);
$htmlOutput = self::ShowHTMLPage($windowTitle, $htmlPageСontent);
return $htmlOutput;
}




// Показать список пользователей. +
static function ShowUserList() {
return self::GetTemplateCode('ShowUserList.html', '');
}
// Показать форму для добавления или редактирования пользователя. +
static function ShowUserForm($formAction='') {
return self::GetTemplateCode('ShowUserForm.html', array($formAction));
}


// Показать список адресов. +
static function ShowAddressList() {
return self::GetTemplateCode('ShowAddressList.html', '');
}
// Показать форму для добавления или редактирования адреса. +
static function ShowAddressForm($formAction='') {
return self::GetTemplateCode('ShowAddressForm.html', array($formAction));
}

// Показать таблицу техкарты. +
static function ShowTechmapTable($TechmapTableRowsArray) {
return self::GetTemplateCode('ShowTechmapTable.html', array($TechmapTableRowsArray));
}











}




?>