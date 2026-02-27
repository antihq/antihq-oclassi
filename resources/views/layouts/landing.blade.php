<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}">
    <head>
        @include("partials.head")
    </head>
    <body class="antialiased bg-[#F5F2EC] text-[#37241A]">
        {{ $slot }}

        @fluxScripts
    </body>
</html>
