<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ChatRoom;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Servico;


class Profissional extends Authenticatable
{
    use HasApiTokens,  Notifiable, HasFactory;

    protected $table = 'tbcontratado';

    protected $primaryKey = 'idContratado';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'nomeContratado',
        'sobrenomeContratado',
        'cpfContratado',
        'emailContratado',
        'telefoneContratado',
        'password',
        'profissaoContratado',
        'descContratado',
        'nascContratado',
        'ruaContratado',
        'cepContratado',
        'regiaoContratado',
        'numCasaContratado',
        'complementoContratado',
        'bairroContratado',
        'ufContratado',
        'cidadeContratado',
        'imagemContratado',
        'is_suspended',
        'valorTotalRecebido',
        'portifilioPro1',
        'portifilioPro2',
        'portifilioPro3',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public $timestamps = true;


    public function getAuthPassword()
    {
        return $this->password;
    }

    public function canJoinRoom($roomId)
    {
        $granted = false;
        $chatRoom = ChatRoom::findOrFail($roomId);
        $contratados = explode(':', $chatRoom->participant);

        foreach ($contratados as $idContratado) {
            if ($this->idContratado == $idContratado) {
                $granted = true;
            }
        }

        return $granted;
    }
    public function isSuspended()
    {
        return $this->is_suspended;
    }

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $model->idContratado = Str::orderedUuid();
        });
    }

    public function servicos()
    {
        return $this->belongsToMany(Servico::class, 'profissional_servico', 'idContratado', 'idServicos');
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'idContratado', 'idContratado');
    }
    public function contratos()
    {
        return $this->hasMany(Contrato::class, 'idContratado', 'idContratado');
    }

}
