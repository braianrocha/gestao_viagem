<?php
namespace App\Notifications;

use App\Models\TravelOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TravelOrderStatusChanged extends Notification
{
    use Queueable;

    public function __construct(private TravelOrder $order)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'destination' => $this->order->destination,
            'status' => $this->order->status,
            'message' => sprintf('Seu pedido #%d para %s foi %s', $this->order->id, $this->order->destination, $this->order->status)
        ];
    }
}
