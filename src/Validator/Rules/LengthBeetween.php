<?php

namespace Serz\Framework\Validator\Rules;


/**
 * Class LengthBeetween
 * @package Serz\Framework\Validator\Rules
 */
class LengthBeetween extends AbstractValidationRule
{
    /**
     * @inheritdoc
     */
    public function checkItem(string $item_name, string $item_value, array $params): bool
    {
        $string_length = strlen($item_value);
        return (int)$params[0] <= $string_length && $string_length <= (int)$params[1];
    }

    /**
     * @inheritdoc
     */
    public function getError(string $item_name, string $item_value, array $params): string
    {
        return "Length of field $item_name should be between " . $params[0] . " and " . $params[1];
    }
}