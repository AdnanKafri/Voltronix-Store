<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * ✅ Sitemap Generated Notification
 * Sends email notification when sitemap is automatically regenerated
 */
class SitemapGenerated extends Notification implements ShouldQueue
{
    use Queueable;

    public array $stats;
    public bool $success;
    public ?string $error;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $stats, bool $success = true, ?string $error = null)
    {
        $this->stats = $stats;
        $this->success = $success;
        $this->error = $error;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        if ($this->success) {
            return $this->successMail();
        }

        return $this->failureMail();
    }

    /**
     * Success email template
     */
    private function successMail(): MailMessage
    {
        return (new MailMessage)
            ->subject('✅ Sitemap Generated Successfully - Voltronix Digital Store')
            ->greeting('Sitemap Generation Complete')
            ->line('The automated sitemap generation has completed successfully for Voltronix Digital Store.')
            ->line('**Generation Statistics:**')
            ->line('• Total URLs: ' . $this->stats['total_urls'])
            ->line('• Static Pages: ' . $this->stats['static_pages'])
            ->line('• Categories: ' . $this->stats['categories'])
            ->line('• Products: ' . $this->stats['products'])
            ->line('• Locales: ' . implode(', ', $this->stats['locales']))
            ->line('• File Size: ' . $this->stats['file_size'])
            ->line('• Execution Time: ' . $this->stats['execution_time_ms'] . 'ms')
            ->line('The sitemap has been saved to `/public/sitemap.xml` and is ready for search engine crawling.')
            ->action('View Sitemap', url('/sitemap.xml'))
            ->line('This automated process helps maintain fresh SEO data for better search engine visibility.');
    }

    /**
     * Failure email template
     */
    private function failureMail(): MailMessage
    {
        return (new MailMessage)
            ->subject('❌ Sitemap Generation Failed - Voltronix Digital Store')
            ->greeting('Sitemap Generation Error')
            ->error()
            ->line('The automated sitemap generation has failed for Voltronix Digital Store.')
            ->line('**Error Details:**')
            ->line($this->error ?? 'Unknown error occurred during generation.')
            ->line('**Recommended Actions:**')
            ->line('• Check the Laravel logs for detailed error information')
            ->line('• Verify database connectivity and product/category data')
            ->line('• Try running manual generation: `php artisan sitemap:generate --force`')
            ->line('• Contact the development team if the issue persists')
            ->action('Check Logs', url('/admin'))
            ->line('Please resolve this issue to maintain optimal SEO performance.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'sitemap_generation',
            'success' => $this->success,
            'stats' => $this->stats,
            'error' => $this->error,
            'generated_at' => now()->toDateTimeString()
        ];
    }
}
