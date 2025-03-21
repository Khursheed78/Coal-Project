<!DOCTYPE html>
<html>
<head>
    <title>Driver Invoice</title>
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

    <h2>Driver Invoice</h2>

    <table>
        <tr>
            <th>Date</th>
            <td>{{ $driverDetail->date ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Driver</th>
            <td>{{ $driverDetail->driver->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Phone</th>
            <td>{{ $driverDetail->driver->phone ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Vehicle Number</th>
            <td>{{ $driverDetail->driver->vehicle_number ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>From</th>
            <td>{{ $driverDetail->from ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>To</th>
            <td>{{ $driverDetail->to ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Previous Trips</th>
            <td>{{ $driverDetail->driver->no_of_trips ?? 0 }}</td>
        </tr>
        <tr>
            <th>Total Trips</th>
            <td>{{ $totalTrips }}</td>
        </tr>
        <tr>
            <th>Previous Balance</th>
            <td class="{{ $previousBalance == 0 ? 'paid' : 'unpaid' }}">
                {{ number_format($previousBalance, 2) }}
                ({{ $previousBalance == 0 ? 'Paid' : 'Unpaid' }})
            </td>
        </tr>
        <tr>
            <th>Current Balance</th>
            <td class="{{ $driverDetail->driver_balance == 0 ? 'paid' : 'unpaid' }}">
                {{ number_format($driverDetail->driver_balance, 2) }}
                ({{ $driverDetail->driver_balance == 0 ? 'Paid' : 'Unpaid' }})
            </td>
        </tr>

        <tr>
            <th>Current Paid Balance</th>
            <td class="paid">
                {{ number_format($driverDetail->total_price - $driverDetail->driver_balance, 2) }}
            </td>
        </tr>

        <tr>
            <th>Total Balance</th>
            <td class="{{ $totalBalance == 0 ? 'paid' : 'unpaid' }}">
                {{ number_format($totalBalance, 2) }}
                ({{ $totalBalance == 0 ? 'Paid' : 'Unpaid' }})
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
            <td class="important-data">{{ number_format($driverDetail->quantity ?? 0, 2) }}</td>
            <td class="important-data">{{ number_format($driverDetail->price_per_ton ?? 0, 2) }}</td>
            <td class="important-data">{{ number_format($driverDetail->transportation_cost ?? 0, 2) }}</td>
            <td class="important-data"><strong>{{ number_format($driverDetail->total_price ?? 0, 2) }}</strong></td>
        </tr>
    </table>

</body>
</html>
