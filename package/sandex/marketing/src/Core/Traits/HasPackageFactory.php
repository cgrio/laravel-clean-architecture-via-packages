<?php

namespace Sandex\Marketing\Core\Traits;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

trait HasPackageFactory
{
    use HasFactory;

    protected static function newFactory()
    {
        $package = Str::before(get_called_class(), 'Data\\Models');
        $modelName = Str::after(get_called_class(), 'Data\\Models\\');
        $path = $package . 'Data\\Factories\\' . $modelName . 'Factory';

        return $path::new();
    }
}
