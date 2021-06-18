<?php


namespace Cabana;


use Bridge\Laminas\Json\Encoder;
use Bridge\League\Csv\Writer;
use Bridge\Spatie\ArrayToXml\ArrayToXml;
use JetBrains\PhpStorm\Pure;
use Traversable;

class DataObject implements \ArrayAccess, \IteratorAggregate, \Countable
{
    protected array $data = [];

    public function __construct(array $data = [])
    {
        if (count($data)) {
            $this -> add($data);
        }
    }

    public function data(): array
    {
        return $this -> data;
    }

    public function keys(): array
    {
        return \array_keys($this -> data);
    }

    #[Pure] public function all(array $keysToIgnore = []): array
    {
        return array_diff_key($this -> data(), array_flip($keysToIgnore));
    }

    public function toJson(array $keys = [], array $keysToIgnore = [], bool $removeKeys = false): string
    {
        $data = $this -> toArray($keys, $keysToIgnore, $removeKeys);
        return Encoder ::encode($data);
    }

    public function toXml(array $keys = [], array $keysToIgnore = [], bool $removeKeys = false, bool $useXmlDeclaration = true): string
    {
        $data = $this -> toArray($keys, $keysToIgnore, $removeKeys);
        $arrayToXml = null;
        try {
            $arrayToXml = new ArrayToXml($data);
        } catch (\DOMException $e) {

        }
        if ($useXmlDeclaration) {
            return $arrayToXml -> prettify() -> toXml();
        }
        return $arrayToXml -> dropXmlDeclaration() -> prettify() -> toXml();
    }

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

    public function toCsv(array $keys = [], array $keysToIgnore = [], bool $removeKeys = false): string
    {
        $data = $this -> toArray($keys, $keysToIgnore, $removeKeys);
        $csv = Writer ::createFromString();
        $csv -> insertOne($this -> keys());
        $csv -> insertAll($data);
        return $csv -> getContent();
    }

    public function flush(): void
    {
        $remObj = function ($objectOrArray, $key) {
            $this -> remove($key);
        };
        array_walk_recursive($this -> data, $remObj);
        $this -> data = [];
    }

    public function replace(array $data = []): void
    {
        $this -> data = $data;
    }

    public function add(mixed $data, mixed $value = null)
    {
        if (is_array($data)) {
            $setData = function ($item, $key) {
                $this -> set($key, $item);
            };
            array_walk($data, $setData);
        }
        if (is_scalar($data)) {
            $this -> set($data, $value);
        }
    }

    #[Pure] public function has(string $key): bool
    {
        return $this -> offsetExists($key);
    }

    public function remove(string $key)
    {
        $this -> offsetUnset($key);
    }

    public function get(string $key): mixed
    {
        return $this -> offsetGet($key);
    }

    public function set(string $key, mixed $value): void
    {
        $this -> offsetSet($key, $value);
    }

    /**
     * @return Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this -> data);
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        if (array_key_exists($offset, $this -> data())) {
            return true;
        }
        return false;
    }

    /**
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
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value): void
    {
        $this -> data[$offset] = $value;
    }

    /**
     * @param mixed $offset
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
     * @return int
     */
    public function count(): int
    {
        return \count($this -> data);
    }
}