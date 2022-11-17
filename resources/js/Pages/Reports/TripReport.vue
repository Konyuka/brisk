<script setup>
// import { useForm, usePage, Link } from "@inertiajs/inertia-vue3";
import { ref, computed, reactive, watch, onMounted } from "vue";
// import { Inertia } from "@inertiajs/inertia";
import moment from "moment";


const props = defineProps({
    trips: Array,
    users: Array,
    sales: Array,
});

const openFilters = ref(false);
const openSort = ref(false);

const formatToCurrency = (amount) => {
    return amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,");
};

const formatTime = (value) => {
    return moment(value).format("MMMM Do YYYY, h:mm:ss a");
};

const checkValueLoaded = (data) => {

}
const checkValueSold = (data) => {

}
const checkLoadedItems = (data) => {

}
const checkReturnedItems = (data) => {

}
const checkSpoiledItems = (data) => {

}
const checkMissingItems = (data) => {

}

</script>

<template>
<div class="my-10">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="mb-10 sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-xl font-semibold text-gray-900">All Trips Report</h1>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                <button type="button"
                    class="inline-flex items-center justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:w-auto">Date Range <i class="ml-2 fas fa-calendar"></i></button>
            </div>
        </div>

       
        <div class="bg-gray-50 shadow-2xl mb-20">
            <!-- Filters -->
            <section aria-labelledby="filter-heading" class="grid items-center border-t border-b border-gray-200">
                <h2 id="filter-heading" class="sr-only">Filters</h2>
                <div class="relative col-start-1 row-start-1 py-4">
                    <div class="mx-auto flex max-w-7xl space-x-6 divide-x divide-gray-200 px-4 text-sm sm:px-6 lg:px-8">
                        <div>
                            <button @click="openFilters = !openFilters"
                            type="button" class="group flex items-center font-medium text-gray-700"
                                aria-controls="disclosure-1" aria-expanded="false">
                                <!-- Heroicon name: mini/funnel -->
                                <svg class="mr-2 h-5 w-5 flex-none text-gray-400 group-hover:text-gray-500" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M2.628 1.601C5.028 1.206 7.49 1 10 1s4.973.206 7.372.601a.75.75 0 01.628.74v2.288a2.25 2.25 0 01-.659 1.59l-4.682 4.683a2.25 2.25 0 00-.659 1.59v3.037c0 .684-.31 1.33-.844 1.757l-1.937 1.55A.75.75 0 018 18.25v-5.757a2.25 2.25 0 00-.659-1.591L2.659 6.22A2.25 2.25 0 012 4.629V2.34a.75.75 0 01.628-.74z"
                                        clip-rule="evenodd" />
                                </svg>
                                2 Filters
                            </button>
                        </div>
                        <div class="pl-6">
                            <button type="button" class="text-gray-500">Clear all</button>
                        </div>
                    </div>
                </div>
                <div v-if="openFilters" 
                class="border-t border-gray-200 py-10" id="disclosure-1">
                    <div class="mx-auto grid max-w-7xl grid-cols-2 gap-x-4 px-4 text-sm sm:px-6 md:gap-x-6 lg:px-8">
                        <div class="grid auto-rows-min grid-cols-1 gap-y-10 md:grid-cols-2 md:gap-x-6">
                            <fieldset>
                                <legend class="block font-medium">Team Lead</legend>
                                <div class="space-y-6 pt-6 sm:space-y-4 sm:pt-4">
                                    <div class="flex items-center text-base sm:text-sm">
                                        <input id="price-0" name="price[]" value="0" type="checkbox"
                                            class="h-4 w-4 flex-shrink-0 rounded border-gray-300 text-green-600 focus:ring-green-500">
                                        <label for="price-0" class="ml-3 min-w-0 flex-1 text-gray-600">Ann</label>
                                    </div>
        
                                    <div class="flex items-center text-base sm:text-sm">
                                        <input id="price-1" name="price[]" value="25" type="checkbox"
                                            class="h-4 w-4 flex-shrink-0 rounded border-gray-300 text-green-600 focus:ring-green-500">
                                        <label for="price-1" class="ml-3 min-w-0 flex-1 text-gray-600">Tony</label>
                                    </div>
                                    
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend class="block font-medium">Trip Status</legend>
                                <div class="space-y-6 pt-6 sm:space-y-4 sm:pt-4">
                                    <div class="flex items-center text-base sm:text-sm">
                                        <input id="color-0" name="color[]" value="white" type="checkbox"
                                            class="h-4 w-4 flex-shrink-0 rounded border-gray-300 text-green-600 focus:ring-green-500">
                                        <label for="color-0" class="ml-3 min-w-0 flex-1 text-gray-600">Open Trip</label>
                                    </div>
        
                                    <div class="flex items-center text-base sm:text-sm">
                                        <input id="color-1" name="color[]" value="beige" type="checkbox"
                                            class="h-4 w-4 flex-shrink-0 rounded border-gray-300 text-green-600 focus:ring-green-500">
                                        <label for="color-1" class="ml-3 min-w-0 flex-1 text-gray-600">Closed Trips</label>
                                    </div>
        
                                    <div class="flex items-center text-base sm:text-sm">
                                        <input id="color-2" name="color[]" value="blue" type="checkbox"
                                            class="h-4 w-4 flex-shrink-0 rounded border-gray-300 text-green-600 focus:ring-green-500"
                                            checked>
                                        <label for="color-2" class="ml-3 min-w-0 flex-1 text-gray-600">With Missing Items</label>
                                    </div>
        
                                    <div class="flex items-center text-base sm:text-sm">
                                        <input id="color-3" name="color[]" value="brown" type="checkbox"
                                            class="h-4 w-4 flex-shrink-0 rounded border-gray-300 text-green-600 focus:ring-green-500">
                                        <label for="color-3" class="ml-3 min-w-0 flex-1 text-gray-600">With Spoiled Items</label>
                                    </div>
        
                                </div>
                            </fieldset>
                        </div>

                        <!-- <div class="grid auto-rows-min grid-cols-1 gap-y-10 md:grid-cols-2 md:gap-x-6">
                            <fieldset>
                                <legend class="block font-medium">Size</legend>
                                <div class="space-y-6 pt-6 sm:space-y-4 sm:pt-4">
                                    <div class="flex items-center text-base sm:text-sm">
                                        <input id="size-0" name="size[]" value="xs" type="checkbox"
                                            class="h-4 w-4 flex-shrink-0 rounded border-gray-300 text-green-600 focus:ring-green-500">
                                        <label for="size-0" class="ml-3 min-w-0 flex-1 text-gray-600">XS</label>
                                    </div>
        
                                    <div class="flex items-center text-base sm:text-sm">
                                        <input id="size-1" name="size[]" value="s" type="checkbox"
                                            class="h-4 w-4 flex-shrink-0 rounded border-gray-300 text-green-600 focus:ring-green-500"
                                            checked>
                                        <label for="size-1" class="ml-3 min-w-0 flex-1 text-gray-600">S</label>
                                    </div>
        
                                    <div class="flex items-center text-base sm:text-sm">
                                        <input id="size-2" name="size[]" value="m" type="checkbox"
                                            class="h-4 w-4 flex-shrink-0 rounded border-gray-300 text-green-600 focus:ring-green-500">
                                        <label for="size-2" class="ml-3 min-w-0 flex-1 text-gray-600">M</label>
                                    </div>
        
                                    <div class="flex items-center text-base sm:text-sm">
                                        <input id="size-3" name="size[]" value="l" type="checkbox"
                                            class="h-4 w-4 flex-shrink-0 rounded border-gray-300 text-green-600 focus:ring-green-500">
                                        <label for="size-3" class="ml-3 min-w-0 flex-1 text-gray-600">L</label>
                                    </div>
        
                                    <div class="flex items-center text-base sm:text-sm">
                                        <input id="size-4" name="size[]" value="xl" type="checkbox"
                                            class="h-4 w-4 flex-shrink-0 rounded border-gray-300 text-green-600 focus:ring-green-500">
                                        <label for="size-4" class="ml-3 min-w-0 flex-1 text-gray-600">XL</label>
                                    </div>
        
                                    <div class="flex items-center text-base sm:text-sm">
                                        <input id="size-5" name="size[]" value="2xl" type="checkbox"
                                            class="h-4 w-4 flex-shrink-0 rounded border-gray-300 text-green-600 focus:ring-green-500">
                                        <label for="size-5" class="ml-3 min-w-0 flex-1 text-gray-600">2XL</label>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend class="block font-medium">Category</legend>
                                <div class="space-y-6 pt-6 sm:space-y-4 sm:pt-4">
                                    <div class="flex items-center text-base sm:text-sm">
                                        <input id="category-0" name="category[]" value="all-new-arrivals" type="checkbox"
                                            class="h-4 w-4 flex-shrink-0 rounded border-gray-300 text-green-600 focus:ring-green-500">
                                        <label for="category-0" class="ml-3 min-w-0 flex-1 text-gray-600">All New
                                            Arrivals</label>
                                    </div>
        
                                    <div class="flex items-center text-base sm:text-sm">
                                        <input id="category-1" name="category[]" value="tees" type="checkbox"
                                            class="h-4 w-4 flex-shrink-0 rounded border-gray-300 text-green-600 focus:ring-green-500">
                                        <label for="category-1" class="ml-3 min-w-0 flex-1 text-gray-600">Tees</label>
                                    </div>
        
                                    <div class="flex items-center text-base sm:text-sm">
                                        <input id="category-2" name="category[]" value="objects" type="checkbox"
                                            class="h-4 w-4 flex-shrink-0 rounded border-gray-300 text-green-600 focus:ring-green-500">
                                        <label for="category-2" class="ml-3 min-w-0 flex-1 text-gray-600">Objects</label>
                                    </div>
        
                                    <div class="flex items-center text-base sm:text-sm">
                                        <input id="category-3" name="category[]" value="sweatshirts" type="checkbox"
                                            class="h-4 w-4 flex-shrink-0 rounded border-gray-300 text-green-600 focus:ring-green-500">
                                        <label for="category-3" class="ml-3 min-w-0 flex-1 text-gray-600">Sweatshirts</label>
                                    </div>
        
                                    <div class="flex items-center text-base sm:text-sm">
                                        <input id="category-4" name="category[]" value="pants-and-shorts" type="checkbox"
                                            class="h-4 w-4 flex-shrink-0 rounded border-gray-300 text-green-600 focus:ring-green-500">
                                        <label for="category-4" class="ml-3 min-w-0 flex-1 text-gray-600">Pants &amp;
                                            Shorts</label>
                                    </div>
                                </div>
                            </fieldset>
                        </div> -->

                    </div>
                </div>
                <div class="col-start-1 row-start-1 py-4">
                    <div class="mx-auto flex max-w-7xl justify-end px-4 sm:px-6 lg:px-8">
                        <div class="relative inline-block">
                            <div class="flex">
                                <button @click="openSort = !openSort"
                                type="button"
                                    class="group inline-flex justify-center text-sm font-medium text-gray-700 hover:text-gray-900"
                                    id="menu-button" aria-expanded="false" aria-haspopup="true">
                                    Sort
                                    <!-- Heroicon name: mini/chevron-down -->
                                    <svg class="-mr-1 ml-1 h-5 w-5 flex-shrink-0 text-gray-400 group-hover:text-gray-500"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
        
                            
                            <div v-if="openSort" 
                            class="absolute right-0 z-10 mt-2 w-40 origin-top-right rounded-md bg-white shadow-2xl ring-1 ring-black ring-opacity-5 focus:outline-none"
                                role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                                <div class="py-1" role="none">
                                    
                                    <a href="#" class="font-medium text-gray-900 block px-4 py-2 text-sm" role="menuitem"
                                        tabindex="-1" id="menu-item-0">Latest</a>
        
                                    <a href="#" class="text-gray-500 block px-4 py-2 text-sm" role="menuitem" tabindex="-1"
                                        id="menu-item-1">Earliest</a>
        
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>


        <div class="mt-8 flex flex-col">
            <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="whitespace-nowrap py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                        Trip ID</th>
                                    <th scope="col"
                                        class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">
                                        Location</th>
                                    <th scope="col"
                                        class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">
                                        Timestamp</th>
                                    <th scope="col"
                                        class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">
                                        Value Loaded</th>
                                    <th scope="col"
                                        class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">
                                        Value Sold</th>
                                    <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">
                                        Loaded Items</th>
                                    <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">
                                        Returned Items</th>
                                    <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">
                                        Spoiled Items</th>
                                    <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">
                                        Missing Items</th>
                                    <th scope="col" class="relative whitespace-nowrap py-3.5 pl-3 pr-4 sm:pr-6">
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                <tr v-for="(trip, index) in trips" :key="index">
                                    <td class="whitespace-nowrap py-2 pl-4 pr-3 text-sm text-black font-extrabold sm:pl-6">{{ trip.id }} - <span class="font-bold capitalize">{{ trip.lead_name }}</span></td>
                                    <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-500 capitalize">{{ trip.trip_location }}</td>
                                    <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">{{ formatTime(trip.created_at) }}</td>
                                    <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">{{ checkValueLoaded(trip.porducts_summary) }}</td>
                                    <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">{{ checkValueSold(trip.porducts_summary) }}</td>
                                    <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">{{ checkLoadedItems(trip.porducts_summary) }}</td>
                                    <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">{{ checkReturnedItems(trip.porducts_summary) }}</td>
                                    <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">{{ checkSpoiledItems(trip.porducts_summary) }}</td>
                                    <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">{{ checkMissingItems(trip.porducts_summary) }}</td>
                                    <td
                                        class="relative whitespace-nowrap py-2 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                        <a href="#" class="text-green-600 hover:text-green-900">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
    
                                <!-- More transactions... -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>