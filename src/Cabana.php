<?php
declare(strict_types=1);

namespace Cabana;

use Bridge\Zend\Registry\Registry;
use Bridge\Zend\Registry\RegistryKeyAlreadyExistsException;
use Core\Application\AbstractApplicationContainer;
use Core\Application\Cache\CacheManager;
use Core\Application\Config\ConfigManager;
use Core\Application\Db\DbManager;
use Core\Application\Dependency\DependencyManager;
use Core\Application\Design\DesignManager;
use Core\Application\Directory\DirectoryManager;
use Core\Application\Environment\EnvironmentManager;
use Core\Application\Log\LogManager;
use Zend_Exception;

class Cabana
{
    protected static ?Registry $registry = null;

    /**
     * @throws Zend_Exception
     */
    public static function app(): AbstractApplicationContainer
    {
        return static ::registry('app');
    }

    /**
     * @throws Zend_Exception
     */
    public static function cacheManager(): CacheManager
    {
        return static ::app() -> getCacheManager();
    }

    /**
     * @throws Zend_Exception
     */
    public static function directoryManager(): DirectoryManager
    {
        return static ::app() -> getDirectoryManager();
    }

    /**
     * @throws Zend_Exception
     */
    public static function dependencyManager(): DependencyManager
    {
        return static ::app() -> getDependencyManager();
    }

    /**
     * @throws Zend_Exception
     */
    public static function dbManager(): DbManager
    {
        return static ::app() -> getDbManager();
    }

    /**
     * @throws Zend_Exception
     */
    public static function environmentManager(): EnvironmentManager
    {
        return static ::app() -> getEnvironmentManager();
    }

    public static function configManager(): ConfigManager
    {
        return static ::app() -> getConfigManager();
    }

    public static function designManager(): DesignManager
    {
        return static ::app() -> getDesignManager();
    }

    /**
     * @throws Zend_Exception
     */
    public static function logManager(): LogManager
    {
        return static ::app() -> getLogManager();
    }

    /**
     * @throws Zend_Exception
     */
    public static function getRegistry(): Registry
    {
        if (is_null(static ::$registry)) {
            static ::$registry = Registry ::getInstance();
        }
        return static ::$registry;
    }

    /**
     * @throws Zend_Exception
     */
    public static function registry(string $key): mixed
    {
        if (static ::getRegistry() -> has($key)) {
            return static ::getRegistry() -> fetch($key);
        }
        return null;
    }

    /**
     * @throws Zend_Exception
     * @throws RegistryKeyAlreadyExistsException
     */
    public static function register(string $key, mixed $value): void
    {
        static ::getRegistry() -> add($key, $value);
    }
}