<?php
namespace Habib\Acl\Router;

class AclRouter
{
    public function acl(array $options=[]): \Closure
    {
        $options['namespace']="\Habib\Acl\Http\Controllers";
        return function () use ($options) {
            $this->group($options,function () {
                $this->get('dashboard/test/{name}','AclController@index');
            });
        };
    }

}
