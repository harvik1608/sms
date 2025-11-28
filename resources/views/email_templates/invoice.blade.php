<!DOCTYPE html>
<html>
    <head>
        <title>Invoice</title>
        <style>
            @font-face {
                font-family: 'Nunito';
                font-style: normal;
                font-weight: 400;
                src: url("{{ public_path('fonts/nunito/Nunito-Regular.ttf') }}") format('truetype');
            }

            @font-face {
                font-family: 'Nunito';
                font-style: normal;
                font-weight: 600;
                src: url("{{ public_path('fonts/nunito/Nunito-SemiBold.ttf') }}") format('truetype');
            }

            @font-face {
                font-family: 'Nunito';
                font-style: normal;
                font-weight: 700;
                src: url("{{ public_path('fonts/nunito/Nunito-Bold.ttf') }}") format('truetype');
            }
            body {
                font-family: 'Nunito', sans-serif;
                font-size: 14px;
                color: #333;
                margin: 0;
                padding: 0;
            }
        </style>
    </head>
    <body>
        <table width="100%">
            <tr>
                <td colspan="2" align="center"><img src="{{ public_path('tega/logo.jpg') }}" style="width: 175px;" /></td>
            </tr>
            <tr>
                <td colspan="2"><h1>Invoice</h1><hr></td>
            </tr>
            <tr>
                <td>
                    <table width="100%">
                        <tr>
                            <td><b>Customer Details</b></td>
                        </tr>
                        <tr>
                            <td>{{ $invoice->customer?->name }}</td>
                        </tr>
                        <tr>
                            <td>{{ $invoice->customer?->address }}</td>
                        </tr>
                        <tr>
                            <td>{{ $invoice->customer?->email }}</td>
                        </tr>
                        <tr>
                            <td>{{ $invoice->customer?->phone }}</td>
                        </tr>
                    </table>    
                </td>
                <td align="right">
                    <table width="100%">
                        <tr>
                            <td align="right"><b>Company Details</b></td>
                        </tr>
                        <tr>
                            <td align="right">{{ general_settings('app_name') }}</td>
                        </tr>
                        <tr>
                            <td align="right">{{ general_settings('app_address') }}</td>
                        </tr>
                        <tr>
                            <td align="right">{{ general_settings('app_email') }}</td>
                        </tr>
                        <tr>
                            <td align="right">{{ general_settings('app_phone') }}</td>
                        </tr>
                    </table>    
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <p>
                        <b>Invoice No.: #{{ $invoice->id }}<br>
                        Date : {{ format_date_time($invoice->created_at) }}</b>
                    </p>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table width="100%" border="1px solid #efefef;">
                        <thead>
                            <tr>
                                <th>Plan</th>
                                <th>Duration</th>
                                <th>Whatsapp Messages</th>
                                <th align="right" style="padding: 5px;">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td align="center">{{ $invoice->plan?->name }}</td>
                                <td align="center">{{ $invoice->duration }} Month(s)</td>
                                <td align="center">{{ $invoice->whatsapp }}</td>
                                <td align="right" style="padding: 5px;">{{ $invoice->amount }}</td>
                            </tr>
                            <tr>
                                <td align="right" colspan="3" style="padding: 5px;"><b>TOTAL</b></td>
                                <td align="right" style="padding: 5px;">{{ number_format($invoice->amount, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>
