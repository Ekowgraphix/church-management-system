<?php

namespace App\Mail;

use App\Models\EmailCampaign;
use App\Models\Member;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CampaignEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $campaign;
    public $member;

    public function __construct(EmailCampaign $campaign, Member $member = null)
    {
        $this->campaign = $campaign;
        $this->member = $member;
    }

    public function build()
    {
        return $this->subject($this->campaign->subject)
            ->view('emails.campaign')
            ->with([
                'content' => $this->campaign->content,
                'memberName' => $this->member ? $this->member->full_name : 'Member',
            ]);
    }
}
