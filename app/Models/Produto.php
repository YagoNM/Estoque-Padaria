<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produto extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'nome',   
        'categoria',    
        'unidade',        
        'quantidade',      
        'min_quantidade',   
        'data_validade',    
        'fornecedor_id',    
        'preco_custo',      
    ];

    protected $casts = [
        'data_validade' => 'date', 
    ];
    
    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class);
    }
}