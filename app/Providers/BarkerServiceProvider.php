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

        $this->addInstructions();

        $this->addRiders();

        $this->addProfiles();
    }

    protected function configureChannels(): void
    {
        Barker::type('accounting', 'Accounting', [
            'email'
        ])->description('Accounting description');

        Barker::type('authentication', 'Authentication', [
            'email',
            'mobile',
        ])->description('Authentication description');

        Barker::type('authorization', 'Authorization', [
            'email',
            'mobile',
            'webhook'
        ])->description('Authorization description');
    }

    protected function configureRoutes()
    {
        //TODO: transfer all the routes here
    }

    protected function addInstructions()
    {
        Barker::instruction('First Template', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.')
            ->timing('pre-checkin')
            ->description('1st description');
        Barker::instruction('Second Template', 'Odio ut enim blandit volutpat maecenas volutpat blandit aliquam etiam.')
            ->timing('pre-checkin')
            ->description('2nd description');
        Barker::instruction('Third Template', 'Mauris pharetra et ultrices neque ornare aenean euismod elementum. ')
            ->timing('pre-checkin')
            ->description('3rd description');
    }

    protected function addRiders()
    {
        Barker::rider('First Rider', 'Ligula ullamcorper malesuada proin libero nunc consequat.')
            ->timing('post-checkin')
            ->description('1st rider');
        Barker::rider('Second Rider', 'Quis ipsum suspendisse ultrices gravida. Nunc eget lorem dolor sed.')
            ->timing('post-checkin')
            ->description('2nd rider');
        Barker::rider('Third Rider', 'Sagittis nisl rhoncus mattis rhoncus urna neque viverra.')
            ->timing('post-checkin')
            ->description('3rd rider');
    }

    protected function addProfiles()
    {
        Barker::profile('Gender', ['Male', 'Female'])
            ->description('Gender description');
        Barker::profile('Age', ['Young', 'Middle-Aged', 'Old'])
            ->description('Age description');
        Barker::profile('Complexion', ['Dark-Skinned', 'Brown-Skinned', 'Fair-Skinned'])
            ->description('Complexion description');
        Barker::profile('Height', ['Short', 'Average Height', 'Tall'])
            ->description('Height description');
    }
}
