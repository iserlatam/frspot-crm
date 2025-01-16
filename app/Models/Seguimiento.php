<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Filament\Forms;

class Seguimiento extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'descripcion',
        'estado',
        'fase',
        'etiqueta',
        'user_id',
        'asesor_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'asesor_id' => 'integer',
    ];

    public static function getForm() {
        return [
            Forms\Components\Grid::make()
                ->columns(3)
                ->schema([
                    Forms\Components\TextInput::make('estado')
                        ->maxLength(20)
                        ->default(null),
                    Forms\Components\TextInput::make('fase')
                        ->label('Origen')
                        ->maxLength(50)
                        ->default(null),
                    Forms\Components\TextInput::make('etiqueta')
                        ->maxLength(50)
                        ->default(null),
                ]),
            Forms\Components\RichEditor::make('descripcion')
                ->columnSpanFull()
                ->required(),
            Forms\Components\Select::make('user_id')
                ->relationship('userWithRoleCliente', 'name')
                ->label('Cliente')
                ->preload()
                ->searchable()
                ->required()
                ->default(null),
            Forms\Components\Select::make('asesor_id')
                ->relationship('asesor', 'id') // Define la relación y la clave foránea
                ->getOptionLabelFromRecordUsing(fn(Model $record) => $record->user->name)
                ->preload()
                ->searchable()
                ->required()
                ->default(null),
            ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function userWithRoleCliente(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id', 'id')
            ->whereHas('roles', function ($query) {
                $query->where('name', 'cliente');
            });
    }

    public function asesor(): BelongsTo
    {
        return $this->belongsTo(Asesor::class);
    }
}
