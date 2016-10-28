<?php
session_start();

$config = __DIR__ . '/db/config.php';
require_once __DIR__ . '/autoload.php';

$db = \TestTask\Main::setConnection($config); // соединение с базой

$auth = new \TestTask\Auth($db); //класс для работы с юзером
$currentCsrf = $auth->getCsrf(); // устанавливаем и получаем токен

$message = '';

$allowToken = isset($_POST['csrf']) && $auth->isCsrfValid($_POST['csrf']);

// обрабатываем POST-запросы если валидный токен
if ($allowToken) {
    
    // выход
    if (isset($_POST['out'])) {
        $auth->signOut();
    }

    // вход
    if (isset($_POST['signin']) && isset($_POST['login']) && isset($_POST['pass'])) {
        $message .= $auth->signIn($_POST['login'], $_POST['pass']);
    }

    // передача сообщений
    if (isset($_POST['suc_msg'])) {
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
    // если юзер залогинился -- выводим приветсвие, в противном случае -- форму входа и регистрации
    if ($auth->isAuth()) {
        echo 'Здравствуйте, ' . htmlspecialchars($_SESSION['login']) . "!";
        ?>

        <form action="" method="post">
            <input type="hidden" name="csrf" value="<?= $currentCsrf ?>">
            <button type="submit" name="out" value="true">Выйти</button>
        </form>

        <?php
    } else {
        ?>

        <div id="regform" style="display: none">
            <h2>Регистрация (<a href="#" class="link-head" id="changeFormToLog">Уже зарегистрированы?</a>)</h2>
            <p>* - обязательно для заполнения.</p>
            <form id="reg_form">
                <label for="reglogin">Логин* (3-15 символов):</label><br>
                <input type="text" id="reglogin" name="reglogin"><br><br>
                <label for="regpsw">Пароль* (3-15 символов):</label><br>
                <input type="password" id="regpsw" name="regpsw"><br><br>
                <label for="email">E-mail:</label><br>
                <input type="text" id="email" name="email"><br><br>
                <label for="gender">Пол:</label><br>
                <select id="gender" name="gender">
                    <option value="other" selected>Не указано</option>
                    <option value="male">Мужской</option>
                    <option value="female">Женский</option>
                </select><br><br>
                <label for="name">Полное имя:</label><br>
                <input type="text" id="name" name="name"><br><br>
                <label for="about">Немного о себе:</label><br>
                <textarea name="about" id="about" rows="10" cols="30"></textarea><br><br>
                <input type="hidden" name="csrf" id="csrf" value="<?= $currentCsrf ?>">
                <input type="submit" id="signup" value="Регистрация">
            </form>
        </div>
        <div id="logform">
            <h2>Вход (<a href="#" class="link-head" id="changeFormToReg">Нет аккаунта?</a>)</h2>
            <form action="" method="post">
                <input name="login" type="text" size="15" maxlength="15" placeholder="Логин">
                <input name="pass" type="password" size="15" maxlength="15" placeholder="Пароль">
                <input type="hidden" name="csrf" value="<?= $currentCsrf ?>">
                <input type="submit" name="signin" value="Войти">
                <br>
            </form>
        </div>
        <form action="" id="message" method="post">
            <input type="hidden" id="suc_msg" name="suc_msg">
            <input type="hidden" name="csrf" value="<?= $currentCsrf ?>">
        </form>
        <?php
    }
    ?>
</div>


<?php
// если пользователь залогинился, -- выводим простенькую табличку с юзерами
if ($auth->isAuth()) {
    echo $auth->createTable();
}
?>


<script src="/js/jquery-3.1.1.min.js"></script>
<script src="/js/script.js"></script>
</body>
</html>
