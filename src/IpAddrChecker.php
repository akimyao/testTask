<?php

namespace TestTask;


class IpAddrChecker extends Main
{
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