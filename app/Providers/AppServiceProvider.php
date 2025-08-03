<?php

namespace App\Providers;

use App\Contracts\ServiceContract;
use App\Contracts\VoucherContract;
use App\Repositories\ServiceRepository;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;
use App\Contracts\PackageContract;
use App\Repositories\PackageRepository;
use App\Contracts\ServiceAppointmentContract;
use App\Repositories\ServiceAppointmentRepository;
use App\Contracts\StaffContract;
use App\Repositories\StaffRepository;
use App\Contracts\ScheduleContract;
use App\Repositories\ScheduleRepository;
use App\Contracts\ScheduleHistoryContract;
use App\Repositories\ScheduleHistoryRepository;
use App\Contracts\OrderContract;
use App\Contracts\UserContract;
use App\Repositories\OrderRepository;
use App\Repositories\UserRepository;
use App\Contracts\AppointmentContract;
use App\Repositories\AppointmentRepository;
use App\Contracts\SystemSettingContract;
use App\Repositories\SystemSettingRepository;
use App\Contracts\SmsContract;
use App\Repositories\SmsRepository;
use App\Contracts\NotificationContract;
use App\Repositories\NotificationRepository;
use App\Contracts\UserProfileContract;
use App\Repositories\UserProfileRepository;
use App\Repositories\VoucherRepository;
use App\Contracts\ProductContract;
use App\Repositories\ProductRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PackageContract::class, PackageRepository::class);
        $this->app->bind(ServiceContract::class, ServiceRepository::class);
        $this->app->bind(ServiceAppointmentContract::class, ServiceAppointmentRepository::class);
        $this->app->bind(StaffContract::class, StaffRepository::class);
        $this->app->bind(ScheduleContract::class, ScheduleRepository::class);
        $this->app->bind(ScheduleHistoryContract::class, ScheduleHistoryRepository::class);
        $this->app->bind(OrderContract::class, OrderRepository::class);
        $this->app->bind(AppointmentContract::class, AppointmentRepository::class);
        $this->app->bind(UserContract::class, UserRepository::class);
        $this->app->bind(SystemSettingContract::class, SystemSettingRepository::class);
        $this->app->bind(SmsContract::class, SmsRepository::class);
        $this->app->bind(NotificationContract::class, NotificationRepository::class);
        $this->app->bind(UserProfileContract::class, UserProfileRepository::class);
        $this->app->bind(VoucherContract::class, VoucherRepository::class);
        $this->app->bind(ProductContract::class, ProductRepository::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
    }
}
