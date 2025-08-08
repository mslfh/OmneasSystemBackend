<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;
use App\Contracts\AttributeContract;
use App\Repositories\AttributeRepository;
use App\Contracts\CategoryContract;
use App\Repositories\CategoryRepository;
use App\Contracts\ComboContract;
use App\Repositories\ComboRepository;
use App\Contracts\ComboItemContract;
use App\Repositories\ComboItemRepository;
use App\Contracts\ComboProductContract;
use App\Repositories\ComboProductRepository;
use App\Contracts\ItemContract;
use App\Repositories\ItemRepository;
use App\Contracts\OrderContract;
use App\Repositories\OrderRepository;
use App\Contracts\OrderItemContract;
use App\Repositories\OrderItemRepository;
use App\Contracts\OrderPaymentContract;
use App\Repositories\OrderPaymentRepository;
use App\Contracts\PrinterContract;
use App\Repositories\PrinterRepository;
use App\Contracts\PrintLogContract;
use App\Repositories\PrintLogRepository;
use App\Contracts\PrintTemplateContract;
use App\Repositories\PrintTemplateRepository;
use App\Contracts\ProductContract;
use App\Repositories\ProductRepository;
use App\Contracts\ProductAttributeContract;
use App\Repositories\ProductAttributeRepository;
use App\Contracts\ProductItemContract;
use App\Repositories\ProductItemRepository;
use App\Contracts\ProductProfileContract;
use App\Repositories\ProductProfileRepository;
use App\Contracts\ProfileContract;
use App\Repositories\ProfileRepository;
use App\Contracts\ScheduleContract;
use App\Repositories\ScheduleRepository;
use App\Contracts\StaffContract;
use App\Repositories\StaffRepository;
use App\Contracts\SystemSettingContract;
use App\Repositories\SystemSettingRepository;
use App\Contracts\UserContract;
use App\Repositories\UserRepository;
use App\Contracts\VoucherContract;
use App\Repositories\VoucherRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Core bindings
        $this->app->bind(AttributeContract::class, AttributeRepository::class);
        $this->app->bind(CategoryContract::class, CategoryRepository::class);
        $this->app->bind(ComboContract::class, ComboRepository::class);
        $this->app->bind(ComboItemContract::class, ComboItemRepository::class);
        $this->app->bind(ComboProductContract::class, ComboProductRepository::class);
        $this->app->bind(ItemContract::class, ItemRepository::class);
        $this->app->bind(OrderContract::class, OrderRepository::class);
        $this->app->bind(OrderItemContract::class, OrderItemRepository::class);
        $this->app->bind(OrderPaymentContract::class, OrderPaymentRepository::class);
        $this->app->bind(PrinterContract::class, PrinterRepository::class);
        $this->app->bind(PrintLogContract::class, PrintLogRepository::class);
        $this->app->bind(PrintTemplateContract::class, PrintTemplateRepository::class);
        $this->app->bind(ProductContract::class, ProductRepository::class);
        $this->app->bind(ProductAttributeContract::class, ProductAttributeRepository::class);
        $this->app->bind(ProductItemContract::class, ProductItemRepository::class);
        $this->app->bind(ProductProfileContract::class, ProductProfileRepository::class);
        $this->app->bind(ProfileContract::class, ProfileRepository::class);
        $this->app->bind(ScheduleContract::class, ScheduleRepository::class);
        $this->app->bind(StaffContract::class, StaffRepository::class);
        $this->app->bind(SystemSettingContract::class, SystemSettingRepository::class);
        $this->app->bind(UserContract::class, UserRepository::class);
        $this->app->bind(VoucherContract::class, VoucherRepository::class);
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
