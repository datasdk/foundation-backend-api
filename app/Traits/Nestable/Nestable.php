<?php

namespace App\Traits\Nestable;

use Kalnoy\Nestedset\NodeTrait;
use model;

trait Nestable
{
    use NodeTrait;


    
   public static function bootNestable()
    {
        static::saved(function ($model) {
            // Ret træet når modellen er gemt
            $model->fixTree();
        });
    }


    public function getLftName()
    {
        return '_lft';
    }


    public function getRgtName()
    {
        return '_rgt';
    }


    public function getParentIdName()
    {
        return 'parent_id';
    }




    public function setParentAttribute($value)
    {

        if (!$this->exists) {

            $this->save(); 

        }

        $this->setParentIdAttribute($value);

        return $this;

    }


    public function scopeHasChildren($query)
    {
        return $query->whereHas('children');
    }


    public function scopeHasNoChildren($query)
    {
        return $query->doesntHave('children');
    }


    public function hasChildren()
    {
        return $this->children->count() > 0;
    }


}
