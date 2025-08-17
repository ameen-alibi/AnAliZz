<script setup>
import api from '@/lib/api'
import useVuelidate from '@vuelidate/core'
import { ref, watch } from 'vue'
import { required, url } from '@vuelidate/validators'
import { useRouter } from 'vue-router'
import Prism from 'prismjs'
import 'prismjs/themes/prism-tomorrow.css'
import 'prismjs/components/prism-javascript'

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

const showSnippetModal = ref(false)
const tracking_token = ref('')
const tracking_snippet = ref('')

watch(tracking_snippet, () => {
    setTimeout(() => Prism.highlightAll(), 0)
})

const handleSubmit = async () => {
    v$.value.$touch()
    if (v$.value.$invalid) return

    try {
        const res = await api.post('/sites/store', {
            name: form.value.name,
            domain: form.value.domain
        })
        tracking_token.value = res.data.tracking_token
        tracking_snippet.value = res.data.tracking_snippet
        showSnippetModal.value = true
    } catch (error) {
        errorMessage.value = error.response?.data?.message || 'An unexpected error occurred.'
    }
}
</script>


<template>

    <!-- <div v-if="showSnippetModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-20">
        <div class="bg-white p-6 rounded-lg max-w-lg">
            <h2 class="text-lg font-semibold mb-4">Your Tracking Code</h2>
            <p class="mb-2"><strong>Tracking Token:</strong> {{ tracking_token }}</p>
            <textarea readonly class="w-full h-48 border p-2">{{ tracking_snippet }}</textarea>
            <button @click="showSnippetModal = false"
                class="mt-4 px-4 py-2 bg-orange-500 text-white rounded">Close</button>
        </div>
    </div> -->

    <div v-if="showSnippetModal"
        class="fixed inset-0 bg-black/60 backdrop-blur-md flex items-center justify-center z-50">
        <div
            class="bg-gray-900/80 text-white p-6 rounded-2xl max-w-lg w-full shadow-[0_0_25px_rgba(0,150,255,0.6)] border border-white/10">
            <h2 class="text-xl font-semibold mb-4 text-blue-400">Your Tracking Code</h2>

            <p class="mb-2 text-gray-300">
                <strong class="text-blue-400">Tracking Token:</strong> {{ tracking_token }}
            </p>

            <pre class="rounded-lg overflow-x-auto max-h-60  bg-gray-800/70 p-4 border border-gray-700">
      <code class="language-javascript">{{ tracking_snippet }}</code>
    </pre>

            <button @click="showSnippetModal = false"
                class="mt-4 px-5 py-2 bg-gradient-to-r from-blue-600 to-blue-800 hover:from-blue-500 hover:to-blue-700 rounded-xl shadow-lg shadow-blue-900/50 transition">
                Close
            </button>
        </div>
    </div>


    <div class="min-h-screen bg-[#071E22] flex justify-center items-center relative">
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
        <div class="absolute bottom-4 right-4">
            <RouterLink to="/dashboard" class="text-orange-600 hover:text-white">Go back to dashboard</RouterLink>
        </div>
    </div>
</template>
