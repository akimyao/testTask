<?php

namespace TestTask;


class Main
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }
    
    public function getConnection()
    {
        return $this->db;
    }
    
    public static function setConnection($config, $host='localhost',$dbname='',$user='',$pass='')
    {
        include $config;

        return new \PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    }
}