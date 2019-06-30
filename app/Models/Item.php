<?php

namespace App\Models;

class Item extends BaseModel
{
    public $table = "items";

    protected $soft_delete  =   true;

    public $timestamps = true;

    public static function boot()
    {
        parent::boot();

        self::creating(function($model){
            $model->created_by = '1';
        });

        self::updating(function($model){
            $model->updated_by = '1';
        });

    }
}
