// backend/app/Providers/AuthServiceProvider.php
<?php

namespace App\Providers;

use App\Models\User;
use App\Models\School;
use App\Models\Activity;
use App\Models\Content;
use App\Models\ForumPost;
use App\Models\StudentActivity;
use App\Policies\UserPolicy;
use App\Policies\SchoolPolicy;
use App\Policies\ActivityPolicy;
use App\Policies\ContentPolicy;
use App\Policies\ForumPostPolicy;
use App\Policies\StudentActivityPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        School::class => SchoolPolicy::class,
        Activity::class => ActivityPolicy::class,
        Content::class => ContentPolicy::class,
        ForumPost::class => ForumPostPolicy::class,
        StudentActivity::class => StudentActivityPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}