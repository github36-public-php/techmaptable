<?php
class HTML
{

// Получить код.
static function getHTMLCode($filePath) {
ob_start();
include $filePath;
return ob_get_clean();
}

// Получить код из шаблона.
static function getTemplateCode($templateFileName, $templateArgumentsArray) {
$templateFullPath=$_SERVER['DOCUMENT_ROOT'].'/templates/'.$templateFileName;
ob_start();
include $templateFullPath;
return ob_get_clean();
}

// Код HTML страницы.
static function showHTMLPage($htmlPageTitle='', $htmlPageСontent='') {
return self::getTemplateCode('ShowHTMLPage.html', array($htmlPageTitle, $htmlPageСontent));
}

// Код формы авторизации.
static function showAuthorizationWindow($formAction='') {
return self::getTemplateCode('ShowAuthorizationWindow.html', array($formAction));
}

// Код окна.
static function showWindow($windowTitle='', $windowContent='', $windowFooter='') {
return self::getTemplateCode('ShowWindow.html', array($windowTitle, $windowContent, $windowFooter));
}

// Код меню.
static function showMenu($menuArray, $menuCode='') {
return self::getTemplateCode('ShowMenu.html', array($menuArray, $menuCode));
}

// Код формы поиска в меню.
static function showMenuFindForm($inputClass, $outputClass) {
return self::getTemplateCode('ShowMenuFindForm.html', array($inputClass, $outputClass, $tableName));
}


// Код панели пользователя.
static function showUserPanelMarkup($userInformation='', $userButtons='') {
return self::getTemplateCode('ShowUserPanelMarkup.html', array($userInformation, $userButtons));
}

// Код панели.
static function showPanelMarkup($panelClass='', $panelMenu='', $panelСontent='') {
return self::getTemplateCode('ShowPanelMarkup.html', array($panelClass, $panelMenu, $panelСontent));
}


// Код кнопки в окне.
static function showWindowButton($buttonHref='#', $buttonClass='', $buttonText='') {
return self::getTemplateCode('ShowWindowButton.html', array($buttonHref, $buttonClass, $buttonText));
}


// Код кнопки.
static function showButton($buttonHref='#', $buttonClass='', $buttonText='') {
return self::getTemplateCode('ShowButton.html', array($buttonHref, $buttonClass, $buttonText));
}


// Показать окно с сообщением.
static function showInformationPage($messageText='', $buttonHref='#') {
$windowTitle='Сообщение';
$windowContent=$messageText;
$windowFooter=self::showWindowButton($buttonHref, '', 'Ok');
$htmlPageСontent=self::showWindow($windowTitle, $windowContent, $windowFooter);
$htmlOutput = self::showHTMLPage($windowTitle, $htmlPageСontent);
return $htmlOutput;
}


// Показать окно с авторизации.
static function showAuthorizationPage($formAction='') {
$windowTitle='Авторизация';
$htmlPageСontent=self::showAuthorizationWindow($formAction);
$htmlOutput = self::showHTMLPage($windowTitle, $htmlPageСontent);
return $htmlOutput;
}

// Код типовой страницы.
static function showPageMarkup($pageMenu, $pageUserPanel='', $pageСontent='') {
return self::getTemplateCode('ShowPageMarkup.html', array($pageMenu, $pageUserPanel, $pageСontent));
}

// Показать типовую страницу.
static function showPage($windowTitle='Страница', $pageMenu, $pageUserPanel, $pageСontent) {
$htmlPageСontent = self::showPageMarkup($pageMenu, $pageUserPanel, $pageСontent);
$htmlOutput = self::showHTMLPage($windowTitle, $htmlPageСontent);
return $htmlOutput;
}




// Показать список пользователей.
static function showUserList() {
return self::getTemplateCode('ShowUserList.html', '');
}
// Показать форму для добавления или редактирования пользователя.
static function showUserForm($formAction='') {
return self::getTemplateCode('ShowUserForm.html', array($formAction));
}


// Показать список адресов.
static function showAddressList() {
return self::getTemplateCode('ShowAddressList.html', '');
}
// Показать форму для добавления или редактирования адреса.
static function showAddressForm($formAction='') {
return self::getTemplateCode('ShowAddressForm.html', array($formAction));
}

// Показать таблицу техкарты.
static function showTechmapTable($TechmapTableRowsArray) {
return self::getTemplateCode('ShowTechmapTable.html', array($TechmapTableRowsArray));
}



}
