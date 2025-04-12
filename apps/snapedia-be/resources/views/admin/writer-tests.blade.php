<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test SnapWriter – Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 20px;
        }
        h1 {
            color: #2c3e50;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }
        th, td {
            padding: 12px 15px;
            border: 1px solid #eee;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        tr:hover {
            background-color: #fafafa;
        }
        a {
            color: #3498db;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Test SnapWriter – Panoramica Utenti</h1>

    <table>
        <thead>
            <tr>
                <th>Utente</th>
                <th>Email</th>
                <th>Tentativi</th>
                <th>Ultimo Punteggio</th>
                <th>Stato</th>
                <th>Data Invio</th>
                <th>PDF</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($summaries as $entry)
                <tr>
                    <td>{{ $entry['user']->name }} ({{ $entry['user']->username }})</td>
                    <td>{{ $entry['user']->email }}</td>
                    <td>{{ $entry['attempts'] }}</td>
                    <td>{{ $entry['latest_score'] }}%</td>
                    <td>{{ ucfirst($entry['latest_status']) }}</td>
                    <td>{{ \Carbon\Carbon::parse($entry['latest_date'])->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ url('/admin/writer-tests/' . $entry['user']->id . '/pdf') }}" target="_blank">
                            Visualizza PDF
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>