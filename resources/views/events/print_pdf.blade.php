<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #333;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0 0;
            color: #666;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #666;
        }
        @page {
            margin: 15mm;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Events List</h1>
        <p>Generated on: {{ now()->format('F j, Y H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Category</th>
                <th>Place</th>
                <th>Organizer</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Price</th>
                <th>Capacity</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $event)
            <tr>
                <td>{{ $event->title }}</td>
                <td>{{ $event->category->name }}</td>
                <td>{{ $event->place->name }}</td>
                <td>{{ $event->organizer->name }}</td>
                <td>{{ is_string($event->start_date) ? $event->start_date : $event->start_date->format('Y-m-d H:i') }}</td>
                <td>{{ is_string($event->end_date) ? $event->end_date : $event->end_date->format('Y-m-d H:i') }}</td>
                <td>{{ number_format($event->price, 2) }}</td>
                <td>{{ $event->capacity }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>© {{ date('Y') }} Event Management System - Page {PAGENO}</p>
    </div>
</body>

</html>