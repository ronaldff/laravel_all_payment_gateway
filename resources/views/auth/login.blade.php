
<style>
    .fa-google-plus{
        font-size: 32px;
        color:rgb(211, 31, 31);
        margin:5px;
    }

    .fa-facebook{
        font-size: 32px;
        color:rgb(49, 16, 147);
        margin:5px;

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
                <a href="{{ route('auth.google') }}"><i class="fa-brands fa-google-plus cursor-pointer"></i></a>
                <i class="fa-brands fa-facebook cursor-pointer"></i>
            </div>
        
            <div>
                <x-primary-button>
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </div>
        
    </form>
</x-guest-layout>
