# AnAliZz – Website Analytics Dashboard

[![Vue 3](https://img.shields.io/badge/Vue-3.3.4-brightgreen?logo=vue.js&logoColor=white)](https://vuejs.org/)
[![Laravel](https://img.shields.io/badge/Laravel-10-red?logo=laravel&logoColor=white)](https://laravel.com/)
[![Tailwind CSS](https://img.shields.io/badge/TailwindCSS-3-blue?logo=tailwind-css&logoColor=white)](https://tailwindcss.com/)

A lightweight web application to track and visualize your website visitors in real-time. Built with **Laravel** (backend API) and **Vue 3 + Tailwind CSS** (frontend SPA).

---

## Features ✨

- Track **pageviews**, **scroll depth**, and **time on screen**  
- Generate per-website **analytics dashboards**  
- View **top countries** of your visitors 🌍  
- Easy **website registration** and **tracking snippet** insertion  
---

## Getting Started ⚡

### Prerequisites

- PHP >= 8.1  
- Composer  
- Node.js & npm  

### Installation

**Backend (Laravel API)**

```bash
cd backend
composer install
php artisan migrate
php artisan serve
```

**Frontend (Vue 3 SPA)**

```bash
cd frontend
npm install
npm run dev
```

Access the app at `http://localhost:5173` (or the port shown in terminal).  

---

## How to Use 🛠️

1. Register and log in.  
2. Add a website with its **name** and **domain**.  
3. Copy the **tracking snippet** and insert it into your website’s `<head>`. 
5. Visit the **dashboard** to see your sites stats and charts 📊.  
6. Delete sites when needed (alert message will show ⚠️).  

---

## Technologies Used 💻

- **Laravel 10** – Backend API  
- **Vue 3 + Composition API** – Frontend SPA  
- **Tailwind CSS** – Styling  
- **Vuelidate** – Form validation  
- **Chart.js** – Analytics charts  

---
