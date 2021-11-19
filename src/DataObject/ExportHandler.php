<?php
/**
 * @author jrk <me at aroadahead.com>
 * @copyright 2021 A Road Ahead, LLC
 * @license Apache 2.0
 */
declare(strict_types=1);

/**
 * @package \Cabana\DataObject
 */
namespace Cabana\DataObject;

/**
 * Import Statements
 */
use Bridge\League\Csv\Writer;
use Bridge\Spatie\ArrayToXml\ArrayToXml;
use DOMException;
use League\Csv\CannotInsertRecord;
use League\Csv\Exception;

/**
 * Class ExportHandler
 *
 * @package \Cabana\DataObject
 */
class ExportHandler
{
    /**
     * @param array $data
     * @param array $keys
     * @return string
     * @throws CannotInsertRecord
     * @throws Exception
     */
    public static function toCsv(array $data = [], array $keys = []): string
    {
        $csv = Writer ::createFromString();
        $csv -> insertOne($keys);
        $csv -> insertAll($data);
        return $csv -> toString();
    }

    /**
     * @param array $data
     * @param bool $useXmlDeclaration
     * @return string
     * @throws DOMException
     */
    public static function toXml(array $data = [], bool $useXmlDeclaration = true): string
    {
        $arrayToXml = new ArrayToXml($data);
        if ($useXmlDeclaration) {
            return $arrayToXml -> prettify() -> toXml();
        }
        return $arrayToXml -> dropXmlDeclaration() -> prettify() -> toXml();
    }

    /**
     * @param array $data
     * @return string
     */
    public static function toJson(array $data = []): string
    {
        return Encoder ::encode($data);
    }
}