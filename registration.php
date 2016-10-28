<?php
session_start();

$config = __DIR__ . '/db/config.php';
include __DIR__ . '/autoload.php';

if (isset($_POST['login']) && isset($_POST['psw']) && isset($_POST['csrf'])) {

    $timeLimit = 60 * 10; // ограничение регистрации для IP - раз в 10 минут
    $db = \TestTask\Main::setConnection($config); // коннект к базе

    $reg = new \TestTask\Registration($db);
    $auth = new \TestTask\Auth($db);
    $ipChecker = new \TestTask\IpAddrChecker($db);

    if (!$auth->isCsrfValid($_POST['csrf'])) {
        $msg = 'Произошла ошибка. Пожалуйста, обновите страницу и попробуйте снова.';
        echo json_encode(array(false, array($msg)));
        exit();
    }

    // проверка - разрешена ли регистрация для этого айпи
    if (!$ipChecker->isIpAllowed($_SERVER['REMOTE_ADDR'], time(), $timeLimit)) {
        $msg = 'Регистрация с этого IP-адреса временно запрещена.';
        echo json_encode(array(false, array($msg)));
        exit();
    }

    // передача параметров для дальнейшей записи в базу
    $login = $reg->setLogin($_POST['login']);
    $password = $reg->setPassword($_POST['psw']);
    $reg->setRemoteAddr($_SERVER['REMOTE_ADDR']);

    if (!empty($_POST['email'])) {
        $reg->setEmail($_POST['email']);
    }

    if (!empty($_POST['gender'])) {
        $reg->setGender($_POST['gender']);
    }

    if (!empty($_POST['name'])) {
        $reg->setName($_POST['name']);
    }

    if (!empty($_POST['about'])) {
        $reg->setAbout($_POST['about']);
    }

    // если есть ошибки, выводим их. Иначе завершаем регистрацию и логинимся
    if ($reg->hasAlerts()) {
        echo json_encode(array(false, $reg->getAlerts()));
    } else {
        $reg->completeReg();
        $dl = $auth->signIn($login, $password);
        $msg = 'Регистрация завершена.'.$dl;
        echo json_encode(array(true, $msg));
    }
}
