<?php

namespace App\Validation;

use App\Exceptions\InvalidRuleException;
use App\Validation\Rules\Rules;

class Validator
{
    /**
     * @var bool
     */
    protected bool $fail = false;
    /**
     * @var array
     */
    protected array $messages = [];
    /**
     * @var array
     */
    protected array $input;
    /**
     * @var array
     */
    protected array $rules;
    /**
     * @var Rules
     */
    protected Rules $validator;

    /**
     * @param array $input
     * @param array $rules
     */
    public function __construct(array $input, array $rules)
    {
        $this->input = $input;
        $this->rules = $rules;
        $this->validator = new Rules();
    }

    /**
     * @param array $input
     * @param array $rules
     * @return Validator
     * @throws InvalidRuleException
     */

    public static function vlaidate(array $input, array $rules)
    {
        $instance = new self($input, $rules);

        foreach ($instance->rules as $key => $rule):
            $fail = $instance->applyRule($rule, $key, $input[$key] ?? null);
            if ($fail) {
                break;
            }

        endforeach;

        return $instance;
    }

    /**
     * @param array $rules
     * @param $key
     * @param $value
     * @return bool
     * @throws InvalidRuleException
     */
    private function applyRule(array $rules, $key, $value): bool
    {
        foreach ($rules as $rule) {
            if (!method_exists($this->validator, $rule)) {
                throw new InvalidRuleException(sprintf('Rule %s is not exists', $rule));
            }
            [$fail, $message] = $this->validator->{$rule}($key, $value);
            if ($fail) {
                $this->fail = true;
                $this->messages[$key] = $message;
                return true;
            }
        }
        return false;
    }

    /**
     * @return bool
     */
    public function fails(): bool
    {
        return $this->fail;
    }

    /**
     * @return array
     */
    public function message(): array
    {
        return $this->messages;
    }
}