@include('layouts.header')
<main id="main" class="mt-16 md:ml-64 ml-0 p-6 transition-all duration-300 min-h-screen">
    <x-toast />
    @yield('content')
</main>
@include('layouts.footer')