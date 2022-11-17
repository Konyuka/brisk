<script setup>
// import { useForm, usePage, Link } from "@inertiajs/inertia-vue3";
import { ref, computed, reactive, watch, onMounted } from "vue";
// import { Inertia } from "@inertiajs/inertia";
import AppLayout from "@/Layouts/AppLayout.vue";
import TripReport from "@/Pages/Reports/TripReport.vue";


defineProps({
    //   canLogin: Boolean,
});

const currentReport = ref("trips");

const setReportMenu = (reportMenu) => {
    console.log(reportMenu)
    currentReport.value = reportMenu
}

</script>

<template>
    <AppLayout title="Dashboard">
        <div>

            <div class="mt-20 max-w-xl mb-10 md:mx-auto sm:text-center lg:max-w-2xl md:mb-12">
                <h2
                    class="max-w-lg mb-6 font-sans text-3xl font-bold leading-none tracking-tight text-gray-900 sm:text-4xl md:mx-auto">
                    <span class="relative inline-block">
                        <svg viewBox="0 0 52 24" fill="currentColor"
                            class="absolute top-0 left-0 z-0 hidden w-32 -mt-8 -ml-20 text-blue-gray-100 lg:w-32 lg:-ml-28 lg:-mt-10 sm:block">
                            <defs>
                                <pattern id="34f481be-159a-4846-821d-9ca19fb6bcc5" x="0" y="0" width=".135"
                                    height=".30">
                                    <circle cx="1" cy="1" r=".7"></circle>
                                </pattern>
                            </defs>
                            <rect fill="url(#34f481be-159a-4846-821d-9ca19fb6bcc5)" width="52" height="24"></rect>
                        </svg>
                        <span class="relative">Reports</span>
                    </span>
                </h2>
            </div>

            <div>
                <div class="sm:hidden">
                    <label for="tabs" class="sr-only">Select a tab</label>
                    <!-- Use an "onChange" listener to redirect the user to the selected tab URL. -->
                    <select id="tabs" name="tabs"
                        class="block w-full rounded-md border-gray-300 focus:border-green-500 focus:ring-green-500">
                        <option selected>Trips Report</option>

                        <option>Sales Report</option>

                        <option>Inventory Report</option>

                        <option>Users Report</option>
                    </select>
                </div>
                <div class="hidden sm:block">
                    <nav class="isolate flex divide-x divide-gray-200 rounded-lg shadow" aria-label="Tabs">
                        <!-- Current: "text-gray-900", Default: "text-gray-500 hover:text-gray-700" -->
                        <button @click="setReportMenu('trips')"
                            class="text-gray-900 rounded-l-lg group relative min-w-0 flex-1 overflow-hidden bg-white py-4 px-4 text-sm font-bold text-center hover:bg-gray-50 focus:z-10"
                            aria-current="page">
                            <span>Trips Report</span>
                            <span aria-hidden="true" :class="
                                currentReport == 'trips'
                                    ? 'bg-green-500'
                                    : 'bg-transparent'
                            " class="0 absolute inset-x-0 bottom-0 h-0.5"></span>
                        </button>

                        <button @click="setReportMenu('sales')"
                            class="text-gray-500 hover:text-gray-700 group relative min-w-0 flex-1 overflow-hidden bg-white py-4 px-4 text-sm font-medium text-center hover:bg-gray-50 focus:z-10">
                            <span>Sales Report</span>
                            <span aria-hidden="true" :class="
                                currentReport == 'sales'
                                    ? 'bg-green-500'
                                    : 'bg-transparent'
                            " class="bg-transparent absolute inset-x-0 bottom-0 h-0.5"></span>
                        </button>

                        <button @click="setReportMenu('inventory')"
                            class="text-gray-500 hover:text-gray-700 group relative min-w-0 flex-1 overflow-hidden bg-white py-4 px-4 text-sm font-medium text-center hover:bg-gray-50 focus:z-10">
                            <span>Inventory Report</span>
                            <span aria-hidden="true" :class="
                                currentReport == 'inventory'
                                    ? 'bg-green-500'
                                    : 'bg-transparent'
                            " class="bg-transparent absolute inset-x-0 bottom-0 h-0.5"></span>
                        </button>

                        <button @click="setReportMenu('users')"
                            class="text-gray-500 hover:text-gray-700 rounded-r-lg group relative min-w-0 flex-1 overflow-hidden bg-white py-4 px-4 text-sm font-medium text-center hover:bg-gray-50 focus:z-10">
                            <span>Users Report</span>
                            <span aria-hidden="true" :class="
                                currentReport == 'users'
                                    ? 'bg-green-500'
                                    : 'bg-transparent'
                            " class="bg-transparent absolute inset-x-0 bottom-0 h-0.5"></span>
                        </button>
                    </nav>
                </div>
            </div>

            <TripReport/>

        </div>

    </AppLayout>

</template>