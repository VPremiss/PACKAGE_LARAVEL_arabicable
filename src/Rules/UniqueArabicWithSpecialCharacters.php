<?php

declare(strict_types=1);

namespace VPremiss\Arabicable\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Schema;
use VPremiss\Arabicable\Facades\ArabicFilter;

class UniqueArabicWithSpecialCharacters implements ValidationRule
{
    public function __construct(
        protected string $modelClass,
        protected ?string $propertyName = null,
    ) {}

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $property = $this->propertyName ?? $attribute;
        $model = $this->modelClass;

        if (!class_exists($model)) {
            $fail("The model class '{$model}' for :attribute does not exist.");
            return;
        }

        $searchableField = ar_searchable($property);
        $normalized = ArabicFilter::forSearch($value);

        if (!Schema::hasColumn($model::getTable(), $searchableField)) {
            $fail("The searchable column for :attribute is missing.");
            return;
        }

        if ($model::query()->where($searchableField, $normalized)->exists()) {
            $fail("A match for :attribute was found, therefore it's not unique.");
        }
    }
}
