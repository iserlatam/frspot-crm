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
                        ->options(Helpers::getEstatusOptions())
                        ->required()
                        ->disabled(function ($livewire) {
                            if ($livewire instanceof SeguimientosRelationManager) {
                                $owner = $livewire->getOwnerRecord();
                                if($owner->cliente->contador_ediciones > 0)
                                return true;
                            }

                            return null;
                        })
                        ->default(function ($livewire) {
                            if ($livewire instanceof SeguimientosRelationManager) {
                                $owner = $livewire->getOwnerRecord();
                                return $owner->cliente?->estado_cliente;
                            }

                            return null;
                        }),
                    Forms\Components\Select::make('fase')
                        ->label('Fase')
                        ->options(Helpers::getFaseOptions())
                        ->required()
                        ->helperText(function ($livewire){
                            if ($livewire instanceof SeguimientosRelationManager) {
                                $owner = $livewire->getOwnerRecord();

                                return 'fase actual: '.$owner->cliente->fase_cliente;
                            }
                            return null;
                        })
                        ->default(null),
                    Forms\Components\TextInput::make('contador_ediciones')
                        ->disabled()
                        ->default(fn($livewire) => $livewire->ownerRecord->cliente?->contador_ediciones ?? 0)
                        ->visible(function($livewire){
                            if ($livewire instanceof SeguimientosRelationManager) {
                                $owner = $livewire->getOwnerRecord();
                                if($owner->cliente->contador_ediciones > 0)
                                return true;
                            }
                            return false;
                        })
                        ->label('Contador de ediciones'),
                ]),
            Forms\Components\RichEditor::make('descripcion')
                ->columnSpanFull()
                ->required(),
            Forms\Components\Select::make('user_id')
                ->relationship('userWithRoleCliente', 'name', modifyQueryUsing: function ($query, $livewire) {

                    if ($livewire instanceof SeguimientosRelationManager) {
                        $query->where('id', $livewire->ownerRecord->id);
                    }

                    if (Helpers::isAsesor()) {
                        $query->whereHas('asignacion', function ($query) {
                            $query->where('asesor_id', auth()->user()->asesor->id);
                        });
                    }
                })
                ->getOptionLabelFromRecordUsing(fn(Model $record) => $record->name)
                ->label('Cliente')
                ->searchable()
                ->required()
                ->default(fn ($livewire) => $livewire instanceof SeguimientosRelationManager ? $livewire->ownerRecord->id : null),
            Forms\Components\TextInput::make('asesor_id')
                ->visible(function () {
                    return !Helpers::isSuperAdmin();
                })
                ->label('Asesor')
                ->default(auth()->user()?->asesor->id ?? null)
                ->readOnly(),
            Forms\Components\Select::make('asesor_id')
                ->visible(function () {
                    return Helpers::isSuperAdmin();
                })
                ->relationship("asesor", 'id') // Define la relación y la clave foránea
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
