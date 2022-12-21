<script setup>
// import { useForm, usePage, Link } from "@inertiajs/inertia-vue3";
import { ref, computed, reactive, watch, onMounted } from "vue";
// import { Inertia } from "@inertiajs/inertia";
import moment from "moment";


const props = defineProps({
    products: Array,
    trips: Array,
    users: Array,
    sales: Array,
    clients: Array,
});

onMounted(()=>{
    let desiredArray = []
    for (let index = 0; index < props.sales.length; index++) {
        const element = props.sales[index];
        const invoiceNumber = element.id
        const tripBatch = element.trip_batch
        const clientID = element.client_id
        const mpesaRef = element.mpesa_ref
        const timestamp = element.created_at
        const parsedElement = JSON.parse(element.products)
        for (let index = 0; index < parsedElement.length; index++) {
            let batchAgentID = parsedElement[0].agentID
            const element2 = parsedElement[index];
            element2["invoiceNumber"] = invoiceNumber;
            element2["tripBatch"] = tripBatch;
            element2["clientID"] = clientID;
            element2["mpesaRef"] = mpesaRef;
            element2["timestamp"] = timestamp;
            if (element2.agentID==undefined){
                element2["agentID"] = batchAgentID;
            }
            desiredArray.push(element2)
        }
    }
    salesArray.value = desiredArray
})

const salesArray = ref([]);


const getPaymentMode = (data) => {
    if (data==0){
        return 'cash'
    }else{
        return 'mpesa'
    }
};

const formatTime = (value) => {
    return moment(value).format("MMMM Do YYYY, h:mm:ss a");
};

const getClientName = (data) => {
    console.log(data)
    if(data==null){
        return 'null'
    }else{
        let client = props.clients.find(obj => obj.id === data);
        return client.client_name
    }
}

const getSaleExcess = (data) => {
    const salePrice = data.salePrice / data.productQuantity
    const result = salePrice - data.wholesalePrice
    return result * data.productQuantity
}

const getUnitPrice = (data) => {
    return data.salePrice / data.productQuantity
}

const getAgentName = (data) => {
    let agent = props.users.find(obj => obj.id == data)
    return agent.name
}




</script>

<template>
    <div class="mt-10">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-xl font-semibold text-gray-900">All Sales Report</h1>
                    <div class="mt-2 sm:flex-none">
                        <button type="button"
                            class="inline-flex items-center justify-center rounded-md border border-transparent bg-gray-300 px-1 py-1 text-xs font-medium text-black hover:text-white shadow-sm hover:bg-black focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:w-auto">
                            Download Report<i class="ml-2 fa-solid fa-download"></i>
                        </button>
                    </div>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                    <button type="button"
                        class="inline-flex items-center justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:w-auto">
                        Load Filters <i class="ml-2 fa-solid fa-filter"></i>
                    </button>
                </div>
            </div>
            <div class="mt-8 flex flex-col">
                <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="py-3 pl-4 pr-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 sm:pl-6">
                                            Invoice #</th>
                                        <th scope="col" class="py-3 pl-4 pr-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 sm:pl-6">
                                            Trip #</th>
                                        <th scope="col"
                                            class="py-3 pl-4 pr-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 sm:pl-6">
                                            Sale Person</th>
                                        <th scope="col"
                                            class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                                            Sale Product</th>
                                        <th scope="col"
                                            class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                                            Sale SKU</th>
                                        <th scope="col"
                                            class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                                            Number of Items</th>
                                        <th scope="col"
                                            class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                                            Sale Price</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                                            Retail Price</th>
                                        <th scope="col"
                                            class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                                            Wholesale Price</th>
                                        <th scope="col"
                                            class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                                            Sale Excess</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                                            Client Name</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                                            Mpesa Ref</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                                            Timestamp</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <tr v-for="(sale, index) in salesArray" :key="index">
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ sale.invoiceNumber }}    
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ sale.tripBatch }}
                                        </td>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-500 sm:pl-6">
                                            {{ getAgentName(sale.agentID) }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ sale.selectedproductName }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ sale.productSKU }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ sale.productQuantity }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-green-800 font-bold">
                                            KES {{ getUnitPrice(sale) }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900 font-bold">
                                            <span class="font-medium">KES</span> {{ sale.productPrice }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900 font-bold">
                                            <span class="font-medium">KES</span> {{ sale.wholesalePrice }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-green-800 font-bold">
                                            KES {{ getSaleExcess(sale) }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ getClientName(sale.clientID)  }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ getPaymentMode(sale.mpesaRef) }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ formatTime(sale.timestamp) }}
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
</template>