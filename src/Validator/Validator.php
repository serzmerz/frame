<?php

namespace Serz\Framework\Validator;

use Serz\Framework\Validator\Exceptions\RuleNotFoundException;
use Serz\Framework\Validator\Exceptions\ValidateItemNameException;

/**
 * Class Validator
 * @package Serz\Framework\Validator
 */
class Validator
{
    /**
     * @var
     */
    private $object;
    /**
     * @var array
     */
    private $rulesItem = [];
    /**
     * @var array
     */
    private $arrayErrors = [];

    /**
     * @var array
     */
    private static $knownRulesItem = [
        'required' => 'Serz\Framework\Validator\Rules\RequiredRule',
        'numeric' => 'Serz\Framework\Validator\Rules\NumericRule',
        'length_between' => 'Serz\Framework\Validator\Rules\LengthBeetween'
    ];

    /**
     * Validator constructor.
     *
     * Example
     * $array = [
     * "param" => 14,
     * "title" => "abcd"
     * ];
     * $validator = new Validator($array,[
     * "param" => ["required", "numeric", "length_between:3,10"],
     * "title" => ["numeric"]
     * ]);
     * if(!$validator->validate()){
     * debug($validator->getArrayErrors());
     * }
     * @param $object
     * @param array $rulesItem
     */
    public function __construct($object, array $rulesItem)
    {
        $this->object = $object;
        $this->rulesItem = $rulesItem;
    }

    /**
     * validate request objects param with rules
     * @return bool
     * @throws RuleNotFoundException
     * @throws ValidateItemNameException
     */
    public function validate(): bool
    {
        $result = true;
        foreach ($this->rulesItem as $item_name => $item_value) {
            foreach ($item_value as $item) {
                $itemArray = explode(":", $item);
                $itemRule = array_shift($itemArray);
                $itemParams = array_pop($itemArray);
                $itemParams = empty($itemParams) ? [] : explode(",",$itemParams);
                if (array_key_exists($itemRule, self::$knownRulesItem)) {
                    $validate_class = new self::$knownRulesItem[$itemRule];
                    if (array_key_exists($item_name, $this->object)) {
                            if(!$validate_class->checkItem($item_name,
                                $this->object[$item_name], $itemParams)){
                                $result = false;
                                $this->arrayErrors[$item_name] = $validate_class->getError($item_name,
                                    $this->object[$item_name], $itemParams);
                            }
                    } else throw new ValidateItemNameException("Item $item_name not found
                     in send object!");
                } else throw new RuleNotFoundException("Rule not found!");
            }
        }
        return $result;
    }

    /**
     * @return array
     */
    public function getArrayErrors(): array
    {
        return $this->arrayErrors;
    }

    /**
     * @param array $knownRulesItem
     */
    public static function setKnownRulesItem(string $key, string $class_namespace): bool
    {
        if (class_exists($class_namespace)) {
            self::$knownRulesItem[$key] = $class_namespace;
            return true;
        } else return false;
    }

}