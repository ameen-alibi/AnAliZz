import axios from 'axios';
import { useAuthStore } from '../store/auth';

const api = axios.create({
    baseURL: 'https://api.my-analytics.com/api',
    headers: { 'Content-Type': 'application/json' }
});


api.interceptors.request.use(config => {
    const token = useAuthStore().token;
    if (token) config.headers.Authorization = `Bearer ${token}`;
    return config;
});

export default api;
