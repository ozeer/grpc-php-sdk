<?php

namespace Pot;

use Exception;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/**
 * Class Log
 * @method static statsign_info($title, $data = [])
 * @method static position_info($title, $data = [])
 * @method static error(string $message, array $data)
 * @method static info(string $message, array $data)
 * @method static debug(string $message, array $data)
 * @package Pot
 */
class Log
{
    public const DEFAULT_LOG_PATH_PREFIX = "/data/logs_bak/";

    public const DEFAULT_LOG_DIRECTORY = "pot";

    public const DEFAULT_PROJECT_DIRECTORY_PREFIX = 'g_';

    public const DEFAULT_LOG_SEPARATOR = '#';

    private static array $singleLog = [];

    private static array $singleStreamHandler = [];

    private static array $singleStreamHandlerPath = [];

    private static array $singleFormatter = [];

    protected static array $levels = [
        'debug'     => Logger::DEBUG,
        'info'      => Logger::INFO,
        'notice'    => Logger::NOTICE,
        'warning'   => Logger::WARNING,
        'error'     => Logger::ERROR,
        'critical'  => Logger::CRITICAL,
        'alert'     => Logger::ALERT,
        'emergency' => Logger::EMERGENCY
    ];

    private static array $logSetting = [
        'logPath'   => "",
        'logSlice'  => 'daily',
        'expireDay' => 30,
        'logList'   => [
            'general'      => [
                "dir"      => "general/",
                "logLevel" => Logger::DEBUG
            ],
            'db'           => [
                "dir"      => "db/",
                "logLevel" => Logger::DEBUG
            ],
            'sys'          => [
                "dir"      => "sys/",
                "logLevel" => Logger::DEBUG
            ],
            'redis'        => [
                "dir"      => "redis/",
                "logLevel" => Logger::DEBUG
            ],
            'curl'         => [
                "dir"      => "curl/",
                "logLevel" => Logger::DEBUG
            ],
            'queue'        => [
                "dir"      => "queue/",
                "logLevel" => Logger::DEBUG
            ],
            'api'          => [
                "dir"      => "api/",
                "logLevel" => Logger::DEBUG
            ],
            'inner'        => [
                "dir"      => "inner/",
                "logLevel" => Logger::DEBUG
            ],
            'amqp'         => [
                "dir"      => "amqp/",
                "logLevel" => Logger::DEBUG
            ],
            'oss'          => [
                "dir"      => "oss/",
                "logLevel" => Logger::DEBUG
            ],
            'order'        => [
                "dir"      => "order/",
                "logLevel" => Logger::DEBUG
            ],
            'goods'        => [
                "dir"      => "goods/",
                "logLevel" => Logger::DEBUG
            ],
            'pay'          => [
                "dir"      => "pay/",
                "logLevel" => Logger::DEBUG
            ],
            'callback'     => [
                "dir"      => "callback/",
                "logLevel" => Logger::DEBUG
            ],
            'refund'       => [
                "dir"      => "refund/",
                "logLevel" => Logger::DEBUG
            ],
            'cart'         => [
                "dir"      => "cart/",
                "logLevel" => Logger::DEBUG
            ],
            'point'        => [
                "dir"      => "point/",
                "logLevel" => Logger::DEBUG
            ],
            'event'        => [
                "dir"      => "event/",
                "logLevel" => Logger::DEBUG
            ],
            'kafka'        => [
                "dir"      => "kafka/",
                "logLevel" => Logger::DEBUG
            ],
            'erp'          => [
                "dir"      => "erp/",
                "logLevel" => Logger::DEBUG
            ],
            'position'     => [
                "dir"      => "position/",
                "logLevel" => Logger::DEBUG
            ],
            'statsign'     => [
                "dir"      => "statsign/",
            ],
            'video'     => [
                "dir"      => "video/",
                "logLevel" => Logger::DEBUG
            ],
            'essearch'     => [
                "dir"      => "essearch/",
                "logLevel" => Logger::DEBUG
            ],
            'im' => [
                "dir"      => "im/",
                "logLevel" => Logger::DEBUG
            ],
            'push' => [
                "dir"      => "push/",
                "logLevel" => Logger::DEBUG
            ],
        ]
    ];

