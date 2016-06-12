<?php

namespace App\Listeners;

use App\Events\ComplainTechnicalAction;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class EmailComplainTechnicalAction
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ComplainTechnicalAction  $event
     * @return void
     */
    public function handle(ComplainTechnicalAction $event)
    {
        //dapatkan nama pengadu

        $complain_from = $event->complain->user->name;

        //dapatkan emel pengadu

        $complain_from_email = $event->complain->user->email;

        //dapatkan maklumat complain (mesej, tarikh, dll)

        $action_comment = $event->complain->action_comment;

        //dapatkan status complain

        $complain_status = $event->complain->complain_status->description;

        //send email to ICT helpdesk

        $helpdesk_email = 'cyberflyx@gmail.com';

        if ($event->complain->complain_status_id==3)
        {
            $email_view = 'email.complain_technical_action_sahkanP';
            $subject = 'Aduan telah bertukar status SAHKAN P';
            $email_to = $complain_from_email;
            $email_to_name = $complain_from;
        }

        $data = [
            'complain_from'=>$complain_from,
            'complain_from_email'=>$complain_from_email,
            'helpdesk_email'=>$helpdesk_email,
            'complain_status'=>$complain_status,
            'action_comment'=>$action_comment,
        ];

        if ($event->complain->complain_status_id>2)
        {
            Mail::queue($email_view, $data, function ($message) use ($data,$subject,$email_to,$email_to_name) {

                $message->from($data['helpdesk_email'], 'ICT Helpdesk');

                $message->to($email_to, $email_to_name)->subject($subject);
            });
        }
    }
}
