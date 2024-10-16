<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Athlete Review Count</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            margin: auto;
            width: 70%;
            padding: 20px;
        }
        .intro {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .table {
            margin: auto;
            width: 100%;
            padding: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
        caption {
            padding: 10px;
            font-size: 1.2em;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="intro">Athlete Review Count</h1>
        <div class="table">
            <table>
                <caption>Review Statistics</caption>
                <tr>
                    <th>Date</th>
                    <th>1-2</th>
                    <th>3-5</th>
                    <th>5-7</th>
                    <th>7++</th>
                </tr>
                <tr>
                    <td>Alfreds Futterkiste</td>
                    <td>Maria Anders</td>
                    <td>Germany</td>
                    <td>Germany</td>
                    <td>Germany</td>
                </tr>
                <!-- Add more rows as needed -->
            </table>
        </div>
    </div>
</body>
</html>

