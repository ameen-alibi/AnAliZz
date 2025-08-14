<script setup>
import axios from 'axios';
import { useRouter } from 'vue-router'; // If using Vue Router

const router = useRouter();

const logout = async () => {
    try {
        const token = localStorage.getItem('token');

        await axios.post('http://localhost:8000/api/logout', {}, {
            headers: { 'Authorization': `Bearer ${token}` }
        });

        localStorage.removeItem('token');

        router.push('/login');
    } catch (error) {
        console.error('Logout failed:', error);
    }
};
</script>
<template>
    <h1 class="w-full bg-purple-600">This is the dashboard</h1>
    <div class="w-full flex justify-center">
        <button class="bg-blue-600 p-3" @click="logout">Logout</button>
    </div>
</template>