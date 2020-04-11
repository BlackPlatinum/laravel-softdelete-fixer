<?php

namespace BlackPlatinum;

use BlackPlatinum\Eloquent\Builder;

trait SoftDeletesFix
{
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
