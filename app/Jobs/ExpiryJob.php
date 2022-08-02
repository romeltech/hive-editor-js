<?php

namespace App\Jobs;

use App\Mail\ExpiryMail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ExpiryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Publisher ID
        $publisherData = $this->data;
        $expired = $publisherData['expired'];
        $expire_soon = $publisherData['expire_soon'];
        $receivers = $publisherData['receivers'];   
       
        $subject =  'List of vehicles : Expired/Soon to expire.'; 

        $getEmails =  (object) $receivers;
         
        $details = array('expired' => $expired, 'expire_soon' => $expire_soon);
        $data = array("details" => $details, 'subject' => $subject); 
       
        Mail::to($getEmails)->queue( new ExpiryMail($data) ); 
    }
}
