<?php

namespace App\Http\Controllers;

use Orion\Http\Controllers\Controller;
use Orion\Http\Requests\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Requests\BasicRequest;

use App\Http\Resources\BaseResource;
use Illuminate\Support\Facades\Event;

use Orion\Concerns\DisableAuthorization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Gate;
//use App\Policies\StandardPolicy;
use Auth;
use User;

class OrionBaseController extends Controller
{
    // Disable authorization for all actions in this controller
    use DisableAuthorization;
    
    // The resource class to be used for the response
    protected $resource = BaseResource::class;

    // Placeholder for a service, can be set in the derived controller
    protected $service = null;

    // The policy to be used for authorization, defaults to StandardPolicy
 //   protected $policy = StandardPolicy::class;

    // Resolve the current authenticated user (using Sanctum guard)
    public function resolveUser()
    {
        return Auth::guard('sanctum')->user();
    }

    // Default pagination limits
    public static $limit = 100; 
    public static $maxlimit = 500;

    // The key name to use when querying resources (defaults to "id")
    protected $keyname = "id";
    
    // Arrays to store includes, scopes, aggregates, filters, etc.
    protected $includes = [];
    protected $exposedScopes = [];
    protected $aggregates = [];
    protected $sortableBy = [];
    protected $filterableBy = [];
    protected $searchableBy = [];
    protected $alwaysIncludes = [];
    protected $services = null;

    // Dynamic arrays for customizing includes, scopes, and aggregates
    public static $dynamicInclude = [];
    public static $dynamicExposedScopes = [];
    public static $dynamicAggregates = [];

    // Return the key name (usually "id" or "slug")
    protected function keyName(): string
    {
        return $this->keyname;
    }

    // Add a scope to the dynamic exposed scopes
    public static function addScope(string $scope)
    {
        return self::$dynamicExposedScopes[] = $scope;
    }

    // Add an aggregate to the dynamic aggregates
    public static function addAggregates(string $Aggregates)
    {
        return self::$dynamicAggregates[] = $Aggregates;
    }

    // Add an include to the dynamic includes (whitelist)
    public static function addInclude($includes)
    {
        if (!is_array($includes)) {
            $includes = [$includes];
        }

        return self::whitelist($includes);
    }

    // Add an aggregate to the dynamic aggregates (alternative method)
    public static function dynamicAggregates(string $aggregates)
    {
        return self::$dynamicAggregates[] = $aggregates;
    }

    // Add includes to the whitelist
    public static function whitelist(array $includes)
    {
        foreach ($includes as $i) {
            self::$dynamicInclude[] = $i;
        }
    }

    // Return all includes (merging with config and dynamic includes)
    public function includes(): array
    {
        return array_merge(config("orion.whitelist.includes"), $this->includes, self::$dynamicInclude);
    }

    // Return all includes that should always be included (merging with config)
    public function alwaysIncludes(): array
    {
        return array_merge(config("orion.whitelist.alwaysIncludes"), $this->alwaysIncludes);
    }

    // Return all aggregates (merging with config and model fillable attributes)
    public function aggregates(): array
    {
        $fillable = $this->getFillableFromModel();
        return array_merge(config("orion.whitelist.aggregates"), $fillable, $this->aggregates);
    }

    // Return all searchable fields (merging with config and model fillable attributes)
    public function searchableBy(): array
    {
        $fillable = $this->getFillableFromModel();
        return array_merge(config("orion.whitelist.searchableBy"), $fillable, $this->searchableBy);
    }

    // Return all sortable fields (merging with config and model fillable attributes)
    public function sortableBy(): array
    {
        $fillable = $this->getFillableFromModel();
        return array_merge(config("orion.whitelist.sortableBy"), $fillable, $this->sortableBy);
    }

    // Return all filterable fields (merging with config and model fillable attributes)
    public function filterableBy(): array
    {
        // Currently returning "*" to allow all fields for filtering
        return ["*"];
        
        $fillable = $this->getFillableFromModel();
        return array_merge(config("orion.whitelist.filterableBy"), $fillable, $this->filterableBy);
    }

    // Return all exposed scopes (merging with config and dynamic scopes)
    public function exposedScopes(): array
    {
        return array_merge(config("orion.whitelist.exposedScopes"), $this->exposedScopes, self::$dynamicExposedScopes);
    }

    // Return the default pagination limit
    public function limit(): int
    {
        return self::$limit;
    }

    // Return the maximum pagination limit
    public function maxLimit(): int
    {
        return self::$maxlimit;
    }

 

    // Get the fillable attributes from the model
    private function getFillableFromModel(): array
    {
        return app($this->model)->getFillable();
    }

    // Show a specific resource by ID, using slug if applicable
    public function show(Request $req, ...$args)
    {
        // Check the language and use slug if the model has a slug column
        
        $id = $args[0];
        $lang = $req->get('lang', config('app.locale'));
        $model = app($this->model);


        if ($this->keyname == "id" && !is_numeric($id)) {
         
            if (Schema::hasColumn($model->getTable(), 'slug')) {

                $this->keyname = $lang ? "slug->" . trim($lang) : "slug";

            }
        }

        return parent::show($req, $args);
    }

    // Run the query to fetch paginated results, with optional filtering and sorting
    protected function runIndexFetchQuery(Request $req, Builder $query, int $paginationLimit): LengthAwarePaginator
    {
  
        
        if (Schema::hasColumn(basename($this->model), 'sorting')) {
            $query->orderBy("sorting");
        }

        return $query->paginate($paginationLimit);

    }


    public function resolvePolicy(){

        $ability = $this->resolveAbility(request()->route()->getActionMethod());

        if (!Gate::allows($ability, $this->model)) {

            abort(403);

        }

    }




    // Get the current request as a string (customized for search behavior)
    public function getRequest(): string
    {
  
        return $this->request;

    }


    public function destroy(Request $req, ...$args)
    {

        $id = $args[0];

        // Dynamisk find modelklasse via $this->model
        $modelClass = $this->model;

        // Find resource eller 404
        $resource = $modelClass::findOrFail($id);

        // Slet resource
        $resource->delete();

        // Returner tomt svar med 204 No Content
        return response()->noContent();
    }


}
