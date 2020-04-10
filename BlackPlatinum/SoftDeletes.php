<?php

namespace BlackPlatinum;

trait SoftDeletes
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    public function newEloquentBuilder($query)
    {
        return new Builder($query);
    }
}