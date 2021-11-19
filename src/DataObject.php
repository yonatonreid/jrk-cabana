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

use ArrayAccess;
use ArrayIterator;
use Bridge\Laminas\Filter\Word\CamelCaseToUnderscore;
use Bridge\Laminas\Json\Encoder;
use Bridge\League\Csv\Writer;
use Bridge\Spatie\ArrayToXml\ArrayToXml;
use Countable;
use DOMException;
use IteratorAggregate;
use JetBrains\PhpStorm\Pure;
use League\Csv\CannotInsertRecord;
use League\Csv\Exception;
use Traversable;
use function array_diff_key;
use function array_flip;
use function array_keys;
use function array_values;
use function array_walk;
use function array_walk_recursive;
use function count;
use function is_array;
use function is_null;
use function is_object;
use function is_scalar;
use function spl_object_id;
use function substr;

/**
 * Class DataObject
 *
 * @implements ArrayAccess
 * @implements IteratorAggregate
 * @implements Countable
 */
class DataObject implements ArrayAccess, IteratorAggregate, Countable
{
    /**
     * Data Cache
     *
     * @var array
     */
    protected array $data = [];

    /**
     * Camel case to underscore
     *
     * @var CamelCaseToUnderscore|null
     */
    protected ?CamelCaseToUnderscore $camelCaseToUnderscore;

    /**
     * Underscore cache
     *
     * @var array
     */
    protected array $underscoreCache = [];

    /**
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this -> camelCaseToUnderscore = new CamelCaseToUnderscore();
        if (count($data)) {
            $this -> add($data);
        }
    }

    /**
     * Return Object ID
     *
     * @return string
     */
    public function getObjectId(): string
    {
        return spl_object_id($this);
    }

    /**
     * Magic setter
     *
     * @param $key
     * @param $value
     * @return void
     */
    public function __set($key, $value): void
    {
        $this -> set($key, $value);
    }

    /**
     * Magic getter
     *
     * @param $key
     * @return mixed
     */
    public function __get($key): mixed
    {
        return $this -> get($key);
    }

    /**
     * Magic isset
     *
     * @param string $name
     * @return bool
     */
    #[Pure] public function __isset(string $name): bool
    {
        return $this -> has($name);
    }

    /**
     * Return keys
     *
     * @return array
     */
    public function keys(): array
    {
        return array_keys($this -> data);
    }

    /**
     * Return all data
     *
     * @param array $keysToIgnore
     * @return array
     */
    #[Pure] public function all(array $keysToIgnore = []): array
    {
        return array_diff_key($this -> data, array_flip($keysToIgnore));
    }

    /**
     * To Json
     *
     * @param array $keys
     * @param array $keysToIgnore
     * @param bool $removeKeys
     * @return string
     */
    public function toJson(array $keys = [], array $keysToIgnore = [], bool $removeKeys = false): string
    {
        $data = $this -> toArray($keys, $keysToIgnore, $removeKeys);
        return Encoder ::encode($data);
    }

    /**
     * To XML
     *
     * @return string
     * @throws DOMException
     */
    public function toXml(array $keys = [], array $keysToIgnore = [], bool $removeKeys = false, bool $useXmlDeclaration = true): string
    {
        $data = $this -> toArray($keys, $keysToIgnore, $removeKeys);
        $arrayToXml = new ArrayToXml($data);
        if ($useXmlDeclaration) {
            return $arrayToXml -> prettify() -> toXml();
        }
        return $arrayToXml -> dropXmlDeclaration() -> prettify() -> toXml();
    }

