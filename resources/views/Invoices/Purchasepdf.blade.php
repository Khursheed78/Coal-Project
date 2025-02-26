<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Invoice</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .invoice-box {
            max-width: 800px;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            margin: auto;
        }
        .invoice-header {
            text-align: center;
            color: #333;
            position: relative;
        }
        .invoice-header h2 {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: white;
            padding: 10px;
            border-radius: 5px;
            display: inline-block;
        }
        .invoice-header .date {
            position: absolute;
            left: 20px;
            top: 10px;
            font-size: 14px;
            color: #555;
        }
        .company-name {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .invoice-details div {
            width: 48%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background: #2575fc;
            color: white;
            padding: 10px;
            text-align: left;
        }
        td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        .total {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: gray;
        }
    </style>
</head>
<body>

<div class="invoice-box">
    <!-- Company Name -->
    <div class="company-name">Afridi Mine Company</div>

    <!-- Invoice Header -->
    <div class="invoice-header">
        <p class="date">Date: {{ date('d-m-Y') }}</p>
        <h2>Purchase Invoice</h2>
    </div>

    <!-- Invoice Details -->
    <div class="invoice-details">
        <div>
            <strong>Supplier:</strong> {{ $supplier->supplier_name ?? 'N/A' }}<br>
            <strong>Phone:</strong> {{ $supplier->phone ?? 'N/A' }}<br>
        </div>
        <div>
            <strong>Driver:</strong> {{ $driver->name ?? 'N/A' }}<br>
            <strong>Trips:</strong> {{ $driver->number_of_trips ?? 'N/A' }}<br>
        </div>
    </div>

    <!-- Invoice Table -->
    <table>
        <tr>
            <th>Description</th>
            <th>Quantity (Tons)</th>
            <th>Price per Ton</th>
            <th>Total</th>
        </tr>
        <tr>
            <td>Coal</td>
            <td>{{ $quantity_tons }}</td>
            <td>${{ $price_per_ton }}</td>
            <td>${{ $total_price }}</td>
        </tr>
    </table>

    <!-- Total Amount -->
    <p class="total">Grand Total: ${{ $total_price }}</p>

    <!-- Footer -->
    <div class="footer">
        Thank you for your business! | Contact: {{ $supplier->phone ?? 'N/A' }}
    </div>
</div>

</body>
</html>
