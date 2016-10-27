<?php

// автозагрузка классов. В принципе, можно было и через композер сделать 
require_once __DIR__ . '/src/Main.php';

require_once __DIR__ . '/src/Auth.php';
require_once __DIR__ . '/src/Registration.php';
require_once __DIR__ . '/src/IpAddrChecker.php';

require_once __DIR__ . '/src/HtmlHelper.php';
