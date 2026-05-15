<?php

namespace App\Events\ActionsModels;

use Illuminate\Database\Eloquent\Model;

class ModelCreating
{
    public function __construct(public Model $model)
    {
    }
}
