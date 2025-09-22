<?php  // CERTIFIQUE-SE DE QUE ESTA LINHA ESTÁ NO TOPO, SEM ESPAÇOS OU LINHAS ANTES

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
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

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Registrar as Policies manualmente
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(School::class, SchoolPolicy::class);
        Gate::policy(Activity::class, ActivityPolicy::class);
        Gate::policy(Content::class, ContentPolicy::class);
        Gate::policy(ForumPost::class, ForumPostPolicy::class);
        Gate::policy(StudentActivity::class, StudentActivityPolicy::class);
    }
}