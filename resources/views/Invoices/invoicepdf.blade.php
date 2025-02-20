<!DOCTYPE html>
<html>
<head>
    <title>Invoices PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

    <h2>Invoice List</h2>
    <h3>Afridi Coal Mine</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Invoice Number</th>
                <th>Customer</th>
                <th>Supplier</th>
                <th>Invoice Date</th>
                <th>Total Amount</th>
                <th>Amount Paid</th>
                <th>Balance Due</th>
                <th>Payment Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoices as $invoice)
                <tr>
                    <td>{{ $invoice->id }}</td>
                    <td>{{ $invoice->invoice_number }}</td>
                    <td>{{ $invoice->customer->customer }}</td>
                    <td>{{ $invoice->supplier->supplier_name }}</td>
                    <td>{{ $invoice->invoice_date }}</td>
                    <td>{{ $invoice->total_amount }}</td>
                    <td>{{ $invoice->amount_paid }}</td>
                    <td>{{ $invoice->balance_due }}</td>
                    <td>{{ $invoice->payment_status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
