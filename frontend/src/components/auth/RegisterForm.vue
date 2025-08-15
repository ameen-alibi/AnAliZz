<script setup>
import { ref, computed } from 'vue';
import { useVuelidate } from '@vuelidate/core';
import { required, email, minLength, sameAs } from '@vuelidate/validators';
import { EnvelopeIcon, LockClosedIcon, EyeIcon, UserIcon } from '@heroicons/vue/24/outline';
import { useRouter } from 'vue-router';
import api from '@/lib/api';

const form = ref({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    device_name: `VueRegisterApp (${navigator.platform})`
});


const rules = () => ({
    name: { required },
    email: { required, email },
    password: { required, minLength: minLength(8) },
    password_confirmation: { required, sameAsPassword: sameAs(computed(() => form.value.password)) }
});


const v$ = useVuelidate(rules, form);
const showPassword = ref(false);
const loading = ref(false);
const errorMessage = ref('');
const router = useRouter();

const handleSubmit = async () => {
    console.log(form.value)
    console.log(form.value.password)
    console.log(form.value.password_confirmation)
    v$.value.$touch();
    if (v$.value.$invalid) return;

    loading.value = true;
    errorMessage.value = '';

    try {
        const res = await api.post('/register', form.value);
        localStorage.setItem('token', res.data.token);
        router.push('/login');
    } catch (error) {
        const response = error.response?.data;
        if (response?.errors) {
            errorMessage.value = Object.values(response.errors)[0][0];
        } else {
            errorMessage.value = response?.message || 'An unexpected error occurred.';
        }
    } finally {
        loading.value = false;
    }
}
</script>

<template>
    <div class="relative min-h-screen flex items-center justify-center">

        <div class="absolute inset-0 bg-[url('@/assets/login-bg.svg')] bg-cover bg-center bg-no-repeat z-0"></div>

        <div
            class="relative z-10 w-full max-w-md md:max-w-lg p-10 mx-auto md:mr-auto md:ml-20 md:mx-0 animate-fade-in-up">
            <header class="mb-10 text-center md:text-left">
                <h2
                    class="text-3xl font-bold bg-gradient-to-r from-orange-400 to-blue-600 bg-clip-text text-transparent">
                    AnAliZz</h2>
                <p class="text-white/80 mt-1">Create your account</p>
            </header>

            <form @submit.prevent="handleSubmit" novalidate class="space-y-6">

                <div>
                    <label class="block text-white/90 mb-1">Name</label>
                    <div class="relative">
                        <UserIcon class="w-5 h-5 text-white absolute left-3 top-3.5" />
                        <input v-model="form.name" type="text" placeholder="Your name"
                            class="pl-10 pr-3 py-2 w-full rounded-xl bg-white/20 focus:bg-white/30 border border-white/30 focus:outline-none focus:ring-2 focus:ring-cyan-400 text-white placeholder-white/70" />
                    </div>
                    <span v-if="v$.name.$error" class="text-sm text-red-300">Name is required</span>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-white/90 mb-1">Email</label>
                    <div class="relative">
                        <EnvelopeIcon class="w-5 h-5 text-white absolute left-3 top-3.5" />
                        <input v-model="form.email" type="email" placeholder="you@example.com"
                            class="pl-10 pr-3 py-2 w-full rounded-xl bg-white/20 focus:bg-white/30 border border-white/30 focus:outline-none focus:ring-2 focus:ring-cyan-400 text-white placeholder-white/70" />
                    </div>
                    <span v-if="v$.email.$error" class="text-sm text-red-300">Valid email is required</span>
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-white/90 mb-1">Password</label>
                    <div class="relative">
                        <LockClosedIcon class="w-5 h-5 text-white absolute left-3 top-3.5" />
                        <input :type="showPassword ? 'text' : 'password'" v-model="form.password" placeholder="••••••••"
                            class="pl-10 pr-10 py-2 w-full rounded-xl bg-white/20 focus:bg-white/30 border border-white/30 focus:outline-none focus:ring-2 focus:ring-cyan-400 text-white placeholder-white/70" />
                        <EyeIcon @click="showPassword = !showPassword"
                            class="w-5 h-5 text-white absolute right-3 top-3.5 cursor-pointer" />
                    </div>
                    <span v-if="v$.password.$error" class="text-sm text-red-300">Password must be at least 8
                        characters</span>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-white/90 mb-1">Confirm Password</label>
                    <div class="relative">
                        <LockClosedIcon class="w-5 h-5 text-white absolute left-3 top-3.5" />
                        <input :type="showPassword ? 'text' : 'password'" v-model="form.password_confirmation"
                            placeholder="••••••••"
                            class="pl-10 pr-10 py-2 w-full rounded-xl bg-white/20 focus:bg-white/30 border border-white/30 focus:outline-none focus:ring-2 focus:ring-cyan-400 text-white placeholder-white/70" />
                    </div>
                    <span v-if="v$.password_confirmation.$error" class="text-sm text-red-300">Passwords must
                        match</span>
                </div>

                <button type="submit"
                    class="w-full py-3 bg-gradient-to-r from-[#485461] to-[#28313B] hover:scale-105 transition-transform duration-300 text-white rounded-xl font-medium"
                    :disabled="loading">
                    <span v-if="loading">Creating account...</span>
                    <span v-else>Sign Up</span>
                </button>

                <p v-if="errorMessage" class="text-sm text-red-300 mt-2">{{ errorMessage }}</p>
            </form>

            <p class="text-center mt-8 text-white/90">
                Already have an account?
                <router-link to="/" class="text-cyan-300 hover:underline">Sign in</router-link>
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
