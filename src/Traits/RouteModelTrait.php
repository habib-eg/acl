<?php


namespace Habib\Acl\Traits;


use Habib\Acl\Casts\JsonArrayCast;

trait RouteModelTrait
{

    public static function bootRouteModelTrait(): void
    {

    }

    public function initializeRouteModelTrait(): void
    {
        $table = config('acl.routes.table_name', 'routes');
        $this->setTable($table);
        $this->fillable = array_merge($this->fillable, ['auth','middleware', 'is_public','is_disable', 'permissions', 'roles', 'method', 'url', 'id', 'created_at', 'updated_at']);
        $this->casts = array_merge($this->casts, [ "roles" => JsonArrayCast::class, "permissions" => JsonArrayCast::class, "middleware" => "array", "is_public" => "boolean", "is_disable" => "boolean", "auth" => "boolean", ]);
    }
}
