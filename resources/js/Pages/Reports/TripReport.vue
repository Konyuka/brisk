<script setup>
// import { useForm, usePage, Link } from "@inertiajs/inertia-vue3";
import { ref, computed, reactive, watch, onMounted } from "vue";
import { Inertia } from "@inertiajs/inertia";
import moment from "moment";
import { parseInt } from "lodash";


const props = defineProps({
    products: Array,
    trips: Array,
    users: Array,
    sales: Array,
});


onMounted(() => {
    // console.log('component mounted');
    let rightProducts = []
    for (var i = 0; i < props.trips.length; i++) {
        let closedTrips = props.trips.find((obj) => obj.trip_complete == 1);
        rightProducts.push(closedTrips);
    }
    // console.log(rightProducts)
    
    // const objWithIdIndex = props.trips.findIndex((obj) => obj.id === id);
    // if (objWithIdIndex > -1) {
    //     arr.splice(objWithIdIndex, 1);
    // }

    // return arr;
});

const currentModalMenu = ref('loaded');
watch(currentModalMenu, (newValue) => {
    if (newValue=='sold'){
        loadSoldData()
    }
    if (newValue=='returned'){
        loadReturnedData()
    }
    if (newValue=='spoiled'){
        loadSpoiledData()
    }
    if (newValue=='missing'){
        loadMissingData()
    }
});


const missingProducts = ref([]);
const spoiledProducts = ref([]);
const returnedProducts = ref([]);
const soldProducts = ref([]);
const tripProducts = ref([]);
const tripID = ref(null);
const tripTeamLead = ref(null);
const tripDriver = ref(null);
const tripvehicle = ref(null);
const tripLocation = ref(null);

const reportDataModal = ref(false);
const openSort = ref(false);
const openFilters = ref(false);


const getWholesale = (value) => {
    let product = props.products.find(obj => obj.id === value);
    return product.wholesale_price
}

const getRetail = (value) => {
    let product = props.products.find(obj => obj.id === value);
    return product.sales_price
}

const closeReportDataModal = () => {
    // reportDataModal.value = false
    Inertia.get("/reports");
}

const missingItemsValue = () => {
    // alert('check')
    const amountsArray = []
    for (let index = 0; index < missingProducts.value.length; index++) {
        const element = missingProducts.value[index];
        // console.log(element)
        const product = props.products.find((obj) => obj.id == element.productID);
        const rowAmount = element.missingProducts * product.wholesale_price
        amountsArray.push(rowAmount)
    }
    return amountsArray.reduce((a, b) => a + b, 0)
}

const exportRep = (type, fn, dl, value) => {
    console.log(type, fn, dl, value)
    // return
    var elt = document.getElementById(value);
    var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
    return dl ?
        XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
        XLSX.writeFile(wb, fn || (tripTeamLead.value + '_' + tripID.value + '_' + currentModalMenu.value + '_' + 'report.' + (type || 'xlsx')));
}

const exportReport = (tableID, filename) => {

    var downloadLink;
    var dataType = 'application/vnd.ms-excel';
    var tableSelect = document.getElementById(tableID);
    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

    // Specify file name
    filename = filename ? filename + '.xlsx' : 'excel_data.xlsx';
    // filename = filename ? filename + '.xls' : 'excel_data.xls';

    // Create download link element
    downloadLink = document.createElement("a");

    document.body.appendChild(downloadLink);

    if (navigator.msSaveOrOpenBlob) {
        var blob = new Blob(['\ufeff', tableHTML], {
            type: dataType
        });
        navigator.msSaveOrOpenBlob(blob, filename);
    } else {
        // Create a link to the file
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;

        // Setting the file name
        downloadLink.download = filename;

        //triggering the function
        downloadLink.click();
    }


}

const sale = () => {

}

const loadMissingData = () => {
    missingProducts.value = []
    for (let index = 0; index < tripProducts.value.length; index++) {
        const element = tripProducts.value[index];
        if (element.missingProducts > 0){
            missingProducts.value.push(element)
        }
    }

}

const loadSpoiledData = () => {
    spoiledProducts.value = []
    for (let index = 0; index < tripProducts.value.length; index++) {
        const element = tripProducts.value[index];
        if (element.spoiledProducts > 0){
            spoiledProducts.value.push(element)
        }
    }

}

const loadReturnedData = () => {
    returnedProducts.value = []
    for (let index = 0; index < tripProducts.value.length; index++) {
        const element = tripProducts.value[index];
        if (element.restocked > 0){
            returnedProducts.value.push(element)
        }
    }

}

