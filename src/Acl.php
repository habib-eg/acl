<?php

namespace Habib\Acl;

use Habib\Acl\Models\Route as RouteModel;

class Acl
{
    protected $route;
    protected $routes;

    public function __construct(Routes $routes)
    {
        $this->route = $this->getCurrentRoute();
        $this->routes = $routes;
        dd($routes->routeRegisterFindOrCreate($this->route));
    }
    public function isPublic()
    {

    }
}
