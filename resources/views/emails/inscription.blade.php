<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue sur JobaBackend</title>
</head>
<body style="margin:0; padding:0; font-family: Arial, sans-serif; background-color: #f5f5f5;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f5f5f5; padding: 20px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #4f46e5; padding: 20px; text-align: center; color: #ffffff;">
                            <h1 style="margin:0; font-size: 24px;">Bienvenue sur JobaBackend !</h1>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 30px; color: #333333; font-size: 16px; line-height: 1.5;">
                            <p>Bonjour <strong>{{ $user->nom }}</strong>👋😊</p>
                            <p>Merci de vous être inscrit sur notre plateforme. Nous sommes ravis de vous compter parmi nous !</p>
                            <p  style="background-color: #f3f4f6"><strong>Votre email :</strong> {{ $user->email }}</p>

                        

                            <p style="margin-top: 30px;">Cordialement,<br>L'équipe JobaBackend</p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f3f4f6; padding: 15px; text-align: center; color: #777777; font-size: 12px;">
                            &copy; {{ date('Y') }} JobaBackend. Tous droits réservés.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