    /**
     * To Array
     *
     * @param array $keys
     * @param array $keysToIgnore
     * @param bool $removeKeys
     * @return array
     */
    public function toArray(array $keys = [], array $keysToIgnore = [], bool $removeKeys = false): array
    {
        if (empty($keys)) {
            return $this -> all($keysToIgnore);
        }
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $this -> get($key);
        }
        if ($removeKeys) {
            return array_values($result);
        }
        return $result;
    }

    /**
     * As array values
     *
     * @param string $key
     * @param array $keysToIgnore
     * @return array
     */
    public function asArrayValues(string $key, array $keysToIgnore = []): array
    {
        $data = $this -> toArray([$key], $keysToIgnore, true);
        return $data[0];
    }

    /**
     * To CSV
     *
     * @param array $keys
     * @param array $keysToIgnore
     * @param bool $removeKeys
     * @return string
     * @throws CannotInsertRecord
     * @throws Exception
     */
    public function toCsv(array $keys = [], array $keysToIgnore = [], bool $removeKeys = false): string
    {
        $data = $this -> toArray($keys, $keysToIgnore, $removeKeys);
        $csv = Writer ::createFromString();
        $csv -> insertOne($this -> keys());
        $csv -> insertAll($data);
        return $csv -> toString();
    }

    /**
     * Flush data
     *
     * @return void
     */
    public function flush(): void
    {
        $remObj = function ($objectOrArray, $key) {
            $this -> remove($key);
        };
        array_walk_recursive($this -> data, $remObj);
        $this -> data = [];
    }

    /**
     * Exchange data
     *
     * @param array $data
     * @param bool $strict
     */
    public function exchangeArray(array $data, bool $strict = false): void
    {
        $this -> flush();
        $setData = function ($item, $key) use ($strict) {
            if ($strict) {
                if (!is_null($item)) {
                    $this -> set($key, $item);
                }
            } else {
                $this -> set($key, $item);
            }
        };
        array_walk($data, $setData);
    }

    /**
     * Add data
     *
     * @param mixed $data
     * @param mixed|null $value
     * @param bool $strict
     */
    public function add(mixed $data, mixed $value = null, bool $strict = false): void
    {
        if (is_array($data)) {
            $setData = function ($item, $key) use ($strict) {
                if ($strict) {
                    if (!is_null($item)) {
                        $this -> set($key, $item);
                    }
                } else {
                    $this -> set($key, $item);
                }
            };
            array_walk($data, $setData);
        }
        if (is_scalar($data)) {
            $this -> set($data, $value);
        }
    }

    /**
     * Has Data?
     *
     * @param string $key
     * @return bool
     */
    #[Pure] public function has(string $key): bool
    {
        return $this -> offsetExists($key);
    }

    /**
     * Remove data
     *
     * @param string $key
     */
    public function remove(string $key): void
    {
        $this -> offsetUnset($key);
    }

    /**
     * Return data
     *
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed
    {
        return $this -> offsetGet($key);
    }

    /**
     * Set data
     *
     * @param string $key
     * @param mixed $value
     */
    public function set(string $key, mixed $value): void
    {
        $this -> offsetSet($key, $value);
    }

    /**
     * Return iterator
     *
     * @return Traversable
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this -> data);
    }

    /**
     * Offset exists
     *
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        if (\array_key_exists($offset, $this -> data)) {
            return true;
        }
        return false;
    }

    /**
     * Offset get
     *
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        if ($this -> offsetExists($offset)) {
            return $this -> data[$offset];
        }
        return null;
    }

    /**
     * Offset set
     *
     * @param mixed $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        $this -> data[$offset] = $value;
    }

    /**
     * Offset unset
     *
     * @param mixed $offset
     * @return void
     */
    public function offsetUnset($offset): void
    {
        if ($this -> offsetExists($offset)) {
            $data = $this -> offsetGet($offset);
            if (is_object($data)) {
                $data -> __destruct();
            }
            unset($this -> data[$offset]);
        }
    }

    /**
     * Return count
     *
     * @return int
     */
    public function count(): int
    {
        return count($this -> data);
    }

    /**
     * Underscore
     *
     * @param $name
     * @return mixed|string
     */
    public function underscore($name)
    {
        if (isset($this -> underscoreCache[$name])) {
            return $this -> underscoreCache[$name];
        }
        $result = strtolower($this -> camelCaseToUnderscore -> filter($name));
        $this -> underscoreCache[$name] = $result;
        return $result;
    }

    /**
     * Magic call
     *
     * @param string $name
     * @param array $arguments
     * @return bool|mixed|void
     */
    public function __call(string $name, array $arguments)
    {
        $method = trim($name);
        switch (substr($method, 0, 3)) {
            case 'get':
                $key = $this -> underscore(substr($method, 3));
                return $this -> get($key);
                break;
            case 'set':
                $key = $this -> underscore(substr($method, 3));
                $value = $arguments[0] ?? null;
                $this -> set($key, $value);
                break;
            case 'uns':
                $key = $this -> underscore(substr($method, 5));
                $this -> remove($key);
                break;
            case 'rem':
                $key = $this -> underscore(substr($method, 6));
                $this -> remove($key);
                break;
            case 'has':
                $key = $this -> underscore(substr($method, 3));
                return $this -> has($key);
                break;
            default:
                throw new InvalidArgumentException("$method is not available in DataObject.");
        }
    }
}