<script setup>
import { ref, computed } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { Head, Link, usePage } from "@inertiajs/inertia-vue3";

defineProps({
  title: String,
});

const currentRoute = computed(() => {
  return route().current();
});
const adminLevel = computed(() => usePage().props.value.user.admin);

const loggedOutModal = ref(false);
const myProfile = ref(false);
const mobileMenu = ref(false);

const logout = () => {
  Inertia.post(route("logout"), {
    onSuccess: () => {
      loggedOutModal.value = true
    },
  });
};
</script>

<template>
  <div>

    <Head :title="title" />

    <div class="flex h-screen flex-col">
      <!-- Top nav-->
      <header class="relative flex h-16 flex-shrink-0 items-center bg-white">
        <!-- Logo area -->
        <div class="absolute inset-y-0 left-0 md:static md:flex-shrink-0">
          <a href="#"
            class="flex h-16 w-16 items-center justify-center bg-light-green-900 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-light-green-600 md:w-20">
            <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/workflow-mark.svg?color=white"
              alt="Workflow" />
          </a>
        </div>

        <!-- Menu button area -->
        <div class="absolute inset-y-0 right-0 flex items-center pr-4 sm:pr-6 md:hidden">
          <!-- Mobile menu button -->
          <button @click="mobileMenu = true" type="button"
            class="-mr-2 inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-green-600">
            <span class="sr-only">Open main menu</span>
            <!-- Heroicon name: outline/menu -->
            <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
              stroke-width="2" stroke="currentColor" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
        </div>

        <!-- Desktop nav area -->
        <div class="hidden md:flex md:min-w-0 md:flex-1 md:items-center md:justify-between">
          <div class="min-w-0 flex-1">
            <div class="relative max-w-2xl text-gray-400 focus-within:text-gray-500">
              <label for="desktop-search" class="sr-only">Search</label>
              <input id="desktop-search" type="search" placeholder="Search"
                class="block w-full border-transparent text-gray-800 font-extrabold pl-12 placeholder-gray-700 focus:border-transparent focus:ring-0 sm:text-sm" />
              <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center justify-center pl-4">
                <!-- Heroicon name: solid/search -->
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                  aria-hidden="true">
                  <path fill-rule="evenodd"
                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                    clip-rule="evenodd" />
                </svg>
              </div>
            </div>
          </div>
          <div class="ml-10 flex flex-shrink-0 items-center space-x-10 pr-4">
            <!-- <nav aria-label="Global" class="flex space-x-10">
              <a href="#" class="text-sm font-medium text-gray-900">Inboxes</a>
              <a href="#" class="text-sm font-medium text-gray-900">Reporting</a>
              <a href="#" class="text-sm font-medium text-gray-900">Settings</a>
            </nav> -->
            <div class="flex items-center space-x-8">
              <span class="inline-flex">
                <a href="#" class="-mx-1 rounded-full bg-white p-1 text-gray-400 hover:text-gray-500">
                  <span class="sr-only">View notifications</span>
                  <!-- Heroicon name: outline/bell -->
                  <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                  </svg>
                </a>
              </span>

              <div class="relative inline-block text-left">
                <button @click="myProfile = !myProfile" type="button"
                  class="flex rounded-full text-light-green-800 hover:text-light-green-900 text-sm focus:outline-none focus:ring-2 focus:ring-light-green-900 focus:ring-offset-2"
                  id="menu-0-button" aria-expanded="false" aria-haspopup="true">
                  <span class="sr-only">Open user menu</span>
                  <i class="fa fa-cog fa-2x p-1"></i>
                  <!-- <img
                    class="h-8 w-8 rounded-full"
                    src="https://images.unsplash.com/photo-1517365830460-955ce3ccd263?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                    alt=""
                  /> -->
                </button>

                <div v-if="myProfile"
                  class="absolute right-0 z-30 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                  role="menu" aria-orientation="vertical" aria-labelledby="menu-0-button" tabindex="-1">
                  <div class="py-1" role="none">
                    <!-- Active: "bg-gray-100", Not Active: "" -->
                    <Link :href="route('profile.show')" class="block px-4 py-2 text-sm text-gray-700" role="menuitem"
                      tabindex="-1" id="menu-0-item-0">
                    Your Profile
                    </Link>
                    <button @click.prevent="logout" class="block px-4 py-2 text-sm text-gray-700" role="menuitem"
                      tabindex="-1" id="menu-0-item-1">
                      Sign Out
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Mobile menu, show/hide this `div` based on menu open/closed state -->

        <div v-if="mobileMenu" class="relative z-40 md:hidden" role="dialog" aria-modal="true">
          <!--
        Off-canvas menu backdrop, show/hide based on off-canvas menu state.

        Entering: "transition-opacity ease-linear duration-300"
          From: "opacity-0"
          To: "opacity-100"
        Leaving: "transition-opacity ease-linear duration-300"
          From: "opacity-100"
          To: "opacity-0"
      -->
          <div class="hidden sm:fixed sm:inset-0 sm:block sm:bg-gray-600 sm:bg-opacity-75"></div>

          <div class="fixed inset-0 z-40">
            <!--
          Mobile menu, toggle classes based on menu state.

          Entering: "transition ease-out duration-150 sm:ease-in-out sm:duration-300"
            From: "transform opacity-0 scale-110 sm:translate-x-full sm:scale-100 sm:opacity-100"
            To: "transform opacity-100 scale-100  sm:translate-x-0 sm:scale-100 sm:opacity-100"
          Leaving: "transition ease-in duration-150 sm:ease-in-out sm:duration-300"
            From: "transform opacity-100 scale-100 sm:translate-x-0 sm:scale-100 sm:opacity-100"
            To: "transform opacity-0 scale-110  sm:translate-x-full sm:scale-100 sm:opacity-100"
        -->
            <div
              class="fixed inset-0 z-40 h-full w-full bg-white sm:inset-y-0 sm:left-auto sm:right-0 sm:w-full sm:max-w-sm sm:shadow-lg"
              aria-label="Global">
              <div class="flex h-16 items-center justify-between px-4 sm:px-6">
                <a href="#">
                  <img class="block h-8 w-auto"
                    src="https://tailwindui.com/img/logos/workflow-mark.svg?color=green&shade=500" alt="Workflow" />
                </a>
                <button @click="mobileMenu = false" type="button"
                  class="-mr-2 inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-green-600">
                  <span class="sr-only">Close main menu</span>
                  <!-- Heroicon name: outline/x -->
                  <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
              <div class="max-w-8xl mx-auto mt-2 px-4 sm:px-6">
                <div class="relative text-gray-400 focus-within:text-gray-500">
                  <label for="mobile-search" class="sr-only">Search</label>
                  <input id="mobile-search" type="search" placeholder="Search"
                    class="block w-full rounded-md border-gray-300 pl-10 placeholder-gray-500 focus:border-green-600 focus:ring-green-600" />
                  <div class="absolute inset-y-0 left-0 flex items-center justify-center pl-3">
                    <!-- Heroicon name: solid/search -->
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                      aria-hidden="true">
                      <path fill-rule="evenodd"
                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                        clip-rule="evenodd" />
                    </svg>
                  </div>
                </div>
              </div>
              <div class="max-w-8xl mx-auto py-3 px-2 sm:px-4">
                <Link v-if="
                  adminLevel == 1 ||
                  adminLevel == 2 ||
                  adminLevel == 3 ||
                  adminLevel == 4
                " href="/dashboard" :class="
  currentRoute == 'dashboard' ? 'text-black' : 'text-gray-500 pl-5'
