<?php

namespace Workdo\Taskly\Emails;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Workdo\Taskly\Entities\Project;

class ShareProjectToClient extends Mailable
{
    use Queueable, SerializesModels;
    public $client;
    public $project;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $client,Project $project)
    {
        $this->client = $client;
        $this->project = $project;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('taskly::email.share')->subject('New Project Share - '.env('APP_NAME'))->with('user,project', $this->client,$this->project);
    }
}
