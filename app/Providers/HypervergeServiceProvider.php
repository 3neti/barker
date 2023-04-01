<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Classes\Hyperverge;

class HypervergeServiceProvider extends ServiceProvider
{

    public function boot(): void
    {
        $this->setupEnvironment();
        $this->configureRoutes();
    }

    protected function setupEnvironment()
    {
        Hyperverge::$appId = config('domain.hyperverge.api.id');
        Hyperverge::$appKey = config('domain.hyperverge.api.key');
        Hyperverge::$endpoint = config('domain.hyperverge.api.url.kyc');//change to request
        Hyperverge::$workflowId = config('domain.hyperverge.url.workflow');
        Hyperverge::$inputs = array_merge(['app' => config('app.name')], []);
        Hyperverge::$languages = ['en' => 'English'];
        Hyperverge::$defaultLanguage = 'en';
        Hyperverge::$expiry = config('domain.hyperverge.api.expiry');
        Hyperverge::$resultEndpoint = config('domain.hyperverge.api.url.result');//change to process
    }

    protected function configureRoutes()
    {
        $this->loadRoutesFrom((base_path('routes/hyperverge.php')));

    }
}
