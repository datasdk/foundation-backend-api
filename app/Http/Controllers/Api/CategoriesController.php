<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\OrionBaseController;
use Orion\Http\Requests\Request;
use App\Models\Categories;
use App\Http\Resources\BaseResource;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\CategoryRequest;

class CategoriesController extends OrionBaseController
{
    // The model that this controller handles
    protected $model = Categories::class;

    // The request class that validates input
    protected $request = CategoryRequest::class;

    // The resource class to format the response
    protected $resource = CategoryResource::class;

    // The related resources to include by default in the response
    protected $includes = ['children'];

    // The exposed scopes available for filtering and querying
    protected $exposedScopes = [
        'type', 'sortingById', 'descendantsAndSelf', 'orDescendantsAndSelf', 'descendantsOf', 'whereId', 'childrenAndSelf'
    ];

    // The fields that are sortable in the response
    protected $sortableBy = ["parent_id", "name", "sorting"];

    // The fields that can be filtered in the query
    protected $filterableBy = ["id", "parent_id", "type", "categories.id"];

    /**
     * Store a newly created category.
     *
     * @param Request $request The incoming request containing category data.
     * @return \Illuminate\Http\JsonResponse The response containing the created category.
     */
    public function store(Request $request)
    {
        $category = Categories::create($request->validated());

        return response()->json($category, 201);
    }

    /**
     * Update an existing category.
     *
     * @param Request $request The incoming request containing the updated category data.
     * @param int $id The ID of the category to update.
     * @return \Illuminate\Http\JsonResponse The response with the updated category.
     */
    public function update(Request $request, ...$args)
    {

        $id = $args[0];

        $category = Categories::findOrFail($id);
        $category->fill($request->validated())->save();

        return response()->json($category->fresh(), 200);
    }



    public function destroy(Request $request, ...$args)
    {

        try {

            $id = $args[0];

            $category = Categories::findOrFail($id);

            $category->delete();

            return response()->noContent(); // 204 No Content, uden body


        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {


            return response()->json(['message' => 'Category not found'], 404);


        } catch (\Exception $e) {


            return response()->json(['message' => $e->getMessage()], 500);


        }


    }



    /**
     * Add categories to a given model.
     *
     * @param Request $request The incoming request containing the categories data.
     * @param mixed $model The model to which categories should be added.
     * @return \Illuminate\Http\JsonResponse The response with the updated model.
     */
    public function addCategories(Request $request, $model)
    {
        return response()->json(['message' => 'Category assignment service is not available'], 501);
    }

    /**
     * Get the category tree.
     *
     * @param Request $request The incoming request.
     * @param string|null $type The category type to filter by.
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection The formatted category tree.
     */
    public function tree(Request $request, $type = null)
    {
        $query = app($this->model)->query()->withDepth();

        // Filter categories by type if provided
        if ($type) {
            $query->whereIn("type", explode(",", $type));
        }

        // Include additional relationships if requested
        if ($request->include) {
            $query->with($request->include);
        }

        // Order categories by specified field
        if ($request->has("orderBy") && in_array($request->orderBy, ["name", "id"])) {
            $query->orderBy($request->orderBy);
        }

        // Get all categories
        $categories = $query->get();

        // Group categories by name and count duplicates
        $nameCount = $categories->groupBy('name')->map->count();

        // Optionally add a '*' to the name of categories that have duplicates
        if ($request->boolean("debug")) {
            $categories->each(function ($item) use ($nameCount) {
                if ($nameCount[$item->name] > 1) {
                    $item->name .= ' *';
                }
            });
        }

        // Return the categories as a tree or flat structure based on the request
        return BaseResource::collection($request->boolean("flat") ? $categories->toFlatTree() : $categories->toTree());
    }

    /**
     * Get the children of a category by type.
     *
     * @param Request $request The incoming request.
     * @param string $type The category type to filter by.
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection The formatted category children.
     */
    public function children(Request $request, $type)
    {
        // Set sorting order based on request, default is 'sorting'
        $sort = $request->sort ?? 'sorting';

        // Query for categories of a specific type
        $query = app($this->model)->query()
            ->where("type", $type);

        // Include additional relationships if requested
        if ($request->include) {
            $query->with(explode(",", $request->include));
        }

        // Return the children categories as a collection
        return BaseResource::collection($query->get());
    }
}
