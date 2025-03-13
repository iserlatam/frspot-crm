<?php

namespace App\Models;

use App\Filament\Admin\Resources\UserResource\RelationManagers\SeguimientosRelationManager;
use App\Helpers\Helpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use PhpParser\Node\Expr\Instanceof_;

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
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('estado')
                        ->label('Estado')
                        ->options([
                            'New' => 'New',
                            'No answer' => 'No answer',
                            'Answer' => 'Answer',
                            'Call again' => 'Call Again',
                            'Potential' => 'Potential',
                            'Low potential' => 'Low Potential',
                            'Declined' => 'Declined',
                            'Under age' => 'Under Age',
                            'Active' => 'Active',
                            'No interested' => 'No interested',
                            'Recovery'  => 'Recovery',
                            'Invalid number' => 'Invalid number',
                            'Stateless  '  => 'Stateless',
                            'interested'  => 'interested',
                        ])
                        ->required()
                        ->default(function ($livewire) {
                            if ($livewire instanceof SeguimientosRelationManager) {
                                $owner = $livewire->getOwnerRecord();
                                return $owner->cliente?->estado_cliente;
                            }
                            
                            return null;
                        }),
                    Forms\Components\Select::make('fase')
                        ->label('Fase')
                        ->options([
                            'New' => 'New',
                            'No answer' => 'No answer',
                            'Answer' => 'Answer',
                            'Call again' => 'Call Again',
                            'Potential' => 'Potential',
                            'Low potential' => 'Low Potential',
                            'Declined' => 'Declined',
                            'Under age' => 'Under Age',
                            'Active' => 'Active',
                            'No interested' => 'No interested',
                            'Recovery'  => 'Recovery',
                            'Invalid number' => 'Invalid number',
                            'Stateless'  => 'Stateless',
                            'Interested'  => 'Interested',
                        ])
                        ->required()
                        ->default(null),                 
                ]),
            Forms\Components\RichEditor::make('descripcion')
                ->columnSpanFull()
                ->required(),
                Forms\Components\Select::make('user_id')
                ->relationship('userWithRoleCliente', 'name', modifyQueryUsing: function ($query, $livewire) {
                    if (Helpers::isAsesor()) {
                        $query->whereHas('asignacion', function ($query) use ($livewire) {
                            $query->where('asesor_id', auth()->user()->asesor->id);
                            if ($livewire instanceof SeguimientosRelationManager) {
                                $query->where('id', $livewire->ownerRecord->id);
                            };
                        });
                    } else {
                        $query->whereHas('asignacion', function ($query) use ($livewire) {
                            if ($livewire instanceof SeguimientosRelationManager) {
                                $query->where('id', $livewire->ownerRecord->id);
                            };
                        });
                    }
                })
                ->getOptionLabelFromRecordUsing(fn(Model $record) => $record->name)
                ->label('Cliente')
                ->preload()
                ->searchable()
                ->required()
                ->default(fn ($livewire) => $livewire instanceof SeguimientosRelationManager ? $livewire->ownerRecord->id : null),
                // ->disabled(fn($livewire) => $livewire instanceof SeguimientosRelationManager),          
            Forms\Components\TextInput::make('asesor_id')
                ->visible(function () {
                    return !Helpers::isOwner();
                })
                ->label('Asesor')
                ->default(function () {
                    if (Helpers::isAsesor()) {
                        return auth()->user()->asesor->id;
                    }
                })
                ->readOnly(),
            Forms\Components\Select::make('asesor_id')
                ->visible(function () {
                    return Helpers::isOwner();
                })
                ->relationship("asesor", 'id', function($query){
                    if(Helpers::isAsesor())
                        $query->where('id',auth()->user()->asesor->id);
                }) // Define la relación y la clave foránea
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
