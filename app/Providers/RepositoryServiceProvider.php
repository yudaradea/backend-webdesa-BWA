<?php

namespace App\Providers;

use App\Interfaces\DevelopmentApplicantRepositoryInterfaces;
use App\Interfaces\DevelopmentRepositoryInterfaces;
use App\Interfaces\EventParticipantRepositoryInterfaces;
use App\Interfaces\EventRepositoryInterfaces;
use App\Interfaces\FamilyMemberRepositoryInterface;
use App\Interfaces\HeadOfFamilyRepositoryInterface;
use App\Interfaces\ProfileImageRepositoryInterfaces;
use App\Interfaces\ProfileRepositoryInterfaces;
use App\Interfaces\SocialAssistanceRepositoryInterfaces;
use App\Interfaces\SocialAssistanceRecipientRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\DevelopmentApplicantRepository;
use App\Repositories\DevelopmentRepository;
use App\Repositories\EventParticipantRepository;
use App\Repositories\EventRepository;
use App\Repositories\FamilyMemberRepository;
use App\Repositories\HeadOfFamilyRepository;
use App\Repositories\ProfileImageRepository;
use App\Repositories\ProfileRepository;
use App\Repositories\SocialAssistanceRecipientRepository;
use App\Repositories\SocialAssistanceRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(HeadOfFamilyRepositoryInterface::class, HeadOfFamilyRepository::class);
        $this->app->bind(FamilyMemberRepositoryInterface::class, FamilyMemberRepository::class);
        $this->app->bind(SocialAssistanceRepositoryInterfaces::class, SocialAssistanceRepository::class);
        $this->app->bind(SocialAssistanceRecipientRepositoryInterface::class, SocialAssistanceRecipientRepository::class);
        $this->app->bind(EventRepositoryInterfaces::class, EventRepository::class);
        $this->app->bind(EventParticipantRepositoryInterfaces::class, EventParticipantRepository::class);
        $this->app->bind(DevelopmentRepositoryInterfaces::class, DevelopmentRepository::class);
        $this->app->bind(DevelopmentApplicantRepositoryInterfaces::class, DevelopmentApplicantRepository::class);
        $this->app->bind(ProfileRepositoryInterfaces::class, ProfileRepository::class);
        $this->app->bind(ProfileImageRepositoryInterfaces::class, ProfileImageRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
