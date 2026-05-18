<?php

namespace App\Contracts\Interfaces;

interface CategoryInterface
{
    /**
     * Get the morph class for polymorphic relations.
     *
     * @return string
     */
    public function getMorphClass();

    /**
     * Define the polymorphic relation to the parent model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function model();

    /**
     * Define a many-to-many polymorphic relation with a given class.
     *
     * @param string $class
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function entries($class);

    /**
     * Get all children category IDs (including descendants) based on IDs or slugs.
     *
     * @param mixed $ids
     * @return array
     */
    public static function getAllChildren($ids = null);

    /**
     * Get children categories dynamically based on request parameters.
     *
     * @return array|null
     */
    public function getChildrenAttribute();

    /**
     * Add a category type to the application's bindings.
     *
     * @param string $type
     * @param string $class
     * @return void
     */
    public static function addInclude(string $type, string $class);
}
