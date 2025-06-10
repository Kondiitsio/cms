# Laravel Vue CMS

A modern content management system (CMS) built with [Laravel](https://laravel.com/) and [Vue.js](https://vuejs.org/), featuring Inertia.js, Tailwind CSS, and a flexible block-based page builder.

I use this cms to manage my [personal website](https://anssij.com)

## 🚀 Features

- Block-based page and content builder
- User authentication (login only, registration disabled)
- Image upload and management
- Responsive design with Tailwind CSS
- **Dashboard UI built with [shadcn-vue](https://ui.shadcn.com/vue)**
- **Public page UI built with [Tailwind UI](https://tailwindui.com/) blocks**
- Modern UI components (Vue 3, Inertia.js)
- API endpoints for pages, blocks and images
- Admin dashboard

## 🛠️ Tech Stack

- ![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=flat-square&logo=laravel&logoColor=white)
- ![Vue.js](https://img.shields.io/badge/Vue.js-4FC08D?style=flat-square&logo=vue.js&logoColor=white)
- ![Inertia.js](https://img.shields.io/badge/Inertia.js-800080?style=flat-square&logo=inertia&logoColor=white)
- ![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-06B6D4?style=flat-square&logo=tailwind-css&logoColor=white)
- ![Vite](https://img.shields.io/badge/Vite-646CFF?style=flat-square&logo=vite&logoColor=white)
- ![SQLite](https://img.shields.io/badge/SQLite-4479A1?style=flat-square&logo=sqlite&logoColor=white)
- ![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat-square&logo=php&logoColor=white)
- ![Node.js](https://img.shields.io/badge/Node.js-339933?style=flat-square&logo=nodedotjs&logoColor=white)

## ⚡ Getting Started

### Prerequisites

- PHP 8.2+
- Composer
- Node.js & npm
- SQLite

### Installation

```sh
git clone https://github.com/kondiitsio/cms.git
cd YOUR-REPO
cp .env.example .env
composer install
npm install
php artisan key:generate
php artisan migrate
php artisan storage:link
composer run dev
```

### Create User

php artisan tinker

Then run:
\App\Models\User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => bcrypt('yourpassword')]);

Login:
yourdomain.com/login

### Deployment

Build assets for production:
npm run build