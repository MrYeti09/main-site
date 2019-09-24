<?php

namespace Viaativa\Viaroot\Traits;

use Symfony\Component\Process\Process;

trait ProviderDatabaseMigrate {

    function migrate($message = "Migrating All Database"){
        $this->info($message);
        $process = new Process(['php', 'artisan', 'migrate']);
        $process->run();
    }

}
