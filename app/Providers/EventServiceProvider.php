<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\StudentCreated;
use App\Events\StudentUpdated;
use App\Events\StudentDeleted;
use App\Events\GradeCreated;
use App\Events\GradeUpdated;
use App\Listeners\LogStudentCreation;
use App\Listeners\LogStudentUpdate;
use App\Listeners\LogStudentDeletion;
use App\Listeners\LogGradeCreation;
use App\Listeners\LogGradeUpdate;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        StudentCreated::class => [
            LogStudentCreation::class,
        ],
        StudentUpdated::class => [
            LogStudentUpdate::class,
        ],
        StudentDeleted::class => [
            LogStudentDeletion::class,
        ],
        GradeCreated::class => [
            LogGradeCreation::class,
        ],
        GradeUpdated::class => [
            LogGradeUpdate::class,
        ],
    ];

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
