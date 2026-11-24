<?php

declare(strict_types=1);

return [
    // Скидка в процентах (диапазон случайных значений)
    'min_percent' => (int) env('DISCOUNT_MIN_PERCENT', 1),
    'max_percent' => (int) env('DISCOUNT_MAX_PERCENT', 50),

    // Часы, в течение которых повторное получение возвращает прежнюю скидку
    'reissue_lock_hours' => (int) env('DISCOUNT_REISSUE_LOCK_HOURS', 1),

    // Часы, в течение которых код скидки действителен
    'valid_hours' => (int) env('DISCOUNT_VALID_HOURS', 3),
];