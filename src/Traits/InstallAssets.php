<?php

namespace Viaativa\Viaroot\Traits;

use Symfony\Component\Process\Process;

trait InstallAssets
{

    function importSass(
        $sassName,
        $message = "Importing the Viaativa sass's into blocks"
    )
    {
        $this->info($message);
        $path = './resources/assets/sass/viaativa-blocks/blocks.scss';
        $grepProcess = Process::fromShellCommandline("grep \"@import \\\"{$sassName}\\\";\" {$path}");
        $grepProcess->setWorkingDirectory(base_path())->run();
        if (!$grepProcess->isSuccessful()) {
            $process = Process::fromShellCommandline("echo @import \"{$sassName}\"; >> {$path}");
            $process->setWorkingDirectory(base_path())->run();
        }
    }

}
