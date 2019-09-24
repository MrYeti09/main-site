<?php

namespace Viaativa\Viaroot\Commands;

use Illuminate\Console\Command;
use TCG\Voyager\Models\DataType;
use Viaativa\Viaroot\Traits\ProviderPublish;
use Viaativa\Viaroot\Traits\InstallAssets;
use Viaativa\Viaroot\Providers\ViarootServiceProvider;
use Viaativa\Viaroot\Traits\ProviderSeed;


class ViarootCommandInstallPackages extends Command
{

    use ProviderPublish, InstallAssets, ProviderSeed;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'viaativa-site:packages';

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
    protected function addSass($name)
    {
        $file_lines = file(base_path() . '/resources/assets/sass/viaativa-blocks/blocks.scss');
        $lines = [];
        foreach ($file_lines as $line) {
            array_push($lines, str_replace(['@import "', '.scss";', "\n"], "", $line));
        }

        if (!in_array($name, $lines) and $name != "blocks.scss") {

            $fp = fopen(base_path() . '/resources/assets/sass/viaativa-blocks/blocks.scss', 'a');//opens file in append mode
            $this->info('Installing '.$name);
            fwrite($fp, "\n" . '@import "' . $name . '";');
            fclose($fp);
        }

    }

    public function handle()
    {
        $dirs = scandir(base_path() . '/resources/assets/sass/viaativa-blocks');
        unset($dirs[0]);
        unset($dirs[1]);
        foreach ($dirs as $dir) {
            $this->addSass($dir);
        }
        $this->info("Finalizado a instalação dos Sass");

    }
}
