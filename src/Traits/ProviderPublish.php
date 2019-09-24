<?php

namespace Viaativa\Viaroot\Traits;

trait ProviderPublish {

    function publish($providerClass, $message = "Publishing", $force = true){

        $publish_array['--provider'] = $providerClass;
        if($force){
            $publish_array['--force'] = null;
        }
        $this->info($message);
        $this->call('vendor:publish', $publish_array);
    }

}
