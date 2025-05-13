<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Profissional;

class Servico extends Model
{
    use HasFactory;


    protected $table = 'tbservicos';

    // Especifica a chave primária da tabela
    protected $primaryKey = 'idServicos';

    // Indica que a chave primária não é auto-incrementável, se for o caso
    public $incrementing = false;

    // Especifica o tipo da chave primária
    protected $keyType = 'int'; // ou 'string', dependendo do tipo

    // Se os timestamps não estão sendo usados
    public $timestamps = false;

    protected $fillable = [
        'nomeServicos',
        'descServicos',

    ];

    public function profissionais()
    {
        return $this->belongsToMany(Profissional::class, 'profissional_servico', 'idServicos', 'idContratado');
    }
}
