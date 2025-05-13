<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Contratante;
use App\Models\Profissional;

class Chat extends Model
{
    protected $keyType = 'string';

    public $incrementing = false;

    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'chat_room_id',
        'user_id',
        'idContratante',
        'idContratado',
        'message',
        'is_read',
        'user_type', // Adicione esta linha
    ];
    protected $connection = "mysql";


    public function contratante()
    {
        return $this->belongsTo(Contratante::class, 'idContratante', 'idContratante');
    }

    public function profissional()
    {
        return $this->belongsTo(Profissional::class, 'idContratado', 'idContratado');
    }

    // Também mantenha o relacionamento com o User, se necessário
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');

    }

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::orderedUuid();
        });
    }
}
