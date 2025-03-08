<?php

declare(strict_types=1);

namespace Common\Helper;

use Olobase\Mezzio\Exception\JsonDecodeException;

class JsonHelper
{
    /**
     * Debuggable json decode
     * 
     * @param  string $data data
     * @return mixed
     * @throws JsonDecodeException
     */
    public static function jsonDecode(string $data)
    {
        if (empty($data)) {
            return [];
        }
        $decodedValue = json_decode($data, true);
        $lastError = json_last_error();
        $jsonErrors = [
            JSON_ERROR_DEPTH => 'Maximum heap size exceeded',
            JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
            JSON_ERROR_SYNTAX => 'Syntax error',
        ];

        if ($lastError !== JSON_ERROR_NONE) {
            $errorMessage = $jsonErrors[$lastError] ?? 'Unknown error';
            throw new JsonDecodeException(
                sprintf('%s. Related data: %s', $errorMessage, print_r($data, true))
            );
        }

        return $decodedValue;
    }

    /**
     * Encode json 
     * 
     * @param  mixed $value val
     * @return string
     */
    public static function jsonEncode($value): string
    {
        // We need to use JSON_UNESCAPED_SLASHES because javascript native 
        // json stringify function use this feature by default
        // 
        // https://stackoverflow.com/questions/10314715/why-is-json-encode-adding-backslashes
        // 
        return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Paginator json decode
     *
     * @param  array  $items   data
     * @return array
     */
    public static function paginatorJsonDecode($items): array
    {
        if (empty($items)) {
            return [];
        }

        $jsonErrors = [
            JSON_ERROR_DEPTH => 'Maximum heap size exceeded',
            JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
            JSON_ERROR_SYNTAX => 'Syntax error',
        ];

        $newData = [];
        foreach ($items as $key => $row) {
            foreach ($row as $field => $value) {
                if (is_string($value) && (strpos($value, '[{"') === 0 || strpos($value, '{"') === 0)) {  // if json encoded value
                    $decodedValue = json_decode($value, true);
                    $lastError = json_last_error();
                    $newData[$key][$field] = ($lastError === JSON_ERROR_NONE) ? $decodedValue : $jsonErrors[$lastError] . ': ' . $value;
                } else {
                    $newData[$key][$field] = $value;
                }
            }
        }

        return $newData;
    }
}
