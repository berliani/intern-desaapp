<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Table Names
    |--------------------------------------------------------------------------
    |
    | If you want to change the table names, you can change them here.
    |
    */
    'tables' => [
        'provinces' => 'provinces',
        'cities' => 'cities',
        'districts' => 'districts',
        'villages' => 'villages',
    ],

    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
    |
    | If you want to use your own models, you can change them here.
    |
    */
    'models' => [
        'province' => \Laravolt\Indonesia\Models\Province::class,
        'city' => \Laravolt\Indonesia\Models\City::class,
        'district' => \Laravolt\Indonesia\Models\District::class,
        'village' => \Laravolt\Indonesia\Models\Village::class,
    ],

    /*
     |--------------------------------------------------------------------------
     | Observers
     |--------------------------------------------------------------------------
     |
     | If you want to use your own observers, you can change them here.
     |
     */
    'observers' => [
        'province' => \Laravolt\Indonesia\Observers\ProvinceObserver::class,
        'city' => \Laravolt\Indonesia\Observers\CityObserver::class,
        'district' => \Laravolt\Indonesia\Observers\DistrictObserver::class,
        'village' => \Laravolt\Indonesia\Observers\VillageObserver::class,
    ],
];
