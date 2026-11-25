<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $website_name }}</title>
</head>
<body style="margin:0;padding:0;background:#f4f6f9;font-family:Arial,Helvetica,sans-serif;color:#333333;">

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#f4f6f9;padding:40px 15px;">
    <tr>
        <td align="center">

            <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:8px;overflow:hidden;border:1px solid #e5e7eb;">

                <!-- Header -->
                <tr>
                    <td style="background:#2563eb;padding:25px;text-align:center;">
                        <h2 style="margin:0;color:#ffffff;font-size:24px;font-weight:bold;">
                            {{ $website_name }}
                        </h2>
                    </td>
                </tr>

                <!-- Body -->
                <tr>
                    <td style="padding:35px 30px;font-size:16px;line-height:1.8;color:#444444;">
                        {!! $content !!}
                    </td>
                </tr>

                <!-- Divider -->
                <tr>
                    <td style="padding:0 30px;">
                        <hr style="border:none;border-top:1px solid #eeeeee;">
                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="padding:20px 30px;text-align:center;font-size:13px;color:#888888;">
                        <p style="margin:0;">
                            © {{ date('Y') }} {{ $website_name }}. All rights reserved.
                        </p>

                        <p style="margin:8px 0 0;">
                            This is an automated email. Please do not reply.
                        </p>
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>