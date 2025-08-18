<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import api from "@/lib/api";
import StatCard from "@/components/StatCard.vue";
import CountryChart from "@/components/CountryChart.vue";

const route = useRoute();
const stats = ref(null);
const loading = ref(true);
const error = ref(null);

onMounted(async () => {
    try {
        const { data } = await api.get(`/sites/${route.params.id}/stats`);
        stats.value = data;
    } catch (e) {
        error.value = "Failed to load stats.";
    } finally {
        loading.value = false;
    }
});
</script>

<template>
    <div class="p-6 overflow-x-auto min-h-screen bg-[#0f172a] text-white relative">
        <h1 class="text-2xl font-bold mb-6">Website Statistics</h1>

        <div v-if="loading" class="text-gray-400">Loading stats...</div>

        <div v-else-if="error" class="text-red-400">{{ error }}</div>

        <div v-else-if="!stats || (!stats.unique_visits && !stats.avg_time_on_screen && !stats.avg_scroll_depth)">
            <div class="p-6 bg-[#1e293b]/60 backdrop-blur-md text-gray-400 rounded-lg">
                No statistics available yet. Once visitors interact with your site, stats will appear here.
            </div>
        </div>

        <div v-else class="space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <StatCard title="Unique Visits" :value="stats.unique_visits" />
                <StatCard title="Avg Time on Screen" :value="stats.avg_time_on_screen" unit="sec" />
                <StatCard title="Avg Scroll Depth" :value="stats.avg_scroll_depth" unit="%" />
            </div>

            <CountryChart :data="stats.top_countries" />
        </div>
        <div class="absolute bottom-2 right-2">
            <RouterLink to="/dashboard" class="text-orange-600 hover:text-white">Go back to dashboard</RouterLink>
        </div>
    </div>
</template>
