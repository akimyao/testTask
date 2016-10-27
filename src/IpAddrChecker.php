<?php

namespace TestTask;

/**
 * Class IpAddrChecker
 *
 * Класс для предотвращения повторных регистраций в течение заданного промежутка времени.
 * 
 * @package TestTask
 */
class IpAddrChecker extends Main
{
    /**
     * Проверяет, можно ли разрешить переданному IP регистрацию.
     * 
     * @param string $ip IP адрес
     * @param int $time текущее время (в формате unix time)
     * @param int $timeLimit время, в течение которого будет недоступна повторная регистрация (в секундах)
     * @return bool
     */
    public function isIpAllowed($ip, $time, $timeLimit)
    {
        $preReq = $this->db->prepare("SELECT regtime FROM testtask WHERE regip=? ORDER BY regtime DESC LIMIT 1");
        $preReq->execute(array($ip));
        $result = $preReq->fetch(\PDO::FETCH_LAZY);
        $diff = $time - (int)$result['regtime'];
        if ($diff >= $timeLimit) {
            return true;
        } else {
            return false;
        }
    }
}