const loadSoldData = () => {
    soldProducts.value = []
    for (let index = 0; index < tripProducts.value.length; index++) {
        const element = tripProducts.value[index];
        if (element.soldProducts > 0){
            soldProducts.value.push(element)
        }
    }

}

const findInfo = (data, type) => {
    let product = props.products.find(obj => obj.id === data);
    if (type == 'name') {
        return product.product_name
    }
    if (type == 'sku') {
        return product.product_quantity
    }

}

const loadReportData = (data) => {
    reportDataModal.value = true
    tripID.value = data.id
    tripTeamLead.value = data.lead_name
    tripDriver.value = data.driver_name
    tripvehicle.value = data.vehicle_number
    tripLocation.value = data.trip_location

    let products = JSON.parse(data.products_summary)
    tripProducts.value = products

};

const formatToCurrency = (amount) => {
    if (amount != undefined) {
        return Number(amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,");
    }
};

const formatTime = (value) => {
    return moment(value).format("MMMM Do YYYY, h:mm:ss a");
};


const checkValueLost = (data) => {
    // console.log(data)
    // if (data != null) {
    //     let parseJson = JSON.parse(data)
    //     return parseInt(parseJson[0].valueLoaded)
    // }
}

const checkValueLoaded = (data) => {
    if (data != null) {
        let parseJson = JSON.parse(data)
        return parseInt(parseJson[0].valueLoaded)
    }
}
const checkValueSold = (data) => {
    if (data != null) {
        let parseJson = JSON.parse(data)
        return parseInt(parseJson[0].valueSold)
    }
}
const checkLoadedItems = (data) => {
    if (data != null) {
        let parseJson = JSON.parse(data)
        let pushArray = []
        for (let index = 0; index < parseJson.length; index++) {
            const element = parseJson[index];
            pushArray.push(element.loadedItems)
        }
        return pushArray.reduce((a, b) => a + b, 0);
    }
}
const checkSoldItems = (data) => {
    if (data != null) {
        let parseJson = JSON.parse(data)
        let pushArray = []
        for (let index = 0; index < parseJson.length; index++) {
            const element = parseJson[index];
            pushArray.push(element.soldProducts)
        }
        return pushArray.reduce((a, b) => a + b, 0);
    }
}
const checkReturnedItems = (data) => {
    if (data != null) {
        let parseJson = JSON.parse(data)
        let pushArray = []
        for (let index = 0; index < parseJson.length; index++) {
            const element = parseJson[index];
            pushArray.push(element.restocked)
        }
        return pushArray.reduce((a, b) => a + b, 0);
    }
}
const checkSpoiledItems = (data) => {
    if (data != null) {
        let parseJson = JSON.parse(data)
        let pushArray = []
        for (let index = 0; index < parseJson.length; index++) {
            const element = parseJson[index];
            pushArray.push(element.spoiledProducts)
        }
        return pushArray.reduce((a, b) => a + b, 0);
    }
}
const checkMissingItems = (data) => {
    if (data != null) {
        let parseJson = JSON.parse(data)
        let pushArray = []
        for (let index = 0; index < parseJson.length; index++) {
            const element = parseJson[index];
            pushArray.push(element.missingProducts)
        }
        return pushArray.reduce((a, b) => a + b, 0);
    }
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

                                        <div class="flex items-center text-base sm:text-sm">
                                            <input id="price-1" name="price[]" value="25" type="checkbox"
                                                class="h-4 w-4 flex-shrink-0 rounded border-gray-300 text-green-600 focus:ring-green-500">
                                            <label for="price-1" class="ml-3 min-w-0 flex-1 text-gray-600">Amos</label>
                                        </div>

                                    </div>
                                </fieldset>
                                <fieldset>
                                    <legend class="block font-medium">Trip Status</legend>
                                    <div class="space-y-6 pt-6 sm:space-y-4 sm:pt-4">
                                        <div class="flex items-center text-base sm:text-sm">
                                            <input id="color-0" name="color[]" value="white" type="checkbox"
                                                class="h-4 w-4 flex-shrink-0 rounded border-gray-300 text-green-600 focus:ring-green-500">
                                            <label for="color-0" class="ml-3 min-w-0 flex-1 text-gray-600">Open
                                                Trip</label>
                                        </div>

                                        <div class="flex items-center text-base sm:text-sm">
                                            <input id="color-1" name="color[]" value="beige" type="checkbox"
                                                class="h-4 w-4 flex-shrink-0 rounded border-gray-300 text-green-600 focus:ring-green-500">
                                            <label for="color-1" class="ml-3 min-w-0 flex-1 text-gray-600">Closed
                                                Trips</label>
                                        </div>

                                        <div class="flex items-center text-base sm:text-sm">
                                            <input id="color-2" name="color[]" value="blue" type="checkbox"
                                                class="h-4 w-4 flex-shrink-0 rounded border-gray-300 text-green-600 focus:ring-green-500"
                                                checked>
                                            <label for="color-2" class="ml-3 min-w-0 flex-1 text-gray-600">With Missing
                                                Items</label>
                                        </div>

                                        <div class="flex items-center text-base sm:text-sm">
                                            <input id="color-3" name="color[]" value="brown" type="checkbox"
                                                class="h-4 w-4 flex-shrink-0 rounded border-gray-300 text-green-600 focus:ring-green-500">
                                            <label for="color-3" class="ml-3 min-w-0 flex-1 text-gray-600">With Spoiled
                                                Items</label>
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
                            <table  class="min-w-full divide-y divide-gray-300">
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
                                        <th scope="col"
                                            class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">
                                            Loaded Items</th>
                                        <th scope="col"
                                            class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">
                                            Returned Items</th>
                                        <th scope="col"
                                            class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">
                                            Sold Items</th>
                                        <th scope="col"
                                            class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">
                                            Spoiled Items</th>
                                        <th scope="col"
                                            class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">
                                            Missing Items</th>
                                        <th scope="col" class="relative whitespace-nowrap py-3.5 pl-3 pr-4 sm:pr-6">
                                            <span class="sr-only">Edit</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <tr v-for="(trip, index) in trips" :key="index">
                                        <td
                                            class="whitespace-nowrap py-2 pl-4 pr-3 text-sm text-black font-extrabold sm:pl-6">
                                            {{ trip.id }} - <span class="font-bold capitalize">{{ trip.lead_name
                                            }}</span></td>
                                        <td
                                            class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-500 capitalize">
                                            {{ trip.trip_location }}</td>
                                        <td class="whitespace-nowrap px-2 py-2 font-medium text-sm text-gray-500">{{
                                                formatTime(trip.created_at)
                                        }}</td>
                                        <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500 font-bold">KES {{
                                                formatToCurrency(checkValueLoaded(trip.products_summary))
                                        }}</td>
                                        <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500 font-bold">KES
                                            <span class="font-bold text-green-500">{{
                                                    formatToCurrency(checkValueSold(trip.products_summary))
                                            }}</span>
                                        </td>
                                        <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500 font-bold">{{
                                                checkLoadedItems(trip.products_summary)
                                        }}</td>
                                        <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500 font-bold">{{
                                                checkReturnedItems(trip.products_summary)
                                        }}</td>
                                        <td class="whitespace-nowrap px-2 py-2 text-sm text-green-500 font-bold">{{
                                                checkSoldItems(trip.products_summary)
                                        }}</td>
                                        <td class="whitespace-nowrap px-2 py-2 text-sm text-orange-500 font-bold">{{
                                                checkSpoiledItems(trip.products_summary)
                                        }}</td>
                                        <td class="whitespace-nowrap px-2 py-2 text-sm text-red-500 font-bold">{{
                                                checkMissingItems(trip.products_summary)
                                        }}</td>
                                        <td
                                            class="relative whitespace-nowrap py-2 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                            <button @click="loadReportData(trip)"
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

            <div v-if="reportDataModal" class="relative z-10" aria-labelledby="modal-title" role="dialog"
                aria-modal="true">

                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

                <div class="fixed inset-0 z-10 overflow-y-auto">
                    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">

                        <div
                            class="relative transform overflow-hidden rounded-lg bg-white px-4 pt-5 pb-4 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-7xl sm:p-6">
                            <div class="absolute top-0 right-0 hidden pt-4 pr-4 sm:block">
                                <button @click="closeReportDataModal" type="button"
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
                                <!-- <div
                                    class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <i class="fas fa-info"></i>
                                </div> -->
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">

                                    <div class="grid grid-cols-5">
                                        <h3 class="text-md font-medium leading-6 text-gray-900" id="modal-title"><span
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
                                        </h3>
                                    </div>

                                    <hr class="m-4 border-1 border-green-800">

                                    <div class="mt-10">
                                        <div>
                                            <div class="sm:hidden">
                                                <label for="tabs" class="sr-only">Select a tab</label>
                                                <!-- Use an "onChange" listener to redirect the user to the selected tab URL. -->
                                                <select id="tabs" name="tabs"
                                                    class="block w-full rounded-md border-gray-300 focus:border-green-500 focus:ring-green-500">
                                                    <option selected>My Account</option>

                                                    <option>Company</option>

                                                    <option>Team Members</option>

                                                    <option>Billing</option>
                                                </select>
                                            </div>

                                            <div class="hidden sm:block">
                                                <nav class="isolate flex divide-x divide-gray-200 rounded-lg shadow"
                                                    aria-label="Tabs">
                                                    <button @click="currentModalMenu = 'loaded'"
                                                        class="text-gray-900 rounded-l-lg group relative min-w-0 flex-1 overflow-hidden bg-white py-4 px-4 text-sm font-medium text-center hover:bg-gray-50 focus:z-10"
                                                        aria-current="page">
                                                        <span>Loaded Products</span>
                                                        <span aria-hidden="true" :class="
                                                            currentModalMenu == 'loaded'
                                                                ? 'bg-green-500'
                                                                : 'bg-transparent'
                                                        " class="absolute inset-x-0 bottom-0 h-0.5"></span>
                                                    </button>

                                                    <button @click="currentModalMenu = 'sold'"
                                                        class="text-gray-500 hover:text-gray-700 group relative min-w-0 flex-1 overflow-hidden bg-white py-4 px-4 text-sm font-medium text-center hover:bg-gray-50 focus:z-10">
                                                        <span>Sold Products</span>
                                                        <span aria-hidden="true" :class="
                                                            currentModalMenu == 'sold'
                                                                ? 'bg-green-500'
                                                                : 'bg-transparent'
                                                        " class="absolute inset-x-0 bottom-0 h-0.5"></span>
                                                    </button>

                                                    <button @click="currentModalMenu = 'returned'"
                                                        class="text-gray-500 hover:text-gray-700 group relative min-w-0 flex-1 overflow-hidden bg-white py-4 px-4 text-sm font-medium text-center hover:bg-gray-50 focus:z-10">
                                                        <span>Returned Products</span>
                                                        <span aria-hidden="true" :class="
                                                            currentModalMenu == 'returned'
                                                                ? 'bg-green-500'
                                                                : 'bg-transparent'
                                                        " class="absolute inset-x-0 bottom-0 h-0.5"></span>
                                                    </button>

                                                    <button @click="currentModalMenu = 'spoiled'"
                                                        class="text-gray-500 hover:text-gray-700 rounded-r-lg group relative min-w-0 flex-1 overflow-hidden bg-white py-4 px-4 text-sm font-medium text-center hover:bg-gray-50 focus:z-10">
                                                        <span>Spoiled Products</span>
                                                        <span aria-hidden="true" :class="
                                                            currentModalMenu == 'spoiled'
                                                                ? 'bg-green-500'
                                                                : 'bg-transparent'
                                                        " class="absolute inset-x-0 bottom-0 h-0.5"></span>
                                                    </button>

                                                    <button @click="currentModalMenu = 'missing'"
                                                        class="text-gray-500 hover:text-gray-700 rounded-r-lg group relative min-w-0 flex-1 overflow-hidden bg-white py-4 px-4 text-sm font-medium text-center hover:bg-gray-50 focus:z-10">
                                                        <span>Missing Products</span>
                                                        <span aria-hidden="true" :class="
                                                            currentModalMenu == 'missing'
                                                                ? 'bg-green-500'
                                                                : 'bg-transparent'
                                                        " class="absolute inset-x-0 bottom-0 h-0.5"></span>
                                                    </button>

                                                    <button @click="currentModalMenu = 'excess'"
                                                        class="text-gray-500 hover:text-gray-700 rounded-r-lg group relative min-w-0 flex-1 overflow-hidden bg-white py-4 px-4 text-sm font-medium text-center hover:bg-gray-50 focus:z-10">
                                                        <span>Excess Products</span>
                                                        <span aria-hidden="true" :class="
                                                            currentModalMenu == 'excess'
                                                                ? 'bg-green-500'
                                                                : 'bg-transparent'
                                                        " class="absolute inset-x-0 bottom-0 h-0.5"></span>
                                                    </button>
                                                </nav>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-10 px-4 sm:px-6 lg:px-8">
                                        <div class="justify-between">
                                            <div class="flex justify-between">
                                                <div class="relative mt-1 rounded-md shadow-sm">
                                                    <div
                                                        class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                        <i class="fas fa-search"></i>
                                                    </div>
                                                    <input type="email" name="email" id="email"
                                                        class="block w-full rounded-md border-gray-300 pl-10 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                        placeholder="search">
                                                </div>
                                                <div>
                                                    <button @click="exportRep('xlsx', fn, dl, currentModalMenu)" 
                                                        class="inline-flex items-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                                        <i class="fa-solid fa-file-excel mr-2"></i> Download Excel
                                                    </button>
                                                </div>
                                            </div>
                                            <!-- <button @click="exportReport('tblData', 'members-data')">Export Table Data To Excel File</button> -->
                                        </div>
                                        
                                        <div v-if="currentModalMenu == 'loaded'" class="mt-8 flex flex-col">
                                            <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                                <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                                                    <div
                                                        class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                                                        <table id="loaded" class="min-w-full divide-y divide-gray-300">
                                                            <thead class="bg-gray-50">
                                                                <tr class="divide-x divide-gray-200">
                                                                    <th scope="col"
                                                                        class="py-3.5 pl-4 pr-4 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                                        Product Name
                                                                    </th>
                                                                    <th scope="col"
                                                                        class="px-4 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                                        Product SKU
                                                                    </th>
                                                                    <th scope="col"
                                                                        class="px-4 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                                        Loaded Items
                                                                    </th>
                                                                    <th scope="col"
                                                                        class="py-3.5 pl-4 pr-4 text-left text-sm font-semibold text-gray-900 sm:pr-6">
                                                                        Sold Items
                                                                    </th>
                                                                    <th scope="col"
                                                                        class="py-3.5 pl-4 pr-4 text-left text-sm font-semibold text-gray-900 sm:pr-6">
                                                                        Returned Items
                                                                    </th>
                                                                    <th scope="col"
                                                                        class="py-3.5 pl-4 pr-4 text-left text-sm font-semibold text-gray-900 sm:pr-6">
                                                                        Spoiled Items
                                                                    </th>
                                                                    <th scope="col"
                                                                        class="py-3.5 pl-4 pr-4 text-left text-sm font-semibold text-gray-900 sm:pr-6">
                                                                        Lost Items
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="divide-y divide-gray-200 bg-white">
                                                                <tr v-for="(product, index) in tripProducts"
                                                                    :key="index" class="divide-x divide-gray-200">
                                                                    <td
                                                                        class="whitespace-nowrap py-4 pl-4 pr-4 text-sm font-medium text-gray-900 sm:pl-6">
                                                                        {{ findInfo(product.productID, 'name') }}</td>
                                                                    <td
                                                                        class="whitespace-nowrap p-4 text-sm text-gray-500">
                                                                        {{ findInfo(product.productID, 'sku') }}</td>
                                                                    <td
                                                                        class="whitespace-nowrap p-4 text-sm text-gray-500">
                                                                        {{ product.loadedItems }}</td>
                                                                    <td
                                                                        class="whitespace-nowrap p-4 text-sm text-gray-500">
                                                                        {{ product.soldProducts }}</td>
                                                                    <td
                                                                        class="whitespace-nowrap p-4 text-sm text-gray-500">
                                                                        {{ product.restocked }}</td>
                                                                    <td class="whitespace-nowrap p-4 text-sm text-gray-500">
                                                                        {{ product.spoiledProducts }}</td>
                                                                    <td
                                                                        class="whitespace-nowrap py-4 pl-4 pr-4 text-sm text-gray-500 sm:pr-6">
                                                                        {{ product.missingProducts }}</td>
                                                                </tr>

                                                                <!-- More people... -->
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div v-if="currentModalMenu == 'sold'" class="mt-8 flex flex-col">
                                            <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                                <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                                                    <div
                                                        class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                                                        <table id="sold" class="min-w-full divide-y divide-gray-300">
                                                            <thead class="bg-gray-50">
                                                                <tr class="divide-x divide-gray-200">
                                                                    <th scope="col"
                                                                        class="py-3.5 pl-4 pr-4 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                                        Product
                                                                    </th>
                                                                    <th scope="col"
                                                                        class="px-4 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                                        SKU
                                                                    </th>
                                                                    <th scope="col"
                                                                        class="px-4 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                                        Loaded
                                                                    </th>
                                                                    <th scope="col"
                                                                        class="py-3.5 pl-4 pr-4 text-left text-sm font-semibold text-gray-900 sm:pr-6">
                                                                        Sold
                                                                    </th>
                                                                    <th scope="col" class="py-3.5 pl-4 pr-4 text-left text-sm font-semibold text-gray-900 sm:pr-6">
                                                                        Retail
                                                                    </th>
                                                                    <th scope="col" class="py-3.5 pl-4 pr-4 text-left text-sm font-semibold text-gray-900 sm:pr-6">
                                                                        Wholesale
                                                                    </th>
                                                                    <th scope="col"
                                                                        class="py-3.5 pl-4 pr-4 text-left text-sm font-semibold text-gray-900 sm:pr-6">
                                                                        Returned
                                                                    </th>
                                                                    <th scope="col"
                                                                        class="py-3.5 pl-4 pr-4 text-left text-sm font-semibold text-gray-900 sm:pr-6">
                                                                        Spoiled
                                                                    </th>
                                                                    <th scope="col"
                                                                        class="py-3.5 pl-4 pr-4 text-left text-sm font-semibold text-gray-900 sm:pr-6">
                                                                        Lost
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="divide-y divide-gray-200 bg-white">
                                                                <tr v-for="(product, index) in soldProducts"
                                                                    :key="index" class="divide-x divide-gray-200">
                                                                    <td
                                                                        class="whitespace-nowrap py-4 pl-4 pr-4 text-sm font-medium text-gray-900 sm:pl-6">
                                                                        {{ findInfo(product.productID, 'name') }}</td>
                                                                    <td
                                                                        class="whitespace-nowrap p-4 text-sm text-gray-500">
                                                                        {{ findInfo(product.productID, 'sku') }}</td>
                                                                    <td
                                                                        class="whitespace-nowrap p-4 text-sm text-gray-500">
                                                                        {{ product.loadedItems }}</td>
                                                                    <td
                                                                        class="whitespace-nowrap p-4 text-sm text-gray-500">
                                                                        {{ product.soldProducts }}</td>
                                                                    <td
                                                                        class="whitespace-nowrap p-4 text-sm font-bold text-gray-500">
                                                                        {{ getRetail(product.productID) }}</td>
                                                                    <td
                                                                        class="whitespace-nowrap p-4 text-sm font-bold text-gray-500">
                                                                        {{ getWholesale(product.productID) }}</td>
                                                                    <td
                                                                        class="whitespace-nowrap p-4 text-sm text-gray-500">
                                                                        {{ product.restocked }}</td>
                                                                    <td class="whitespace-nowrap p-4 text-sm text-gray-500">
                                                                        {{ product.spoiledProducts }}</td>
                                                                    <td
                                                                        class="whitespace-nowrap py-4 pl-4 pr-4 text-sm text-gray-500 sm:pr-6">
                                                                        {{ product.missingProducts }}</td>
                                                                </tr>

                                                                <!-- More people... -->
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div v-if="currentModalMenu == 'returned'" class="mt-8 flex flex-col">
                                            <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                                <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                                                    <div
                                                        class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                                                        <table id="returned" class="min-w-full divide-y divide-gray-300">
                                                            <thead class="bg-gray-50">
                                                                <tr class="divide-x divide-gray-200">
                                                                    <th scope="col"
                                                                        class="py-3.5 pl-4 pr-4 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                                        Product Name
                                                                    </th>
                                                                    <th scope="col"
                                                                        class="px-4 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                                        Product SKU
                                                                    </th>
                                                                    <th scope="col"
                                                                        class="px-4 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                                        Loaded Items
                                                                    </th>
                                                                    <th scope="col"
                                                                        class="py-3.5 pl-4 pr-4 text-left text-sm font-semibold text-gray-900 sm:pr-6">
                                                                        Sold Items
                                                                    </th>
                                                                    <th scope="col"
                                                                        class="py-3.5 pl-4 pr-4 text-left text-sm font-semibold text-gray-900 sm:pr-6">
                                                                        Returned Items
                                                                    </th>
                                                                    <th scope="col"
                                                                        class="py-3.5 pl-4 pr-4 text-left text-sm font-semibold text-gray-900 sm:pr-6">
                                                                        Spoiled Items
                                                                    </th>
                                                                    <th scope="col"
                                                                        class="py-3.5 pl-4 pr-4 text-left text-sm font-semibold text-gray-900 sm:pr-6">
                                                                        Lost Items
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="divide-y divide-gray-200 bg-white">
                                                                <tr v-for="(product, index) in returnedProducts"
                                                                    :key="index" class="divide-x divide-gray-200">
                                                                    <td
                                                                        class="whitespace-nowrap py-4 pl-4 pr-4 text-sm font-medium text-gray-900 sm:pl-6">
                                                                        {{ findInfo(product.productID, 'name') }}</td>
                                                                    <td
                                                                        class="whitespace-nowrap p-4 text-sm text-gray-500">
                                                                        {{ findInfo(product.productID, 'sku') }}</td>
                                                                    <td
                                                                        class="whitespace-nowrap p-4 text-sm text-gray-500">
                                                                        {{ product.loadedItems }}</td>
                                                                    <td
                                                                        class="whitespace-nowrap p-4 text-sm text-gray-500">
                                                                        {{ product.soldProducts }}</td>
                                                                    <td
                                                                        class="whitespace-nowrap p-4 text-sm text-gray-500">
                                                                        {{ product.restocked }}</td>
                                                                    <td
                                                                        class="whitespace-nowrap p-4 text-sm text-gray-500">
                                                                        {{ product.spoiledProducts }}</td>
                                                                    <td
                                                                        class="whitespace-nowrap py-4 pl-4 pr-4 text-sm text-gray-500 sm:pr-6">
                                                                        {{ product.missingProducts }}</td>
                                                                </tr>

                                                                <!-- More people... -->
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div v-if="currentModalMenu == 'spoiled'" class="mt-8 flex flex-col">
                                            <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                                <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                                                    <div
                                                        class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                                                        <table id="spoiled" class="min-w-full divide-y divide-gray-300">
                                                            <thead class="bg-gray-50">
                                                                <tr class="divide-x divide-gray-200">
                                                                    <th scope="col"
                                                                        class="py-3.5 pl-4 pr-4 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                                        Product Name
                                                                    </th>
                                                                    <th scope="col"
                                                                        class="px-4 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                                        Product SKU
                                                                    </th>
                                                                    <th scope="col"
                                                                        class="px-4 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                                        Loaded Items
                                                                    </th>
                                                                    <th scope="col"
                                                                        class="py-3.5 pl-4 pr-4 text-left text-sm font-semibold text-gray-900 sm:pr-6">
                                                                        Sold Items
                                                                    </th>
                                                                    <th scope="col"
                                                                        class="py-3.5 pl-4 pr-4 text-left text-sm font-semibold text-gray-900 sm:pr-6">
                                                                        Returned Items
                                                                    </th>
                                                                    <th scope="col"
                                                                        class="py-3.5 pl-4 pr-4 text-left text-sm font-semibold text-gray-900 sm:pr-6">
                                                                        Spoiled Items
                                                                    </th>
                                                                    <th scope="col"
                                                                        class="py-3.5 pl-4 pr-4 text-left text-sm font-semibold text-gray-900 sm:pr-6">
                                                                        Lost Items
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="divide-y divide-gray-200 bg-white">
                                                                <tr v-for="(product, index) in spoiledProducts"
                                                                    :key="index" class="divide-x divide-gray-200">
                                                                    <td
                                                                        class="whitespace-nowrap py-4 pl-4 pr-4 text-sm font-medium text-gray-900 sm:pl-6">
                                                                        {{ findInfo(product.productID, 'name') }}</td>
                                                                    <td
                                                                        class="whitespace-nowrap p-4 text-sm text-gray-500">
                                                                        {{ findInfo(product.productID, 'sku') }}</td>
                                                                    <td
                                                                        class="whitespace-nowrap p-4 text-sm text-gray-500">
                                                                        {{ product.loadedItems }}</td>
                                                                    <td
                                                                        class="whitespace-nowrap p-4 text-sm text-gray-500">
                                                                        {{ product.soldProducts }}</td>
                                                                    <td
                                                                        class="whitespace-nowrap p-4 text-sm text-gray-500">
                                                                        {{ product.restocked }}</td>
                                                                    <td
                                                                        class="whitespace-nowrap p-4 text-sm text-gray-500">
                                                                        {{ product.spoiledProducts }}</td>
                                                                    <td
                                                                        class="whitespace-nowrap py-4 pl-4 pr-4 text-sm text-gray-500 sm:pr-6">
                                                                        {{ product.missingProducts }}</td>
                                                                </tr>

                                                                <!-- More people... -->
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div v-if="currentModalMenu == 'missing'" class="mt-8 flex flex-col">
                                            <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                                <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                                                    <div
                                                        class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                                                        <table id="missing" class="min-w-full divide-y divide-gray-300">
                                                            <thead class="bg-gray-50">
                                                                <tr class="divide-x divide-gray-200">
                                                                    <th scope="col"
                                                                        class="py-3.5 pl-4 pr-4 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                                        Product Name
                                                                    </th>
                                                                    <th scope="col"
                                                                        class="px-4 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                                        Product SKU
                                                                    </th>
                                                                    <th scope="col"
                                                                        class="px-4 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                                        Loaded Items
                                                                    </th>
                                                                    <th scope="col"
                                                                        class="py-3.5 pl-4 pr-4 text-left text-sm font-semibold text-gray-900 sm:pr-6">
                                                                        Sold Items
                                                                    </th>
                                                                    <th scope="col" class="py-3.5 pl-4 pr-4 text-left text-sm font-semibold text-gray-900 sm:pr-6">
                                                                        Retail
                                                                    </th>
                                                                    <th scope="col" class="py-3.5 pl-4 pr-4 text-left text-sm font-semibold text-gray-900 sm:pr-6">
                                                                        Wholesale
                                                                    </th>
                                                                    <th scope="col"
                                                                        class="py-3.5 pl-4 pr-4 text-left text-sm font-semibold text-gray-900 sm:pr-6">
                                                                        Returned Items
                                                                    </th>
                                                                    <th scope="col"
                                                                        class="py-3.5 pl-4 pr-4 text-left text-sm font-semibold text-gray-900 sm:pr-6">
                                                                        Spoiled Items
                                                                    </th>
                                                                    <th scope="col"
                                                                        class="py-3.5 pl-4 pr-4 text-left text-sm font-semibold text-gray-900 sm:pr-6">
                                                                        Lost Items
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="divide-y divide-gray-200 bg-white">
                                                                <tr v-for="(product, index) in missingProducts"
                                                                    :key="index" class="divide-x divide-gray-200">
                                                                    <td
                                                                        class="whitespace-nowrap py-4 pl-4 pr-4 text-sm font-medium text-gray-900 sm:pl-6">
                                                                        {{ findInfo(product.productID, 'name') }}</td>
                                                                    <td
                                                                        class="whitespace-nowrap p-4 text-sm text-gray-500">
                                                                        {{ findInfo(product.productID, 'sku') }}</td>
                                                                    <td
                                                                        class="whitespace-nowrap p-4 text-sm text-gray-500">
                                                                        {{ product.loadedItems }}</td>
                                                                    <td
                                                                        class="whitespace-nowrap p-4 text-sm text-gray-500">
                                                                        {{ product.soldProducts }}</td>
                                                                    <td class="whitespace-nowrap p-4 text-sm font-bold text-gray-500">
                                                                        {{ getRetail(product.productID) }}</td>
                                                                    <td class="whitespace-nowrap p-4 text-sm font-bold text-gray-500">
                                                                        {{ getWholesale(product.productID) }}</td>
                                                                    <td
                                                                        class="whitespace-nowrap p-4 text-sm text-gray-500">
                                                                        {{ product.restocked }}</td>
                                                                    <td
                                                                        class="whitespace-nowrap p-4 text-sm text-gray-500">
                                                                        {{ product.spoiledProducts }}</td>
                                                                    <td
                                                                        class="whitespace-nowrap py-4 pl-4 pr-4 text-sm text-gray-500 sm:pr-6">
                                                                        {{ product.missingProducts }}</td>
                                                                </tr>
                                                                <tr
                                                                    class="divide-x divide-gray-200">
                                                                    <td
                                                                        class="whitespace-nowrap py-4 pl-4 pr-4 text-sm font-medium text-gray-900 sm:pl-6">
                                                                        </td>
                                                                    <td
                                                                        class="whitespace-nowrap p-4 text-sm text-gray-500">
                                                                        </td>
                                                                    <td
                                                                        class="whitespace-nowrap p-4 text-sm text-gray-500">
                                                                        </td>
                                                                    <td
                                                                        class="whitespace-nowrap p-4 text-sm text-gray-500">
                                                                        </td>
                                                                    <td
                                                                        class="whitespace-nowrap p-4 text-sm text-gray-500">
                                                                        </td>
                                                                    <td
                                                                        class="whitespace-nowrap p-4 text-sm text-gray-500">
                                                                        </td>
                                                                    <td
                                                                        class="whitespace-nowrap p-4 text-sm text-gray-500">
                                                                        </td>
                                                                    <td
                                                                        class="whitespace-nowrap p-4 text-sm text-gray-500">
                                                                        </td>
                                                                    <td
                                                                        class="whitespace-nowrap py-4 pl-4 pr-4 text-xs text-red-400 sm:pr-6">
                                                                        KES <span class="font-bold text-red-500 text-sm"> {{ missingItemsValue() }} </span> 
                                                                    </td>
                                                                </tr>

                                                                <!-- More people... -->
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>




                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</template>