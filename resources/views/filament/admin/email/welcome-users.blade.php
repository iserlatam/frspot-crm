<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #4a4a4a;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            background-color: #000000;
            color: #ffffff;
            text-align: center;
            padding: 20px;
        }

        .email-body {
            padding: 20px;
            color: #4a4a4a;
            background-image: url({{asset('login-imgs/fondo.jpeg') }});
        }

        .text-container {
            background-color: #2d2c2cbf;
            padding: 20px;
            color: #ffffff;
            border-radius: 8px;
            border: 2px solid #000000;
        }

        .email-footer {
            background-color: #000000;
            text-align: center;
            padding: 10px;
            font-size: 12px;
            color: #ffffff;
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        a {
            color: #00bcd4;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">
            <img src="{{ asset('login-imgs/logo.jpeg') }}" alt="Logo" style="height: 80px">
            <h1>Bienvenido a la familia</h1>
        </div>
        <div class="email-body">
            <div class="text-container">
                    <p>Hola <strong>{{ $user->name }}</strong>,</p>
                    <p>Nos complace informarte que hemos migrado a una nueva plataforma administrativa con el objetivo de mejorar tu experiencia y brindarte un entorno más intuitivo y eficiente.</p>
                    <p>A continuación, te compartimos tus credenciales de acceso:</p>
                    <p><strong>Usuario:</strong> {{ $user->email }}</p>
                    <p><strong>Contraseña:</strong> Aa123456 </p>

                    <p>🔐 Te recomendamos cambiar tu contraseña después del primer ingreso por motivos de seguridad.</p>

                    <p>Para acceder a la plataforma, simplemente haz clic en el siguiente enlace:</p>

                    <p><a href="{{ url('https://crm.frspot.com/client/login') }}">Acceder a la plataforma</a></p>

                    <p>Si tienes alguna pregunta, no dudes en contactar a nuestro equipo de soporte.</p>

                    <p>¡Gracias por acompañarnos en esta nueva etapa!</p>
                    <p>Saludos cordiales,</p>
                    <p>El Equipo de Frspot</p>
            </div>
        </div>
        <div class="email-footer">
            <img src="{{ asset('login-imgs/logo4.png') }}" alt="Logo" style="height: 40px">
            <p>&copy; {{ date('Y') }} Frspot. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
