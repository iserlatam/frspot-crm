<?php

namespace App\Filament\Admin\Widgets;

use App\Helpers\Helpers;
use App\Models\Asesor;
use Filament\Tables;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use App\Models\Seguimiento;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\Pagination\CursorPaginator;

use function Laravel\Prompts\table;

class SeguimientosTable extends BaseWidget
{
    public static ?string $heading = 'Seguimientos del día';

    protected int|string|array $columnSpan = 'full';

    protected static ?string $description = 'Lista de seguimientos realizados hoy por los asesores.';

    protected function paginateTableQuery(Builder $query): Paginator|CursorPaginator
    {
        /** cuántos por página (dropdown del widget) */
        $perPage = $this->getTableRecordsPerPage();

        $paginator = $query->paginate(
            $perPage === 'all' ? $query->count() : $perPage
        );

        // $paginator->onEachSide(-1); // muestra 4 página antes y después

        return $paginator;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Seguimiento::query()
                    ->whereDate('created_at', today())
                    ->whereHas('asesor',function ($q){
                        $q->where('tipo_asesor','ftd')
                          ->whereHas('user.roles', function($q2){
                                $q2->whereIn('name',['asesor','team ftd']);
                          });
                    })
                    ->with([
                        // 1️⃣  Asesor con el campo tipo_asesor
                        'asesor:id,user_id,tipo_asesor',
                        // 2️⃣  El usuario del asesor (solo id y name)
                        'asesor.user:id,name',
                        // 3️⃣  El cliente y sus columnas
                        'user:id,name',
                        // 3️⃣  El cliente y sus columnas
                        'user.cliente:id,user_id,estado_cliente,fase_cliente',
                    ])
            )
            ->paginationPageOptions([10, 20, 30, 50])
            ->defaultPaginationPageOption(10)
            ->defaultSort('created_at', 'desc')
            ->columns([
                    Tables\Columns\TextColumn::make('user.name')
                        ->label('Cliente')
                        ->searchable()
                        ->limit(15)
                        ->extraAttributes(['class' => 'cursor-default'])
                        ->tooltip(function ($record): ?string {
                            return $record->user->name;
                        }),
                    Tables\Columns\TextColumn::make('asesor.user.name')
                        ->label('Asesor')
                        ->searchable(),
                    Tables\Columns\TextColumn::make('descripcion')
                        ->label('Descripción')
                        ->searchable()
                        ->html()
                        ->limit(300)
                        ->wrap(),
                        
                    Tables\Columns\TextColumn::make('user.cliente.estado_cliente')
                        ->label('Estado cliente')
                        ->badge()
                        ->color(fn($state) => match ($state) {
                            'Active' => 'success',
                            'Deposit' => 'success',
                            'Potential' => 'danger',
                            'Declined' => 'warning',
                            default => 'gray',
                        }),
                    Tables\Columns\TextColumn::make('user.cliente.fase_cliente')
                        ->label('Fase actual')
                        ->badge()
                        ->color(fn($state) => match ($state) {
                            'Active' => 'success',
                            'Deposit' => 'success',
                            'Potential' => 'danger',
                            'Declined' => 'warning',
                            default => 'gray',
                        }),
                    Tables\Columns\TextColumn::make('asesor.tipo_asesor')
                        ->label('Tipo de Asesor')
                        ->badge()
                        ->color(fn($state) => match ($state) {
                            'ftd' => 'success',
                            'retencion' => 'primary',
                            'recovery' => 'warning',
                            default => 'gray',
                        }),
                    Tables\Columns\TextColumn::make('created_at')
                        ->label('Fecha de Creación')
                        ->dateTime('d/m/Y H:i')
                        ->sortable(),
                ])
            ->filters([
                Tables\Filters\SelectFilter::make('asesor_id')
                    ->label('Asesor (FTD)')
                    ->options(function(){
                        return Asesor::query()
                            ->where('tipo_asesor','ftd')
                            ->whereHas('user.roles', fn($q)=>$q->wherein('name',['asesor', 'team ftd']))
                            ->with('user:id,name')
                            ->get()
                            ->pluck('user.name', 'id')
                            ->toArray();
                        })
                    ->searchable()
                    ->preload(),
            ])->deferFilters()
            ->headerActions([
                Helpers::renderReloadTableAction(),
            ])
            ->Actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('ver_cliente')
                        ->label('Ver cliente')
                        ->url(fn($record) =>
                            route('filament.admin.resources.users.edit', $record->user->id))
                        ->icon('heroicon-o-eye'),
                    ])
                ], ActionsPosition::BeforeCells);

    }

}