<?php

namespace Pot;

class Util
{
    public const ENV_PRODUCT = 'production';
    public const ENV_GRAY    = 'gray';
    public const ENV_TEST    = 'pot-test';
    public const ENV_DEV     = 'pot-dev';
    public const ENV_LIST    = [
        self::ENV_PRODUCT,
        self::ENV_GRAY,
        self::ENV_TEST,
        self::ENV_DEV,
    ];

    /*
     * @desc : 获取当前运行环境，在按照正常逻辑获取失败的情况下，走兜底逻辑返回线上正式环境
     * */
    public static function getEnv(): array|string
    {
        $sEnv = getenv('CLUSTER_NAME');
        if (false === $sEnv) {
            $sEnv = getenv('ECS_ENV_NAME');
        }

        if (false === $sEnv || !in_array($sEnv, self::ENV_LIST, true)) {
            // 一期兜底策略，没查到环境的情况下先返回production并记录异常日志
            // 上线后观察一段时间，如果没有异常日志的话，直接抛出异常
            //Log::debug('运行环境获取异常', [$sEnv, debug_backtrace(5), getenv()]);
            return self::ENV_PRODUCT;
        }

        return $sEnv;
    }
}
