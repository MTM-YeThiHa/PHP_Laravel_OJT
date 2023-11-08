<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;

use function Laravel\Prompts\password;

class MatchOldPassword implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
      $result = Hash::check($value, auth()->user()->password);
        if(!$result) {
          $fail("The :attribute is not match with old password.");
        }
    }

     /**
   * Get the validation error message.
   * 
   * @return string message
   */
  public function message()
  {
    return 'The :attribute is not match with old password.';
  }
}
