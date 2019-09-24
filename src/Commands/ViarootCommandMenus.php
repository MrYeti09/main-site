<?php

namespace Viaativa\Viaroot\Commands;

use Illuminate\Console\Command;
use TCG\Voyager\Models\DataType;
use Viaativa\Viaroot\Models\MenuItem;
use Viaativa\Viaroot\Traits\ProviderPublish;
use Viaativa\Viaroot\Traits\InstallAssets;
use Viaativa\Viaroot\Providers\ViarootServiceProvider;
use Viaativa\Viaroot\Traits\ProviderSeed;

class ViarootCommandMenus extends Command
{

    use ProviderPublish, InstallAssets, ProviderSeed;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'viaativa-site:menus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Website Root';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {


        //
        $this->seed(__DIR__, "MenuSeeder", "Seeding Voyager data into the database");

//        exec('php artisan cache:clear');
    }
}
