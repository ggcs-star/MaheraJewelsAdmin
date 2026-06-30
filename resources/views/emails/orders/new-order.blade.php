<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Order Received</title>
    <style>
        /* Reset styles */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Arial, Helvetica, sans-serif;
            background: #f0f4f8;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        
        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }
        
        img {
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
            -ms-interpolation-mode: bicubic;
        }
        
        /* Main container */
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* Card styling */
        .email-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 40px 35px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
        }
        
        /* Header */
        .header {
            border-bottom: 2px solid #e8edf3;
            padding-bottom: 25px;
            margin-bottom: 25px;
        }
        
        .header h1 {
            font-size: 26px;
            font-weight: 700;
            color: #0d6efd;
            margin: 0 0 5px 0;
            letter-spacing: -0.3px;
        }
        
        .header .subtitle {
            color: #6c7a8a;
            font-size: 16px;
            margin: 0;
        }
        
        /* Order details table */
        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0 25px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
        }
        
        .order-table tr {
            border-bottom: 1px solid #eef2f7;
        }
        
        .order-table tr:last-child {
            border-bottom: none;
        }
        
        .order-table th {
            text-align: left;
            padding: 14px 18px;
            background: #f8fafc;
            font-weight: 600;
            color: #2d3748;
            width: 40%;
            font-size: 14px;
        }
        
        .order-table td {
            padding: 14px 18px;
            color: #1a202c;
            font-weight: 500;
            font-size: 15px;
            background: #ffffff;
        }
        
        .order-table td .highlight {
            color: #0d6efd;
            font-weight: 700;
        }
        
        /* Status badge */
        .status-badge {
            display: inline-block;
            background: #e6f7e6;
            color: #0f7b3a;
            font-size: 13px;
            font-weight: 600;
            padding: 4px 14px;
            border-radius: 20px;
            letter-spacing: 0.3px;
        }
        
        /* Button */
        .btn-primary {
            display: inline-block;
            background: #0d6efd;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            letter-spacing: 0.3px;
            transition: background 0.2s;
            box-shadow: 0 2px 8px rgba(13, 110, 253, 0.25);
            mso-padding-alt: 0;
            text-underline: none;
        }
        
        .btn-primary:hover {
            background: #0b5ed7;
        }
        
        /* Footer */
        .footer {
            margin-top: 35px;
            padding-top: 25px;
            border-top: 2px solid #e8edf3;
            text-align: center;
            color: #8896a8;
            font-size: 14px;
        }
        
        .footer a {
            color: #0d6efd;
            text-decoration: none;
        }
        
        .footer a:hover {
            text-decoration: underline;
        }
        
        /* Responsive */
        @media only screen and (max-width: 480px) {
            .email-container {
                padding: 10px;
            }
            
            .email-card {
                padding: 25px 20px;
            }
            
            .header h1 {
                font-size: 22px;
            }
            
            .order-table th,
            .order-table td {
                padding: 12px 14px;
                font-size: 14px;
            }
            
            .btn-primary {
                padding: 12px 24px;
                font-size: 15px;
                display: block;
                text-align: center;
            }
        }
        
        /* Outlook and old email clients */
        .ExternalClass {
            width: 100%;
        }
        
        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        
        /* Safe for all email clients */
        .table-fix {
            width: 100% !important;
            max-width: 600px !important;
        }
    </style>
</head>
<body>
    <table class="table-fix" align="center" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td align="center" valign="top">
                <!-- Main container -->
                <table class="email-container" align="center" cellpadding="0" cellspacing="0" border="0" width="100%">
                    <tr>
                        <td align="center" valign="top">
                            <!-- Card -->
                            <table class="email-card" align="center" cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr>
                                    <td align="center" valign="top">
                                        
                                        <!-- Header -->
                                        <table class="header" align="center" cellpadding="0" cellspacing="0" border="0" width="100%">
                                            <tr>
                                                <td>
                                                    <h1>🛒 New Order Received</h1>
                                                    <p class="subtitle">A new order has been placed successfully on your store.</p>
                                                </td>
                                            </tr>
                                        </table>
                                        
                                        <!-- Order Details -->
                                        <table class="order-table" align="center" cellpadding="0" cellspacing="0" border="0" width="100%">
                                            <tr>
                                                <th>Order Number</th>
                                                <td><span class="highlight">{{ $order->order_number }}</span></td>
                                            </tr>
                                            <tr>
                                                <th>Order ID</th>
                                                <td>#{{ $order->id }}</td>
                                            </tr>
                                          <tr>
    <th>Grand Total</th>
    <td>
        <strong>₹{{ number_format((float) $order->total, 2) }}</strong>
    </td>
</tr>
                                            <tr>
                                                <th>Order Date</th>
                                                <td>{{ $order->created_at->format('d M Y, h:i A') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Status</th>
                                                <td><span class="status-badge">Pending</span></td>
                                            </tr>
                                        </table>
                                        
                                        <!-- Button -->
                                        <table align="center" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td align="center" style="padding: 8px 0 10px;">
                                                    <a href="{{ route('admin.orders.confirm', $order->id) }}" class="btn-primary">
                                                        View Order Details
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                        
                                        <!-- Footer -->
                                        <table class="footer" align="center" cellpadding="0" cellspacing="0" border="0" width="100%">
                                            <tr>
                                                <td>
                                                    <p style="margin: 0 0 4px;">
                                                        Need help? Contact us at 
                                                        <a href="mailto:support@yourstore.com">support@yourstore.com</a>
                                                    </p>
                                                    <p style="margin: 0; font-size: 13px; color: #a0aec0;">
                                                        &copy; {{ date('Y') }} Your Store Name. All rights reserved.
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                        
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>