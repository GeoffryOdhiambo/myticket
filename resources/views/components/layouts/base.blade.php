<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' — MyTicket' : 'MyTicket — Your Event. Your Ticket. Your Experience.' }}</title>
    <meta name="description" content="{{ $description ?? 'MyTicket makes it easy to discover events and buy tickets online — concerts, parties, conferences, festivals and more.' }}">

    <link rel="icon" href="data:image/svg+xml,{{ rawurlencode('<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 24 24%22><rect width=%2224%22 height=%2224%22 rx=%226%22 fill=%22%23ff6a00%22/><text x=%2212%22 y=%2217%22 font-family=%22sans-serif%22 font-weight=%22800%22 font-size=%2214%22 fill=%22white%22 text-anchor=%22middle%22>M</text></svg>') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{ $head ?? '' }}
</head>
<body class="font-sans antialiased bg-white text-neutral-900">
    {{ $slot }}
</body>
</html>
