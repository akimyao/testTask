<!DOCTYPE html>
<html>
<head>
    <title>Test Task</title>
    <link href="/css/style.css" rel="stylesheet">
</head>
<body>
<div class="box">
    <div id="regform" style="display: none;">
        <h2>Регистрация (<a href="#" id="changeFormToLog">Уже зарегестрированы?</a>)</h2>
        <form id="reg_form">
            Логин:<br>
            <input type="text" id="reglogin" name="reglogin" value=""><br><br>
            Пароль:<br>
            <input type="password" id="regpsw" name="regpsw" value=""><br><br>
            Пол:<br>
            <select name="gender">
                <option value="male">Мужской</option>
                <option value="female">Женский</option>
                <option value="other" selected>Не указано</option>
            </select><br><br>
            Немного о себе:<br>
            <textarea name="about" rows="10" cols="30">
            </textarea><br><br>
            <input type="button" id="signup" value="Регистрация">
        </form>
    </div>
    <div id="logform">
        <h2>Вход (<a href="#" id="changeFormToReg">Нет аккаунта?</a>)</h2>
        <form id="signInForm">
            <input type="text" id="login" name="login" placeholder="Логин" />
            <input type="password" id="psw" name="psw" placeholder="Пароль" />
            <input type="button" id="signin" value="Войти" />
        </form>
    </div>
</div>

<script src="/js/jquery-3.1.1.min.js"></script>
<script src="/js/script.js"></script>
</body>
</html>