<script setup>
defineProps({
    title: String,
    value: [String, Number],
    unit: { type: String, default: "" }
});
</script>

<template>
    <div class="p-6 bg-[#1e293b]/80 backdrop-blur-md text-white rounded-2xl shadow-lg w-full">
        <h3 class="text-sm text-gray-400 mb-1">{{ title }}</h3>
        <p class="text-2xl font-bold">
            {{ value }}
            <span v-if="unit" class="text-base font-normal ml-1 text-gray-500">{{ unit }}</span>
        </p>
    </div>
</template>
