<?php

namespace App\Contracts\Abstracts;


use App\Contracts\Interfaces\ActionModelInterface;
use App\Http\Scopes\OrionScopes;
use App\Observers\ActionModelObserver;

use App\Traits\Available\Available;
use App\Traits\Categories\Categories;
use App\Traits\DateFormat\DateFormat;
use App\Traits\Language\Language;
use App\Traits\Roles\Roles;
use App\Traits\Slugs\TranslatableSlug;
use App\Traits\Sorting\Sorting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


abstract class ActionModel extends Model implements ActionModelInterface
{
    use HasFactory;
    use Available, Categories, Language, TranslatableSlug;
    use Sorting, OrionScopes, Roles, DateFormat;

    protected $autofill = true;

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::observe(ActionModelObserver::class);
    }
}