" class="block rounded-md py-2 px-3 text-lg font-bold hover:bg-gray-100">Sale</Link>

                <Link v-if="adminLevel == 1" href="/dashboard/product_inventory" :class="
                  currentRoute == 'product_inventory'
                    ? 'text-black'
                    : 'text-gray-500 pl-5'
                " class="block rounded-md py-2 pr-3 text-lg font-bold hover:bg-gray-100">Inventory</Link>

                <Link v-if="adminLevel == 1 || adminLevel == 2 " href="/dashboard/product_delivery"
                  :class="
                    currentRoute == 'product_delivery'
                      ? 'text-black'
                      : 'text-gray-500 pl-5'
                  " class="block rounded-md py-2 pr-3 text-lg font-bold hover:bg-gray-100">Delivery</Link>

                <Link v-if="adminLevel == 1 || adminLevel == 2" href="/dashboard/users_clients" :class="
                  currentRoute == 'add_client' ? 'text-black' : 'text-gray-500 pl-5'
                " class="block rounded-md py-2 pr-3 text-lg font-bold hover:bg-gray-100">Users</Link>
              </div>
              <hr />
              <div class="border-t border-gray-200 pt-4 pb-3">
                <div class="max-w-8xl mx-auto mt-3 space-y-1 px-2 sm:px-4">
                  <a href="#"
                    class="block rounded-md py-2 px-3 text-base font-medium text-gray-900 hover:bg-gray-50">Your
                    Profile</a>

                  <a href="#"
                    class="block rounded-md py-2 px-3 text-base font-medium text-gray-900 hover:bg-gray-50">Logout</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </header>

      <!-- Bottom section -->
      <div class="flex min-h-0 flex-1 overflow-hidden">
        <!-- Narrow sidebar-->
        <nav aria-label="Sidebar" class="hidden md:block md:flex-shrink-0 md:overflow-y-auto md:bg-light-green-900">
          <div class="relative flex w-20 flex-col space-y-3 p-3">
            <Link v-if="
              adminLevel == 1 || adminLevel == 2 || adminLevel == 3 || adminLevel == 4
            " href="/dashboard" :class="
  currentRoute == 'dashboard'
    ? 'bg-white text-black'
    : 'bg-black text-white'
