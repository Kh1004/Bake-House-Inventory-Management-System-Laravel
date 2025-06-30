<?php

namespace App\Notifications;

use App\Models\AlertConfiguration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\NexmoMessage;

class AlertTriggered extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The alert configuration
     *
     * @var AlertConfiguration
     */
    public $config;

    /**
     * The alert data
     *
     * @var array
     */
    public $data;

    /**
     * The notification channel
     *
     * @var string
     */
    public $channel;

    /**
     * Create a new notification instance.
     */
    public function __construct(AlertConfiguration $config, array $data, string $channel)
    {
        $this->config = $config;
        $this->data = $data;
        $this->channel = $channel;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [$this->channel];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $message = new MailMessage;
        
        // Set subject based on alert type
        $subject = $this->getSubject();
        $message->subject($subject);
        
        // Add greeting
        $message->greeting("Hello {$notifiable->name},");
        
        // Add main content
        $message->line($this->getMessage());
        
        // Add action button if applicable
        if ($url = $this->getActionUrl()) {
            $message->action('View Details', $url);
        }
        
        // Add custom message if set
        if (!empty($this->config->custom_message)) {
            $message->line("\n" . $this->config->custom_message);
        }
        
        return $message;
    }
    
    /**
     * Get the SMS representation of the notification.
     */
    public function toNexmo($notifiable)
    {
        return (new NexmoMessage())
            ->content($this->getSmsMessage());
    }
    
    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'type' => $this->config->alert_type,
            'message' => $this->getMessage(),
            'data' => $this->data,
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => $this->config->alert_type,
            'message' => $this->getMessage(),
            'data' => $this->data,
            'timestamp' => now()->toDateTimeString(),
        ];
    }
    
    /**
     * Get the notification subject based on alert type
     */
    protected function getSubject(): string
    {
        $type = $this->config->alert_type;
        
        $subjects = [
            'low_stock' => 'Low Stock Alert',
            'expiry_alert' => 'Expiry Alert',
            'price_change' => 'Price Change Alert',
        ];
        
        return $subjects[$type] ?? 'Alert Notification';
    }
    
    /**
     * Get the notification message based on alert type
     */
    protected function getMessage(): string
    {
        $type = $this->config->alert_type;
        $data = $this->data;
        
        switch ($type) {
            case 'low_stock':
                $level = $data['level'] === 'critical' ? 'CRITICAL' : 'Warning';
                return "{$level}: Low stock alert for {$data['ingredient']}. " .
                       "Current: {$data['current_quantity']} {$data['unit']}, " .
                       "Threshold: {$data['threshold']} {$data['unit']}";
                
            case 'expiry_alert':
                return "Expiry Alert: {$data['ingredient']} (Batch: {$data['batch_number']}) " .
                       "will expire in {$data['days_until_expiry']} days on {$data['expiry_date']}.";
                
            case 'price_change':
                return "Price Change Alert: {$data['ingredient']} price has changed by {$data['percentage']}% " .
                       "in the last {$data['time_frame']} hours.";
                
            default:
                return 'Alert: Please check your dashboard for details.';
        }
    }
    
    /**
     * Get the SMS message
     */
    protected function getSmsMessage(): string
    {
        // Shorter version for SMS
        $message = $this->getMessage();
        return substr($message, 0, 140); // Ensure it fits in one SMS
    }
    
    /**
     * Get the action URL for the notification
     */
    protected function getActionUrl(): ?string
    {
        // Return URL to relevant page based on alert type
        $type = $this->config->alert_type;
        
        $routes = [
            'low_stock' => route('ingredients.index') . '?filter=low_stock',
            'expiry_alert' => route('ingredients.index') . '?filter=expiring_soon',
            'price_change' => route('reports.price-history'),
        ];
        
        return $routes[$type] ?? null;
    }
}
