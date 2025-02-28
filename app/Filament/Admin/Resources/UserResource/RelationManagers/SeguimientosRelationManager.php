<?php

namespace App\Filament\Admin\Resources\UserResource\RelationManagers;

use App\Filament\Admin\Resources\SeguimientoResource;
use App\Helpers\Helpers;
use App\Models\Cliente;
use App\Models\Seguimiento;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Relationship;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SeguimientosRelationManager extends RelationManager
{
    protected static string $relationship = 'seguimientos';


    public function form(Form $form): Form
    {
        return SeguimientoResource::form($form);
    }

    public function table(Table $table): Table
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
                        $query->whereHas('user', function ($query) {
                            $query->where('name', $this->ownerRecord->name);
                        });
                    } else{
                        $query->whereHas('user', function ($query) {
                            $query->where('name', $this->ownerRecord->name);
                        });
                    }
                    return $query; // Siempre debe retornar una consulta válida
                }
            )
            // ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Cliente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('asesor.user.name')
                    ->label('Asesor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('descripcion')
                    ->html()
                    ->limit(120),
                Tables\Columns\TextColumn::make('user.cliente.estado_cliente')
                ->label('Estado Actual'),
                Tables\Columns\TextColumn::make('user.cliente.fase_cliente')
                    ->label('Fase Actual'),
                Tables\Columns\TextColumn::make('etiqueta'),
                Tables\Columns\TextColumn::make('created_at')
                    ->date('M d/Y H:i:s')
                    ->label('Creado el')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->using(function(array $data, string $model){
                    $cliente = Cliente::where('user_id', $data['user_id'])->first();

                    $cliente->update([
                            'estado_cliente' => $data['estado'],
                            'origenes' => $data['origen'],
                            'fase_cliente' => $data['fase'],
                        ]);

                    // Agregar registro a HistorialSeguimiento

                    return $model::create([
                        'user_id' => $data['user_id'],
                        'asesor_id' => $data['asesor_id'],
                        'descripcion' => $data['descripcion'],
                    ]);
                }),
                Helpers::renderReloadTableAction(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->iconButton()
                    ->tooltip('Ver comentario')
                    ->iconPosition('before')
                    ->icon('heroicon-o-eye')
                    ->color('success'),
                Tables\Actions\EditAction::make()
                    ->fillForm([
                        'estado' => $this->ownerRecord->cliente->estado_cliente ?? '',
                        'origen' => $this->ownerRecord->cliente->origenes ?? '',
                        'fase' => $this->ownerRecord->cliente->fase_cliente ?? '',
                    ])
                    ->using(function(Model $record, array $data){
                        $cliente = Cliente::where('user_id', $data['user_id'])->first();

                        $cliente->update([
                            'estado_cliente' => $data['estado'],
                            'origenes' => $data['origen'],
                            'fase_cliente' => $data['fase'],
                        ]);

                        $record->update([
                            'user_id' => $data['user_id'],
                            'asesor_id' => $data['asesor_id'],
                            'descripcion' => $data['descripcion'],
                        ]);

                        return $record;
                    })
                    ->iconButton()
                    ->tooltip('Editar comentario')
                    ->visible(fn() => Helpers::isSuperAdmin()),
                Tables\Actions\DeleteAction::make()
                    ->iconButton()
                    ->tooltip('Borrar comentario'),
            ], position: ActionsPosition::BeforeCells)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