    private static function getSingleLog($logPath)
    {
        if (!isset(self::$singleLog[$logPath])) {
            self::$singleLog[$logPath] = new Logger($logPath);
        }
        return self::$singleLog[$logPath];
    }

    private static function getSlice(): string
    {
        return match (self::$logSetting['logSlice']) {
            "hourly" => "-" . date("YmdH") . ".log",
            "daily" => "-" . date("Ymd") . ".log",
        };
    }

    private static function setLogPath($backtrace = null): void
    {
        $sLogDirPrefix = self::DEFAULT_LOG_PATH_PREFIX;
        // 一般服务器上都有/data/logs_bak文件夹，如果没有表示用户是在本地环境，将日志输出到/tmp目录下
        // 无论是dev环境，还是pot-test环境亦或者是gray和production环境，都是有/data/logs_bak目录
        if (!is_dir(self::DEFAULT_LOG_PATH_PREFIX)) {
            $sLogDirPrefix = "/tmp/";
            $_SERVER['APP_NAME'] = self::DEFAULT_LOG_DIRECTORY;
        }

        if (isset($_SERVER['APP_NAME'])) {
            self::$logSetting['logPath'] = $sLogDirPrefix . $_SERVER['APP_NAME'] . DIRECTORY_SEPARATOR;
        } else {
            $backtraceDir = ltrim(str_replace(dirname(__DIR__, 2), '', $backtrace[0]['file']), '/');
            $dirList = explode("/", $backtraceDir);
            $logPath = str_replace(self::DEFAULT_PROJECT_DIRECTORY_PREFIX, '', array_shift($dirList));
            self::$logSetting['logPath'] = $sLogDirPrefix . $logPath . DIRECTORY_SEPARATOR;
        }
    }

    /**
     * @throws Exception
     */
    public static function setExcelPath($oldPath, $fileName)
    {
        if (isset($_SERVER['APP_NAME']) && $_SERVER['APP_NAME'] === 'sapi') {
            $path = self::DEFAULT_LOG_PATH_PREFIX . $_SERVER['APP_NAME'] . '/excel';
        } else {
            $path = self::DEFAULT_PROJECT_DIRECTORY_PREFIX . 'excel';
        }
        if (!file_exists($path) && !mkdir($path, 0755, true) && !is_dir($path)) {
            throw new \RuntimeException('文件夹创建失败');
        }
        $ret = move_uploaded_file($oldPath, $path . '/' . $fileName);
        if ($ret) {
            return $path . '/' . $fileName;
        }
    }

    public static function __callstatic($method, $args)
    {
        $backtrace = debug_backtrace(0, 1);
        self::setLogPath($backtrace);
        $methodList = explode("_", strtolower($method));
        if (count($methodList) > 1) {
            if (count($methodList) === 2) {
                self::addSystemLog($methodList[0], $methodList[1], $args);
            } elseif (count($methodList) > 2) {
                self::addSystemLog($methodList[0], $methodList[2], $args, $methodList[1]);
            }
        } else {
            [$logPath, $line] = self::getBackTrace($backtrace);
            self::addGeneralLog($logPath, $line, $method, $args);
        }
    }

    private static function getBackTrace($backtrace): array
    {
        $callFile = ltrim(str_replace(array(
            dirname(__DIR__, 2),
            '.php',
            '/'
        ), array(
            "",
            "",
            "-"
        ), $backtrace[0]['file']), "-");

        if (empty($callFile)) {
            $callFile = 'general';
        }
        return array(
            $callFile,
            $backtrace[0]['line']
        );
    }

