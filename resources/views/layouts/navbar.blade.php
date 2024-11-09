<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03"
        aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="{{ url('/') }}">@auth Welcome {{ auth()->user()->name }}
        @else
        logo @endauth
    </a>

    <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
        </ul>
        <div class="form-inline my-2 my-lg-0">
            @if (Route::has('login'))
                <nav>
                    @auth
                        @if (!Request::is('checkout'))
                            <a href="{{ route('checkout') }}">
                                <button class="btn btn-outline-success my-2 my-sm-0" type="button">Checkout</button>
                            </a>
                        @else
                            <a href="{{ url('/') }}">
                                <button class="btn btn-outline-success my-2 my-sm-0" type="button">Home </button>
                            </a>
                        @endif


                        <a href="{{ route('logout') }}">
                            <button class="btn btn-outline-success my-2 my-sm-0" type="button">Log Out</button>
                        </a>
                    @else
                        <a href="{{ route('login') }}">

                            <button class="btn btn-outline-success my-2 my-sm-0" type="button">Log in</button>
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">

                                <button class="btn btn-outline-success my-2 my-sm-0" type="button">Register</button>
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif

        </div>
    </div>
</nav>
