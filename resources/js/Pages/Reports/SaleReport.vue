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
    currentFilter.value = 'todaysSales'

})

const hideUser = ref(false)
const filteredUserName = ref(null)
const filtertedTripID = ref(null)
const filterData = ref({
    saleAgent:null,
    team:null,
    trip:null,
    product:null,
    location:null,
    driver:null,
    payment:null,
    client:null,
});


const currentFilter = ref(null);
const filtersModal = ref(false);
const filteredSalesArray = ref([]);
const salesArray = ref([]);

const todaysale = computed(() => {
    const todaydate = new Date()
    const yesterday = new Date()
    yesterday.setDate(todaydate.getDate() - 1)
    return salesArray.value.filter(item => new Date(item.timestamp).toLocaleDateString() === yesterday.toLocaleDateString())
});


const hide = (data) => {
    console.log(data)

    // if (filterData.value.saleAgent != undefined || filterData.value.saleAgent != null) {
    //     hideUser.value = true;
    // }else{
    //     hideUser.value = false;
    // }
}

watch(
    filterData,
    (value) => {
        // if (value.saleAgent.length != 0 || value.saleAgent.length != null || value.saleAgent.length != '') {
        //     productsCollapseValue.value = true;
        // }
        hide(value)

    },
    {
        deep: true,
        immediate: true,
    }
);

const loadFilters = () => {
    const finalArray = []
    for (const key in filterData.value) {
        if (!filterData.value[key] && filterData.value[key] !== 0) {
            delete filterData.value[key];
        }
    }
    const propertyNames = []
    for (const key in filterData.value) {
        propertyNames.push(key)
    }
    if (propertyNames.includes('saleAgent')){
        for (let index = 0; index < salesArray.value.length; index++) {
            const element = salesArray.value[index];
            if (element.agentID == filterData.value.saleAgent.id){
                finalArray.push(element)
            }
        }
    }
    for (let index = 0; index < finalArray.length; index++) {
        const element = finalArray[index];
        filteredSalesArray.value.push(element)
    }
    currentFilter.value = 'filteredUserOnly'
    filteredUserName.value = filterData.value.saleAgent.name
    filtersModal.value = false
    
}

const clearFilters = () => {
    filterData.value = {}
    hideUser.value = false
}

const saleTeam = () => {
  let desiredAgents = []  
  for (let index = 0; index < props.users.length; index++) {
      const element = props.users[index];
      if (element.admin == 3){
          desiredAgents.push(element)
      }  
  }
  return desiredAgents
}

const saleAgents = () => {
  let desiredAgents = []  
  for (let index = 0; index < props.users.length; index++) {
      const element = props.users[index];
      if (element.admin == 4 || element.admin == 3){
          desiredAgents.push(element)
      }  
  }
  return desiredAgents
}

const getDesiredName = () => {
    if (currentFilter.value == 'todaysSales'){
        return 'All Teams & Users'
    }
    if (currentFilter.value == 'filteredUserOnly') {
        return filteredUserName.value
    }
}

const getDesiredTitle = () => {
    if (currentFilter.value == 'todaysSales'){
        const todaydate = new Date()
        const yesterday = new Date()
        yesterday.setDate(todaydate.getDate() - 1)
        return yesterday.toLocaleDateString("en-US", { year: "numeric", month: "long", day: "numeric" })
    }

    if (currentFilter.value == 'filteredUserOnly') {
        return 'All time User Data'
    }

}

const getDesiredArray = () => {
    if (currentFilter.value == 'todaysSales'){
        return todaysale.value
    }
    
    if (currentFilter.value == 'filteredUserOnly'){
        return filteredSalesArray.value
    }

}

