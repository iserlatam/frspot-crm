<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SeguimientoResource\Pages;
use App\Filament\Admin\Resources\SeguimientoResource\RelationManagers;
use App\Helpers\Helpers;
use App\Models\Asesor;
use App\Models\Seguimiento;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SeguimientoResource extends Resource
{
    protected static ?string $model = Seguimiento::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $activeNavigationIcon = 'heroicon-s-clipboard-document-list';

    protected static ?string $navigationGroup = 'Cuentas y movimientos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Seguimiento::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->query(
                function () {
                    $query = Seguimiento::query();

                    if (Helpers::isAsesor()) {
                        $query->whereHas('asesor.user', function ($query) {
                            $query->where('name', auth()->user()->name);
                        });
                    }

                    return $query; // Siempre debe retornar una consulta válida
                }
            )
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Cliente')
                    ->copyable()
                    ->tooltip('Nombre del cliente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('asesor.user.name')
                    ->label('Asesor')
                    ->searchable(),
                    Tables\Columns\TextColumn::make('descripcion')
                    ->html()
                    ->wrap()
                    ->limit(100),
                Tables\Columns\TextColumn::make('user.cliente.estado_cliente')
                    ->label('Estado actual')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.cliente.fase_cliente')
                    ->label('Fase Actual')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.asignacion.asesor.user.name')
                    ->label('Asesor asignado')
                    ->searchable(),
                Tables\Columns\TextColumn::make('etiqueta')
                    ->searchable()
                    ->visible(false),
                Tables\Columns\TextColumn::make('created_at')
                    ->date('M d/Y h:i A')
                    ->label('Creado el')
                    ->sortable(),
            ])
            ->filters([
                Filter::make('seguimiento_filters')
                    ->form([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\DatePicker::make('created_at_day')
                                    ->label('Día específico')
                                    ->columnSpanFull()
                                    ->displayFormat('d/m/Y'),  
                                // 📌 Filtro por Estado del Cliente
                                Forms\Components\Select::make('estado_cliente')
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
                                        'Invalid number' => 'Invalid number',
                                        'Stateless'  => 'Stateless',
                                        'Interested'  => 'Interested',
                                        'Recovery'  => 'Recovery',
                                        ])
                                    ->label('Estado cliente'),
                            
                                // 📌 Filtro por Fase del Cliente
                                Forms\Components\Select::make('fase_cliente')
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
                                    'Invalid number' => 'Invalid number',
                                    'Stateless'  => 'Stateless',
                                    'Interested'  => 'Interested',
                                    'Recovery'  => 'Recovery',
                                    ])
                                    ->label('Fase cliente'),      
                                    Forms\Components\Select::make('asesor_name')
                                    ->label('Asesor asignado')
                                    ->options(fn() => \App\Models\User::whereHas('asesor')->pluck('name', 'name')) // Muestra solo asesores
                                    ->searchable()
                                    ->preload()
                                    ->columnSpanFull(),
                                
                                      
                            ]),          
                        ])
                        ->query(function (Builder $query, array $data): Builder {
                            return $query
                                ->when($data['created_at_day'] ?? null, fn ($query, $date) => 
                                    $query->whereDate('created_at', $date)
                                )
                                ->when($data['estado_cliente'] ?? null,fn ($query, $estado) => $query->whereHas('user.cliente', function ($query) use ($estado) {
                                    $query->where('estado_cliente', $estado);
                                }))
                                ->when($data['fase_cliente'] ?? null,fn ($query, $fase) => $query->whereHas('user.cliente', function ($query) use ($fase) {
                                    $query->where('fase_cliente', $fase);
                                }))
                                ->when($data['asesor_name'] ?? null, fn ($query, $asesorNombre) => 
                                $query->whereHas('asesor.user', function ($query) use ($asesorNombre) {
                                    $query->where('name', $asesorNombre);
                                }));
                        }),
                ])
                ->deferFilters()
            ->headerActions([
                Helpers::renderReloadTableAction(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('ver comentario')
                    ->iconPosition('before')
                    ->icon('heroicon-o-eye')
                    ->iconButton()
                    ->tooltip('Ver comentario')
                    ->color('success'),
                Tables\Actions\EditAction::make()
                    ->iconButton()
                    ->tooltip('Editar comentario')
                    ->visible(fn () => helpers::isSuperAdmin())
            ], position: ActionsPosition::BeforeCells)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSeguimientos::route('/'),
            // 'create' => Pages\CreateSeguimiento::route('/create'),
            // 'edit' => Pages\EditSeguimiento::route('/{record}/edit'),
        ];
    }
}
