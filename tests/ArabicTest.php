<?php

declare(strict_types=1);

use VPremiss\Arabicable\Database\Seeders\CommonArabicTextSeeder;
use VPremiss\Arabicable\Facades\Arabic;

use function Pest\Laravel\seed;

it('can filter out common Arabic text', function () {
    seed(CommonArabicTextSeeder::class);

    $words = [
        "ومن",
        "سن",
        "في",
        "الإسلام",
        "سنة",
        "سيئة",
        "فعليه",
        "وزرها",
        "ووزر",
        "من",
        "يعمل",
        "بها",
        "من",
        "غير",
        "أن",
        "ينتقص",
        "من",
        "أوزارهم",
        "شيئا",
    ];
    $expectation = [
        "سن",
        "الإسلام سنة سيئة فعليه وزرها ووزر",
        "يعمل",
        "ينتقص",
        "أوزارهم",
    ];

    $filteredWords = Arabic::removeCommons($words);

    expect($filteredWords)->toEqualCanonicalizing($expectation);
});
