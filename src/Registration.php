<?php

namespace TestTask;

/**
 * Class Registration
 * 
 * Класс, осуществляющий регистрацию - проверку пользовательских данных и последующую их запись в базу данных.
 * 
 * @package TestTask
 */
class Registration extends Main
{
    /**
     * Регулярное выражение для проверки валидности логина и пароля
     */
    const LOGPSW_VALID = '#^[a-zA-Z0-9]{3,15}$#';

    /**
     * @var array ошибки выполнения и предупреждения
     */
    private $alerts = [];
    
    private $login = '';
    private $password = '';
    private $email = '';
    private $name = '';
    private $about = '';
    private $gender = '';
    private $remoteAddr = '';

    /**
     * Проверяет и устанавливает логин
     * 
     * @param string $login
     * @return string
     */
    public function setLogin($login)
    {
        $this->validEmpty($login, 'Поле "Логин" обязательно для заполнения');
        $this->validLoginExists($login);
        if (!preg_match(self::LOGPSW_VALID, $login)) {
            $this->alerts[] = 'Логин должен быть от 3 до 15 символов и содержать только цифры и латиницу.';
        }
        $this->login = $login;
        return $login;
    }

    /**
     * Проверяет, использовался логин ранее
     * 
     * @param string $login
     */
    private function validLoginExists($login)
    {
        $preReq = $this->db->prepare("SELECT COUNT(login) FROM testtask WHERE login=?");
        $preReq->execute(array($login));
        $check = $preReq->fetch();
        if ($check[0] > 0) {
            $this->alerts[] = 'Этот логин уже занят. Пожалуйста, выберите другой.';
        }
    }

    /**
     * Проверяет и устанавливает пароль
     * 
     * @param string $psw
     * @return string
     */
    public function setPassword($psw)
    {
        $this->validEmpty($psw, 'Поле "Пароль" обязательно для заполнения');
        if (!preg_match(self::LOGPSW_VALID, $psw)) {
            $this->alerts[] = 'Пароль должен быть от 3 до 15 символов и содержать только цифры и латиницу.';
        }
        $this->password = password_hash($psw, PASSWORD_BCRYPT);;
        return $psw;
    }

    /**
     * Проверяет и устанавливает email
     * 
     * @param string $email
     */
    public function setEmail($email)
    {
        $emailValid = filter_var($email, FILTER_VALIDATE_EMAIL);
        if ($emailValid == $email) {
            $this->email = $email;
        } else {
            $this->alerts[] = 'Некорректный e-mail.';
        }
    }

    /**
     * Проверяет и устанавливает пол
     * 
     * @param string $gender
     */
    public function setGender($gender)
    {
        switch ($gender) {
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

    /**
     * Проверяет и устанавливает имя
     * 
     * @param string $name
     */
    public function setName($name)
    {
        if (strlen($name) < 60) {
            $this->name = $name;
        } else {
            $this->alerts[] = 'Слишком длинное имя.';
        }
    }

    /**
     * Проверяет и устанавливает описание
     * 
     * @param string $about
     */
    public function setAbout($about)
    {
        if (strlen($about) < 500) {
            $this->about = $about;
        } else {
            $this->alerts[] = 'Недопустимое значение поля "О себе". Ограничение - 500 символов.';
        }
    }

    /**
     * Устанавливает IP пользователя
     * 
     * @param string $ip
     */
    public function setRemoteAddr($ip)
    {
        $this->remoteAddr = $ip;
    }

    /**
     * Проверяет, возникали ли ошибки
     * 
     * @return bool
     */
    public function hasAlerts()
    {
        if (count($this->alerts) > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Возвращает массив возникших ошибок
     * 
     * @return array
     */
    public function getAlerts()
    {
        return $this->alerts;
    }

    /**
     * Завершение регистрации
     * 
     * Записывает все данные в базу данных
     * 
     * @return bool
     */
    public function completeReg()
    {
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
            . " VALUES (?, ?, ?, ?, ?, ?, ?)");
        $preReq->execute($info);
        return true;
    }

    /**
     * Проверка на наличие значения
     * 
     * Если первым параметром передано пустое значение, 
     * то добавляет ошибку с текстом, содержащимся во втором параметре
     * 
     * @param $string
     * @param $msg
     */
    private function validEmpty($string, $msg)
    {
        if (empty($string)) {
            $this->alerts[] = $msg;
        }
    }
}
