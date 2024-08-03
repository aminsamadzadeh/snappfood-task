<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;

class DelayReportLockService
{
    protected static $lockTimeout = 5;
    protected static $lockKey = 'delayReport:lock';

    public static function lock($delay_report_id)
    {
        $lockKey = self::$lockKey . $delay_report_id;

        $lock = Redis::set($lockKey, $delay_report_id, 'EX', self::$lockTimeout, 'NX');

        if ($lock) {
            return true;
        }

        return false;
    }

    public static function releaseLock($delay_report_id)
    {
        $lockKey = self::$lockKey . $delay_report_id;
        Redis::del($lockKey);
    }
}