const exportRep = (type, fn, dl, value) => {
    var elt = document.getElementById(value);
    var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
    return dl ?
        XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
        XLSX.writeFile(wb, fn || ('sales_report.' + (type || 'xlsx')));
        // XLSX.writeFile(wb, fn || (tripTeamLead.value + '_' + tripID.value + '_' + currentModalMenu.value + '_' + 'report.' + (type || 'xlsx')));
}

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
                    <!-- <h1 class="text-xl font-semibold text-gray-900">All Sales Report</h1> -->
                    <div class="mt-2 sm:flex-none">
                        <h2 class="font-bold text-xl">
                            Sales Report
                        </h2>
                    </div>
                </div>
                <div class="sm:flex-auto">
                    <h1 class="text-xl font-semibold text-gray-900">
                         
                        <button class="ml-4 inline-flex items-center justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:w-auto">
                            <i class="fas fa-caret-down mr-4"></i> 
                            {{ getDesiredTitle() }} 
                        </button>
                        <button @click="filtersModal=true" class="capitalize ml-4 inline-flex items-center justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:w-auto">
                            <i class="fas fa-caret-down mr-4"></i> 
                            {{ getDesiredName() }}
                        </button>

                    </h1>
                    
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                    <button @click="exportRep('xlsx', fn, dl, 'sales')" type="button"
                        class="inline-flex items-center justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:w-auto">
                        Download Report <i class="ml-2 fa-solid fa-download"></i>
                    </button>
                </div>
            </div>
            <div class="mt-8 flex flex-col">
                <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                            <table id="sales" class="min-w-full divide-y divide-gray-300">
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
                                    <tr v-for="(sale, index) in getDesiredArray()" :key="index">
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
                                            {{ getUnitPrice(sale) }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900 font-bold">
                                             {{ sale.productPrice }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900 font-bold">
                                            {{ sale.wholesalePrice }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-green-800 font-bold">
                                            {{ getSaleExcess(sale) }}
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

        <div v-if="filtersModal" class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <!--
            Background backdrop, show/hide based on modal state.
        
            Entering: "ease-out duration-300"
              From: "opacity-0"
              To: "opacity-100"
            Leaving: "ease-in duration-200"
              From: "opacity-100"
              To: "opacity-0"
          -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        
            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <!--
                Modal panel, show/hide based on modal state.
        
                Entering: "ease-out duration-300"
                  From: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                  To: "opacity-100 translate-y-0 sm:scale-100"
                Leaving: "ease-in duration-200"
                  From: "opacity-100 translate-y-0 sm:scale-100"
                  To: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
              -->
                    <div
                        class="relative transform overflow-hidden rounded-lg bg-white px-4 pt-5 pb-4 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-6xl sm:p-6">
                        <div>
                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
                                <i class="fa-solid fa-filter-circle-xmark"></i>
                            </div>
                            <div class="mt-5 text-center sm:mt-10">
                                
                                <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-4 sm:gap-3">

                                    <div class="sm:col-span-1 mt-2">
                                        <label for="team" class="block text-sm font-medium text-gray-700">Select Team</label>
                                        <div class="mt-1">
                                            <select v-model="filterData.team" id="team" name="team" autocomplete="team-name"
                                                class="font-bold block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                                                <option v-for="agent in saleTeam()" :value="agent" class="font-bold">{{ agent.name }}'s team </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="sm:col-span-1 mt-2">
                                        <label for="team" class="block text-sm font-medium text-gray-700">Select User</label>
                                        <div class="mt-1">
                                            <select :disabled="hideUser" v-model="filterData.saleAgent" id="team" name="team" autocomplete="team-name"
                                                class="font-bold block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                                                <option v-for="agent in saleAgents()" :value="agent" class="font-bold">{{  agent.name  }}</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="sm:col-span-1 mt-2">
                                        <label for="team" class="block text-sm font-medium text-gray-700">Select Trip</label>
                                        <div class="mt-1 sm:col-span-2 sm:mt-0">
                                            <input v-model="filterData.trip" type="number" name="first-name" id="first-name" autocomplete="given-name"
                                                class="font-bold block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:max-w-xs sm:text-sm">
                                        </div>
                                    </div>

                                    <div class="sm:col-span-1 mt-2">
                                        <label for="team" class="block text-sm font-medium text-gray-700">Select Product</label>
                                        <div class="mt-1">
                                            <select v-model="filterData.product" id="team" name="team" autocomplete="team-name"
                                                class="font-bold block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                                                <option v-for="product in products" :value="product" class="font-bold">{{ product.product_name  }} - {{ product.product_quantity }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="sm:col-span-1 mt-2">
                                        <label for="team" class="block text-sm font-medium text-gray-700">Select Location</label>
                                        <div class="mt-1">
                                            <select disabled v-model="filterData.location" id="team" name="team" autocomplete="team-name"
                                                class="font-bold block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                                                <option v-for="agent in saleAgents()" :value="agent" class="font-bold">Mexico</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="sm:col-span-1 mt-2">
                                        <label for="team" class="block text-sm font-medium text-gray-700">Select Driver</label>
                                        <div class="mt-1">
                                            <select disabled v-model="filterData.driver" id="team" name="team" autocomplete="team-name"
                                                class="font-bold block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                                                <option v-for="agent in saleAgents()" :value="agent" class="font-bold">Mexico</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="sm:col-span-1 mt-2">
                                        <label for="team" class="block text-sm font-medium text-gray-700">Select Payment</label>
                                        <div class="mt-1">
                                            <select disabled v-model="filterData.payment" id="team" name="team" autocomplete="team-name"
                                                class="font-bold block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                                                <option v-for="agent in saleAgents()" :value="agent" class="font-bold">Mexico</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="sm:col-span-1 mt-2">
                                        <label for="team" class="block text-sm font-medium text-gray-700">Select Client</label>
                                        <div class="mt-1">
                                            <select v-model="filterData.client" id="team" name="team" autocomplete="team-name"
                                                class="font-bold block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                                                <option v-for="client in clients" :value="client" class="font-bold">{{ client.client_name }}</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="mt-5 sm:mt-20 sm:grid sm:grid-flow-row-dense sm:grid-cols-3 sm:gap-3">
                            <button @click="clearFilters()" type="button"
                                class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-300  focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:col-start-1 sm:mt-0 sm:text-sm">
                                Clear Filters
                            </button>
                            <button @click="loadFilters" type="button"
                                class="inline-flex w-full justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:col-start-2 sm:text-sm">
                                Set Filters
                            </button>
                            <button @click="filtersModal=false" type="button"
                                class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-red-400 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-red-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:col-start-3 sm:mt-0 sm:text-sm">
                                Close Dialogue
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>