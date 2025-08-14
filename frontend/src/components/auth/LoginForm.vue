<script setup>
import { ref } from 'vue';
import { useVuelidate } from '@vuelidate/core';
import { required, email } from '@vuelidate/validators';
import { EnvelopeIcon, LockClosedIcon, EyeIcon } from '@heroicons/vue/24/outline';
import { useRouter } from 'vue-router';
import api from '@/lib/api';

const form = ref({
    email: '',
    password: '',
    remember: false
});

const rules = {
    email: { required, email },
    password: { required }
};

const v$ = useVuelidate(rules, form)
const showPassword = ref(false)
const loading = ref(false)
const errorMessage = ref('')
const router = useRouter()

const handleSubmit = async () => {
    v$.value.$touch();
    if (v$.value.$invalid) return;

    loading.value = true;

    try {
        const response = await api.post('http://localhost:8000/api/login', {
            email: form.value.email,
            password: form.value.password,
            remember: form.value.remember,
            device_name: `VueLoginApp (${navigator.platform})`
        });
        const token = response.data.token;
        localStorage.setItem('token', token);
        router.push('/dashboard')

    } catch (error) {
        const errors = error.response?.data
        if (errors) {
            errorMessage.value = errors.message
        } else {
            errorMessage.value = 'An unexpected error occurred.'
        }
    } finally {
        loading.value = false;
    }
}

</script>


<template>
    <div class="relative min-h-screen flex items-center justify-center">

        <div class="absolute inset-0 bg-[url('@/assets/login-bg.svg')] bg-cover bg-center bg-no-repeat z-0">
        </div>

        <div
            class="relative z-10 w-full max-w-md md:max-w-lg p-10 mx-auto md:mr-auto md:ml-20 md:mx-0 animate-fade-in-up">
            <header class="mb-10 text-center md:text-left">
                <h2
                    class="text-3xl font-bold bg-gradient-to-r from-orange-400 to-blue-600 bg-clip-text text-transparent">
                    AnAliZz</h2>
                <p class="text-white/80 mt-1">Take look at your users behaviour</p>
            </header>

            <form @submit.prevent="handleSubmit" novalidate class="space-y-6">
                <div>
                    <label class="block text-white/90 mb-1">Email</label>
                    <div class="relative">
                        <EnvelopeIcon class="w-5 h-5 text-white absolute left-3 top-3.5" />
                        <input v-model="form.email" type="email"
                            class="pl-10 pr-3 py-2 w-full rounded-xl bg-white/20 focus:bg-white/30 border border-white/30 focus:outline-none focus:ring-2 focus:ring-cyan-400 text-white placeholder-white/70"
                            placeholder="you@example.com" aria-label="Email address" />
                    </div>
                    <span v-if="v$.email.$error" class="text-sm text-red-300">Valid email is required</span>
                </div>

                <div>
                    <div class="flex justify-between mb-1">
                        <label class="text-white/90">Password</label>
                        <a href="#" class="text-cyan-300 text-sm hover:underline">Forgot password?</a>
                    </div>
                    <div class="relative">
                        <LockClosedIcon class="w-5 h-5 text-white absolute left-3 top-3.5" />
                        <input :type="showPassword ? 'text' : 'password'" v-model="form.password"
                            class="pl-10 pr-10 py-2 w-full rounded-xl bg-white/20 focus:bg-white/30 border border-white/30 focus:outline-none focus:ring-2 focus:ring-cyan-400 text-white placeholder-white/70"
                            placeholder="••••••••" aria-label="Password" />
                        <EyeIcon @click="showPassword = !showPassword"
                            class="w-5 h-5 text-white absolute right-3 top-3.5 cursor-pointer" />
                    </div>
                    <span v-if="v$.password.$error" class="text-sm text-red-300">Password is required</span>
                </div>

                <div class="flex items-center">
                    <input id="remember" type="checkbox" class="rounded text-cyan-400 focus:ring-cyan-400"
                        v-model="form.remember" />
                    <label for="remember" class="ml-2 text-white/90">Remember me</label>
                </div>

                <button type="submit"
                    class="w-full py-3 bg-gradient-to-r from-[#485461] to-[#28313B] hover:scale-105 transition-transform duration-300 text-white rounded-xl font-medium"
                    :disabled="loading">
                    <span v-if="loading">Signing in...</span>
                    <span v-else>Sign In</span>
                </button>

                <p v-if="errorMessage" class="text-sm text-red-300 mt-2">{{ errorMessage }}</p>
            </form>

            <!-- TODO -->
            <!-- Social Login -->
            <!-- <div class="mt-10">
                <div class="relative flex items-center">
                    <div class="flex-grow h-px bg-white/30"></div>
                    <span class="px-4 text-white/80 text-sm">Or continue with</span>
                    <div class="flex-grow h-px bg-white/30"></div>
                </div>

                <div class="grid grid-cols-2 gap-3 mt-6">
                    <button
                        class="flex items-center justify-center gap-2 py-2 bg-white/20 hover:bg-white/30 border border-white/30 text-white rounded-xl">
                        <img src="@/assets/google.svg" alt="Google" class="w-5 h-5" /> Google
                    </button>
                    <button
                        class="flex items-center justify-center gap-2 py-2 bg-white/20 hover:bg-white/30 border border-white/30 text-white rounded-xl">
                        <img src="@/assets/github.svg" alt="GitHub" class="w-5 h-5" /> GitHub
                    </button>
                </div>
            </div> -->

            <p class="text-center mt-8 text-white/90">
                Don't have an account?
                <a href="/register" class="text-cyan-300 hover:underline">Sign up</a>
            </p>
        </div>
    </div>
</template>

<style scoped>
@keyframes fade-in-up {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fade-in-up 0.7s ease-out;
}
</style>
