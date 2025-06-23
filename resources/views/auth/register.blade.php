<x-guest-layout>
    <!-- Main card: rounded, shadow, and flex container for columns -->
    <div class="w-full mx-4 md:mx-0 md:max-w-[1059px] md:max-h-[900px] bg-white shadow-2xl rounded-2xl flex flex-col md:flex-row overflow-hidden">

        <!-- Left Column: Image Section -->
        <div class="w-full  md:w-4/6 hidden md:flex items-center justify-center relative bg-transparent" style="min-height: 400px;">
    <img src="{{ asset('storage/assets/login.png') }}"
        alt="Two children reading a glowing book"
        class="h-[90%] object-contain rounded-br-2xl px-8 z-10"
        onerror="this.onerror=null;this.src='https://placehold.co/400x400/D2C2B5/FFFFFF?text=Image+Not+Found';">
</div>

        <!-- Right Column: Form Section -->
        <div class="w-full md:w-2/6 py-8 px-4 md:py-12 md:mr-16 flex flex-col  justify-center">
            <h1 class="text-4xl font-extrabold text-gray-800  text-center">Register</h1>

            <!-- Session Status Message -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('register') }}" x-data="{ level: '{{ old('level_user', 'Petugas') }}' }">
                @csrf

                <!-- Level User -->
                <div class="mt-4">
                    <x-input-label for="level_user" :value="__('Status')" class="font-semibold" />
                    <select id="level_user" name="level_user"
                        class="block mt-1 w-full border-2 border-gray-600 rounded-xl font-semibold focus:border-[#DFAEB1] focus:ring-[#DFAEB1]"
                        x-model="level" required>
                        <option value="Petugas">Petugas</option>
                        <option value="Admin">Admin</option>
                        <option value="Siswa">Siswa</option>
                    </select>
                    <x-input-error :messages="$errors->get('level_user')" class="mt-2" />
                </div>

                <!-- Nama Siswa & Kelas (only if siswa) -->
                <div class="mt-4" x-show="level === 'Siswa'">
                    <x-input-label for="nama_siswa" :value="__('Nama Siswa')" />
                    <x-text-input id="nama_siswa" class="block mt-1 w-full border-2 border-gray-600 rounded-xl font-semibold focus:border-[#DFAEB1] focus:ring-[#DFAEB1]" type="text" name="nama_siswa" :value="old('nama_siswa')" x-bind:required="level === 'Siswa'" />
                    <x-input-error :messages="$errors->get('nama_siswa')" class="mt-2" />
                </div>
                <div class="mt-4" x-show="level === 'Siswa'">
                    <x-input-label for="kelas" :value="__('Kelas')" />
                    <x-text-input id="kelas" class="block mt-1 w-full border-2 border-gray-600 rounded-xl font-semibold focus:border-[#DFAEB1] focus:ring-[#DFAEB1]" type="text" name="kelas" :value="old('kelas')" x-bind:required="level === 'Siswa'"/>
                    <x-input-error :messages="$errors->get('kelas')" class="mt-2" />
                </div>

                <!-- Username -->
                <div class="mt-4">
                    <x-input-label for="username" :value="__('Username')" />
                    <x-text-input id="username" class="block mt-1 w-full border-2 border-gray-600 rounded-xl font-semibold focus:border-[#DFAEB1] focus:ring-[#DFAEB1]" type="text" name="username" :value="old('username')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('username')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div class="mt-4">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full border-2 border-gray-600 rounded-xl font-semibold focus:border-[#DFAEB1] focus:ring-[#DFAEB1]" type="email" name="email" :value="old('email')" required autocomplete="email" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="block mt-1 w-full border-2 border-gray-600 rounded-xl font-semibold focus:border-[#DFAEB1] focus:ring-[#DFAEB1]"
                                    type="password"
                                    name="password"
                                    required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full border-2 border-gray-600 rounded-xl font-semibold focus:border-[#DFAEB1] focus:ring-[#DFAEB1]"
                                    type="password"
                                    name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="mt-4">
                     <x-primary-button >
                        {{ __('Sign Up') }}
                    </x-primary-button>
                </div>
                  
                <p class="text-center text-sm font-semibold text-gray-900 mt-4">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="font-semibold text-gray-500 hover:text-gray-400 hover:underline">
                        Sign in Here
                    </a>
                </p>

            </form>
        </div>
    </div>
</x-guest-layout>