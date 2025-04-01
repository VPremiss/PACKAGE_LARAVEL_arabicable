<?php

declare(strict_types=1);

namespace VPremiss\Arabicable\Traits;

use VPremiss\Arabicable\Observers\ArabicableModelObserver;

trait Arabicable
{
    public static function bootArabicable()
    {
        static::observe(new ArabicableModelObserver);
    }

    public function getSearchableTranslations(): array
    {
        $translations = [];

        if (! isset($this->translatable)) {
            throw new \VPremiss\Arabicable\Support\Exceptions\ArabicableException(
                'Please implement Spatie Laravel Tanslatable\'s property $translatable on the model before using getSearchableTranslations helper.',
            );
        }

        foreach ($this->translatable as $property) {
            foreach ($this->$property as $locale => $value) {
                $searchableLabel = ar_searchable($property);
                $propertyName = $locale === 'ar' ? $searchableLabel : $property;

                $propertyValue = $locale === 'ar' ? $this->$propertyName : $value;

                $translations["{$searchableLabel}_{$locale}"] = $propertyValue;
            }
        }

        return $translations;
    }
}
