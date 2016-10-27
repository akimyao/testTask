<?php

namespace TestTask;

/**
 * Class Main
 *
 * Основной класс, реализующий соединение с базой данных.
 *
 * @package TestTask
 */
class Main
{
    /**
     * @var \PDO Подключение к базе
     */
    protected $db;

    /**
     * Main constructor. Принимает PDO объект и устанавливает в качестве состояния объекта.
     * 
     * @param $db
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * @return \PDO
     */
    public function getConnection()
    {
        return $this->db;
    }

    /**
     * Статический метод. Создаёт экземпляр PDO и возвращает его
     * 
     * @param string $config ссылка на файл, содержащий параметры подключения
     * @param string $host
     * @param string $dbname
     * @param string $user
     * @param string $pass
     * @return \PDO
     */
    public static function setConnection($config = '', $host = 'localhost', $dbname = '', $user = '', $pass = '')
    {
        include $config;

        return new \PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    }
}