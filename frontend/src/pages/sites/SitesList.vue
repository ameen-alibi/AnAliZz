<script setup>
import api from '@/lib/api';
import { onMounted, ref } from 'vue';
import { RouterLink, useRouter } from 'vue-router';

const sites = ref([]);
const loading = ref(true);
const error = ref('');
const router = useRouter();

const fetchSites = async () => {
    try {
        const response = await api.get('/sites');
        sites.value = response.data;
    } catch (e) {
        error.value = e.response?.data?.message || 'Failed to load sites';
        alert(error.value)
    } finally {
        loading.value = false;
    }
};

const deleteSite = async (id) => {
    if (!confirm('Are you sure you want to delete this site?')) return;

    try {
        await api.delete(`/sites/${id}`);
        sites.value = sites.value.filter(site => site.id !== id);
    } catch (e) {
        alert('Failed to delete site.');
    }
};

const viewStats = (id) => {
    router.push(`/sites/${id}/stats`);
};

onMounted(fetchSites);
</script>

<template>
    <div class="min-h-screen bg-[#071E22] text-white p-8 relative">
        <h1 class="text-3xl font-bold mb-6">Your Websites</h1>

        <div v-if="loading" class="text-gray-400">Loading...</div>
        <div v-else-if="error" class="text-red-400">{{ error }}</div>

        <table v-else
            class="w-full bg-white/10 backdrop-blur-md border border-white/20 rounded-lg overflow-hidden mb-3">
            <thead>
                <tr class="bg-white/20">
                    <th class="py-3 px-4 text-left">Name</th>
                    <th class="py-3 px-4 text-left">Domain</th>
                    <th class="py-3 px-4 text-left">Date Added</th>
                    <th class="py-3 px-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="site in sites" :key="site.id" class="border-t border-white/10 hover:bg-white/5">
                    <td class="py-3 px-4">{{ site.name }}</td>
                    <td class="py-3 px-4">{{ site.domain }}</td>
                    <td class="py-3 px-4">{{ new Date(site.created_at).toLocaleDateString() }}</td>
                    <td class="py-3 px-4 flex gap-3 justify-center">
                        <button @click="viewStats(site.id)" class="text-blue-400 hover:underline">View Stats</button>
                        <button @click="deleteSite(site.id)" class="text-red-400 hover:underline">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="absolute bottom-2 right-2">
            <RouterLink to="/dashboard" class="text-orange-600 hover:text-white">Go back to dashboard</RouterLink>
        </div>
    </div>

</template>
