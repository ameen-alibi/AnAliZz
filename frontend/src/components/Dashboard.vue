<script setup>
import axios from 'axios';
import { useRouter } from 'vue-router';
import Navbar from '@/components/Navbar.vue';

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
    <div class="min-h-screen flex flex-col bg-[#071E22] text-white">

        <Navbar />

        <main class="flex-1 p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="flex flex-col justify-center">
                <h1 class="text-4xl font-bold mb-4">Hello, welcome to <span
                        class="text-orange-600 font-bold">AnAliZz</span>
                </h1>
                <p class="text-gray-300 leading-relaxed">
                    This is your personal control center for the User Behavior Analyzer —
                    a tool that helps you understand how visitors interact with your website.
                    Track user engagement, analyze scroll depth, monitor clicks, and more.
                </p>
            </div>

            <div class="flex flex-col items-center h-[500px]"> <!-- Fixed height container -->
                <img src="@/assets/dashboard-img-1.jpg" alt="Data Analysis 1" class="w-[400px] h-[400px] rounded-lg shadow-lg border border-white/20 
          -mb-24 mr-24 transition-all duration-300 transform-gpu hover:scale-105 z-20
          relative" />
                <img src="@/assets/dashboard-img-2.jpg" alt="Data Analysis 2" class="w-[400px] h-[400px] rounded-lg shadow-lg border border-white/20 
          -mt-24 ml-24 transition-all duration-300 transform-gpu hover:scale-105 hover:z-20
          relative" />
            </div>
        </main>

        <section class="bg-white/10 p-8 text-center border-t border-white/20">
            <h2 class="text-2xl font-semibold mb-2">About the Founder</h2>
            <p class="text-gray-300 max-w-2xl mx-auto">
                Placeholder text — this section will talk about you, your vision,
                and why you created this project.
            </p>
        </section>

        <footer class="bg-[#06181B] p-4 text-center border-t border-white/20 text-sm">
            <p>Contact: <a href="mailto:ameen_alibi@proton.me"
                    class="text-[#FD7A34] hover:underline">ameen_alibi@proton.me</a></p>
            <p class="mt-1">Made by <span class="text-cyan-300 font-light italic hover:scale-50">Amine
                    Alibi</span></p>
        </footer>
    </div>
</template>
