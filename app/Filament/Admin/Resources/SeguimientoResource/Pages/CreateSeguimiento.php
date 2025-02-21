<?php

namespace App\Filament\Admin\Resources\SeguimientoResource\Pages;

use App\Filament\Admin\Resources\SeguimientoResource;
use App\Models\Cliente;
use App\Models\Seguimiento;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Filament\Support\Facades\FilamentView;
use Throwable;

class CreateSeguimiento extends CreateRecord
{
    protected static string $resource = SeguimientoResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $data;
    }

    public function create(bool $another = false): void
    {
        $this->authorizeAccess();

        try {
            $this->beginDatabaseTransaction();

            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeCreate($data);

            // Update estado, origen y fase
            $cliente = Cliente::where('user_id', $data['user_id'])->first();

            $cliente->update([
                    'estado_cliente' => $data['estado'],
                    'origenes' => $data['origen'],
                    'fase_cliente' => $data['fase'],
                ]);

            Seguimiento::create([
                'user_id' => $data['user_id'],
                'asesor_id' => $data['asesor_id'],
                'descripcion' => $data['descripcion'],
            ]);

            $this->callHook('beforeCreate');

            $this->form->model($this->getRecord())->saveRelationships();

            $this->callHook('afterCreate');

            $this->commitDatabaseTransaction();
        } catch (Halt $exception) {
            $exception->shouldRollbackDatabaseTransaction() ?
                $this->rollBackDatabaseTransaction() :
                $this->commitDatabaseTransaction();

            return;
        } catch (Throwable $exception) {
            $this->rollBackDatabaseTransaction();

            throw $exception;
        }

        $this->rememberData();

        $this->getCreatedNotification()?->send();

        if ($another) {
            // Ensure that the form record is anonymized so that relationships aren't loaded.
            $this->form->model($this->getRecord()::class);
            $this->record = null;

            $this->fillForm();

            return;
        }

        $redirectUrl = $this->getRedirectUrl();

        $this->redirect($redirectUrl, navigate: FilamentView::hasSpaMode() && is_app_url($redirectUrl));
    }

}
