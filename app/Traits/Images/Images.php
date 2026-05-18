<?php

namespace App\Traits\Images;

use Plank\Mediable\Mediable;
use Modules\Media\Models\Media as MediaModel;
use App\Models\Media;
use Illuminate\Http\UploadedFile;

trait Images
{
    use Mediable;


    /**
     * Add a single image by its ID.
     *
     * @param int $id
     * @return $this
     */
    public function addImage(int $id)
    {
        return $this->addImages([$id]);
    }

    /**
     * Add multiple images by their IDs.
     *
     * @param array|int $ids
     * @return $this
     */
    public function addImages($ids)
    {
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        foreach ($ids as $id) {
            $id = intval($id);
            if ($id) {
                $this->syncMedia($id, "main");
            }
        }

        return $this;
    }

    /**
     * Get all images associated with the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function images()
    {
        return $this->morphToMany(MediaModel::class, 'mediable');
    }

    /**
     * Accessor: Get the first image.
     *
     * @return MediaModel|null
     */
    public function getImageAttribute()
    {
        return $this->images()->first();
    }

    protected function uploadImage(UploadedFile $file, ?string $imageName = null)
    {
        $this->image = Media::upload($file, $imageName);
   
        return $this;
    }
}
