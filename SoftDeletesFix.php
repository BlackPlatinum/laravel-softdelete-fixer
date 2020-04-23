<?php

namespace BlackPlatinum;

use Illuminate\Database\Eloquent\SoftDeletes;
use BlackPlatinum\Eloquent\Builder;

trait SoftDeletesFix
{
    use SoftDeletes;
    
    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return Builder
     */
    public function newEloquentBuilder($query)
    {
        return new Builder($query);
    }
}
