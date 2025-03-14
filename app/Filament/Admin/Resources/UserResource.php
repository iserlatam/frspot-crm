<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Filament\Admin\Resources\UserResource\RelationManagers;
use App\Filament\Admin\Resources\UserResource\RelationManagers\AsignacionRelationManager;
use App\Filament\Admin\Resources\UserResource\RelationManagers\CuentaClienteRelationManager;
use App\Filament\Admin\Resources\UserResource\RelationManagers\SeguimientosRelationManager;
use App\Filament\Admin\Resources\UserResource\RelationManagers\UserMovimientosRelationManager;
use App\Helpers\Helpers;
use App\Models\User;
use Attribute;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use NunoMaduro\Collision\Adapters\Phpunit\State;

use function Laravel\Prompts\search;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $activeNavigationIcon = 'heroicon-s-users';

    protected static ?string $navigationGroup = 'Control de usuarios';

    protected static ?string $modelLabel = 'Usuarios';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(User::getForm());
    }

    public static function table(Table $table): Table {
        return $table
           
            ->query(function () {
                $query = User::query();

                $query->whereHas('roles', function ($query) {
                    $query->where('name', 'cliente');
                });
                
                if (Helpers::isAsesor()) {
                    $query->whereHas('asignacion', function ($query) {
                        $query->whereHas('asesor.user', function ($query) {
                            $query->where('name', auth()->user()->name);
                        });
                    });
                }
            
                return $query;
            })
            ->defaultSort('cliente.updated_at', fn() => Helpers::isSuperAdmin() ? 'desc' : 'asc')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('usuario')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->copyable()
                    ->limit(fn()=>Helpers::isSuperAdmin() ? 6 : 15 )
                    ->tooltip(function ($record) : ?string {
                        return $record->name;
                    }),
                Tables\Columns\TextColumn::make('email')
                    // ->limit(10)
                    ->formatStateUsing(fn($record) => Helpers::isSuperAdmin() ? $record->email : '*****@*****.***')
                    ->copyable()
                    ->copyableState(fn($record) =>$record->email)
                    ->tooltip('Haga click para copiar')
                    ->limit(fn()=>Helpers::isSuperAdmin() ? 6 : 15 )
                    ->searchable(),                   
                Tables\Columns\TextColumn::make('cliente.celular')
                     ->label('Celular')
                    ->formatStateUsing(fn($record)=>Helpers::isSuperAdmin()?$record->cliente?->celular:'********')
                    ->tooltip('Haga click para copiar')
                    ->copyable()
                    ->copyableState(fn($record) => $record->cliente?->celular)
                    ->searchable(),
                Tables\Columns\TextColumn::make('cliente.pais')
                    ->label('pais')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cliente.estado_cliente')
                    ->label('Estado cliente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cliente.fase_cliente')
                    ->label('Fase actual')
                    ->searchable(),
                Tables\Columns\TextColumn::make('asignacion.asesor.user.name')
                    ->label('Asesor asignado')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cliente.origenes')
                    ->label('Origen cliente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cliente.updated_at')
                    ->date('M d/Y h:i A')
                    ->label('ultima actualización')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->date('M d/y h:i A')
                    ->label('Fecha de creacion')
                    ->sortable(),
                Tables\Columns\TextColumn::make('asignacion.estado_asignacion')
                    ->label('Estado asignacion')
                    ->badge()
                    ->searchable()
                    ->color(function ($state) {
                        return match ($state) {
                            true => 'success',
                            false => 'danger',
                        };
                    })
                    ->formatStateUsing(function ($state) {
                        return $state ? 'Activa' : 'Inactiva';
                    }),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Rol asignado')
                    ->searchable(),
                Tables\Columns\TextColumn::make('asignacion.id')
                    ->label('asignacion id')
                    ->searchable(),
            ])
            //inicio filtros
            ->filters([

                // 📌 Filtro por Asesor Asignado
                SelectFilter::make('asignacion.asesor.user.name')
                ->relationship('asignacion.asesor', 'id')
                ->getOptionLabelFromRecordUsing(fn(Model $record) => $record->user->name)
                ->searchable()
                ->label('Asesor asignado'),

                Filter::make('filtros')
                ->form([
                    Forms\Components\Grid::make(2) // 📌 Organiza los filtros en 3 columnas
                        ->schema([

                            // 📌 Filtro por Día Específico
                            Forms\Components\DatePicker::make('created_at_day')
                            ->label('Día específico') 
                            ->columnSpanFull()                         
                            ->displayFormat('d/m/Y'),
                        ]),
                    Forms\Components\Grid::make(2)
                        ->schema([
                            Forms\Components\Select::make('cliente_estado_cliente')
                                ->options(Helpers::getEstatusOptions())
                                ->label('Estado cliente'),
                            // 📌 Filtro por Fase del Cliente
                            Forms\Components\Select::make('cliente_fase_cliente')
                            ->options(Helpers::getFaseOptions())
                                ->label('Fase cliente'),                         
                            // 📌 Filtro por Estado del Cliente
                            
                        ]),
                    Forms\Components\Grid::make(2)
                        ->schema([
                             // 📌 Filtro por Rol
                             Forms\Components\Select::make('roles')
                             ->label('Rol asignado')                     
                             ->relationship('roles', 'name'),
                              
                        // 📌 Filtro por País del Cliente
                            Forms\Components\TextInput::make('cliente_pais')
                                ->label('País Cliente')
                                ->default(''),
                         ])
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        // Seleccionar solo campos necesarios
                        $query = $query->select([
                            'id', 'created_at', 'cliente_id', 'asignacion_id',
                            // Añadir otros campos esenciales que necesites
                        ]);
                        
                        // Aplicar filtro de fecha
                        if ($data['created_at_day'] ?? null) {
                            $query->whereDate('created_at', $data['created_at_day']);
                        }
                        
                        // Optimizar filtros relacionados con cliente usando una sola consulta whereHas
                        $hasClienteFilter = ($data['cliente_estado_cliente'] ?? null) || 
                                           ($data['cliente_fase_cliente'] ?? null) || 
                                           ($data['cliente_pais'] ?? null);
                        
                        if ($hasClienteFilter) {
                            $query->whereHas('cliente', function ($clienteQuery) use ($data) {
                                if ($data['cliente_estado_cliente'] ?? null) {
                                    $clienteQuery->where('estado_cliente', $data['cliente_estado_cliente']);
                                }
                                
                                if ($data['cliente_fase_cliente'] ?? null) {
                                    $clienteQuery->where('fase_cliente', $data['cliente_fase_cliente']);
                                }
                                
                                if ($data['cliente_pais'] ?? null) {
                                    $clienteQuery->where('pais', $data['cliente_pais']);
                                }
                                
                                return $clienteQuery;
                            });
                            
                            // Cargar las relaciones necesarias con constraint para evitar cargar datos innecesarios
                            $query->with(['cliente' => function ($q) use ($data) {
                                $q->select(['id', 'estado_cliente', 'fase_cliente', 'pais']);
                            }]);
                        }
                        
                        // Aplicar filtro de roles si está presente
                        if ($data['roles'] ?? null) {
                            $query->whereHas('roles', function ($roleQuery) use ($data) {
                                $roleQuery->where('id', $data['roles']);
                            });
                        }
                        
                        // Aplicar los límites de paginación aquí no es necesario
                        // ya que Filament maneja la paginación automáticamente
                        
                        return $query;
                    }),
            ])       
            ->deferFilters()
            //fin filtros            

            ->actions([
                Tables\Actions\EditAction::make()
                ->iconButton()
                ->tooltip('Editar usuario'),
            ], position: ActionsPosition::BeforeCells)
            ->bulkActions([
                /**
                 *  ASIGNACION MASIVA
                 */
                BulkAction::make('Asignar asesor')
                    ->color('primary')
                    ->icon('heroicon-s-identification')
                    ->form([
                        Select::make('asesor_id')
                            ->relationship('asesor', 'id') // Define la relación y la clave foránea
                            ->searchable()
                            ->preload()
                            ->getOptionLabelFromRecordUsing(fn(Model $record) => $record->user->name ?? 'Sin nombre')
                            ->default(null),
                    ])
                    ->action(function (array $data, Collection $records) {
                        $records->each->assignNewAsesor($data['asesor_id']);
                    })
                    ->after(function () {
                        // NOTIFICAR QUE LA ASIGNACION FUE EXITOSA
                        Notification::make()
                            ->title('Asesor asignado con éxito')
                            ->success()
                            ->send();
                    })
                    ->deselectRecordsAfterCompletion()
                    ->visible(function(){
                        if(Helpers::isSuperAdmin()){
                            return true;
                        }
                    }),
                /**
                 *  ASIGNAR ROL CLIENTE
                 */
                BulkAction::make('Asignar nuevo estado')
                    ->color('info')
                    ->icon('heroicon-s-arrows-right-left')
                    ->form([
                        Select::make('estado_cliente')
                        ->options(Helpers::getEstatusOptions())
                        ->required()
                        ])
                    ->action(function (array $data, Collection $records) {
                        $records->each(function($user)use($data){
                            $user?->cliente->update(['estado_cliente'=> $data['estado_cliente']]);
                        });
                    })
                    ->after(function () {
                        // NOTIFICAR QUE LA ASIGNACION FUE EXITOSA
                        Notification::make()
                            ->title('estado actualizado con éxito')
                            ->success()
                            ->send();
                    })
                    ->deselectRecordsAfterCompletion()
                    ->visible(function(){
                        if(Helpers::isSuperAdmin()){
                            return true;
                        }
                    }),
                BulkAction::make('assignar nueva fase')
                    ->color('success')
                    ->icon('heroicon-s-arrow-path')
                    ->form([
                        Select::make('fase')
                        ->label('Fase del cliente')
                        ->options(Helpers::getFaseOptions())
                        ->required(),
                    ])
                    ->action(function( array $data, Collection $records): void {
                        $records->each(function ($user)use($data){
                            $user->assingNewFase($data['fase']);
                        });
                    })
                    ->after(function () {
                        // NOTIFICAR QUE LA ASIGNACION FUE EXITOSA
                        Notification::make()
                            ->title('Fase actualizado con éxito')
                            ->success()
                            ->send();
                    })
                    ->deselectRecordsAfterCompletion()
                    ->visible(function(){
                        if(Helpers::isSuperAdmin()){
                            return true;
                        }
                        return false;
                    }),
                    BulkAction::make('Asignar nuevo origen')
                    ->color('warning')
                    ->icon('heroicon-s-globe-americas')
                    ->form([
                        Select::make('origenes')
                        ->options([
                                'RECOVERY' => 'RECOVERY',
                                'AMZN' => 'AMZN',
                                'AMZN200' => 'AMZN200',
                                'AMZN280' => 'AMZN280',
                                'BTC' => 'BTC',
                                'PETROLEO' => 'PETROLEO',
                                'APPLE' => 'APPLE',
                                'CURSOS' => 'CURSOS',
                                'PETROBLAS' => 'PETROBLAS',
                                'XAUUSD' => 'XAUUSD',
                                'TESLA' => 'TESLA',
                                'INGRESOS_EXTRAS' => 'INGRESOS EXTRAS',
                                'FRSPOT' => 'FRSPOT',
                                'Conferencia_Musk' => 'Conferencia Musk',
                                'COCA-COLA' => 'COCA-COLA',
                                'ENTEL' => 'ENTEL',
                                'BIMBO' => 'BIMBO',
                            ])
                            ->required()
                        ])
                    ->action(function (array $data, Collection $records) {
                        $records->each(function($user)use($data){
                            $user?->cliente->update(['origenes'=> $data['origenes']]);
                        });
                    })
                    ->after(function () {
                        // NOTIFICAR QUE LA ASIGNACION FUE EXITOSA
                        Notification::make()
                            ->title('Origenes actualizados con éxito')
                            ->success()
                            ->send();
                    })
                    ->deselectRecordsAfterCompletion()
                    ->visible(function(){
                        if(Helpers::isSuperAdmin()){
                            return true;
                        }
                    }),
                /**
                 *  ACCIONES DE ELIMINACION DE USUARIOS
                 */
                 Tables\Actions\DeleteBulkAction::make('delete')
                    ->visible(fn()=> Helpers::isSuperAdmin()),
                ]);
    }
    
    public static function getRelations(): array
    {
        return [
            // Cuentas y movimientos
            RelationGroup::make('Cuentas y movimientos', [
                UserMovimientosRelationManager::class,
                CuentaClienteRelationManager::class,
            ])->icon('heroicon-m-arrows-up-down'),
            // Asignaciones
            RelationGroup::make('Asignaciones', [
                AsignacionRelationManager::class,
                SeguimientosRelationManager::class,
            ])->icon('heroicon-m-arrows-right-left')
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
