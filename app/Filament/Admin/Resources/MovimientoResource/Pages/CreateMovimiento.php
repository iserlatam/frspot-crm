<?php

namespace App\Filament\Admin\Resources\MovimientoResource\Pages;

use App\Filament\Admin\Resources\MovimientoResource;
use App\Filament\Admin\Resources\MovimientoResource\Widgets\InfoAccountClient;
use App\Filament\Admin\Widgets\AccountsTable;
use App\Helpers\Helpers;
use App\Models\CuentaCliente;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Filament\Support\Facades\FilamentView;
use Illuminate\Database\Eloquent\Model;
use Throwable;

use function Filament\Support\is_app_url;

class CreateMovimiento extends CreateRecord
{
    protected static string $resource = MovimientoResource::class;

    protected function getFooterWidgets(): array
    {
        return [
            AccountsTable::class,
        ];
    }

    public function create(bool $another = false): void
    {
        $this->authorizeAccess();

        try {
            $this->beginDatabaseTransaction();

            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $cuenta = CuentaCliente::findOrFail($this->data['cuenta_cliente_id']);

            // MODIFICACION DEL METODO PARA LA VALIDACION DEL SALDO TOTAL
            if ($data['tipo_st'] == 'r') {
                if ($data['ingreso'] > $cuenta->monto_total) {
                    Helpers::sendErrorNotification('No se puede realizar la solicitud, saldo insuficiente');
                    $this->commitDatabaseTransaction();
                    return;
                }
            }

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeCreate($data);

            $this->callHook('beforeCreate');

            $this->record = $this->handleRecordCreation($data);

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