    private static function addSystemLog($logTag, $logType, $logData, $logPrefix = null): void
    {
        $logPath = $logPrefix ? $logTag . "-" . $logPrefix : $logTag;
        if (!isset(self::$singleFormatter[$logPath])) {
            $output = "%datetime%#%level_name%#%message%#%context%\n";
            self::$singleFormatter[$logPath] = new LineFormatter($output);
        }
        self::getSingleLog($logPath);
        $realPath = self::$logSetting['logPath'] . self::$logSetting['logList'][$logTag]['dir'] . $logPath . self::getSlice();
        if (!isset(self::$singleStreamHandlerPath[$logPath]) || self::$singleStreamHandlerPath[$logPath] !== $realPath || !file_exists($realPath)) {
            self::$singleStreamHandler[$logPath] = new StreamHandler(
                $realPath,
                self::$logSetting['logList'][$logTag]['logLevel'],
                true,
                0666
            );
            self::$singleStreamHandler[$logPath]->setFormatter(self::$singleFormatter[$logPath]);
            //释放文件句柄
            if (!empty(self::$singleLog[$logPath]->getHandlers())) {
                self::$singleLog[$logPath]->popHandler()->close();
            }
            self::$singleLog[$logPath]->pushHandler(self::$singleStreamHandler[$logPath]);
            self::$singleStreamHandlerPath[$logPath] = $realPath;
        }
        self::writeLog($logPath, $logType, $logData);
    }

    private static function addGeneralLog($logPath, $line, $logType, $logData): void
    {
        $flag = $logPath . "-" . $line;
        if (!isset(self::$singleFormatter[$flag])) {
            $output = "%datetime%#line:{$line}#%level_name%#%message%#%context%\n";
            self::$singleFormatter[$logPath . "-" . $line] = new LineFormatter($output);
            if (isset(self::$singleStreamHandler[$logPath])) {
                self::$singleStreamHandler[$logPath]->setFormatter(self::$singleFormatter[$logPath . "-" . $line]);
            }
        }
        self::getSingleLog($logPath);
        $realPath = self::$logSetting['logPath'] . self::$logSetting['logList']['general']['dir'] . $logPath . self::getSlice();
        //var_dump($logType);
        //var_dump(self::$logSetting['logPath']);
        //var_dump(self::$logSetting['logList']['general']['dir']);
        //var_dump($logPath);
        //var_dump(self::getSlice());
        //var_dump($realPath);
        if (!isset(self::$singleStreamHandlerPath[$logPath]) || self::$singleStreamHandlerPath[$logPath] !== $realPath || !file_exists($realPath)) {
            self::$singleStreamHandler[$logPath] = new StreamHandler(
                $realPath,
                self::$logSetting['logList']['general']['logLevel'],
                true,
                0666
            );
            self::$singleStreamHandler[$logPath]->setFormatter(self::$singleFormatter[$logPath . "-" . $line]);
            // 释放文件句柄
            if (!empty(self::$singleLog[$logPath]->getHandlers())) {
                self::$singleLog[$logPath]->popHandler()->close();
            }
            self::$singleLog[$logPath]->pushHandler(self::$singleStreamHandler[$logPath]);
            self::$singleStreamHandlerPath[$logPath] = $realPath;
        }
        self::writeLog($logPath, $logType, $logData);
    }

    private static function writeLog($logPath, $logType, $logData): void
    {
        $log = self::getSingleLog($logPath);
        //$message = Ip::getClientIp() . self::DEFAULT_LOG_SEPARATOR . array_shift($logData);
        $message = self::DEFAULT_LOG_SEPARATOR . array_shift($logData);
        if (PHP_SAPI === "fpm-fcgi" && isset($_SERVER['HTTP_USER_AGENT'])) {
            array_push($logData, $_SERVER['REQUEST_URI'], $_SERVER['HTTP_USER_AGENT']);
        }
        //$addFunction = 'add' . ucfirst($logType);
        //$log->$addFunction($message, $logData);
        $log->$logType($message, $logData);
    }
}
