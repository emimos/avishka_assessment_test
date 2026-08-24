<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_number',
        'customer_name',
        'email',
        'phone_number',
        'problem_description',
        'status',
        'is_opened',
        'opened_at',
    ];

    protected $casts = [
        'is_opened' => 'boolean',
        'opened_at' => 'datetime',
    ];

    public function replies(): HasMany
    {
        return $this->hasMany(TicketReply::class)->orderBy('created_at', 'asc');
    }

    public static function generateReferenceNumber(): string
    {
        do {
            $part1 = strtoupper(Str::random(4));
            $part2 = strtoupper(Str::random(4));
            $reference = "TK-{$part1}-{$part2}";
        } while (static::where('reference_number', $reference)->exists());

        return $reference;
    }
}
