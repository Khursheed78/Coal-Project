<!DOCTYPE html>
<html>
<head>
    <title>Purchase Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: auto;
        }

        h2 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
            font-size: 14px;
        }

        th {
            background-color: #f2f2f2;
            font-size: 16px;
        }

        .paid {
            color: green;
            font-weight: bold;
        }

        .unpaid {
            color: red;
            font-weight: bold;
        }

        .important-data {
            font-size: 16px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <h2>Purchase Invoice</h2>

    <table>
        <tr>
            <th>Date</th>
            <td>{{ $date ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Supplier</th>
            <td>{{ $supplier_name ?? 'N/A' }}
        </tr>
        <tr>
            <th>Phone</th>
            <td>({{ $supplier_phone ?? 'N/A' }})</td>
        </tr>
        <tr>
            <th>Driver</th>
            <td>{{ $driver_name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Phone</th>
            <td>{{ $driver_phone ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>From</th>
            <td>{{ $from ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>To</th>
            <td>{{ $to ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Supplier Balance</th>
            <td class="{{ ($supplier_balance ?? 0) == 0 ? 'paid' : 'unpaid' }}">
                {{ number_format($supplier_balance ?? 0, 2) }}
                ({{ ($supplier_balance ?? 0) == 0 ? 'Paid' : 'Unpaid' }})
            </td>
        </tr>
        <tr>
            <th>Driver Balance</th>
            <td class="{{ ($driver_balance ?? 0) == 0 ? 'paid' : 'unpaid' }}">
                {{ number_format($driver_balance ?? 0, 2) }}
                ({{ ($driver_balance ?? 0) == 0 ? 'Paid' : 'Unpaid' }})
            </td>
        </tr>
    </table>

    <h3>Payment Details</h3>
    <table>
        <tr>
            <th>Quantity (Tons)</th>
            <th>Price/Ton</th>
            <th>Transportation Cost</th>
            <th>Total Price</th>
        </tr>
        <tr>
            <td class="important-data">{{ number_format($quantity_tons ?? 0, 2) }}</td>
            <td class="important-data">{{ number_format($price_per_ton ?? 0, 2) }}</td>
            <td class="important-data">{{ number_format($transportation_cost ?? 0, 2) }}</td>
            <td class="important-data"><strong>{{ number_format($total_price ?? 0, 2) }}</strong></td>
        </tr>
    </table>

</body>
</html>
