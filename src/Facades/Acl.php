<?php

namespace Habib\Acl\Facades;

use Illuminate\Support\Facades\Facade;

class Acl extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'acl';
    }

    public static function acl(array $options = [])
    {
        static::$app->make('router')->acl($options);
    }
}
