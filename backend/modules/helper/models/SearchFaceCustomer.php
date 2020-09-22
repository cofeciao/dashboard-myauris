<?php

namespace backend\modules\helper\models;

class SearchFaceCustomer extends \backend\models\CustomerModel
{
    public function rules()
    {
        return [
            [['name'], 'required', 'message' => ' '],
        ];
    }
}
