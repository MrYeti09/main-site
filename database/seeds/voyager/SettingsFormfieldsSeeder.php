<?php

use Illuminate\Database\Seeder;
use Viaativa\Viaroot\Models\SettingsFormfields;

class SettingsFormfieldsSeeder extends Seeder
{

    public function run()
    {
        $this->iconSeeder();
    }

    private function iconSeeder()
    {
        if(!SettingsFormfields::where('slug', 'font-icon')->exists()){
            $settingFormField = new SettingsFormfields();
            $settingFormField->name = "Icons";
            $settingFormField->slug = "font-icon";
            $settingFormField->route = "voyager::formfields.bread.font-icon";
            $settingFormField->type = "bread";
            $settingFormField->save();
        }
    }

}
