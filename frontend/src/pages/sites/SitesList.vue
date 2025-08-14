<script setup>
import api from '@/lib/api';
import { onMounted, ref } from 'vue';

const sites = ref([])
const loading = ref(true)
const error = ref('')

onMounted(async () => {
    try {
        const response = api.get('/sites')
        sites.value = respose.data
    } catch (e) {
        error.value = e.response?.data?.message || 'Failed to load sites'
    } finally {
        loading.value = false
    }
})

// 'id', 'name', 'domain', 'tracking_token', 'created_at'
</script>
<template>
    <b>sites.value</b>
</template>