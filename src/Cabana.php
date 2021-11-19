<?php
/**
 * @author jrk <me at aroadahead.com>
 * @copyright 2021 A Road Ahead, LLC
 * @license Apache 2.0
 */
declare(strict_types=1);

/**
 * @package \Cabana
 */

namespace Cabana;

/**
 * Import Statements
 */

use Bridge\Zend\Registry\Registry;
use Bridge\Zend\Registry\RegistryKeyAlreadyExistsException;
use Cabana\Registry\Exception\RegistryKeyNotFoundException;
use Core\Application\AbstractApplicationContainer;
use Core\Application\Cache\Manager\CacheManager;
use Core\Application\Config\Manager\ConfigManager;
use Core\Application\Db\DbManager;
use Core\Application\Dependency\DependencyManager;
use Core\Application\Design\DesignManager;
use Core\Application\Directory\DirectoryManager;
use Core\Application\Environment\EnvironmentManager;
use Core\Application\Log\LogManager;
use Interop\Container\ContainerInterface;
use Zend_Exception;
use function is_null;

/**
 * Class Cabana
 *
 * @package \Cabana
 */
class Cabana
{
    /**
     * App Registry Key
     *
     * @var string
     */
    public const APP_REGISTRY_KEY = 'app';

    /**
     * Registry Instance.
     *
     * @var Registry|null
     */
    protected static ?Registry $registry = null;

    /**
     * Root Path.
     *
     * @var string
     */
    protected static ?string $rootPath = null;

    /**
     * Set Root Path.
     *
     * @param string $rootPath
     * @return void
     */
    public static function setRoot(string $rootPath): void
    {
        static ::$rootPath = $rootPath;
    }

    /**
     * Return Root Path.
     *
     * @param string|null $path
     * @return string|null
     */
    public static function getRoot(?string $path = null): ?string
    {
        if (!is_null($path)) {
            $path = static ::$rootPath . DIRECTORY_SEPARATOR . $path;
            return Fs ::fixDirectorySeparator($path);
        }
        return static ::$rootPath;
    }

    /**
     * Return Application Instance.
     *
     * @return AbstractApplicationContainer
     * @throws Zend_Exception
     */
    public static function app(): AbstractApplicationContainer
    {
        return static ::registry(static::APP_REGISTRY_KEY);
    }

    /**
     * Return Service Manager
     *
     * @return ContainerInterface
     * @throws Zend_Exception
     */
    public static function sm(): ContainerInterface
    {
        return static ::app() -> getContainer();
    }

    /**
     * Return Cache Manager
     *
     * @return CacheManager
     * @throws Zend_Exception
     */
    public static function cacheManager(): CacheManager
    {
        return static ::app() -> getCacheManager();
    }

    /**
     * Return Directory Manager
     *
     * @return DirectoryManager
     * @throws Zend_Exception
     */
    public static function directoryManager(): DirectoryManager
    {
        return static ::app() -> getDirectoryManager();
    }

    /**
     * Return Dependency Manager
     *
     * @return DependencyManager
     * @throws Zend_Exception
     */
    public static function dependencyManager(): DependencyManager
    {
        return static ::app() -> getDependencyManager();
    }

    /**
     * Return DB Manager
     *
     * @return DbManager
     * @throws Zend_Exception
     */
    public static function dbManager(): DbManager
    {
        return static ::app() -> getDbManager();
    }

    /**
     * Return Environment Manager
     *
     * @return EnvironmentManager
     * @throws Zend_Exception
     */
    public static function environmentManager(): EnvironmentManager
    {
        return static ::app() -> getEnvironmentManager();
    }

    /**
     * Return Config Manager
     *
     * @return ConfigManager
     * @throws Zend_Exception
     */
    public static function configManager(): ConfigManager
    {
        return static ::app() -> getConfigManager();
    }

    /**
     * Return Design Manager
     *
     * @return DesignManager
     * @throws Zend_Exception
     */
    public static function designManager(): DesignManager
    {
        return static ::app() -> getDesignManager();
    }

    /**
     * Return Log Manager
     *
     * @throws Zend_Exception
     */
    public static function logManager(): LogManager
    {
        return static ::app() -> getLogManager();
    }

    /**
     * Return Registry
     *
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
     * Has Registry Key?
     *
     * @param string $key
     * @return bool
     * @throws Zend_Exception
     */
    public static function hasRegistryKey(string $key): bool
    {
        return static ::getRegistry() -> has($key);
    }

    /**
     * Return registry data.
     *
     * @throws Zend_Exception
     * @throws RegistryKeyNotFoundException
     */
    public static function registry(string $key): mixed
    {
        if (static ::getRegistry() -> has($key)) {
            return static ::getRegistry() -> fetch($key);
        }
        throw new RegistryKeyNotFoundException("Registry key not found: $key");
    }

    /**
     * Register registry data.
     *
     * @throws Zend_Exception
     * @throws RegistryKeyAlreadyExistsException
     */
    public static function register(string $key, mixed $value): void
    {
        static ::getRegistry() -> add($key, $value);
    }
}