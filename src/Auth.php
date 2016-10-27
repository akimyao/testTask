<?php

namespace TestTask;

class Auth extends Main
{
    public function signIn($login, $password, $csrf)
    {
        if($this->checkPassword($login, $password) && $this->isCsrfValid($csrf)) {
            $_SESSION['exists'] = true;
            $_SESSION['login'] = $login;
            return '';
        } else {
            $alert = 'Ошибка авторизации. Попробуйте ещё раз.';
            return HtmlHelper::generateAlert($alert);
        }
    }

    public function signOut($csrf)
    {
        if ($this->isCsrfValid($csrf)) {
            $_SESSION = array();
            session_destroy();
        }
    }

    public function isAuth()
    {
        return isset($_SESSION['exists']);
    }

    public function setCsrf()
    {
        if (!$this->csrfExists()) {
            $csrf = $this->generateCsrfToken();
            setcookie("csrf", $csrf, time() + (60 * 60 * 24), "/"); // сутки
        }
    }
    
    public function isCsrfValid($token)
    {
        return $this->csrfExists() && $token == $_COOKIE['csrf'];
    }

    private function csrfExists()
    {
        return isset($_COOKIE['csrf']) && !empty($_COOKIE['csrf']);
    }

    private function checkPassword($login, $psw)
    {
        $preReq = $this->db->prepare("SELECT * FROM testtask WHERE login=?");
        $preReq->execute(array($login));
        $result = $preReq->fetch(\PDO::FETCH_LAZY);
        return password_verify($psw, $result['password']);
    }

    private function generateCsrfToken()
    {
        return substr(md5(rand()), 0, 25);
    }

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
        $preReq = $this->db->query("SELECT id, login, gender, regip, regtime from testtask ORDER BY id DESC");
        $preReq->setFetchMode(\PDO::FETCH_LAZY);
        while ($row = $preReq->fetch()) {
            $line = '';
            $line .= HtmlHelper::setTagWith('td', $row['id']);
            $line .= HtmlHelper::setTagWith('td', $row['login']);
            $line .= HtmlHelper::setTagWith('td', $row['gender']);
            $line .= HtmlHelper::setTagWith('td', $row['regip']);
            $line .= HtmlHelper::setTagWith('td', $row['regtime']);

            $lines .= HtmlHelper::setTagWith('tr', $line);
        }

        return HtmlHelper::setTagWith('table', $tableHead . $lines);
    }
}