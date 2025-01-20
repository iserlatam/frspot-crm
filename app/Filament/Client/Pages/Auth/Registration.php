<?php

namespace App\Filament\Client\Pages\Auth;

use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Pages\Auth\Register;
use Illuminate\Support\HtmlString;
use Parfaitementweb\FilamentCountryField\Forms\Components\Country;
use App\Enums\RegisterCuestionaryOptions as CuestionaryOption;
use App\Helpers\Helpers;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Events\Auth\Registered;
use Filament\Facades\Filament;
use Filament\Forms\Get;
use Filament\Http\Responses\Auth\Contracts\RegistrationResponse;
use Filament\Support\Exceptions\Halt;
use Filament\Support\RawJs;

class Registration extends Register
{
    protected static string $view = 'filament.client.pages.auth.registration';

    public function register(): ?RegistrationResponse
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return null;
        }

        $user = $this->wrapInDatabaseTransaction(function () {
            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            dd($data);

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeRegister($data);

            $this->callHook('beforeRegister');

            $user = $this->handleRegistration($data);

            $this->form->model($user)->saveRelationships();

            $this->callHook('afterRegister');

            return $user;
        });

        // dd($this->data);

        // Registrar informacion del cliente
        $user->cliente()->create([
            'nombre_completo' => $this->data['nombre_completo'],
            'identificacion' => $this->data['tipo_documento_identidad'],
            'fecha_nacimiento' => $this->data['fecha_nacimiento'],
            'pais' => $this->data['pais'],
            'ciudad' => $this->data['ciudad'],
            'direccion' => $this->data['direccion'],
            'cod_postal' => $this->data['codigo_postal'],
            'celular' => $this->data['telefono'],
            'is_activo' => true,
            'promocion' => 'N/A',
            'estado_cliente' => 'nuevo',
            'fase_cliente' => 'nuevo',
            'origenes' => 'N/A',
            'infoeeuu' => 'N/A',
            'caso' => $this->data['caso'],
            'tipo_doc_subm' => $this->data['tipo_documento_soporte'],
            'activo_subm' => 'N/A',
            'metodo_pago' => $this->data['entidad'],
            'doc_soporte' => $this->data['documento_soporte'],
            'archivo_soporte' => $this->data['documento_soporte'],
            'comprobante_pag' => 'N/A',
        ]);

        // Abrir nueva cuenta


        event(new Registered($user));

        $this->sendEmailVerificationNotification($user);

        Filament::auth()->login($user);

        session()->regenerate();

        return app(RegistrationResponse::class);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make(label: 'Cuenta')
                        ->afterValidation(function (Get $get) {
                            $code = $get('verification_code');

                            if ($code != env('VERIFICATION_CODE')) {
                                Helpers::sendErrorNotification('El codigo de verificacion es incorrecto');
                                throw new Halt('El codigo de verificacion es incorrecto');
                            }

                            return true;
                        })
                        ->schema([
                            // Nombre completo
                            Forms\Components\TextInput::make('nombre_completo')
                                ->label('Nombre completo:')
                                ->required()
                                ->autofocus(),
                            // Email
                            $this->getEmailFormComponent()
                                ->label('Correo electronico:'),
                            // Telefono
                            Forms\Components\TextInput::make('telefono')
                                ->label('Numero/celular:')
                                ->required(),
                            // Nombre de usuario
                            $this->getNameFormComponent()
                                ->label('Nombre de usuario:'),
                            // Contraseña
                            $this->getPasswordFormComponent()
                                ->label('Clave de acceso:'),
                            $this->getPasswordConfirmationFormComponent()
                                ->label('Confirmar clave de acceso:'),
                            Forms\Components\TextInput::make('verification_code')
                                ->required()
                                ->label('Codigo de verificacion')
                                ->maxLength(4),
                        ]),
                    Wizard\Step::make('Informacion')
                        ->schema([
                            // Direccion
                            Forms\Components\TextInput::make('direccion')
                                ->label('Direccion:')
                                ->required(),
                            // Codigo postal
                            Forms\Components\TextInput::make('codigo_postal')
                                ->label('Codigo postal:')
                                ->required(),
                            // Ciudad
                            Forms\Components\TextInput::make('ciudad')
                                ->label('Ciudad:')
                                ->required(),
                            // Fecha de nacimiento
                            Forms\Components\DatePicker::make('fecha_nacimiento')
                                ->label('Fecha de nacimiento:')
                                ->native()
                                ->required(),
                            // Pais
                            Country::make('pais')
                                ->label('Pais:')
                                ->searchable()
                                ->required(),
                        ]),
                    Wizard\Step::make('Cuestionario')
                        ->afterValidation(function (Get $get) {
                            $code = $get('est_tributario_usa');

                            if ($code != false) {
                                Helpers::sendErrorNotification('No es posible realizar el registro');
                                throw new Halt();
                            }

                            return true;
                        })
                        ->schema([
                            // Terminos y condiciones
                            Forms\Components\Radio::make('est_tributario_usa')
                                ->label('¿Soy una persona de la que hay que informar en Estados Unidos?')
                                ->boolean()
                                ->inline()
                                ->inlineLabel(false)
                                ->required(),
                            Forms\Components\Radio::make('caso')
                                ->label('Indica cual es tu caso:')
                                ->inline()
                                ->inlineLabel(false)
                                ->options([
                                    'a' => CuestionaryOption::OPTION_A->value,
                                    'b' => CuestionaryOption::OPTION_B->value,
                                    'c' => CuestionaryOption::OPTION_C->value,
                                    'd' => CuestionaryOption::OPTION_D->value,
                                ])
                                ->required(),
                        ]),
                    Wizard\Step::make('Documentos')
                        ->schema([
                            // Documento de identidad
                            Forms\Components\Select::make('tipo_documento_identidad')
                                ->label('Tipo de documento de identificacion:')
                                ->options([
                                    'cedula' => 'Cedula',
                                    'pasaporte' => 'Pasaporte',
                                ])
                                ->required(),
                            Forms\Components\SpatieMediaLibraryFileUpload::make('documento_identidad')
                                ->label('Sube el documento de identificacion:')
                                ->collection('users_id_documents')
                                ->required()
                                ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                                ->maxSize(15000) // 15MB in KB
                                ->helperText(new HtmlString('
                                    <span style="font-size: 14px">
                                        <b>Nota importante:</b>
                                        <br>
                                        <p>
                                            El archivo debe contener la misma direccion personal que figura en su solicitud, ademas
                                            debe tener su nombre completo en el documento.
                                        </p>
                                        <b>Formatos permitidos:</b>
                                        <br>
                                        <p>
                                            PDF, JPG, PNG
                                        </p>
                                        <b>Tamano maximo: 15MB</b>
                                    </span>
                                ')),
                            // Documento de soporte
                            Forms\Components\TextInput::make('tipo_documento_soporte')
                                ->label('Tipo de documento de soporte:')
                                ->required(),
                            Forms\Components\SpatieMediaLibraryFileUpload::make('documento_soporte')
                                ->label('Sube el documento de soporte:')
                                ->collection('users_support_documents')
                                ->required(),
                        ]),
                    Wizard\Step::make('Depositar')
                        ->schema([
                            Forms\Components\TextInput::make('monto')
                                ->label('Elija un monto:')
                                ->numeric()
                                ->mask(RawJs::make('$money($input)'))
                                ->stripCharacters(','),
                            Forms\Components\Select::make('entidad')
                                ->label('Elije tu metodo de pago preferido:')
                                ->options([
                                    'theter' => 'Theter',
                                ])
                                ->helperText(new HtmlString('
                                    <span style="font-size: 14px">
                                        <div style="text-align: center; padding: 16px; display: flex; flex-direction: column; align-items: center; gap: 4px;  background-color: #f0f0f0">
                                            <b>Cartera para el pago</b>
                                            <p>
                                                TLVSLdp1H192PPEeqqHuokqDQEy4GqJdfF
                                            </p>
                                            <b>USDT: Thether(USDT-Trc20)</b>
                                            <b>Con el codigo dirigete a una de estas paginas de pago Y con tu comprobante de pago continua al registro</b>
                                            <span>
                                                <a style="color: blue" href="https://www.binance.com/es" target="_blank">Enlace a simplex</a>
                                                <a style="color: blue" href="https://www.binance.com/es" target="_blank">Enlace a banxa</a>
                                            </span>
                                        </div>
                                    </span>
                                    ')),
                        ]),
                ]),
                // ->skippable()
            ]);
    }
}
