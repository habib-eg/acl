<?php

namespace Habib\Acl\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class JsonArrayCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return mixed
     */
    public function get($model, $key, $value, $attributes)
    {
        $array = json_decode($value, true);
        if (in_array($key, ['role', 'roles'])) {
            $array = config('permission.models.role', Role::class)::findMany(is_array($array) ? $array : [$array]);
        } elseif (in_array($key, ['permission', 'permissions'])) {
            $array = config('permission.models.permission', Permission::class)::findMany(is_array($array) ? $array : [$array]);
        }
        return $array;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param Model $model
     * @param string $key
     * @param array $value
     * @param array $attributes
     * @return mixed
     */
    public function set($model, $key, $value, $attributes)
    {
        $ids = null;
        if ($value instanceof Collection) {
            $ids = $value->pluck('id')->toArray();
        } elseif ($value instanceof Model) {
            $ids = [$value->id];
        }
        return json_encode($ids ?? $value ?? []);
    }

}
