<?php

use \PHPUnit\Framework\TestCase;

use \UserAccess\Core\Util\AuditLog;

class AuditLogTest extends TestCase {

    public function test() {
        $auditLog = new AuditLog('testdata/log');
        $this->assertNotEmpty($auditLog);
    }

}