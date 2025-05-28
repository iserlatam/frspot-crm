<?php

namespace App\Filament\Client\Resources\MovimientoResource\Pages;

use App\Filament\Client\Resources\MovimientoResource;
use App\Filament\Client\Resources\MovimientoResource\Widgets\InfoAccountClient;
use App\Filament\Client\Widgets\AccountsTable;
use App\Helpers\Helpers;
use App\Helpers\NotificationHelpers;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Auth;
use Throwable;

use function Filament\Support\is_app_url;

class CreateMovimiento extends CreateRecord
{
    protected static string $resource = MovimientoResource::class;

    public function create(bool $another = false): void
    {
        // $this->authorizeAccess();

        try {
            $this->beginDatabaseTransaction();

            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            // Lógica para obtener la cuenta del cliente autenticado
            $user = Auth::user();
            if (!$user || !isset($user->cuentaCliente)) {
                // Si el usuario no está autenticado o no tiene cuenta, lanzar un error o redirigir
                Helpers::sendErrorNotification('No se pudo encontrar la cuenta asociada al usuario.');
                $this->rollBackDatabaseTransaction(); // Revertir la transacción
                return;
            }
            $cuenta = $user->cuentaCliente;

            // Asegúrate de que el movimiento se asocie a la cuenta correcta
            $data['cuenta_cliente_id'] = $cuenta->id;

            // MODIFICACION DEL METODO PARA LA VALIDACION DEL SALDO TOTAL
            if ($data['tipo_st'] === 'r') { // Asumiendo que 'r' es para retiro
                if ($data['ingreso'] > $cuenta->monto_total) {
                    Helpers::sendErrorNotification('No se puede realizar la solicitud, saldo insuficiente.');
                    $this->rollBackDatabaseTransaction(); // Importante: revertir la transacción en caso de error
                    return;
                }
            }

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeCreate($data);

            $this->callHook('beforeCreate');

            $this->record = $this->handleRecordCreation($data);
            NotificationHelpers::notifyByTipoMovimiento($data['tipo_st']); // Ajusta si la lógica de notificación es diferente para clientes

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
            $this->form->model($this->getRecord()::class);
            $this->record = null;
            $this->fillForm();

            return;
        }

        $redirectUrl = $this->getRedirectUrl();
        $this->redirect($redirectUrl, navigate: FilamentView::hasSpaMode() && is_app_url($redirectUrl));
    }

    // Opcional: Sobrescribir getRedirectUrl si quieres una redirección diferente para clientes
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); // Redirige a la lista de movimientos del cliente
    }

    // Opcional: Sobrescribir mutateFormDataBeforeCreate para establecer automáticamente el usuario/cuenta
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();
        if ($user && isset($user->cuentaCliente)) {
            $data['cuenta_cliente_id'] = $user->cuentaCliente->id;
            // Si necesitas el ID del usuario en el movimiento
            // $data['user_id'] = $user->id;
        }
        return parent::mutateFormDataBeforeCreate($data);
    }
}
