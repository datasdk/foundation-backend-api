<?php

namespace App\Http\Controllers;

use Orion\Http\Controllers\RelationController;
use Orion\Http\Requests\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;
use Orion\Concerns\DisableAuthorization;
//use App\Policies\StandardPolicy;
use App\Http\Resources\BaseResource;
use Illuminate\Support\Facades\Auth;

class OrionRelationController extends RelationController
{

    use DisableAuthorization;

    // Resource class used for response formatting
    protected $resource = BaseResource::class;

    // Policy class for authorization
   // protected $policy = StandardPolicy::class;

    // Default pagination limits
    public static $limit = 100;
    public static $maxlimit = 500;

    // Eloquent key name (id or slug)
    protected $keyname = 'id';

    // Allowed includes, always includes, aggregates, etc.
    protected $includes = [];
    protected $alwaysIncludes = [];
    protected $aggregates = [];
    protected $sortableBy = [];
    protected $filterableBy = [];
    protected $searchableBy = [];
    protected $exposedScopes = [];

    // Dynamic lists for includes, scopes, aggregates
    public static $dynamicInclude = [];
    public static $dynamicExposedScopes = [];
    public static $dynamicAggregates = [];

    /**
     * Return the key name (id or slug)
     */
    protected function keyName(): string
    {
        return $this->keyname;
    }

    /**
     * Resolve current authenticated user (sanctum guard)
     */
    public function resolveUser()
    {
        return Auth::guard('sanctum')->user();
    }

    /**
     * Merge and return allowed includes
     */
    public function includes(): array
    {
        return array_merge(config("orion.whitelist.includes", []), $this->includes, self::$dynamicInclude);
    }

    /**
     * Merge and return always included relations
     */
    public function alwaysIncludes(): array
    {
        return array_merge(config("orion.whitelist.alwaysIncludes", []), $this->alwaysIncludes);
    }

    /**
     * Merge and return aggregates
     */
    public function aggregates(): array
    {
        return array_merge(config("orion.whitelist.aggregates", []), $this->aggregates, $this->getFillableFromModel());
    }

    /**
     * Merge and return searchable fields
     */
    public function searchableBy(): array
    {
        return array_merge(config("orion.whitelist.searchableBy", []), $this->searchableBy, $this->getFillableFromModel());
    }

    /**
     * Merge and return sortable fields
     */
    public function sortableBy(): array
    {
        return array_merge(config("orion.whitelist.sortableBy", []), $this->sortableBy, $this->getFillableFromModel());
    }

    /**
     * Return filterable fields (default allow all)
     */
    public function filterableBy(): array
    {
        return ["*"];
    }

    /**
     * Merge and return exposed scopes
     */
    public function exposedScopes(): array
    {
        return array_merge(config("orion.whitelist.exposedScopes", []), $this->exposedScopes, self::$dynamicExposedScopes);
    }

    /**
     * Pagination limit
     */
    public function limit(): int
    {
        return self::$limit;
    }

    /**
     * Maximum pagination limit
     */
    public function maxLimit(): int
    {
        return self::$maxlimit;
    }

    
    /**
     * Hent en instans af parent model (relationens ejer)
     */
    protected function getParentModelInstance(): ?\Illuminate\Database\Eloquent\Model
    {
        if (property_exists($this, 'parentModel') && $this->parentModel) {
            return app($this->parentModel);
        }
        if (property_exists($this, 'model') && $this->model) {
            return app($this->model);
        }
        return null;
    }

    /**
     * Hent fillable felter fra model
     */
    protected function getFillableFromModel(): array
    {
        $modelInstance = $this->getParentModelInstance();
        return $modelInstance ? $modelInstance->getFillable() : [];
    }

  
}
