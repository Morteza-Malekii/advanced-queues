# Advanced Queues in Laravel 12 (Practice Project)

A hands-on project exploring **advanced job/queue patterns** in **Laravel 12** with the **database** queue driver.  
This repo demonstrates real-world patterns like **delayed dispatch**, **self-rescheduling jobs (`release`)**, **time-boxed retries (`retryUntil`)**, **`failed()` hooks**, and **webhook delivery** — all structured for interview-ready, resume-level clarity.

> ⚙️ Current queue connection: **database** (via `jobs` & `failed_jobs` tables).  
> 🔭 Optional roadmap includes Redis/Horizon (not required to run this project now).

---

## Why this project?

- Demonstrates **production-oriented queue design** in Laravel 12.
- Shows **clean commit history** and incremental delivery of queue features.
- Acts as a **reference** for patterns like `failed()`, `afterCommit`, `backoff`, `release`, and model-level helpers used by jobs.

---

## Key Features (mapped to commits)

- **Webhook Job & Dispatching**
  - `SendWebHook` job with POST delivery and error handling.
  - Dispatched from `CheckoutController`  
    **Commit:** `feat(dispatch): dispatch sendwebhook in checkoutController`
  - `failed()` hook to capture final failures  
    **Commit:** `feat(WebHook): add failed() to sendwebhook job`

- **Order Lifecycle Utilities**
  - `OrderStatus` enum for lifecycle states  
    **Commit:** `feat(order): add OrderStatus enum for tracking order lifecycle`
  - `olderThan()` time helper on `Order`  
    **Commit:** `feat(order): add olderThan() helper to Order model`
  - `markAsCompleted()` domain method  
    **Commit:** `feat(order): add markAsCompleted method`

- **Monitoring Pending Orders**
  - `MonitorPendingOrder` job for periodic checks & conditional cancellation  
    **Commit:** `feat(jobs): add monitorpendingorder job`  
    Demonstrates controlled cadence with `release()`.

- **Mailables**
  - `DiscountEmail` + Blade view  
    **Commit:** `feat(mail): add discountEmail and its view`
  - Earlier verification mail flow is kept in history (the project currently runs on **database** queue).

- **Controllers & Routes**
  - `CheckoutController` and related routes  
    **Commits:**  
    `feat(controller): add checkout controller`  
    `feat(route): add new route for chechoutController`

- **Fixes/Polish**
  - Migration typo fix  
    **Commit:** `fix(migration): correct typo in User_id`
  - Targeted bug fixes in jobs/controllers  
    **Commits:**  
    `fix(controller): bug fixed`  
    `fix(jobs): fix typo in sendverificationemailjob handle method`

---

## Architecture at a Glance

```text
HTTP Request (Checkout)
        │
        ▼
CheckoutController
        │  dispatch()
        ▼
SendWebHook Job  ──► Http::post(...)  ──► Receiver
        │                ▲
        │                └─ retry/release/backoff on failure
        │
        └─► failed(Throwable $e) hook on final failure

(Periodic)
MonitorPendingOrder ──► checks Order status/age
        │
        ├─ olderThan() helper
        └─ cancels or reschedules via release()
```

---

## Patterns demonstrated

- **Database queue** driver (no Redis required to run)
- **Self-rescheduling** with `release(N seconds)`
- **Time-boxed retries** with a fixed deadline via `retryUntil()`
- **Backoff strategies** for error retries (optional)
- **`failed()` hook** for final failure logging/alerts
- **Model serialization** (queue-friendly) & domain helpers (`olderThan`, `markAsCompleted`)
- **Controller-triggered dispatch** for web-hooks and order flows

---

## Getting Started

### Prerequisites
- PHP 8.2+
- Composer
- MySQL/MariaDB (or SQLite for quick run)
- Laravel 12

### Installation
```bash
git clone https://github.com/Morteza-Malekii/advanced-queues.git
cd advanced-queues
composer install
cp .env.example .env
php artisan key:generate
```

### Configure `.env`
Use **database** queue driver:
```env
QUEUE_CONNECTION=database
```

(Optional) Mail driver for development:
```env
MAIL_MAILER=log        # logs emails to storage/logs/laravel.log
# or configure a real SMTP/Mailtrap if needed
```

### Migrate
```bash
php artisan migrate       # creates users/orders/... + jobs/failed_jobs
```

### Run the worker
```bash
php artisan queue:work database
# After code changes:
php artisan queue:restart
```

---

## Usage (Quick Tour)

### 1) Trigger checkout → dispatch webhook
- Call the checkout route (see `routes` and `CheckoutController`).
- The controller **dispatches `SendWebHook`** to the **database** queue.
- The job posts JSON to the configured URL and handles failures.

### 2) Monitor pending orders (periodic)
- `MonitorPendingOrder` shows **`release()` cadence** (e.g., every N seconds).
- Uses `Order::olderThan()` and `OrderStatus` to **cancel or finalize** gracefully.

### 3) Observe failures
- Final failures land in `failed_jobs` and trigger job’s **`failed(Throwable $e)`** hook.
- Check logs in `storage/logs/laravel.log`.

---

## Notable Code Patterns (Laravel 12)

### `failed()` hook
```php
public function failed(\Throwable $e): void
{
    // Log / notify / cleanup
}
```

### Self-reschedule with `release`
```php
$this->release(60); // re-queue the job to run in 60 seconds
```

### Time-boxed retries with a fixed deadline
```php
public \DateTimeInterface $deadline;

public function __construct()
{
    $this->deadline = now()->addMinutes(15); // frozen at dispatch time
}

public function retryUntil(): \DateTimeInterface
{
    return $this->deadline;
}
```

> Tip: Prefer a **fixed** deadline (set in `__construct`) rather than returning `now()->addX()` inside `retryUntil()` each run.

---

## Testing & Debugging

```bash
# Run worker
php artisan queue:work database

# View failed jobs
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry all   # or use a specific ID

# Flush failed jobs
php artisan queue:flush

# Restart workers after code/ENV changes
php artisan queue:restart
```

---

## Roadmap

- [ ] Optional Redis + Horizon dashboards  
- [ ] Exponential `backoff()` strategies  
- [ ] Idempotency keys for webhook requests  
- [ ] More end-to-end tests

---

## License

MIT — use freely for learning and reference.

---

## Author

**Morteza** — exploring production-grade queue patterns in Laravel 12.  
Pull requests & suggestions are welcome!
