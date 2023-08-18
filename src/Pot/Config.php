<?php

namespace Pot;

use JsonException;
use RuntimeException;

class Config
{
    private string $sConfigFile;
    public static ?Config $oInstance = null;

    public function __construct()
    {
        $sRootDir = __DIR__;
        $sConfigFileDirPrefix = $sRootDir . DIRECTORY_SEPARATOR . "Config" . DIRECTORY_SEPARATOR;
        $sEnv = Util::getEnv();
        
        if (Util::ENV_PRODUCT === $sEnv) {
            $sConfigFileName = $sConfigFileDirPrefix . "config_prod.json";
        } else if (Util::ENV_GRAY === $sEnv) {
            $sConfigFileName = $sConfigFileDirPrefix . "config_gray.json";
        } else if (Util::ENV_TEST === $sEnv) {
            $sConfigFileName = $sConfigFileDirPrefix . "config_test.json";
        } else if (Util::ENV_DEV === $sEnv) {
            $sConfigFileName = $sConfigFileDirPrefix . "config_dev.json";
        } else {
            $sConfigFileName = $sConfigFileDirPrefix . "config_prod.json";
        }

        $this->sConfigFile = $sConfigFileName;

        if (null === self::$oInstance) {
            self::$oInstance = $this;
        }
    }

    public static function getInstance(): ?Config
    {
        return self::$oInstance;
    }

	/**
	 * @throws JsonException
	 */
	public function parse()
    {
        if (!is_file($this->sConfigFile)) {
            throw new RuntimeException("配置文件不存在");
        }
        $sConfigContent = file_get_contents($this->sConfigFile);
        if (false === $sConfigContent) {
            throw new RuntimeException("读取文件内容失败");
        }
        if ('' === trim($sConfigContent)) {
            throw new RuntimeException("配置文件内容为空");
        }
        $aConfig = json_decode($sConfigContent, true, 512, JSON_THROW_ON_ERROR);
        if (!$aConfig) {
            throw new RuntimeException("配置文件内容为空");
        }

        return $aConfig;
    }
}
