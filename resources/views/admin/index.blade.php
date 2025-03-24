<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @vite('resources/css/app.css')
        <title>Message Proto Admin</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#18181b] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
        <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
            <main class="flex max-w-[335px] w-full flex-col-reverse lg:max-w-2xl lg:flex-row">
                <div class="text-center relative flex flex-col text-[13px] p-6 pb-12 pt-15 lg:p-20 bg-white dark:bg-[#303032] dark:text-[#EDEDEC] border border-[#444] rounded-xl">
                    <div class="mr-5 flex items-center space-x-2 absolute top-0 left-0 p-3">
                        <x-app-logo></x-app-logo>
                    </div>
                    <flux:heading size="xl" level="1">
                        Welcome to Messaging Prototype Admin!
                    </flux:heading>
                    <flux:subheading size="lg">
                        The messages are rolling in, and you're here to keep things in check.
                        Your mission? Click to process messages and keep the system running smoothly—simple, right?
                        Just you, some messages, and some buttons—plus the power to press them!
                    </flux:subheading>
                    <div class="w-3xs self-center mt-12">
                        <flux:button class="w-full" variant="primary" href="{{ route('admin.login') }}" icon-trailing="chevron-right">
                            Get Started
                        </flux:button>
                    </div>
                </div>
            </main>
        </div>

        @if (Route::has('admin.login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif
    </body>
</html>
