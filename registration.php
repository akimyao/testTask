<?php

$config = __DIR__ . '/db/config.php';
include __DIR__ . '/autoload.php';

if(isset($_POST['login']) && isset($_POST['psw']) && isset($_POST['csrf'])) {

    $timeLimit = 60 * 60;
    $db = \TestTask\Main::setConnection($config);
    
    $reg = new \TestTask\Registration($db);
    $auth = new \TestTask\Auth($db);
    $ipChecker = new \TestTask\IpAddrChecker($db);

    if (!$auth->isCsrfValid($_POST['csrf'])) {
        $msg = 'Произошла ошибка. Пожалуйста, обновите страницу и попробуйте снова.';
        echo json_encode(array(false, array($msg)));
        exit();
    }
    
    if (!$ipChecker->isIpAllowed($_SERVER['REMOTE_ADDR'], time(), $timeLimit)) {
        $msg = 'Регистрация с этого IP-адреса временно запрещена.';
        echo json_encode(array(false, array($msg)));
        exit();
    }

    $reg->setLogin($_POST['login']);
    $reg->setPassword($_POST['psw']);
    $reg->setRemoteAddr($_SERVER['REMOTE_ADDR']);
    
    if ($reg->isSetNotEmpty($_POST['email'])) {
        $reg->setEmail($_POST['email']);
    }

    if ($reg->isSetNotEmpty($_POST['gender'])) {
        $reg->setGender($_POST['gender']);
    }

    if ($reg->isSetNotEmpty($_POST['name'])) {
        $reg->setName($_POST['name']);
    }

    if ($reg->isSetNotEmpty($_POST['about'])) {
        $reg->setAbout($_POST['about']);
    }

    if ($reg->isAlerts()) {
        echo json_encode(array(false, $reg->getAlerts()));
    } else {
        $response = $reg->completeReg();
        echo json_encode(array(true, array($response)));
    }
}
