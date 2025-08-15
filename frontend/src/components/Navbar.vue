<script setup>
import { useRoute, useRouter } from 'vue-router';
import api from '@/lib/api';

const route = useRoute();
const router = useRouter();

const links = [
    { name: 'Dashboard', path: '/dashboard' },
    { name: 'Sites List', path: '/sites' },
    { name: 'Create Site', path: '/sites/new' },
];

const logout = async () => {
    try {
        await api.post('/logout');
        localStorage.removeItem('token');
        router.push('/login');
    } catch (err) {
        console.error('Logout failed', err);
    }
};
</script>

<template>
    <nav class="bg-[#071E22] text-white p-4 flex gap-6">
        <template v-for="link in links" :key="link.path">
            <router-link v-if="route.path !== link.path" :to="link.path" class="hover:underline">
                {{ link.name }}
            </router-link>
        </template>

        <button @click="logout" class="ml-auto hover:underline text-red-400">
            Logout
        </button>
    </nav>
</template>
