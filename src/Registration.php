<?php

namespace TestTask;


class Registration extends Main
{
    const LOGPSW_VALID_OPTS = array('options'=>array('regexp'=>'#[a-zA-Z0-9]{3,15}#'));

    private $alerts = [];

    private $login = '';
    private $password = '';
    private $email = '';
    private $name = '';
    private $about = '';
    private $gender = '';
    private $remoteAddr = '';


    public function setLogin($login)
    {
        $logValid = filter_var($login, FILTER_VALIDATE_REGEXP, self::LOGPSW_VALID_OPTS);
        $this->validEmpty($login, 'Поле "Логин" обязательно для заполнения');
        $this->validLoginExists($login);
        if($login != $logValid){
            $this->alerts[] = 'Логин должен быть от 3 до 15 символов и содержать только цифры и латиницу.';
        }
        $this->login = $login;
    }

    private function validLoginExists($login)
    {
        $preReq = $this->db->prepare("SELECT COUNT(login) FROM testtask WHERE login=?");
        $preReq->execute(array($login));
        $check = $preReq->fetch();
        if ($check[0] > 0) {
            $this->alerts[] = 'Этот логин уже занят. Пожалуйста, выберите другой.';
        }
    }

    public function setPassword($psw)
    {
        $pswValid = filter_var($psw, FILTER_VALIDATE_REGEXP, self::LOGPSW_VALID_OPTS);
        $this->validEmpty($psw, 'Поле "Пароль" обязательно для заполнения');
        if($psw != $pswValid){
            $this->alerts[] = 'Пароль должен быть от 3 до 15 символов и содержать только цифры и латиницу.';
        }
        $this->password = password_hash($psw, PASSWORD_BCRYPT);;
    }

    public function setEmail($email)
    {
        $emailValid = filter_var($email, FILTER_VALIDATE_EMAIL);
        if($emailValid == $email) {
            $this->email = $email;
        } else {
            $this->alerts[] = 'Некорректный e-mail.';
        }
    }

    public function setGender($gender)
    {
        switch ($gender){
            case 'other':
                $this->gender = 'other';
                break;
            case 'male':
                $this->gender = 'male';
                break;
            case 'female':
                $this->gender = 'female';
                break;
            default:
                $this->alerts[] = 'Недопустимое значение для поля "Пол".';
                break;
        }
    }

    public function setName($name)
    {
        if (strlen($name) < 60) {
            $this->name = $name;
        } else {
            $this->alerts[] = 'Слишком длинное имя.';
        }
    }

    public function setAbout($about)
    {
        if(strlen($about) < 500) {
            $this->about = $about;
        } else {
            $this->alerts[] = 'Недопустимое значение поля "О себе". Ограничение - 500 символов.';
        }
    }

    public function setRemoteAddr($ip)
    {
        $this->remoteAddr = $ip;
    }

    public function isSetNotEmpty($string)
    {
        if (isset($string)) {
            if (!empty($string)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function isAlerts() {
        if (count($this->alerts) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getAlerts() {
        return $this->alerts;
    }

    public function completeReg() {
        $timeNow = time();
        $info = array($this->login,
            $this->password,
            $this->gender,
            $this->name,
            $this->about,
            $this->remoteAddr,
            $timeNow
        );
        
        $preReq = $this->db->prepare("INSERT INTO testtask (login, password, gender, name, about, regip, regtime)"
                                    ." VALUES (?, ?, ?, ?, ?, ?, ?)");
        $preReq->execute($info);
        return 'Регистрация успешно завершена.';
    }

    private function validEmpty($string, $msg)
    {
        if(empty($string)) {
            $this->alerts[] = $msg;
        }
    }

}