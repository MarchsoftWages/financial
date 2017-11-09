<!DOCTYPE html>
<html lang="{{ config('home') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="X-CSRF-TOKEN" content="{{csrf_token()}}">
    <link href="https://cdn.bootcss.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet">
    <link href="{{mix('css/admin.css')}}" rel="stylesheet">
    <title>{{ config('app.name')  }}</title>
    @include('analysis')
</head>
<body>
    <div>
        404
    </div>
</body>
</html>
