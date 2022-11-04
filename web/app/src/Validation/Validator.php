<?php

namespace App\Validation;

use App\Exceptions\InvalidRuleException;
use App\Validation\Rules\Rules;

class Validator
{
    protected bool $fail = false;

    protected array $messages = [];

    protected array $input;

    protected array $rules;


    protected Rules $validator;

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
     */

    public static function vlaidate(array $input, array $rules)
    {
        $instance  = new self($input, $rules);

        foreach ($instance->rules as $key => $rule):
            $fail = $instance->applyRule($rule, $key, $input[$key] ?? null);
            if ($fail) {
                break;
            }

        endforeach;

        return $instance;
    }

    private function applyRule(array $rules, $key, $value) : bool
    {
        foreach ($rules as $rule){
            if (!method_exists($this->validator, $rule)){
                throw new InvalidRuleException(sprintf('Rule %s is not exists', $rule));
            }
            [$fail, $message] =  $this->validator->{$rule}($key, $value);
            if ( $fail){
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
    public function fails() :bool
    {
        return $this->fail;
    }

    /**
     * @return array
     */
    public function message() :array
    {
        return $this->messages;
    }
}