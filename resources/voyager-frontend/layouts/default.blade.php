@include('voyager-frontend::partials.meta')
@include('voyager-frontend::partials.header')

<main class="main-content">
    <div class="grid-container full" style="">
        <div class="grid-x">
        @yield('content')
        </div>
    </div>

</main>

@include('voyager-frontend::partials.footer')
