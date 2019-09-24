<?php

namespace Viaativa\Viaroot\Traits;

use TCG\Voyager\Traits\Seedable;

trait ProviderSeed {

    use Seedable {
        seed as protected parentSeed;
    }

    function seed($dir, $seedClassString, $message = "Seeding data into the database"){
        $this->seedersPath = $dir . '/../../database/seeds/';
        $this->info($message);
        $this->parentSeed($seedClassString);
    }

}
