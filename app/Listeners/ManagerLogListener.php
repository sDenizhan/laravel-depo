<?php

namespace App\Listeners;

use App\Events\ManagerLogEvent;
use App\Models\Logs;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ManagerLogListener
{
    public function handle(ManagerLogEvent $event): void
    {
        $data = [
            'user_id' => auth()->id() ?? 0,
            'action' => 'manager_log',
            'data' => $event->event,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ];
        Logs::create($data);
    }
}
