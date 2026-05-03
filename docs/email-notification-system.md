# Email Notification System Documentation (Laravel)

## 1. Overview
The Email Notification System sends automated emails for key order and delivery events.

It is designed to be:
- Event-driven
- Queue-ready
- Safe (email failures do not break order flow)
- Localized (English and Arabic)

### Triggered events
Emails are triggered when:
- An order is placed
- An order status changes (approved/rejected/cancelled)
- A delivery record is created (delivery ready)

## 2. Quick Flow Summary
Typical flow:

`OrderPlaced -> Event -> Listener -> Mailable -> Queue -> User`

Example:
- `OrderPlaced` event is dispatched
- `SendOrderPlacedNotifications` listener handles the event
- `OrderPlacedMail` is queued
- Queue worker sends it to the user

## 3. Email Types

### User emails
1. **Order Placed**
- Sent after a new order is created
- Confirms order receipt and shows order summary

2. **Order Approved**
- Sent when an order becomes approved
- Informs the customer that processing is complete and delivery is available/near-ready

3. **Order Rejected**
- Sent when an order is rejected
- Includes rejection context if available

4. **Order Cancelled**
- Sent when a user/admin cancels the order
- Confirms cancellation

5. **Delivery Ready**
- Sent when a delivery is created for the order item
- Directs user to access delivery from the order page

### Admin emails
1. **New Order Notification**
- Sent to configured admin recipient(s) when a new order is placed
- Includes customer, payment method, and order total details

## 4. Architecture
The system follows Laravel’s event-driven architecture:

1. **Event occurs**
- `OrderPlaced`
- `OrderStatusChanged`
- `DeliveryCreated`

2. **Listener handles event**
- Listener decides which email(s) to send

3. **Mailable is queued**
- Mailables and listeners implement `ShouldQueue`
- Emails are processed asynchronously by queue workers

### Why this architecture
- Keeps controllers/models clean
- Makes notifications easy to extend
- Prevents email sending from slowing down user requests

## 5. File Structure

### `app/Events/`
- `OrderPlaced.php`
- `OrderStatusChanged.php`
- `DeliveryCreated.php`

Defines event payloads for notification flow.

### `app/Listeners/`
- `SendOrderPlacedNotifications.php`
- `SendOrderStatusNotifications.php`
- `SendDeliveryReadyNotification.php`

Consumes events and queues correct mailables.

### `app/Mail/`
- `OrderPlacedMail.php`
- `OrderApprovedMail.php`
- `OrderRejectedMail.php`
- `OrderCancelledMail.php`
- `DeliveryReadyMail.php`
- `AdminNewOrderMail.php`

Each class defines subject and Blade view for one email type.

### `resources/views/emails/`
- `layouts/notification.blade.php` (shared layout)
- `orders/*.blade.php` (user emails)
- `admin/new-order.blade.php` (admin email)
- `orders/partials/order-details.blade.php` (reusable block)

### Translation files
- `lang/en/emails.php`
- `lang/ar/emails.php`

Localized email text and labels.

### Config and environment
- `config/services.php`
  - `services.admin.email`
  - `services.admin.emails`
- `.env`
  - Mail transport and admin recipient settings

## 6. How to Enable Emails
Update `.env` with your mail transport values (example placeholders only):

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=your_smtp_username
MAIL_PASSWORD=your_smtp_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@example.com
MAIL_FROM_NAME="Your App Name"
```

Then clear and rebuild config cache:

```bash
php artisan config:clear
php artisan config:cache
```

## 7. Queue Setup

### Why queue is used
Email sending is asynchronous to avoid blocking checkout/order actions.

### Run worker (development/manual)
```bash
php artisan queue:work
```

### Production recommendation
Use a process manager (for example Supervisor/systemd) to keep workers running continuously and auto-restart on failure/deploy.

## 8. Admin Email Configuration
Use one or both of these in `.env`:

```env
ADMIN_EMAIL=admin@example.com
ADMIN_EMAILS=ops@example.com,sales@example.com,owner@example.com
```

Rules:
- `ADMIN_EMAILS` supports multiple comma-separated recipients
- If `ADMIN_EMAILS` is empty, system falls back to `ADMIN_EMAIL`

## 9. Testing Emails
For safe testing, use log mailer:

```env
MAIL_MAILER=log
```

Run queue worker, trigger events (place/approve/reject/cancel order, create delivery), then check logs:
- `storage/logs/laravel.log`

You should see generated mail activity and notification flow entries.

## 10. Quick Test
Fast manual test:

1. Set:
```env
MAIL_MAILER=log
QUEUE_CONNECTION=database
```

2. Run worker:
```bash
php artisan queue:work
```

3. Place a test order in the app.

4. Check:
- `storage/logs/laravel.log`
- queued jobs processed by worker

Optional Tinker check:
```bash
php artisan tinker
```
Then inspect recent orders or dispatch a test event manually if needed.

## 11. Troubleshooting

### Emails not sending?
- Check `MAIL_MAILER` value in `.env`
- Check SMTP settings if using SMTP
- Ensure queue worker is running
- Check `storage/logs/laravel.log` for mail/queue errors

### Queue not working?
- Ensure `php artisan queue:work` is running
- Confirm `QUEUE_CONNECTION=database`
- Make sure queue tables exist (`php artisan queue:table` then `php artisan migrate` if needed)

## 12. Extending the System
To add a new email:

1. Create a new Mailable in `app/Mail/`
2. Create Blade template in `resources/views/emails/...`
3. Add translation keys in:
- `lang/en/emails.php`
- `lang/ar/emails.php`
4. Trigger from an existing or new event:
- Add event class in `app/Events/` if needed
- Add listener in `app/Listeners/`
- Register mapping in `app/Providers/EventServiceProvider.php`
5. Ensure listener/mailable uses `ShouldQueue`

## 13. Safety Notes
- Email failures are wrapped safely and logged
- Core business operations continue even if mail fails
- Queue-based async sending is strongly recommended
- Avoid synchronous email sending in request lifecycle for production workloads

## Quick Operational Checklist
1. Configure `.env` mail values
2. Configure `ADMIN_EMAIL` / `ADMIN_EMAILS`
3. Run queue worker
4. Keep config cached in production
5. Verify logs or SMTP dashboard for successful sends

This notification system is production-ready once mail credentials and queue workers are properly configured.
