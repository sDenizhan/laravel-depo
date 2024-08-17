<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowProductQuantitiesNotification extends Notification
{
    use Queueable;

    public $product;

    /**
     * Create a new notification instance.
     */
    public function __construct($product)
    {
        $this->product = (object) $product;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'repo_name' => $this->product->repo_name,
            'product_name' => $this->product->product_name,
            'product_quantity' => $this->product->product_count,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return [
            'repo_name' => $this->product->repo_name,
            'product_name' => $this->product->product_name,
            'product_quantity' => $this->product->product_count,
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'repo_name' => $this->product->repo_name,
            'product_name' => $this->product->product_name,
            'product_quantity' => $this->product->product_count,
        ];
    }
}
