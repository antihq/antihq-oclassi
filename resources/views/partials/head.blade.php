<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $title ?? config('app.name') }}</title>

<link href="{{ url('/logo-large.png') }}" rel="apple-touch-icon">
<link href="{{ url('/logo-small.png') }}" rel="shortcut icon" type="image/x-icon">

@vite(['resources/css/app.css', 'resources/js/app.js'])
