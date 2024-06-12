<?php

namespace App\Rules;

use App\Models\ProductCategory;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueUseForPrescriptionForUpdate implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ( $value == 1 ){
            $category = ProductCategory::where('use_for_prescription', 1)->first();
            if ($category && $category->id != request()->route('productCategory') ) {
                $fail('There is already a category that can be used for prescription');
            }
        }
    }
}
