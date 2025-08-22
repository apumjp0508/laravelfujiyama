<x-guest-layout>
    <h1 class="admin-register-title">Adminの登録</h1>
    <form method="POST" action="{{ route('admin.register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" class="admin-register-label" />
            <x-text-input id="name" class="block mt-1 w-full admin-register-input" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" class="admin-register-label" />
            <x-text-input id="email" class="block mt-1 w-full admin-register-input" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" class="admin-register-label" />

            <x-text-input id="password" class="block mt-1 w-full admin-register-input"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="admin-register-label" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full admin-register-input"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="admin-register-link" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ml-4 admin-register-btn">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    <style>
    /* モノトーンの管理者登録ページスタイル */
    .admin-register-title {
        color: #333 !important;
        font-weight: 600;
    }

    .admin-register-label {
        color: #555 !important;
        font-weight: 500;
    }

    .admin-register-input {
        border-color: #ddd !important;
        background-color: #fafafa !important;
    }

    .admin-register-input:focus {
        border-color: #666 !important;
        box-shadow: 0 0 0 0.2rem rgba(102, 102, 102, 0.25) !important;
        background-color: #fff !important;
    }

    .admin-register-link {
        color: #999 !important;
        font-size: 0.9rem;
        text-decoration: none;
    }

    .admin-register-link:hover {
        color: #666 !important;
        text-decoration: underline;
    }

    .admin-register-btn {
        background-color: #333 !important;
        border-color: #333 !important;
        color: #fff !important;
        font-weight: 500;
    }

    .admin-register-btn:hover {
        background-color: #555 !important;
        border-color: #555 !important;
    }
    </style>
</x-guest-layout>