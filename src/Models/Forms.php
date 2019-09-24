<?php

namespace Viaativa\Viaroot\Models;
use Illuminate\Database\Eloquent\Model;
use Pvtl\VoyagerForms\Form;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class Forms extends Model
{
    protected $models = [
        'Form' => Form::class,
    ];

    protected function model($name)
    {
        return Viaativa\Viaroot\Models($this->models[studly_case($name)]);
    }

    public function forms($key, $vars = [], $default = null)
    {
        $form = self::model('Form')->where('id', $key)->first();

        $res = ['form' => $form];

        foreach($vars as $key => $val) {
            $res[$key] = $val;
        }




        try {
            if (!View::exists('voyager-forms::layouts.' . $form->layout)) {
                $form->layout = 'default';
            }

            //dd($vars);

            return view('voyager-forms::layouts.' . $form->layout, $res );
        } catch (\Exception $e) {
            Log::error($e->getTraceAsString());
        }
    }
}
