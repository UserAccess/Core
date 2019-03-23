<?php

namespace UserAccess\Util;

use \Monolog\Logger;
use \Monolog\Handler\StreamHandler;

class AuditLog {

    private $logger;

    public function __construct(string $directory = 'log') {
        $this->logger = new Logger('UserAccess');
        $this->logger->pushHandler(new StreamHandler($directory . '/auditlog.log', Logger::DEBUG));
        $this->logger->addInfo('Logger initialized');
    }

    //User Created By, modified, deleted

    // authenticated


}