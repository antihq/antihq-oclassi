<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}">
    <head>
        @include("partials.head")
    </head>
    <body class="antialiased bg-[#1C362E] text-[#FFFBEB] bg-[url('../img/pattern.svg')] bg-repeat">
        {{ $slot }}

        @fluxScripts
    </body>
</html>
