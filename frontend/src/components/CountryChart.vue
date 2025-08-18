<script setup>
import { BarChart, Bar, XAxis, YAxis, Tooltip, ResponsiveContainer } from 'recharts';

defineProps({
    data: {
        type: Array,
        default: () => []
    }
});
</script>

<template>
    <div class="w-full h-64 bg-[#1e293b]/80 backdrop-blur-md rounded-2xl p-4 shadow-lg text-white">
        <h3 class="text-sm text-gray-400 mb-3">Top Countries</h3>

        <div v-if="!data.length" class="flex items-center justify-center h-full text-gray-500">
            No country data available
        </div>

        <ResponsiveContainer v-else width="100%" height="100%">
            <BarChart :data="data">
                <XAxis dataKey="country_name" stroke="#94a3b8" />
                <YAxis stroke="#94a3b8" />
                <Tooltip contentStyle="{ backgroundColor: '#1e293b', border: 'none', color: '#f1f5f9' }" />
                <Bar dataKey="sessions" fill="#3b82f6" radius="6" />
            </BarChart>
        </ResponsiveContainer>
    </div>
</template>
