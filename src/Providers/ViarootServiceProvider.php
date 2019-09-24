<?php

namespace Viaativa\Viaroot\Providers;

use TCG\Voyager\FormFields\FontHandler;
use TCG\Voyager\FormFields\ProfileFormfield;
use Viaativa\Viaroot\Commands\ViarootCommandAdminInstallPackages;
use Viaativa\Viaroot\Commands\ViarootCommandInstallPackages;
use Viaativa\Viaroot\Commands\ViarootCommandMenus;
use Viaativa\Viaroot\Commands\ViarootCommandSeed;
use Viaativa\Viaroot\FormFields\ColorFormField2;
use Viaativa\Viaroot\FormFields\CroppableImage;
use Viaativa\Viaroot\FormFields\FontAwesomePicker;
use Viaativa\Viaroot\FormFields\Icon;
use Viaativa\Viaroot\FormFields\IconFormField;
use Viaativa\Viaroot\FormFields\Packery;
use Viaativa\Viaroot\FormFields\Tags;
use Viaativa\Viaroot\Http\Middleware\Permissions;
use Illuminate\Support\ServiceProvider;
use Viaativa\Viaroot\Commands\ViarootCommand;
use Viaativa\Viaroot\Http\Middleware\AdminPermissions;
use TCG\Voyager\Facades\Voyager as VoyagerFacade;

class ViarootServiceProvider extends ServiceProvider
{
    const PACKAGE_DIR = __DIR__ . '/../../';
    const VERSION = '3.0.0';
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        if(config('viaativa-site') == null) {
            //$this->mergeConfigFrom(self::PACKAGE_DIR . "/config/viaativa-site.php", "viaativa-site");
        }
        if(file_exists(config_path('viaativa-site.php')))
        {
            $config = require(config_path('viaativa-site.php'));
            $this->mergeConfigFrom(config_path('viaativa-site.php'), "viaativa-site");
        } else
        {
            $config = config('viaativa-site');
        }

        if($config['helpers']['BlockTypesDataHelper']) {
            $file = self::PACKAGE_DIR . 'src/Helpers/BlockTypesDataHelper.php';
            if (file_exists($file)) {
                require_once($file);
            }
        }
        if($config['helpers']['BasicFunctionsHelper']) {
            $file = self::PACKAGE_DIR . 'src/Helpers/BasicFunctionsHelper.php';
            if (file_exists($file)) {
                require_once($file);
            }
        }
        if($config['helpers']['Router']) {
            $file = self::PACKAGE_DIR . 'src/Helpers/Router.php';
            if (file_exists($file)) {
                require_once($file);
            }
        }
        VoyagerFacade::addFormField(ColorFormField2::class);
        VoyagerFacade::addFormField(CroppableImage::class);
        VoyagerFacade::addFormField(FontAwesomePicker::class);
        VoyagerFacade::addFormField(IconFormField::class);
        VoyagerFacade::addFormField(Packery::class);
        VoyagerFacade::addFormField(Tags::class);
        VoyagerFacade::addFormField(Icon::class);

//        $this->mergeConfigFrom(self::PACKAGE_DIR . "/config/page-blocks.php", "page-blocks");
        $this->loadViewsFrom(self::PACKAGE_DIR . '/resources/voyager', 'voyager');
        $this->loadViewsFrom(self::PACKAGE_DIR . '/resources/voyager-forms', 'voyager-forms');
        $this->loadViewsFrom(self::PACKAGE_DIR . '/resources/voyager-frontend', 'voyager-frontend');
        $this->loadViewsFrom(self::PACKAGE_DIR . '/resources/voyager-page-blocks', 'voyager-page-blocks');
        \Blade::directive('css', function ($expression) {
            list($style, $attr) = explode(',',$expression);
            if(strlen($attr) > 1) {
                return '<?php
                if(strlen('.$attr.'))
                {
                echo ' . $style . '.":".' . $attr . '.";";
                }
                ?>';
            }
        });

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {





        $this->loadMigrationsFrom(self::PACKAGE_DIR.'/database/migrations');
        app('router')->aliasMiddleware('admin-permissions', AdminPermissions::class);
        app('router')->aliasMiddleware('permission', Permissions::class);
        $this->loadTranslationsFrom(self::PACKAGE_DIR.'/lang', 'voyager');
        $this->loadRoutesFrom(self::PACKAGE_DIR.'/routes/web.php');
        $this->publishes([
            self::PACKAGE_DIR. 'publish' => base_path()
        ]);
        $this->loadViewsFrom(self::PACKAGE_DIR. 'resources/views', 'viaativa-site');
        $this->loadViewsFrom(self::PACKAGE_DIR . 'resources/views/voyager', 'viaativa-voyager');
        if ($this->app->runningInConsole()) {
            $this->commands([
                ViarootCommand::class,
                ViarootCommandSeed::class,
                ViarootCommandInstallPackages::class,
                ViarootCommandAdminInstallPackages::class,
                ViarootCommandMenus::class
            ]);

        }
    }
}
