<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RequestCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $requestId;
    public $requestType;
    public $patientName;

    public function __construct($requestId, $requestType, $patientName)
    {
        $this->requestId = $requestId;
        $this->requestType = $requestType;
        $this->patientName = $patientName;
    }

    public function broadcastOn()
    {
        return new Channel('requests-channel');
    }

    public function broadcastAs()
    {
        return 'RequestCreated';
    }
}