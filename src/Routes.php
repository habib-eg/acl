<?php

namespace Habib\Acl;

use Habib\Acl\Models\Route;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Routing\RouteCollection;
use Illuminate\Routing\Router;

class Routes
{
    /**
     * @var string[]
     */
    public $exceptRoutes = [
        '_debugbar',
        '_ignition',
        'telescope',
    ];
    /**
     * @var array
     */
    protected $routes = [];
    public $models = [];
    /**
     * @var Router
     */
    protected $router;

    /**
     * Routes constructor.
     * @param Router $router
     */
    public function __construct()
    {
        $this->router = app()->make('router');
        $this->routes = $this->router->getRoutes();
        $this->models=$this->routesDatabaseRegister();
    }

    /**
     * @return array
     */
    public function routesDatabaseRegister(): array
    {
        return collect($this->getRoutes())->map(function ($route) {
            return $this->routeRegisterFindOrCreate($route);
        })->toArray();
    }

    /**
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->mergeRoutes();
    }

    /**
     * @param array $routes
     * @return $this
     */
    public function setRoutes(array $routes): self
    {
        $this->routes = $routes;
        return $this;
    }

    /**
     * @return array
     */
    public function mergeRoutes(): array
    {
        $routes = [];
        foreach ($this->filterRoutesByMethod() as $item) {
            foreach ($item as $value) {
                $routes[] = $value;
            }
        }
        return $routes;
    }

    /**
     * @return array
     */
    public function filterRoutesByMethod(): array
    {
        $listOfRoutes = $this->filtered($this->getRoutesByMethod());
        $listOfRoutes['GET'] = array_merge($listOfRoutes['GET'], $listOfRoutes['HEAD']);
        $listOfRoutes['PUT'] = array_merge($listOfRoutes['PUT'], $listOfRoutes['PATCH']);
        unset($listOfRoutes['HEAD']);
        unset($listOfRoutes['PATCH']);
        return $listOfRoutes;
    }

    /**
     * @param $routes
     * @return array
     */
    public function filtered($routes): array
    {
        return collect($routes)->map(function ($key) {
            return $this->cleanRoutes($key);
        })->toArray();
    }

    /**
     * @param array $routes
     * @return array
     */
    private function cleanRoutes(array $routes): array
    {
        $exceptRoutes = array_merge($this->exceptRoutes, config('acl.except_routes', []));
        foreach ($routes as $key => $route) {
            foreach ($exceptRoutes as $rule) {
                if (!empty($rule) && strpos((is_string($route) ? ($route) : ($route->uri)), $rule) !== false) {
                    unset($routes[$key]);
                }
            }
        }
        return $routes;
    }

    /**
     * @return array
     */
    public function getRoutesByMethod(): array
    {
        return $this->routes()->getRoutesByMethod();
    }

    /**
     * @return RouteCollection
     * @throws BindingResolutionException
     */
    public function routes(): RouteCollection
    {
        return app()->make('router')->getRoutes();
    }

    /**
     * @param $route
     * @return Route
     */
    public function routeRegisterFindOrCreate(\Illuminate\Routing\Route $route)
    {
        return Route::firstOrCreate(["url" => $route->uri, "method" => $route->methods()[0]], ["roles" => [], "permissions" => [], "middleware" => [], "is_public" => false, "auth" => true,]);
    }

    /**
     * @return \Illuminate\Routing\Route|null
     * @throws BindingResolutionException
     */
    public function getCurrentRoute()
    {
        return app()->make('router')->getCurrentRoute();
    }

    /**
     * @return string[]
     */
    public function getExceptRoutes(): array
    {
        return $this->exceptRoutes;
    }

    /**
     * @param array $exceptRoutes
     * @return $this
     */
    public function setExceptRoutes(array $exceptRoutes): self
    {
        $this->exceptRoutes = $exceptRoutes;
        return $this;
    }

    /**
     * @return Route[]|\LaravelIdea\Helper\Habib\Acl\Models\_RouteCollection
     */
    public function getDatabaseRoutes()
    {
        return Route::latest()->get();
    }
}
