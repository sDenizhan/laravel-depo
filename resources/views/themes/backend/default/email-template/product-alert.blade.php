<!DOCTYPE html>
<html>
<head>
    <title>{{ $details['title'] }}</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        table, th, td, tr {
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
<h1>{{ $details['title'] }}</h1>

<p>{!! $details['body'] !!}</p>

</body>
</html>
