<?php

namespace TestTask;

/**
 * Class Auth
 *
 * Выполняет авторизацию и взаимодействие с пользователем.
 * @package TestTask
 */
class Auth extends Main
{
    /**
     * Вход
     * @param string $login
     * @param string $password
     * @return string
     */
    public function signIn($login, $password)
    {
        if ($this->checkPassword($login, $password)) {
            $_SESSION['exists'] = true;
            $_SESSION['login'] = $login;
            return '';
        } else {
            $alert = 'Ошибка авторизации. Попробуйте ещё раз.';
            return HtmlHelper::generateAlert($alert);
        }
    }

    /**
     * Выход
     *
     * Уничтожает сессию.
     */
    public function signOut()
    {
        $_SESSION = array();
        session_destroy();
    }

    /**
     * Проверяет, существует ли сессия
     * @return bool
     */
    public function isAuth()
    {
        return isset($_SESSION['exists']);
    }

    /**
     * Инициализация CSRF-токена
     *
     * Устанавливает CSRF-токен на сутки в cookie, если тот не существует
     */
    public function setCsrf()
    {
        if (!$this->csrfExists()) {
            $csrf = $this->generateCsrfToken();
            setcookie("csrf", $csrf, time() + (60 * 60 * 24), "/"); // сутки
        }
    }

    /**
     * Проверка CSRF-токена.
     *
     * Проверяет, совпадает ли переданный токен с тем, который установлен в cookie
     *
     * @param $token
     * @return bool
     */
    public function isCsrfValid($token)
    {
        return $this->csrfExists() && $token == $_COOKIE['csrf'];
    }

    /**
     * Проверяет, установлен ли CSRF-токен
     *
     * @return bool
     */
    private function csrfExists()
    {
        return isset($_COOKIE['csrf']) && !empty($_COOKIE['csrf']);
    }

    /**
     * Проверяет, верный ли пароль
     *
     * @param string $login логин
     * @param string $psw пароль
     * @return bool
     */
    private function checkPassword($login, $psw)
    {
        $preReq = $this->db->prepare("SELECT * FROM testtask WHERE login=?");
        $preReq->execute(array($login));
        $result = $preReq->fetch(\PDO::FETCH_LAZY);
        return password_verify($psw, $result['password']);
    }

    /**
     * Генерирует случайный 25-тизначный токен
     * 
     * @return string
     */
    private function generateCsrfToken()
    {
        return substr(md5(rand()), 0, 25);
    }

    /**
     * Создаёт таблицу со списком последних зарегистрированных посльзователей
     * 
     * @return string
     */
    public function createTable()
    {
        $tableHead = '';
        $tableHead .= HtmlHelper::setTagWith('td', 'ID');
        $tableHead .= HtmlHelper::setTagWith('td', 'Login');
        $tableHead .= HtmlHelper::setTagWith('td', 'Gender');
        $tableHead .= HtmlHelper::setTagWith('td', 'IP');
        $tableHead .= HtmlHelper::setTagWith('td', 'Reg.Time');

        $tableHead = HtmlHelper::setTagWith('tr', $tableHead);
        $tableHead = HtmlHelper::setTagWith('thead', $tableHead);

        $lines = '';
        $preReq = $this->db->query("SELECT id, login, gender, regip, regtime from testtask ORDER BY id DESC LIMIT 50");
        $preReq->setFetchMode(\PDO::FETCH_LAZY);
        while ($row = $preReq->fetch()) {
            $line = '';
            $line .= HtmlHelper::setTagWith('td', $row['id']);
            $line .= HtmlHelper::setTagWith('td', $row['login']);
            $line .= HtmlHelper::setTagWith('td', $row['gender']);
            $line .= HtmlHelper::setTagWith('td', $row['regip']);
            $line .= HtmlHelper::setTagWith('td', date('d F Y H:i', $row['regtime']));

            $lines .= HtmlHelper::setTagWith('tr', $line);
        }

        return HtmlHelper::setTagWith('table', $tableHead . $lines);
    }
}