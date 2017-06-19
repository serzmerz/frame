<?php

namespace Serz\Framework\Validator\Rules;


/**
 * Class RequiredRule
 * @package Serz\Framework\Validator\Rules
 */
class RequiredRule extends AbstractValidationRule
{
    /**
     * @inheritdoc
     */
    public function checkItem(string $item_name, string $item_value, array $params): bool
    {
        return !is_null($item_name) && $item_value !== "";
    }

    /**
     * @inheritdoc
     */
    public function getError(string $item_name, string $item_value, array $params): string
    {
        return "Field $item_name should be required!";
    }
}