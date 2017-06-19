<?php

namespace Serz\Framework\Validator\Rules;

/**
 * Class AbstractValidationRule
 * @package Serz\Framework\Validator\Rules
 */
abstract class AbstractValidationRule
{
    /**
     * @param string $item_name
     * @param string $item_value
     * @param array $params
     * @return bool
     */
    abstract function checkItem(string $item_name, string $item_value, array $params): bool;

    /**
     * @param string $item_name
     * @param string $item_value
     * @param array $params
     * @return string
     */
    public function getError(string $item_name, string $item_value, array $params): string
    {
        "Item $item_name have validation error";
    }
}