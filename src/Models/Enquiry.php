<?php

namespace Viaativa\Viaroot\Models;

use Pvtl\VoyagerForms\Enquiry as pvtlEnquiry;
use Viaativa\Viaroot\Models\Events\NewEnquiry;

class Enquiry extends pvtlEnquiry
{
   public static function boot(){
       if(class_exists('\Viaativa\ModuleTicket\Events\NewEnquiry')) {
           static::created(function ($ticket) {
               if (!in_array($ticket->form_id, config('module-tickets-config')['tickets']['ignore_form_ticket'])) {
                   event(new \Viaativa\ModuleTicket\Events\NewEnquiry($ticket));
               }
           });
       }
       parent::boot();
   }
}
