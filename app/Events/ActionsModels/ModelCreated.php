<?php

namespace App\Events\ActionsModels;

use Illuminate\Database\Eloquent\Model;

class ModelCreated
{
    public function __construct(public Model $model)
    {
    }
}
