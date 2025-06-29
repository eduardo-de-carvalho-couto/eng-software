<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovimentacaoMeta extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'movimentacao_metas';

    protected $fillable = [
        'meta_id',
        'transacao_id',
        'valor',
        'tipo',
        'data_movimentacao',
        'observacoes',
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'data_movimentacao' => 'date',
        'deleted_at' => 'datetime',
    ];

    // Relacionamentos
    public function meta(): BelongsTo
    {
        return $this->belongsTo(MetaFinanceira::class, 'meta_id');
    }

    public function transacao(): BelongsTo
    {
        return $this->belongsTo(Transacao::class, 'transacao_id');
    }

    // Accessors
    public function getTipoBadgeColorAttribute(): string
    {
        return match ($this->tipo) {
            'deposito' => 'success',
            'retirada' => 'danger',
            default => 'gray',
        };
    }

    public function getTipoLabelAttribute(): string
    {
        return match ($this->tipo) {
            'deposito' => 'Depósito',
            'retirada' => 'Retirada',
            default => 'N/A',
        };
    }

    public function getValorFormatadoAttribute(): string
    {
        $sinal = $this->tipo === 'deposito' ? '+' : '-';
        return $sinal . ' R$ ' . number_format($this->valor, 2, ',', '.');
    }

    // Scopes
    public function scopeDepositos($query)
    {
        return $query->where('tipo', 'deposito');
    }

    public function scopeRetiradas($query)
    {
        return $query->where('tipo', 'retirada');
    }

    public function scopePorMeta($query, $metaId)
    {
        return $query->where('meta_id', $metaId);
    }

    public function scopePorPeriodo($query, $dataInicio, $dataFim)
    {
        return $query->whereBetween('data_movimentacao', [$dataInicio, $dataFim]);
    }
}