" class="text-gray-400 flex-shrink-0 inline-flex items-center justify-center h-14 w-14 rounded-lg">
            <span class="sr-only">Open</span>
            <!-- Heroicon name: outline/inbox -->
            <i class="fas fa-print fa-xl"></i>
            </Link>

            <Link v-if="adminLevel == 1 " href="/dashboard/product_inventory" :class="
              currentRoute == 'product_inventory'
                ? 'bg-white text-black'
                : 'bg-black text-white'
            "
              class="text-gray-400 hover:bg-white hover:text-black flex-shrink-0 inline-flex items-center justify-center h-14 w-14 rounded-lg">
            <span class="sr-only">Archive</span>
            <!-- Heroicon name: outline/archive -->
            <i class="fas fa-box-open fa-xl"></i>
            </Link>

            <Link v-if="adminLevel == 1 || adminLevel == 2" href="/dashboard/product_delivery"
              :class="
                currentRoute == 'product_delivery'
                  ? 'bg-white text-black'
                  : 'bg-black text-white'
              "
              class="text-gray-400 hover:bg-white hover:text-black flex-shrink-0 inline-flex items-center justify-center h-14 w-14 rounded-lg">
            <span class="sr-only">Archive</span>
            <!-- Heroicon name: outline/archive -->
            <i class="fas fa-people-carry-box fa-xl"></i>
            </Link>

            <Link v-if="
              adminLevel == 1 || adminLevel == 2
            " href="/reports" :class="
  currentRoute == 'reports'
    ? 'bg-white text-black'
    : 'bg-black text-white'
"
              class="text-gray-400 flex-shrink-0 inline-flex items-center justify-center h-14 w-14 rounded-lg">
            <span class="sr-only">Open</span>
            <!-- Heroicon name: outline/inbox -->
            <i class="fa-solid fa-book-open fa-xl"></i>
            </Link>

            <Link v-if="adminLevel == 1 || adminLevel == 2" href="/dashboard/users_clients" :class="
              currentRoute == 'users_clients'
                ? 'bg-white text-black'
                : 'bg-black text-white'
            "
              class="text-gray-400 hover:bg-white hover:text-black flex-shrink-0 inline-flex items-center justify-center h-14 w-14 rounded-lg">
            <span class="sr-only">Archive</span>
            <!-- Heroicon name: outline/archive -->
            <i class="fas fa-users fa-xl"></i>
            </Link>



          </div>
        </nav>

        <!-- Main area -->
        <main class="min-w-0 flex-1 border-t border-gray-200 lg:flex">
          <!-- Primary column -->
          <section aria-labelledby="primary-heading"
            class="flex h-full min-w-0 flex-1 flex-col overflow-y-auto lg:order-last">
            <h1 id="primary-heading" class="sr-only">Home</h1>
            <!-- Your content -->
            <slot />
          </section>

          <div v-if="loggedOutModal" class="relative z-10" aria-labelledby="modal-title" role="dialog"
            aria-modal="true">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

            <div class="fixed inset-0 z-10 overflow-y-auto">
              <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">

                <div
                  class="relative transform overflow-hidden rounded-lg bg-white px-4 pt-5 pb-4 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-sm sm:p-6">
                  <div>
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
                      <!-- Heroicon name: outline/check -->
                      <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                      </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-5">
                      <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                        Karibu Tena
                      </h3>
                    </div>
                  </div>
                  <div class="mt-5 sm:mt-6">
                    <Link href="/" type="button"
                      class="inline-flex w-full justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:text-sm">
                    Go to Landing Page
                    </Link>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </main>
      </div>
    </div>

    <!-- <div class="min-h-screen bg-gray-100">
      <main>
        <slot />
      </main>
    </div> -->
  </div>
</template>
