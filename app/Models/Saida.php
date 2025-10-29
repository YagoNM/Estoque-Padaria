<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Saida extends Model
{
    use HasFactory, SoftDeletes; // Ativar o SoftDeletes

    protected $fillable = [
        'produto_id',
        'quantidade',
        'data',
        'motivo',
        'observacao',
    ];

    protected $casts = [
        'data' => 'date',
    ];

    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class);
    }
}