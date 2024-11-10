
<style>
    .fa-google-plus,.fa-github,.fa-facebook,.fa-linkedin{
        font-size: 32px;
        margin:5px;
    }
    .fa-google-plus{
        color:rgb(211, 31, 31);
    }
    .fa-facebook{
        color:rgb(49, 16, 147);
    }
</style>
<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex mt-4 justify-between w-full">
            <div>
                {{-- login with google --}}
                <a href="{{ route('auth.redirection','google') }}"><i class="fa-brands fa-google-plus cursor-pointer"></i></a>

                {{-- login with facebook --}}
                <a href="{{ route('auth.redirection','facebook') }}"><i class="fa-brands fa-facebook cursor-pointer"></i></a>

                {{-- login with github --}}
                <a href="{{ route('auth.redirection','github') }}"><i class="fa-brands fa-github cursor-pointer"></i></a>

                {{-- login with github --}}
                <a href="{{ route('auth.redirection','linkedin-openid') }}"><i class="fa-brands fa-linkedin cursor-pointer"></i></a>

                

                
               
            </div>
        
            <div>
                <x-primary-button>
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </div>
        
    </form>
</x-guest-layout>
