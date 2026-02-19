<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 *  indica que este modelo es el usuario autenticable del sistema (login, tokens, etc). 
 *  Los modelos normales extienden solo Model.
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    /** en lugar de borrar el registro de la BD, agrega un campo deleted_at. 
     * El registro sigue existiendo pero Laravel lo ignora en las queries.  */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'lastname',
        'username',
        'email',
        'password',
        'dui',
        'phone',
        'birthdate',
        'hiring_date',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     * 
     * campos que se omiten cuando retornas JSON. Por eso nunca ves el password en las respuestas.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     * Le dice a Laravel cómo transformar un valor al leerlo o escribirlo:
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime', // escribe la fecha y hora
            'password' => 'hashed', // la guarda hasheada, no en texto plano
            'birthdate' => 'date', // solo la fecha, sin hora
            'hiring_date' => 'date', // solo la fecha, sin hora
        ];
    }
}
