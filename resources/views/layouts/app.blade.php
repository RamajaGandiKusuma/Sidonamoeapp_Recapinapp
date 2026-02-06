<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'SIDONAMOE')</title>

    @vite([
            'resources/css/form-kunjungan.css',
            'resources/js/sidonamoe-form.js'
            ])
</head>
<body>

    @yield('content')

</body>
</html>
