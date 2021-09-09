<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ContactUs extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $requestObj;
    
    public function __construct(Request $request)
    {
        $this->requestObj = $request->all();
        Log::info($this->requestObj);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Contact Us')->view('emails.contact-us')
            ->with([
                'name' => $this->requestObj['name'],
                'phone' => $this->requestObj['phone'],
                'description' => $this->requestObj['description'],
            ]);
    }
}
