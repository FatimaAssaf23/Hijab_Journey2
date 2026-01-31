<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Events\Dispatcher;
use App\Models\StudentLessonProgress;
use App\Models\QuizAttempt;
use App\Observers\StudentLessonProgressObserver;
use App\Observers\QuizAttemptObserver;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // 'App\Events\SomeEvent' => [
        //     'App\Listeners\SomeListener',
        // ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();

        // Register observers for ML predictions
        StudentLessonProgress::observe(StudentLessonProgressObserver::class);
        QuizAttempt::observe(QuizAttemptObserver::class);
    }
}
