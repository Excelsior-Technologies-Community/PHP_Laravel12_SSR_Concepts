# PHP_Laravel12_SSR_Concepts

##  Introduction

This project demonstrates **Server‑Side Rendering (SSR) concepts using Laravel 12 as a pure API backend**, combined with **Inertia.js + Vue 3 SSR**.

Here, Laravel works strictly as an **API provider**, while Vue handles rendering — but **initial HTML is rendered on the server (SSR)** for SEO and performance.

---

##  What is SSR with Laravel API?

### Traditional SPA (CSR)

```
Browser → Vue loads → API call → HTML renders
```

### Laravel API + SSR

```
Browser → Node SSR renders Vue → Calls Laravel API → HTML sent
```

### Key Difference

* Laravel does **not return traditional Blade HTML views**
* Laravel returns **Inertia responses** (JSON page data)
* Vue + Node SSR render the initial HTML on the server
---

##  Tech Stack

**Backend (API Only)**

* Laravel 12
* PHP 8.2+
* REST API

**Frontend (SSR)**

* Inertia.js
* Vue 3
* Node.js 18+
* Vite
* Tailwind CSS

---

##  Project Name

```
PHP_Laravel12_SSR_Concepts
```

---

##  Step 1: Create Laravel 12 Project

```bash
composer create-project laravel/laravel PHP_Laravel12_SSR_Concepts "12.*"
cd PHP_Laravel12_SSR_Concepts
```

Run server:

```bash
php artisan serve
```

---

##  Step 2: Create API Endpoint

### `routes/api.php`

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HomeController;

Route::get('/home', [HomeController::class, 'index']);
```

---

## Step 3: Enable API Routing in Laravel 12 (Bootstrap Configuration)

###  `bootstrap/app.php`

```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
```

---


##  Step 4: Create API Controller

### `app/Http/Controllers/Api/HomeController.php`

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return response()->json([
            'title' => 'Laravel 12 SSR API',
            'message' => 'This data comes from Laravel API',
        ]);
    }
}
```

---

## Step 5: Install Inertia Laravel Adapter 

Laravel does not support Inertia::render() by default.
We must install the official Inertia Laravel adapter.

```bash
composer require inertiajs/inertia-laravel
```

Why this step is required

Enables Inertia::render() in routes/web.php

Connects Laravel with Vue via Inertia

---


##  Step 6: Install Frontend Dependencies (SSR)

```bash
npm install vue@3 @inertiajs/vue3 axios
npm install @vitejs/plugin-vue --save-dev
npm install @inertiajs/server
npm install
```

---


##  Step 7: Configure Vite

### `resources/js/app.js`

```js
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'

createInertiaApp({
    resolve: name => import(`./Pages/${name}.vue`),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el)
    },
})
```

---

##  Step 8: Blade Root Layout

### `resources/views/app.blade.php`

```blade
<!DOCTYPE html>
<html>

<head>
    <title>Laravel SSR</title>
    @vite('resources/js/app.js')
    @inertiaHead
</head>

<body>
    @inertia
</body>

</html>
```

---

##  Step 9: Create Inertia Page (SSR Page)

### `resources/js/Pages/Home.vue`

```vue
<template>
  <div>
    <h1>{{ title }}</h1>
    <p>{{ message }}</p>
  </div>
</template>

<script setup>
defineProps({
  title: String,
  message: String,
})
</script>
```

---

##  Step 10: Inertia Entry Route (SSR Only)

### `routes/web.php`

```php
<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Inertia::render('Home', [
        'title' => 'Laravel 12 SSR Page',
        'message' => 'This data comes from Laravel API via SSR',
    ]);
});
```

---

##  Step 11: Create SSR Entry File

### `resources/js/ssr.js`

```js
import { createInertiaApp } from '@inertiajs/vue3'
import { renderToString } from '@vue/server-renderer'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'

export default function render(page) {
    return createInertiaApp({
        page,
        render: renderToString,
        resolve: name =>
            resolvePageComponent(
                `./Pages/${name}.vue`,
                import.meta.glob('./Pages/**/*.vue')
            ),
        setup({ App, props, plugin }) {
            return App
        },
    })
}

```

---

##  Step 12: Update Vite Config

### `vite.config.js`

```js
    import { defineConfig } from 'vite';
    import laravel from 'laravel-vite-plugin';
    import vue from '@vitejs/plugin-vue'; // <-- add this
    import tailwindcss from '@tailwindcss/vite';

    export default defineConfig({
        plugins: [
            laravel({
                input: ['resources/css/app.css', 'resources/js/app.js'],
                ssr: 'resources/js/ssr.js',
                refresh: true,
            }),
            vue(), // <-- add this
            tailwindcss(),
        ],
    });
```

---

## Step 13: Build SSR Bundle

```bash
npx vite build --ssr resources/js/ssr.js
```

Generates SSR files in bootstrap/ssr/

Required for server-side rendering

---

## Step 14: Run the Application (SSR Mode)

For SSR to work correctly, three services must run at the same time.

Open three terminals:

### Terminal 1 — Laravel Backend (API + Inertia)

```bash
php artisan serve
```

Runs Laravel API and Inertia responses at:

```bash
http://127.0.0.1:8000
```

###  Terminal 2 — Vite Dev Server (Client Hydration)

```bash
npm run dev
```

Handles client-side JavaScript

Required for hydration after SSR

Needed during development


###  Terminal 3 — Inertia SSR Server (Node)

```bash
php artisan inertia:start-ssr
```

Starts Node-based SSR server

Renders Vue pages on the server

---

##  Final Project Structure

```
PHP_Laravel12_SSR_Concepts
├── app/
│   └── Http/
│       └── Controllers/
│           └── Api/
│               └── HomeController.php
├── bootstrap/
│   └── ssr/                  # SSR bundle after Vite build
│       ├── ssr.js
│       ├── ssr-manifest.json
│       └── assets/
├── resources/
│   ├── js/
│   │   ├── Pages/
│   │   │   └── Home.vue
│   │   ├── app.js
│   │   └── ssr.js
│   └── views/
│       └── app.blade.php
├── routes/
│   ├── api.php
│   └── web.php
├── vite.config.js
└── package.json
```

---

## Output

<img width="1813" height="1080" alt="Screenshot 2026-01-29 174929" src="https://github.com/user-attachments/assets/7c690fc4-e6af-43f9-b35b-9c1e25cf27a3" />

<img width="1820" height="1078" alt="Screenshot 2026-01-29 174952" src="https://github.com/user-attachments/assets/0dd373dc-d0be-46be-8b94-60ad696b1b17" />

<img width="1377" height="997" alt="Screenshot 2026-01-29 124631" src="https://github.com/user-attachments/assets/4dc0f5d2-b225-4499-ad7f-160a019dc647" />

---

## How to Test

API: Postman   

Method: GET

```bash
http://127.0.0.1:8000/api/home
```
You should see JSON:

```json
{
  "title": "Laravel 12 SSR API",
  "message": "This data comes from Laravel API"
}
```

SSR: Browser 

```bash
http://127.0.0.1:8000
```

View Page Source: Confirm HTML is rendered server-side

---

Your PHP_Laravel12_SSR_Concepts Project is now ready!


