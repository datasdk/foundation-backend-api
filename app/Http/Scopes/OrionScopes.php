<?php

namespace App\Http\Scopes;

use App\Http\Scopes\Categories\Categories;
use App\Http\Scopes\Relations\Relations;
use App\Http\Scopes\Sorting\Sorting;

trait OrionScopes
{
    use Relations;
    use Categories;
    use Sorting;
}
