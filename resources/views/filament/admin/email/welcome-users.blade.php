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
            background-color: #6a0dad;
            color: #ffffff;
            text-align: center;
            padding: 20px;
        }

        .email-body {
            padding: 20px;
            color: #4a4a4a;
        }

        .email-footer {
            background-color: #f4f4f4;
            text-align: center;
            padding: 10px;
            font-size: 12px;
            color: #7a7a7a;
        }

        a {
            color: #6a0dad;
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
            <h1>Bienvenido a la familia</h1>
        </div>
        <div class="email-body">
            <p>Hola {{ $user->name }},</p>
            <p>Estamos encantados de tenerte a bordo. Nuestra plataforma está diseñada para ayudarte a alcanzar tus
                objetivos y hacer que tu experiencia sea fluida.</p>
            <p>Si tienes alguna pregunta, no dudes en <a href="mailto:support@example.com">contactar a nuestro equipo de
                    soporte</a>.</p>
            <p>¡Disfruta tu viaje con nosotros!</p>
            <p>Saludos cordiales,</p>
            <p>El Equipo</p>
        </div>
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} Our Platform. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
