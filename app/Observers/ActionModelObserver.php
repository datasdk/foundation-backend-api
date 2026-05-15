<?php

namespace App\Observers;

use App\Contracts\Interfaces\ActionModelInterface;
use App\Events\ActionsModels\ModelCreated;
use App\Events\ActionsModels\ModelCreating;
use App\Events\ActionsModels\ModelUpdated;

class ActionModelObserver
{
    public function creating(ActionModelInterface $model): void
    {
        event(new ModelCreating($model));
    }

    public function created(ActionModelInterface $model): void
    {
        event(new ModelCreated($model));
    }

    public function updated(ActionModelInterface $model): void
    {
        event(new ModelUpdated($model));
    }
}
