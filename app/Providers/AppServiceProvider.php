<?php

namespace App\Providers;

use App\Contracts\Repositories\FileRepositoryInterface;
use App\Contracts\Repositories\FolderRepositoryInterface;
use App\Contracts\Repositories\TagRepositoryInterface;
use App\Repositories\FileRepository;
use App\Repositories\FolderRepository;
use App\Repositories\TagRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(FolderRepositoryInterface::class, FolderRepository::class);
        $this->app->bind(TagRepositoryInterface::class, TagRepository::class);
        $this->app->bind(FileRepositoryInterface::class, FileRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
