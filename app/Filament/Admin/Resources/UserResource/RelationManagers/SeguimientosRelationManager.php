<?php

namespace App\Filament\Admin\Resources\UserResource\RelationManagers;

use App\Filament\Admin\Resources\SeguimientoResource;
use App\Helpers\Helpers;
use App\Helpers\NotificationHelpers;
use App\Models\Cliente;
use App\Models\Seguimiento;
use Carbon\Carbon;
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
          
                    $query->whereHas('user', function ($query) {
                        $query->where('name', $this->ownerRecord->name);
                    });                    
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
                    ->copyable()
                    ->limit(300)
                    ->wrap() // Habilita el envolvimiento básico
                    ->extraAttributes([
                        'class' => 'max-w-md break-words', // Limita el ancho máximo y fuerza el salto de palabras
                        'style' => 'white-space: pre-wrap; word-break: break-word; min-width: 250px; text-wrap: true; word-break: break-all; overflow-wrap: break-word;', // Control adicional con CSS
                    ]),
                Tables\Columns\TextColumn::make('user.cliente.estado_cliente')
                    ->label('Estado Actual'),
                Tables\Columns\TextColumn::make('user.cliente.fase_cliente')
                    ->label('Fase Actual'),
                Tables\Columns\TextColumn::make('user.cliente.updated_at')
                    ->date('M d/Y h:i A')
                    ->label('última actualizacion')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                        ->date('M d/Y h:i A')
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
                    
                    // Obtener los datos completos del formulario
                    $formData = array_merge([
                        'estado' => $cliente->estado_cliente,
                        'fase' => $cliente->fase_cliente,
                    ], $data);
                    
                    // Guardar valores anteriores para comparar
                    $estadoAnterior = $cliente->estado_cliente;
                    $faseAnterior = $cliente->fase_cliente;
                    
                    // Verificar si la fase es la misma
                    // if ($faseAnterior == $formData['fase']) {
                    //     NotificationHelpers::sendErrorNotification('La fase no puede ser la misma');
                        
                    // }
                    
                    // Actualizar cliente
                    $cliente->update([
                        'estado_cliente' => $formData['estado'],                           
                        'fase_cliente' => $formData['fase'],
                    ]);
                    
                    // Lógica del contador...
                    // if ($faseAnterior != $formData['fase']) {
                    //     if ($cliente->contador_ediciones > 0) {
                    //         $cliente->decrement('contador_ediciones');
                    //     }
                    // }
                    
                    $cliente->touch();
                
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
                    ->fillForm(fn($record) => [
                        'estado' => $this->ownerRecord->cliente->estado_cliente ?? '',
                        'fase' => $this->ownerRecord->cliente->fase_cliente ?? '',
                        'descripcion' => $this->ownerRecord->seguimientos->where('id', $record->id)->first()?->descripcion ?? '',
                        // 'origen' => $this->ownerRecord->cliente->origenes ?? '',
                        // dd($this->ownerRecord->seguimientos->first),
                    ])
                    ->tooltip('Ver comentario')
                    ->iconPosition('before')
                    ->icon('heroicon-o-eye')
                    ->color('success'),
                Tables\Actions\EditAction::make()
                    ->fillForm(fn($record) => [
                        'estado' => $this->ownerRecord->cliente->estado_cliente ?? '',
                        'fase' => $this->ownerRecord->cliente->fase_cliente ?? '',
                        'descripcion' => $this->ownerRecord->seguimientos->where('id', $record->id)->first()?->descripcion ?? '',
                        // 'origen' => $this->ownerRecord->cliente->origenes ?? '',
                        // dd($this->ownerRecord->seguimientos->first),
                    ])
                    ->using(function(Model $record, array $data){
                        $cliente = Cliente::where('user_id', $data['user_id'])->first();

                        $cliente->update([
                            'estado_cliente' => $data['estado'],
                            // 'origenes' => $data['origen'],
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
