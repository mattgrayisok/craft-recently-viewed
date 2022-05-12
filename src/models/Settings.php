<?php

namespace mattgrayisok\recentlyviewed\models;

use craft\base\Model;

class Settings extends Model
{
    public $autoTrack = true;

    public function rules(): array
    {
        return [
            [['autoTrack'], 'boolean'],
        ];
    }
}
