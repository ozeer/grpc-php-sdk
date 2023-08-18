<?php

namespace Pot;

/**
 * 报警通知：用于通过第三方IM发送报警消息通知
 */
class Alarm
{
    // 消息hook地址
    public const POT_ALARM_URL = "";

    public static function send($sUrl, $sAlarmContentType, $sAlarmContent): bool|string
    {
        $sAlarmContent = self::_formatAlarmContent($sAlarmContentType, $sAlarmContent);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $sUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json;charset=utf-8'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $sAlarmContent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // 不用开启curl证书验证
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $mData = curl_exec($ch);
        //$info = curl_getinfo($ch);
        //var_dump($info);
        curl_close($ch);
        
        return $mData;
    }

    private static function _formatAlarmContent($sAlarmContentType, $sAlarmContent): string
    {
        $sAlarmContent = "";
        return $sAlarmContent;
    }
}
