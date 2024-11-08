<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ProfileNameLength implements ValidationRule
{
    /**
     * Indicates whether the rule should be implicit.
     *
     * @var bool
     */
    public $implicit = true;

    protected $max;

    public function __construct(int $max)
    {
        $this->max = $max;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if($value){
            $fileName = strlen($value->getClientOriginalName());
            if($fileName > $this->max){
                $fail('O nome do arquivo da imagem de perfil deve ter menos que '.$this->max.' caracteres');
            }
        }
    }
}
