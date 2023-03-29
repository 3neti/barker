<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Classes\Barker;

class BarkerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->configureChannels();

        $this->configureRoutes();
    }

    protected function configureChannels(): void
    {
        Barker::type('accounting', 'Accounting', [
            'mobile',
            'email'
        ])->description('Accounting description');

        Barker::type('authentication', 'Authentication', [
            'mobile',
            'email',
            'webhook'
        ])->description('Authentication description');

        Barker::type('authorization', 'Authorization', [
            'mobile',
            'email',
            'webhook'
        ])->description('Authorization description');
    }

    protected function configureRoutes()
    {
        //TODO: transfer all the routes here
    }
}
