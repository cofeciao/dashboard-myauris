<?php

namespace backend\models\query;

use yii\elasticsearch\ActiveQuery;

class CustomerElasticQuery extends ActiveQuery
{
    public static function name($name)
    {
        return ['match' => ['name' => $name]];
    }

    public static function address($address)
    {
        return ['match' => ['address' => $address]];
    }

    // filter
    public static function searchAll($search)
    {
        return [
            'multi_match' => [
                'type' => 'best_fields',
                'query' => $search,
                'lenient' => true
            ]
        ];
    }

    public static function registrationDateRange($dateFrom, $dateTo)
    {
        return ['range' => ['registration_date' => [
            'gte' => $dateFrom,
            'lte' => $dateTo,
        ]]];
    }
}
