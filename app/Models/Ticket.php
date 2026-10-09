<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    public const STATUS_NEW = 'Nowe';
    public const STATUS_IN_PROGRESS = 'W realizacji';
    public const STATUS_WAITING = 'Oczekuje na odpowiedź';
    public const STATUS_RESOLVED = 'Rozwiązane';
    public const STATUS_CLOSED = 'Zamknięte';

    public const PRIORITY_LOW = 'Niski';
    public const PRIORITY_MEDIUM = 'Średni';
    public const PRIORITY_HIGH = 'Wysoki';
    public const PRIORITY_CRITICAL = 'Krytyczny';

    protected $fillable = [
        'user_id',
        'agent_id',
        'category_id',
        'title',
        'description',
        'priority',
        'status',
        'closed_at',
    ];

    protected function casts(): array
    {
        return [
            'closed_at' => 'datetime',
        ];
    }

    public static function statuses(): array
    {
        return [
            self::STATUS_NEW,
            self::STATUS_IN_PROGRESS,
            self::STATUS_WAITING,
            self::STATUS_RESOLVED,
            self::STATUS_CLOSED,
        ];
    }

    public static function priorities(): array
    {
        return [
            self::PRIORITY_LOW,
            self::PRIORITY_MEDIUM,
            self::PRIORITY_HIGH,
            self::PRIORITY_CRITICAL,
        ];
    }

    // autor zgłoszenia
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // technik obsługujący zgłoszenie
    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TicketComment::class);
    }

    public function history(): HasMany
    {
        return $this->hasMany(TicketHistory::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }
}