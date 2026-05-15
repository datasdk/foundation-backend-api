<?php

namespace App\Events\ActionsModels;

use Illuminate\Database\Eloquent\Model;

class ModelUpdated
{
    public function __construct(public Model $model)
    {
    }
}
