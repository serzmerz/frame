<?php

namespace Serz\Framework\Validator\Rules;


/**
 * Class NumericRule
 * @package Serz\Framework\Validator\Rules
 */
class NumericRule extends AbstractValidationRule
{
    /**
     * @inheritdoc
     */
    public function checkItem(string $item_name, string $item_value, array $params): bool
    {
        return is_numeric($item_value);
    }

    /**
     * @inheritdoc
     */
    public function getError(string $item_name, string $item_value, array $params): string
    {
        return "Field $item_name should be numeric!";
    }
}