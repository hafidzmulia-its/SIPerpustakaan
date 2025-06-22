<x-guest-layout>
    <!-- Main card: rounded, shadow, and flex container for columns -->
    <div class="w-full mx-4 md:mx-0 md:max-w-[1059px] md:h-[640px] bg-white shadow-2xl rounded-2xl flex flex-col md:flex-row overflow-hidden">

        <!-- Left Column: Image Section -->
        <div class="w-full  md:w-4/6 hidden md:flex items-center justify-center relative bg-transparent" style="min-height: 400px;">
            <img src="{{ asset('storage/assets/login.png') }}"
                alt="Two children reading a glowing book"
                class="h-[90%] object-contain rounded-br-2xl px-8 z-10"
                onerror="this.onerror=null;this.src='https://placehold.co/400x400/D2C2B5/FFFFFF?text=Image+Not+Found';">
        </div>

        <!-- Right Column: Form Section -->
        <div class="w-full md:w-2/6 py-8 px-4 md:py-12 md:mr-16 flex flex-col justify-center">
            
            <h1 class="text-4xl font-extrabold text-gray-800 mb-8 text-center ">Login</h1>

            <!-- Session Status Message -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Alpine.js component for password toggle -->
            <form method="POST" action="{{ route('login') }}" x-data="{ showPassword: false }">
                @csrf

                <!-- Username Input -->
                <div>
                    <label for="username" class="block mb-2 text-sm font-semibold  text-gray-700">{{ __('Username') }}</label>
                    <div class="relative">
                        <!-- Mail Icon -->

                        <img src="{{ asset('storage/assets/mail.png') }}" alt="Mail Icon" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5">

                        <x-text-input class="block mt-1 w-full pl-10 rounded-xl border-gray-600 focus:border-[#DFAEB1] focus:ring-[#DFAEB1]" 
                                      id="username"
                                      name="username"
                                      type="text"
                                      :value="old('username')"
                                      required
                                      autofocus
                                      autocomplete="username" />
                    </div>
                    <x-input-error :messages="$errors->get('username')" class="mt-2" />
                </div>

                <!-- Password Input -->
                <div class="mt-6">
                    <label for="password" class="block mb-2 text-sm font-semibold text-gray-600">{{ __('Password') }}</label>
                    <div class="relative">
                    <button type="button" @click="showPassword = !showPassword" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 items-center text-gray-500 hover:text-gray-700">
                            <svg x-show="!showPassword" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.243 4.243L6.228 6.228" />
                            </svg>
                            <svg x-show="showPassword" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5s-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                        <input id="password"
                               name="password"
                               x-bind:type="showPassword ? 'text' : 'password'"
                               class="block pl-10 mt-1 w-full  border-gray-700 focus:border-[#DFAEB1] focus:ring-[#DFAEB1] rounded-xl shadow-sm"
                               required 
                               autocomplete="current-password" />
                        
                        
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Forgot Password Link -->
                <div class="text-right mt-4 mb-6">
                    @if (Route::has('password.request'))
                        <a class="text-sm text-[#DFAEB1] hover:text-pink-400 hover:underline rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#DFAEB1]" href="{{ route('password.request') }}">
                            {{ __('Forgot Password?') }}
                        </a>
                    @endif
                </div>

                <!-- Log in Button -->
                <div class="mt-2">
                     <x-primary-button  >
                        {{ __('Log in') }}
                    </x-primary-button>
                </div>

                 <!-- Register Link -->
                <p class="text-center text-sm font-semibold text-gray-900 mt-8 border-t border-gray-500 pt-5">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="font-semibold text-gray-500 hover:text-gray-400 hover:underline">
                        Sign Up Here
                    </a>
                </p>
            </form>
        </div>
    </div>

</x-guest-layout>
