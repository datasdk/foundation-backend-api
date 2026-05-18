<?php

namespace App\Traits\Sorting;

use Illuminate\Support\Facades\DB;

trait Sorting
{
    /**
     * Get the class name of the model.
     *
     * @return string The fully qualified class name of the model.
     */
    public function getModel()
    {
        return get_class($this);
    }

    /**
     * Boot the sorting trait.
     *
     * This method is responsible for adding the global scope for automatic sorting,
     * as well as handling the creation, updating, and deletion of models with the 'sorting' field.
     *
     * @return void
     */
    protected static function bootSorting()
    {
        // Adds an automatic ORDER BY sorting on all queries for models using this trait
        static::addGlobalScope('sorting', function ($query) {
            $query->orderBy('sorting');
        });

        // Handles the creation of new models and assigns them an automatic sorting value
        static::created(function ($model) {
            $query = $model::query();

            // If the model has a 'sortable' property with a 'group_column', group by that column
            if (isset($model->sortable) && isset($model->sortable['group_column'])) {
                $groupColumn = $model->sortable['group_column'];
                $query->where($groupColumn, $model[$groupColumn]);
            }

            // Get the maximum sorting value in the group and increment it
            $maxSorting = $query->max('sorting');
            $newSorting = $maxSorting ? $maxSorting + 1 : 1;

            // Avoid recursive calls by using updateQuietly()
            $model->updateQuietly(['sorting' => $newSorting]);
        });

        // If sorting is null on update, set it to max sorting + 1
        static::updating(function ($model) {
            if (is_null($model->sorting)) {
                $query = $model::query();

                // If the model has a 'sortable' property with a 'group_column', group by that column
                if (isset($model->sortable) && isset($model->sortable['group_column'])) {
                    $groupColumn = $model->sortable['group_column'];
                    $query->where($groupColumn, $model[$groupColumn]);
                }

                // Get the maximum sorting value in the group and increment it
                $maxSorting = $query->max('sorting');
                $newSorting = $maxSorting ? $maxSorting + 1 : 1;

                // Update the sorting value without triggering events
                $model->updateQuietly(['sorting' => $newSorting]);
            }
        });

        // When a model is deleted, update the sorting for the remaining elements
        static::deleting(function ($model) {
            $table = $model->getTable();
            $sorting = $model->sorting;

            // If there is a valid table and sorting value, update the sorting values of other records
            if ($table && $sorting) {
                DB::statement("UPDATE $table SET sorting = sorting - 1 WHERE sorting > ?", [$sorting]);
            }
        });
    }

    /**
     * Scope to sort by a related table's nested value.
     *
     * This method allows sorting by a related table's nested field, using a custom order.
     * It handles null values by putting them at the end or beginning, depending on the direction.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $value The related table's column to sort by.
     * @param string $direction The sort direction ('asc' or 'desc').
     * @return \Illuminate\Database\Eloquent\Builder The modified query builder.
     */
    public function scopeOrderByRelated($query, $value, $direction = 'asc')
    {
        $table = $query->getModel()->getTable(); // Get the model's table name dynamically
    
        return $query
            // First, prioritize rows with null values
            ->orderByRaw("CASE WHEN {$table}.{$value} IS NULL THEN 1 ELSE 0 END")
            // Then, order by the column value in the specified direction
            ->orderBy("{$table}.{$value}", $direction);
    }

}
