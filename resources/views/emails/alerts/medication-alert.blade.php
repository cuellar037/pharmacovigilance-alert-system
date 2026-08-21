<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aviso de Farmacovigilancia</title>
</head>

<body
    style="margin: 0; padding: 0; background-color: #020617; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #f8fafc;">

    <table width="100%" border="0" cellspacing="0" cellpadding="0"
        style="background-color: #020617; padding: 40px 10px;">
        <tr>
            <td align="center">
                <table width="100%" max-width="600" border="0" cellspacing="0" cellpadding="0"
                    style="max-width: 600px; background-color: #0f172a; border: 1px solid #1e293b; border-radius: 12px; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5);">
                    <tr>
                        <td height="4" style="background: linear-gradient(90deg, #06b6d4 0%, #f59e0b 100%);"></td>
                    </tr>
                    <tr>
                        <td style="padding: 32px 32px 16px 32px; text-align: left;">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td>
                                        <span
                                            style="font-family: monospace; font-size: 11px; text-transform: uppercase; letter-spacing: 2px; color: #f59e0b; background-color: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); padding: 4px 10px; border-radius: 4px; display: inline-block; font-weight: bold;">
                                            ⚠️ Aviso Oficial de Seguridad
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-top: 16px;">
                                        <h1
                                            style="margin: 0; font-size: 22px; font-weight: 800; color: #ffffff; letter-spacing: -0.5px;">
                                            Alerta de seguridad sobre el medicamento adquirido
                                        </h1>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 0 32px 24px 32px; font-size: 14px; line-height: 22px; color: #94a3b8;">
                            <p style="margin-top: 0; color: #e2e8f0; font-weight: 500;">
                                Estimado/a <strong style="color: #ffffff;">{{ $customerName }}</strong>,
                            </p>
                            <p style="margin-bottom: 24px;">
                                Nos ponemos en contacto con usted desde el área de Farmacovigilancia para informarle que
                                se ha emitido una alerta de seguridad relacionada directamente con un producto que usted
                                adquirió en nuestra farmacia.
                            </p>
                            <table width="100%" border="0" cellspacing="0" cellpadding="0"
                                style="background-color: #020617; border: 1px solid #334155; border-radius: 8px; padding: 16px; margin-bottom: 24px;">
                                <tr>
                                    <td
                                        style="padding: 6px 0; font-size: 12px; color: #64748b; text-transform: uppercase; font-family: monospace;">
                                        Medicamento:</td>
                                    <td
                                        style="padding: 6px 0; font-size: 13px; color: #ffffff; font-weight: bold; text-align: right;">
                                        {{ $medicationName }}</td>
                                </tr>
                                <tr>
                                    <td
                                        style="padding: 6px 0; font-size: 12px; color: #64748b; text-transform: uppercase; font-family: monospace;">
                                        Lote Afectado:</td>
                                    <td
                                        style="padding: 6px 0; font-size: 13px; color: #06b6d4; font-family: monospace; font-weight: bold; text-align: right;">
                                        #{{ $lotNumber }}</td>
                                </tr>
                                <tr>
                                    <td
                                        style="padding: 6px 0; font-size: 12px; color: #64748b; text-transform: uppercase; font-family: monospace;">
                                        Fecha de Compra:</td>
                                    <td style="padding: 6px 0; font-size: 13px; color: #cbd5e1; text-align: right;">
                                        {{ $purchaseDate }}</td>
                                </tr>
                            </table>
                            <div
                                style="background-color: rgba(239, 68, 68, 0.1); border-left: 4px solid #ef4444; border-radius: 0 6px 6px 0; padding: 16px; margin-bottom: 24px;">
                                <h4
                                    style="margin: 0 0 6px 0; font-size: 13px; color: #fca5a5; text-transform: uppercase; font-family: monospace; font-weight: bold;">
                                    Instrucción Importante
                                </h4>
                                <p style="margin: 0; font-size: 13px; color: #f8fafc; line-height: 20px;">
                                    Por su seguridad, le solicitamos <strong style="color: #ef4444;">suspender
                                        inmediatamente el uso de este medicamento</strong> y comunicarse con nosotros a
                                    la brevedad para coordinar los pasos a seguir.
                                </p>
                            </div>
                            <table width="100%" border="0" cellspacing="0" cellpadding="0"
                                style="margin-top: 28px; margin-bottom: 12px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ config('app.url') }}" target="_blank"
                                            style="display: inline-block; padding: 12px 28px; background-color: #06b6d4; color: #020617; font-size: 13px; font-weight: bold; text-decoration: none; border-radius: 6px; font-family: monospace; text-transform: uppercase; letter-spacing: 0.5px;">
                                            Contactar a la Farmacia &rarr;
                                        </a>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>
                    <tr>
                        <td
                            style="padding: 20px 32px; background-color: #020617; border-top: 1px solid #1e293b; text-align: center; font-size: 11px; color: #64748b;">
                            <p style="margin: 0 0 4px 0;">Atentamente,</p>
                            <p style="margin: 0; font-weight: bold; color: #94a3b8;">{{ config('app.name') }} — Módulo
                                de Farmacovigilancia</p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>
