<script setup>
import { Head, Link } from "@inertiajs/inertia-vue3";
import path from 'path';

console.log('th')
console.log(path.resolve('/vendor/tightenco/ziggy/dist/vue.m.js'))

// const templateDir = path.resolve(
//     new URL(import.meta.url).pathname, // This is where it gives the error
//     '../../templates',
//     options.template
// );

defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
  laravelVersion: String,
  phpVersion: String,
});
</script>

<template>
  <Head title="Welcome" />

  <div
    class="relative flex items-top justify-center min-h-screen bg-[url('/img/bg.jpg')] dark:bg-gray-900 sm:items-center sm:pt-0"
  >
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
      <div class="flex justify-center pt-8 sm:justify-start sm:pt-0">
        <!-- This example requires Tailwind CSS v2.0+ -->
        <div class="relative bg-transparent overflow-hidden">
          <div class="relative pt-6 pb-16 sm:pb-24">
            <main class="mt-48 mx-auto max-w-7xl px-4 sm:mt-24">
              <div class="text-center">
                <h1
                  class="text-6xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl"
                >
                  <span class="block xl:inline">Brisk </span>
                  <span class="block text-light-green-900 xl:inline">POS</span>
                </h1>
                <p
                  class="mt-3 max-w-md mx-auto text-base text-black italic sm:text-lg md:mt-5 md:text-xl md:max-w-3xl"
                >
                  Data to enhance sales businesses
                </p>

                <div v-if="canLogin">
                  <div
                    v-if="!$page.props.user"
                    class="mt-5 max-w-md mx-auto sm:flex sm:justify-center md:mt-8"
                  >
                    <div class="rounded-md shadow">
                      <Link
                        :href="route('login')"
                        class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white hover:text-light-green-900 bg-light-green-900 hover:bg-white md:py-4 md:text-lg md:px-10"
                      >
                        Login
                      </Link>
                    </div>
                    <div
                      v-if="canRegister"
                      class="mt-3 rounded-md shadow sm:mt-0 sm:ml-3"
                    >
                      <Link
                        :href="route('register')"
                        class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md hover:text-light-green-900 text-white hover:bg-white bg-gray-900 md:py-4 md:text-lg md:px-10"
                      >
                        Register
                      </Link>
                    </div>
                  </div>

                  <div
                    v-else
                    class="mt-5 max-w-md mx-auto sm:flex sm:justify-center md:mt-8"
                  >
                    <div class="rounded-md shadow">
                      <Link
                        :href="route('dashboard')"
                        class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-semibold rounded-md text-white hover:text-light-green-900 hover:font-extrabold bg-light-green-900 hover:bg-white md:py-4 md:text-lg md:px-10"
                      >
                        Dashboard
                      </Link>
                    </div>
                  </div>
                </div>
              </div>
            </main>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.bg-gray-100 {
  background-color: #f7fafc;
  background-color: rgba(247, 250, 252, var(--tw-bg-opacity));
}

.border-gray-200 {
  border-color: #edf2f7;
  border-color: rgba(237, 242, 247, var(--tw-border-opacity));
}

.text-gray-400 {
  color: #cbd5e0;
  color: rgba(203, 213, 224, var(--tw-text-opacity));
}

.text-gray-500 {
  color: #a0aec0;
  color: rgba(160, 174, 192, var(--tw-text-opacity));
}

.text-gray-600 {
  color: #718096;
  color: rgba(113, 128, 150, var(--tw-text-opacity));
}

.text-gray-700 {
  color: #4a5568;
  color: rgba(74, 85, 104, var(--tw-text-opacity));
}

.text-gray-900 {
  color: #1a202c;
  color: rgba(26, 32, 44, var(--tw-text-opacity));
}

@media (prefers-color-scheme: dark) {
  .dark\:bg-gray-800 {
    background-color: #2d3748;
    background-color: rgba(45, 55, 72, var(--tw-bg-opacity));
  }

  .dark\:bg-gray-900 {
    background-color: #1a202c;
    background-color: rgba(26, 32, 44, var(--tw-bg-opacity));
  }

  .dark\:border-gray-700 {
    border-color: #4a5568;
    border-color: rgba(74, 85, 104, var(--tw-border-opacity));
  }

  .dark\:text-white {
    color: #fff;
    color: rgba(255, 255, 255, var(--tw-text-opacity));
  }

  .dark\:text-gray-400 {
    color: #cbd5e0;
    color: rgba(203, 213, 224, var(--tw-text-opacity));
  }
}
</style>
