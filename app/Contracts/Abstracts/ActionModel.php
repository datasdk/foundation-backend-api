<?php

namespace App\Contracts\Abstracts;

use App\Contracts\Interfaces\ActionModelInterface;
use App\Http\Scopes\OrionScopes;
use App\Observers\ActionModelObserver;
use DataSDK\Available\Traits\Available;
use DataSDK\Categories\Traits\Categories;
use DataSDK\Tools\Traits\DateFormat;
use DataSDK\Tools\Traits\Language;
use DataSDK\Tools\Traits\Roles;
use DataSDK\Tools\Traits\Slugs\TranslatableSlug;
use DataSDK\Tools\Traits\Sorting;
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
