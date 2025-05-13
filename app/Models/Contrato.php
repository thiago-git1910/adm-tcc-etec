<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    use HasFactory;

    protected $table = 'contracts';

    protected $primaryKey = 'id';


    protected $keyType = 'int'; // ou 'string', dependendo do tipo


    protected $hidden = [
        'remember_token',
    ];

    protected $fillable = ['idSolicitarPedido', 'idContratante', 'idContratado', 'valor', 'data','hora', 'desc_servicoRealizado', 'status','forma_pagamento'];



    public function contratante()
    {
        return $this->belongsTo(Contratante::class, 'idContratante', 'idContratante');
    }

    public function contratado()
    {
        return $this->belongsTo(Profissional::class, 'idContratado', 'idContratado');
    }

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'idSolicitarPedido', 'idSolicitarPedido');
    }
}
