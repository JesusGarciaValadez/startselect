<html>
<head>
    <title>{{ $title ?? 'StartSelect coding test' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
<div class="bg-white">
    <x-header/>
    {{ $slot }}
    <x-footer/>
</div>
</body>
</html>
