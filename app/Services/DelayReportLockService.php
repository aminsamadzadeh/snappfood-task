<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;
use App\Models\DelayReport;

class DelayReportLockService
{
    protected static $lockTimeout = 5;
    protected static $lockKey = 'delayReport:lock';
    protected $delayReport;

    public function __construct(DelayReport $delayReport) {
        $this->delayReport = $delayReport;
    }

    public function lock()
    {
        $lockKey = self::$lockKey . $this->delayReport->id;

        $lock = Redis::set($lockKey, $this->delayReport->id, 'EX', self::$lockTimeout, 'NX');

        if ($lock) {
            return true;
        }

        return false;
    }

    public function releaseLock()
    {
        $lockKey = self::$lockKey . $this->delayReport->id;
        Redis::del($lockKey);
    }
}
