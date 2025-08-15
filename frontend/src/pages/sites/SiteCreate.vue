<script setup>
import api from '@/lib/api'
import useVuelidate from '@vuelidate/core'
import { ref } from 'vue'
import { required, url } from '@vuelidate/validators'
import { useRouter } from 'vue-router'

const form = ref({
    name: '',
    domain: ''
})

const rules = {
    name: { required },
    domain: { required, url }
}
const v$ = useVuelidate(rules, form)
const errorMessage = ref('')

const router = useRouter()

const handleSubmit = async () => {
    v$.value.$touch();
    if (v$.value.$invalid) return;

    try {
        await api.post('/sites/store', {
            name: form.value.name,
            domain: form.value.domain,
        });
        router.push('/sites')

    } catch (error) {
        const errors = error.response?.data
        if (errors) {
            errorMessage.value = errors.message
        } else {
            errorMessage.value = 'An unexpected error occurred.'
        }
    }
}
</script>

<template>
    <div class="absolute inset-0 bg-[#071E22] flex justify-center items-center">
        <Navbar />
        <div class="w-full max-w-md p-6 rounded-2xl shadow-lg bg-white/10 backdrop-blur-md border border-white/20">
            <h2 class="text-2xl font-semibold text-white mb-6 text-center">
                Add a New Website
            </h2>

            <form novalidate @submit.prevent="handleSubmit" class="space-y-5">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-200 mb-1">
                        Website Name
                    </label>
                    <input id="name" v-model="form.name" type="text" placeholder="Example Site"
                        class="w-full p-3 rounded-lg bg-white/20 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-[#FD7A34]" />
                    <span v-if="v$.name.$error" class="mt-2 text-sm text-red-300">Website name is required</span>
                </div>


                <div>
                    <label for="domain" class="block text-sm font-medium text-gray-200 mb-1">
                        Website Domain
                    </label>
                    <input id="domain" v-model="form.domain" type="url" placeholder="https://example.com"
                        class="w-full p-3 rounded-lg bg-white/20 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-[#FD7A34]" />
                    <span v-if="v$.domain.$error" class="mt-2 text-sm text-red-300">Valid domain is required </span>
                </div>

                <button type="submit"
                    class="w-full py-3 bg-[#FD7A34] hover:bg-orange-600 text-white font-semibold rounded-lg transition-all duration-300 shadow-md">
                    Create Website
                </button>
                <p v-if="errorMessage" class="text-sm text-red-300 mt-2">{{ errorMessage }}</p>
            </form>
        </div>
    </div>
</template>
