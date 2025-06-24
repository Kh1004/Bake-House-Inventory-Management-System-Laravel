<?php

namespace App\Notifications;

use App\Models\Ingredient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $ingredient;

    public function __construct(Ingredient $ingredient)
    {
        $this->ingredient = $ingredient;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Low Stock Alert: ' . $this->ingredient->name)
            ->line('The ingredient ' . $this->ingredient->name . ' is running low on stock.')
            ->line('Current Stock: ' . $this->ingredient->current_stock . ' ' . $this->ingredient->unit_of_measure)
            ->line('Minimum Required: ' . $this->ingredient->minimum_stock . ' ' . $this->ingredient->unit_of_measure)
            ->action('View Ingredient', route('ingredients.show', $this->ingredient->id));
    }

    public function toArray($notifiable)
    {
        return [
            'ingredient_id' => $this->ingredient->id,
            'ingredient_name' => $this->ingredient->name,
            'current_stock' => $this->ingredient->current_stock,
            'minimum_stock' => $this->ingredient->minimum_stock,
            'unit_of_measure' => $this->ingredient->unit_of_measure,
            'message' => 'Low stock alert for ' . $this->ingredient->name,
        ];
    }
}
