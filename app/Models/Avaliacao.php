<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class Avaliacao extends Model
{
    use HasFactory;

    protected $table = 'tbavaliacao';

    protected $fillable = [
        'idContratado',
        'idContratante',
        'ratingAvaliacao',
        'descavaliacao',
        'imagem',
        'nome',
    ];
    public $timestamps = false;


    public function contratante()
    {
        return $this->belongsTo(Contratante::class, 'idContratante', 'idContratante');
    }

    public function contratado()
    {
        return $this->belongsTo(Profissional::class, 'idContratado', 'idContratado');
    }



}
