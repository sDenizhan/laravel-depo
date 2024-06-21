<?php

namespace App\Listeners;

use App\Events\LogInventoryAdded;
use App\Models\Logs;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ListenerLogInventoryAdded
{
    /**
     * Handle the event.
     */
    public function handle(LogInventoryAdded $event): void
    {
        $data = [
            'user_id' => auth()->id() ?? 0,
            'action' => 'inventory_added',
            'data' => $event->event->toArray(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ];
        Logs::create($data);
    }
}
