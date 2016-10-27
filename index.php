<?php
session_start();
$config = __DIR__ . '/db/config.php';
require_once __DIR__ . '/autoload.php';

$db = \TestTask\Main::setConnection($config);
$auth = new \TestTask\Auth($db);
$auth->setCsrf();

$message = '';

if(isset($_POST['out']) && isset($_POST['csrf'])) {
    $auth->signOut($_POST['csrf']);
}

if(isset($_POST['signin']) && isset($_POST['login']) && isset($_POST['pass']) && isset($_POST['csrf'])) {
    $message .= $auth->signIn($_POST['login'], $_POST['pass'], $_POST['csrf']);
}

if(isset($_POST['suc_msg']) && isset($_POST['csrf'])) {
    if ($auth->isCsrfValid($_POST['csrf'])) {
        $message .= \TestTask\HtmlHelper::generateSuccess($_POST['suc_msg']);
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Test Task</title>
    <link href="/css/style.css" rel="stylesheet">
</head>
<body>
<?= $message ?>
<div class="box" id="main-box">
    <?php
    if ($auth->isAuth()){
        echo 'Здравствуйте, ' . $_SESSION['login'] ."!" ;
        ?>

        <form action="" method="post">
            <input type="hidden" name="csrf" value="<?= $_COOKIE['csrf'] ?>">
            <button type="submit" name="out" value="true">Выйти</button>
        </form>

        <?php
    } else {
    ?>

    <div id="regform" style="display: none">
        <h2>Регистрация (<a href="#" id="changeFormToLog">Уже зарегестрированы?</a>)</h2>
        <p>* - обязательно для заполнения.</p>
        <form id="reg_form">
            Логин* (3-15 символов):<br>
            <input type="text" id="reglogin" name="reglogin"><br><br>
            Пароль* (3-15 символов):<br>
            <input type="password" id="regpsw" name="regpsw"><br><br>
            E-mail:<br>
            <input type="text" id="email" name="email"><br><br>
            Пол:<br>
            <select id="gender" name="gender">
                <option value="male">Мужской</option>
                <option value="female">Женский</option>
                <option value="other" selected>Не указано</option>
            </select><br><br>
            Полное имя:<br>
            <input type="text" id="name" name="name"><br><br>
            Немного о себе:<br>
            <textarea name="about" id="about" rows="10" cols="30"></textarea><br><br>
            <input type="hidden" name="csrf" id="csrf" value="<?= $_COOKIE['csrf'] ?>">
            <input type="button" id="signup" value="Регистрация">
        </form>
    </div>
    <div id="logform">
        <h2>Вход (<a href="#" id="changeFormToReg">Нет аккаунта?</a>)</h2>
        <form action="" method="post">
                <input name="login" type="text" size="15" maxlength="15" placeholder="Логин">
                <input name="pass" type="password" size="15" maxlength="15" placeholder="Пароль">
                <input type="hidden" name="csrf" value="<?= $_COOKIE['csrf'] ?>">
                <input type="submit" name="signin" value="Войти">
                <br>
        </form>
    </div>
        <?php
    }
    ?>
</div>


<?php
if ($auth->isAuth()) {
    echo $auth->createTable();
}
?>


<script src="/js/jquery-3.1.1.min.js"></script>
<script src="/js/script.js"></script>
</body>
</html>