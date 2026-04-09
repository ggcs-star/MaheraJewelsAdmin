<!DOCTYPE html>
<html>
<head>
    <title>Low Stock Alert</title>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            padding: 25px 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            color: white;
            font-size: 24px;
        }
        .header p {
            margin: 8px 0 0;
            color: #fecaca;
            font-size: 14px;
        }
        .content {
            padding: 30px;
        }
        .alert-box {
            background: #fef2f2;
            border-left: 4px solid #dc2626;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
        }
        .alert-box strong {
            color: #dc2626;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th {
            background: #1e293b;
            color: white;
            padding: 12px;
            text-align: left;
            font-size: 13px;
        }
        td {
            padding: 10px 12px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
        }
        tr:hover {
            background: #f8fafc;
        }
        .remaining-low {
            color: #dc2626;
            font-weight: bold;
        }
        .color-box {
            display: inline-block;
            width: 20px;
            height: 20px;
            border-radius: 6px;
            margin-right: 8px;
            vertical-align: middle;
            border: 1px solid #cbd5e1;
        }
        .footer {
            background: #f8fafc;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            font-size: 12px;
            color: #64748b;
        }
        .btn {
            display: inline-block;
            background: #4f46e5;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⚠️ Low Stock Alert</h1>
            <p>Inventory Management System</p>
        </div>
        
        <div class="content">
            <div class="alert-box">
                <strong>Attention Required!</strong> Following products have stock less than or equal to <strong>{{ $threshold }}</strong> units.
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Variant</th>
                        <th>Color</th>
                        <th>Remaining</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lowStockItems as $item)
                    <tr>
                        <td>{{ $item['product_name'] }}</td>
                        <td>{{ $item['variant_name'] }}</td>
                        <td>
                            @if(isset($item['color_hex']) && $item['color_hex'] && $item['color_hex'] != 'N/A' && $item['color_hex'] != '#')
                                <span class="color-box" style="background: {{ $item['color_hex'] }};"></span>
                            @endif
                            <strong>{{ $item['color_name'] }}</strong>
                        </td>
                        <td class="remaining-low">{{ $item['remaining_qty'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div style="background: #fef3c7; padding: 15px; border-radius: 8px; margin-top: 20px;">
                <strong>📌 Action Required:</strong> Please restock these items as soon as possible to avoid stockouts.
            </div>
            
            <center>
                <a href="{{ url('/admin/stock-management') }}" class="btn">📦 Go to Stock Management</a>
            </center>
        </div>
        
        <div class="footer">
            <p>This is an automated alert from your Stock Management System.</p>
            <p>&copy; {{ date('Y') }} Inventory Management System</p>
        </div>
    </div>
</body>
</html>