<?php


namespace Cabana;


class DataCollection
{
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

    public function toJson():string{
        
    }

    public function items():array{
        return $this->items;
    }
}