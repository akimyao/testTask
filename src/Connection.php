<?php

namespace TestTask;


class Connection
{
    protected $db;

    public function __construct($config, $host='localhost',$dbname='',$user='',$pass='')
    {
        include $config;

        $this->db = new \PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
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