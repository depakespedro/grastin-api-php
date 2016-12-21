<?php

namespace Depakespedro\Grastin;

use Illuminate\Support\ServiceProvider;

class GrastinServiceProvider extends ServiceProvider {

    public function boot(){

    }

    public function register(){
        $this->app['grastin'] = $this->app->share(function(){
            return new Grastin();
        });
    }

}
