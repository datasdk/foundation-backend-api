<?php 

namespace App\Traits\Tags;

use Spatie\Tags\HasTags;
use Spatie\Tags\Tag;
use App\Models\Tags as OrigTags;

trait Tags
{
    use HasTags;

    /**
     * Create a slug from the given name.
     *
     * This method generates a slug based on the provided name or a custom rule.
     * 
     * @param string $name The name to generate a slug from.
     * @return string The generated slug.
     */
    public static function createSlug(string $name)
    {
        return Str::slug($name); // Generate and return the slug from the name.
    }

    /**
     * Get the tags for the model.
     *
     * If the 'groupTags' query parameter is true, the tags will be grouped by their group name.
     * If the group name is not set, the tag will be under the default 'standard' group.
     *
     * @return \Illuminate\Support\Collection A collection of tag slugs.
     */

    
    public function getTagsGroupAttribute()
    {
        $tags = $this->tags()->get();

        if (request()->boolean('groupTags')) {
            return $tags->groupBy(fn($tag) => $tag->group_name ?: 'standard')
                        ->map(fn($group) => $group->pluck("slug"));
        }

        return $tags->pluck("slug");
    }

    /**
     * Get a tag by its ID or name.
     *
     * This method allows you to find a tag by its string name.
     * 
     * @param string $name The name of the tag to find.
     * @param string|null $type The type of the tag (optional).
     * @return \Spatie\Tags\Tag|null The found tag or null if not found.
     */
    public static function getTag(string $name, $type = null)
    {
        return Tag::findFromString($name); // Find and return the tag from the name.
    }

    /**
     * Set tags for the model.
     *
     * This method allows you to assign multiple tags to the model. It will also optionally create tags if they don't already exist.
     * 
     * @param array|string|null $tags The tags to assign to the model.
     * @param bool $createIfNotExists If true, new tags will be created if they don't exist.
     * @return $this The model instance.
     */
    public function setTags(?array $tags, $createIfNotExists = false)
    {
        // Flatten the input tags and handle each tag.
        $insertTag = collect($tags)->flatten()->map(function ($tags, $type) use($createIfNotExists) {

            $type = $type ?: null; // Set type to null if it's not provided.

            return collect($tags)->map(function ($tagname) use ($createIfNotExists, $type) {
                
                $tag = Tag::findFromStringOfAnyType($tagname); // Find the tag by its name and type.
                
                // If the tag doesn't exist and we should create it, create the tag.
                if ($tag->isEmpty() && $createIfNotExists) {
                    $type = $this->getTable(); // Set the tag type to the model's table name.
                    $tag = Tag::findOrCreate($tagname, $type); // Create the tag.
                }

                return $tag; // Return the found or created tag.
            
            })->filter(); // Filter out any empty tags.

        })
        ->filter() // Filter out any empty collections.
        ->flatten(); // Flatten the tags into a single collection.

        if (empty($insertTag)) {
            $insertTag = [];
        }

        // Sync the tags with the model.
        $this->syncTags($insertTag);

        return $this; // Return the model instance.
    }

    /**
     * Check if the model has a specific tag.
     *
     * This method checks if the model has a tag with the given name.
     * 
     * @param \Spatie\Tags\Tag|string $tag The tag to check for, either as a Tag object or a string.
     * @return bool True if the model has the tag, false otherwise.
     */
    public function hasTag(string $tag)
    {
        $tagName = is_string($tag) ? $tag : $tag->name; // If the tag is a string, use it directly; otherwise, use the tag's name.
        return $this->tags->pluck('name')->contains($tagName); // Check if the model's tags contain the specified tag name.
    }

    /**
     * Scope a query to include models with all given tag slugs.
     *
     * This query scope allows you to filter models by all tags provided in an array of tag slugs.
     * It also handles language-specific tags based on the 'lang' query parameter.
     * 
     * @param \Illuminate\Database\Eloquent\Builder $q The query builder.
     * @param array $tagsArray An array of tag slugs to filter by.
     * @param bool $matchNumbersOfTags Whether to match all tags or just any of them (optional).
     * @param bool $strict Whether to match tags strictly (optional).
     * @return \Illuminate\Database\Eloquent\Builder The modified query builder.
     */
    public function scopeWithAllTagsFromArray($q, array $tagsArray, $matchNumbersOfTags = false, $strict = true)
    {
        if (empty($tagsArray)) {
            return; // If no tags are provided, do nothing.
        }

        $req = request();
        $lang = $req->has("lang") ? $req->get("lang") : config("app.locale"); // Get the language from the request or use the default.

        // Remove null values from the tags array.
        $tagsCollection = collect($tagsArray)->filter(fn($tag) => !is_null($tag))->flatten();

        if ($tagsCollection->isNotEmpty()) {
            foreach ($tagsCollection as $tag) {
                // Filter the query by each tag, considering the language.
                $q->whereHas('tags', function ($query) use ($tag, $lang) {
                    $query->where("slug->$lang", $tag); // Check the slug for the specified language.
                });
            }
        } else {
            // If no valid tags are found, do nothing.
        }
    }
}
