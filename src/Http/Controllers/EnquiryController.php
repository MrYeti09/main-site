<?php

namespace Viaativa\Viaroot\Http\Controllers;

use Viaativa\Viaroot\Models\Page;
use Pvtl\VoyagerForms\Http\Controllers\EnquiryController as pvtlEnquiryController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Pvtl\VoyagerForms\{
    Form,
    Mail\Enquiry as EnquiryMailable
};
use Pvtl\VoyagerFrontend\Helpers\ClassEvents;
use Viaativa\Viaroot\Models\Enquiry;

class EnquiryController extends pvtlEnquiryController
{
    /**
     * This submit method is triggered by any front-end forms generated
     * with a shortcode - when a user submits the form it will dynamically
     * trigger a series of events that are associated with this specific form.
     *
     * Woah-ho-ho it's magic! Ya'know... never believe it ain't so.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function submit(Request $request)
    {
        $form = Form::findOrFail($request->id);

        // Get $formData and $filesKeys verifying the MIME of files.
        $formDataAndFilesKeys = $this->getFormDataAndFilesKeys($form, $request);
        if($formDataAndFilesKeys instanceof RedirectResponse){
            return $formDataAndFilesKeys;
        }
        list($formData, $filesKeys) = $formDataAndFilesKeys;

        // Check if reCAPTCHA is on & verify
        if (setting('admin.google_recaptcha_site_key')) {
            $this->verifyCaptcha($request);
        }

        // Execute the hook
        if ($form->hook) {
            ClassEvents::executeClass($form->hook, $formData);
        }

        // The recipients
        if(isset($form->usemail))
        {
            if($form->usemail == "on") {
                if (empty($form->mailto)) {
                    $form->mailto = !empty(setting('forms.default_to_email'))
                        ? setting('forms.default_to_email')
                        : 'voyager.forms@mailinator.com';
                }
            }
        } else {
            if (empty($form->mailto)) {
                $form->mailto = !empty(setting('forms.default_to_email'))
                    ? setting('forms.default_to_email')
                    : 'voyager.forms@mailinator.com';
            }
        }

        // The from address
        $form->mailfrom = !empty(setting('forms.default_from_email'))
            ? setting('forms.default_from_email')
            : 'voyager.forms@mailinator.com';

        // The from name (eg. site address)
        $form->mailfromname = !empty(setting('site.title'))
            ? setting('site.title')
            : 'Website';

        // Upload the images files, update $formData to save the image directory and return all the file keys.

        // Save the enquiry to the DB
        $enquiry = Enquiry::create([
            'form_id' => $form->id,
            'data' => $formData,
            'mailto' => $form->mailto,
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'files_keys' => $filesKeys
        ])->save();

        // Debug/Preview the email
        // return (new EnquiryMailable($form, $formData, $filesKeys))->render();

        // Send the email
        if(strlen(env('MAIL_HOST')) and env('MAIL_USERNAME') != null) {
            if (isset($form->usemail)) {
                if ($form->usemail == "on") {
                    Mail::to(array_map('trim', explode(',', $form->mailto)))
                        ->send(new EnquiryMailable($form, $formData, $filesKeys));
                }
            } else {
                Mail::to(array_map('trim', explode(',', $form->mailto)))
                    ->send(new EnquiryMailable($form, $formData, $filesKeys));
            }
        }

        if($form->target != null)
        {
            return redirect(url( \Pvtl\VoyagerPages\Page::where('id',$form->target)->first()->slug ));
        } else {
            return redirect()
                ->back()
                ->with('success', $form->message_success);
        }
    }
}
