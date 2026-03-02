<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}">
    <head>
        @include("partials.head")
    </head>
    <body class="antialiased bg-[#132620] text-[#FFFEFA] bg-[url('../img/pattern.svg')] bg-repeat">
        {{ $slot }}

        @fluxScripts
    </body>
</html>
