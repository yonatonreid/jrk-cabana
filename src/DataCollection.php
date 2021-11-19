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
use Bridge\Laminas\Json\Encoder;

/**
 * Class DataCollection
 *
 * @package \Cabana
 */
class DataCollection
{
    /**
     * DataObject Array
     *
     * @var array
     */
    protected array $items;

    public function __construct()
    {
        $this -> items = [];
    }

    public function addItem(DataObject $dataObject, string $key = null)
    {
        if (Scalar ::isNull($key)) {
            $this -> items[] = $dataObject;
        } else {
            if (Arrays ::arrayKeyExists($key, $this -> items)) {
                throw new \Exception("Key $key already in use.");
            }
            $this -> items[$key] = $dataObject;
        }
    }

    public function getItem(string $key): DataObject
    {
        return $this -> items[$key];
    }

    public function deleteItem(string $key): void
    {
        unset($this -> items[$key]);
    }

    public function toJson(array $keys = [], array $keysToIgnore = [], bool $removeKeys = false): string
    {
        $data = [];
        foreach ($this -> items as $item) {
            /* @var $item DataObject */
            $data[] = $item -> toArray($keys, $keysToIgnore, $removeKeys);
        }
        return Encoder ::encode($data);
    }

    public function items(): array
    {
        return $this -> items;
    }
}