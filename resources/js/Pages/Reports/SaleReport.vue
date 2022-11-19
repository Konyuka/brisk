<script setup>
// import { useForm, usePage, Link } from "@inertiajs/inertia-vue3";
import { ref, computed, reactive, watch, onMounted } from "vue";
// import { Inertia } from "@inertiajs/inertia";
import moment from "moment";
import { parseInt } from "lodash";


const props = defineProps({
    products: Array,
    trips: Array,
    users: Array,
    sales: Array,
});



const tripLocation = ref(null);
const salesReportDataModal = ref(false);
const openSort = ref(false);
const openFilters = ref(false);


const findLocation = (data) => {
    let trip = props.trips.find(obj => obj.id === data);
    return trip.trip_location
}

const findLeadName = (data) => {
    let trip = props.trips.find(obj => obj.id === data);
    return trip.lead_name
}

const formatToCurrency = (amount) => {
    if (amount != undefined) {
        return Number(amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,");
    }
};

const formatTime = (value) => {
    return moment(value).format("MMMM Do YYYY, h:mm:ss a");
};


</script>

<template>
    <div class="my-10">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="mb-10 sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-xl font-semibold text-gray-900">All Sales Report</h1>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                    <button type="button"
                        class="inline-flex items-center justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:w-auto">Date
                        Range <i class="ml-2 fas fa-calendar"></i></button>
                </div>
            </div>


            <div class="bg-gray-50 shadow-2xl mb-20">
                <!-- Filters -->
                <section aria-labelledby="filter-heading" class="grid items-center border-t border-b border-gray-200">
                    <h2 id="filter-heading" class="sr-only">Filters</h2>
                    <div class="relative col-start-1 row-start-1 py-4">
                        <div
                            class="mx-auto flex max-w-7xl space-x-6 divide-x divide-gray-200 px-4 text-sm sm:px-6 lg:px-8">
                            <div>
                                <button @click="openFilters = !openFilters" type="button"
                                    class="group flex items-center font-medium text-gray-700"
                                    aria-controls="disclosure-1" aria-expanded="false">
                                    <!-- Heroicon name: mini/funnel -->
                                    <svg class="mr-2 h-5 w-5 flex-none text-gray-400 group-hover:text-gray-500"
                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                        fill="currentColor">
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
                    <div v-if="openFilters" class="border-t border-gray-200 py-10" id="disclosure-1">
                        <div class="mx-auto grid max-w-7xl grid-cols-4 gap-x-4 px-4 text-sm sm:px-6 md:gap-x-6 lg:px-8">
                            <fieldset>
                                <legend class="block font-medium">Teams</legend>
                                <div class="space-y-6 pt-6 sm:space-y-4 sm:pt-4">
                                    <div class="flex items-center text-base sm:text-sm">
                                        <input id="price-0" name="price[]" value="0" type="checkbox"
                                            class="h-4 w-4 flex-shrink-0 rounded border-gray-300 text-green-600 focus:ring-green-500">
                                        <label for="price-0" class="ml-3 min-w-0 flex-1 text-gray-600">Ann's</label>
                                    </div>

                                    <div class="flex items-center text-base sm:text-sm">
                                        <input id="price-1" name="price[]" value="25" type="checkbox"
                                            class="h-4 w-4 flex-shrink-0 rounded border-gray-300 text-green-600 focus:ring-green-500">
                                        <label for="price-1" class="ml-3 min-w-0 flex-1 text-gray-600">Tony's</label>
                                    </div>

                                    <div class="flex items-center text-base sm:text-sm">
                                        <input id="price-1" name="price[]" value="25" type="checkbox"
                                            class="h-4 w-4 flex-shrink-0 rounded border-gray-300 text-green-600 focus:ring-green-500">
                                        <label for="price-1" class="ml-3 min-w-0 flex-1 text-gray-600">Amos's</label>
                                    </div>

                                </div>
                            </fieldset>
                            <div class="grid auto-rows-min grid-cols-1 gap-y-10 md:grid-cols-2 md:gap-x-6">

                                <fieldset>
                                    <legend class="block font-medium">Payment Method</legend>
                                    <div class="space-y-6 pt-6 sm:space-y-4 sm:pt-4">
                                        <div class="flex items-center text-base sm:text-sm">
                                            <input id="price-0" name="price[]" value="0" type="checkbox"
                                                class="h-4 w-4 flex-shrink-0 rounded border-gray-300 text-green-600 focus:ring-green-500">
                                            <label for="price-0" class="ml-3 min-w-0 flex-1 text-gray-600">Mpesa</label>
                                        </div>

                                        <div class="flex items-center text-base sm:text-sm">
                                            <input id="price-1" name="price[]" value="25" type="checkbox"
                                                class="h-4 w-4 flex-shrink-0 rounded border-gray-300 text-green-600 focus:ring-green-500">
                                            <label for="price-1" class="ml-3 min-w-0 flex-1 text-gray-600">Cash</label>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset class="bg-white">
                                    <legend class="block text-sm font-medium text-gray-700">Select Trip Batch</legend>
                                    <div class="mt-1 -space-y-px rounded-md shadow-sm">
                                        <div>
                                            <label for="country" class="sr-only">Batch</label>
                                            <select id="country" name="country" autocomplete="country-name"
                                                class="relative block w-full rounded-none rounded-t-md border-gray-300 bg-transparent focus:z-10 focus:border-green-500 focus:ring-green-500 sm:text-sm">
                                                <option></option>
                                                <option class="font-bold">20 - Anne's Trip</option>
                                                <option class="font-bold">22 - Tony's Trip</option>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <fieldset class="bg-white">
                                <legend class="block text-sm font-medium text-gray-700">Select Sale Range</legend>
                                <div class="mt-1 -space-y-px rounded-md shadow-sm">
                                    <div>
                                        <label for="country" class="sr-only">Batch</label>
                                        <select id="country" name="country" autocomplete="country-name"
                                            class="relative block w-full rounded-none rounded-t-md border-gray-300 bg-transparent focus:z-10 focus:border-green-500 focus:ring-green-500 sm:text-sm">
                                            <option></option>
                                            <option class="font-bold">KES 1000 - KES 2000</option>
                                            <option class="font-bold">KES 2000 - KES 5000</option>
                                            <option class="font-bold">KES 5000 - KES 10000</option>
                                            <option class="font-bold">KES 10000 - KES 20000</option>
                                        </select>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="bg-white">
                                <legend class="block text-sm font-medium text-gray-700">Select User</legend>
                                <div class="mt-1 -space-y-px rounded-md shadow-sm">
                                    <div>
                                        <label for="country" class="sr-only">User</label>
                                        <select id="country" name="country" autocomplete="country-name"
                                            class="relative block w-full rounded-none rounded-t-md border-gray-300 bg-transparent focus:z-10 focus:border-green-500 focus:ring-green-500 sm:text-sm">
                                            <option></option>
                                            <option class="font-bold">User 1</option>
                                            <option class="font-bold">User 2</option>
                                            <option class="font-bold">User 3</option>
                                        </select>
                                    </div>
                                </div>
                            </fieldset>

                        </div>
                    </div>
                    <div class="col-start-1 row-start-1 py-4">
                        <div class="mx-auto flex max-w-7xl justify-end px-4 sm:px-6 lg:px-8">
                            <div class="relative inline-block">
                                <div class="flex">
                                    <button @click="openSort = !openSort" type="button"
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

                                        <a href="#" class="font-medium text-gray-900 block px-4 py-2 text-sm"
                                            role="menuitem" tabindex="-1" id="menu-item-0">Latest</a>

                                        <a href="#" class="text-gray-500 block px-4 py-2 text-sm" role="menuitem"
                                            tabindex="-1" id="menu-item-1">Earliest</a>

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
                                            class="whitespace-nowrap py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                            Trip Location</th>
                                        <th scope="col"
                                            class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">
                                            Amount Sold</th>
                                        <th scope="col"
                                            class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">
                                            Items Sold</th>
                                        <th scope="col"
                                            class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">
                                            Timestamp</th>
                                        <th scope="col"
                                            class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">
                                            Sale From</th>
                                        <th scope="col"
                                            class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">
                                            Payment Type</th>
                                        <th scope="col"
                                            class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">
                                            Sale Type</th>
                                        <th scope="col"
                                            class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">
                                            Mpesa Reference</th>
                                        <th scope="col" class="relative whitespace-nowrap py-3.5 pl-3 pr-4 sm:pr-6">
                                            <span class="sr-only">Edit</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <tr v-for="(sale, index) in sales" :key="index">
                                        <td
                                            class="whitespace-nowrap py-2 pl-4 pr-3 text-sm text-black font-extrabold sm:pl-6">
                                            {{ sale.trip_batch }} - <span class="font-bold capitalize">{{
                                                    findLeadName(sale.trip_batch)
                                            }}</span></td>
                                        <td
                                            class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-500 capitalize">
                                            {{ findLocation(sale.trip_batch)  }}</td>
                                        <td
                                            class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-500 capitalize">
                                            {{ findAmountSold(sale.products)  }}</td>
                                        <td
                                            class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-500 capitalize">
                                            {{ findLocation(sale.trip_batch)  }}</td>
                                        <td class="whitespace-nowrap px-2 py-2 font-medium text-sm text-gray-500">{{
                                                formatTime(sale.created_at)
                                        }}</td>
                                        <td
                                            class="relative whitespace-nowrap py-2 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                            <button @click="loadReportData(sale)"
                                                class="text-green-600 hover:text-green-900">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- More transactions... -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="salesReportDataModal" class="relative z-10" aria-labelledby="modal-title" role="dialog"
                aria-modal="true">

                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

                <div class="fixed inset-0 z-10 overflow-y-auto">
                    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">

                        <div
                            class="relative transform overflow-hidden rounded-lg bg-white px-4 pt-5 pb-4 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-7xl sm:p-6">
                            <div class="absolute top-0 right-0 hidden pt-4 pr-4 sm:block">
                                <button @click="salesReportDataModal = false" type="button"
                                    class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                    <span class="sr-only">Close</span>
                                    <!-- Heroicon name: outline/x-mark -->
                                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">

                                    <div class="grid grid-cols-5">
                                        <!-- <h3 class="text-md font-medium leading-6 text-gray-900" id="modal-title"><span
                                                class="text-lg text-gray-600">Trip Number:</span> {{ tripID }}</h3>
                                        <h3 class="text-md font-medium leading-6 text-gray-900 uppercase"
                                            id="modal-title"><span class="text-lg text-gray-600 capitalize">Team
                                                Lead:</span> {{ tripTeamLead }}</h3>
                                        <h3 class="text-md font-medium leading-6 text-gray-900 " id="modal-title"><span
                                                class="text-lg text-gray-600">Trip Location:</span> {{ tripLocation }}
                                        </h3>
                                        <h3 class="text-md font-medium leading-6 text-gray-900" id="modal-title"><span
                                                class="text-lg text-gray-600">Trip Driver:</span> {{ tripDriver }}</h3>
                                        <h3 class="text-md font-medium leading-6 text-gray-900" id="modal-title"><span
                                                class="text-lg text-gray-600">Vehicle Plate:</span> {{ tripvehicle }}
                                        </h3> -->
                                    </div>

                                    <hr class="m-4 border-1 border-green-800">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</template>