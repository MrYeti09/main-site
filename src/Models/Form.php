<?php

namespace Viaativa\Viaroot\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Pvtl\VoyagerForms\FormInput;

class Form extends Model
{

    protected $fillable = [
        'title',
        'view',
        'mailto',
        'hook',
        'layout',
        'email_template',
        'message_success',
        'target'
    ];


    public function inputs()
    {
        return $this->hasMany(FormInput::class)->ordered();
    }

    public function setMailToAttribute($value)
    {
        $this->attributes['mailto'] = serialize($value);
    }

    public function getMailToAttribute($value)
    {
        return unserialize($value);
    }


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
