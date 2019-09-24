<?php

namespace Viaativa\Viaroot\Commands;

use Illuminate\Console\Command;
use Viaativa\Viaroot\Traits\ProviderPublish;
use Viaativa\Viaroot\Traits\InstallAssets;
use Viaativa\Viaroot\Providers\ViarootServiceProvider;
use Viaativa\Viaroot\Traits\ProviderSeed;



class ViarootCommand extends Command
{

    use ProviderPublish, InstallAssets, ProviderSeed;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'viaativa-site:install';

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
        $this->publish(ViarootServiceProvider::class);
    }
}
