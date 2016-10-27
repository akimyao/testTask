<?php

namespace TestTask;

class Auth extends Connection
{
    public function signIn($login, $password)
    {
        if($this->checkPassword($login, $password)) {
            $_SESSION['is_set'] = true;
            $_SESSION['login'] = $login;
            $_SESSION['csrf'] = $this->generateCsrfToken();
            return '';
        } else {
            $alert = 'Неверный логин или пароль';
            return HtmlHelper::generateAlert($alert);
        }
    }

    public function signOut()
    {
        $_SESSION = array();
        session_destroy();
    }

    public function isAuth()
    {
        if (isset($_SESSION['is_set'])) {
            return true;
        } else {
            return false;
        }
    }
    
    public function isCsrfValid($token)
    {
        if ($token == $_SESSION['csrf']) {
            return true;
        } else {
            return false;
        }
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
}