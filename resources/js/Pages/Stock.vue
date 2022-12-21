<script setup>
import { useForm, usePage, Link } from "@inertiajs/inertia-vue3";
import { ref, toRefs, computed, reactive, watch, onMounted } from "vue";
import { Inertia } from "@inertiajs/inertia";
import AppLayout from "@/Layouts/AppLayout.vue";
import Canvas from "./Canvas.vue";
import moment from "moment";

const props = defineProps({
  products: Array,
  clients: Array,
  trips: Array,
  sales: Array,
  message: String,
  invoiceLog: String,
  activeAgents: Array,
  users: Array,
});

onMounted(() => {
  if (currentMessage.value != null) {
    bottomCanvas.value = true;
  } else {
    bottomCanvas.value = false;
  }

  let saleItems = props.sales.map(({ products }) => products);

  var myArray = [];
  for (let i = 0; i < saleItems.length; i++) {
    myArray.push(saleItems[i]);
  }
  agentSales.value = myArray

  orderedPriceList.value = props.products.sort((a, b) => (a.product_name > b.product_name ? 1 : -1));


  

});

const { activeAgents } = toRefs(props);

const currentBatch = computed(() => usePage().props.value.user.trip_batch);
const currentMessage = computed(() => usePage().props.value.flash.success);
const currentUser = computed(() => usePage().props.value.user.id);
const currentTime = computed(() => moment().format("LLL"));

const form = useForm({
  product_name: "",
  product_quantity: 20,
  stock_quantity: 10,
  production_price: 120,
  sales_price: 150,
  product_description: "SoftSol Shampoo",
  added_by: currentUser,
});


const orderedPriceList = ref([]);
const itemsToShow = ref([]);
const loeadeditemsToShow = ref([]);
const agentSales = ref([]);
const selectedItem = ref({});
watch(selectedItem, (newX) => {
  payload.product_id = newX.id;
});
const purchasingClient = ref({});
watch(purchasingClient, (newX) => {
  payload.client_id = newX.id;
});
const purchasingQuantity = ref(1);
const purchasingPrice = ref(200);
const bottomCanvas = ref(false);
const showItemsModal = ref(false);
const addModal = ref(false);
const saleModal = ref(false);
const priceListModal = ref(false);
const tripSummaryModal = ref(false);
const showInvoice = ref(false);
const defaultClientButtons = ref(true);
const selectClientButtons = ref(false);
const clientTypeModal = ref(false);
const saleDetails = ref(false);

const payload = reactive({
  added_by: currentUser,
  client_id: null,
  product_id: null,
  product_quantity: purchasingQuantity,
  sale_amount: purchasingPrice,
  payment_status: false,
  invoice_number: 1,
});



const totals = (data) => {
  let numberToSum = []
  for (let index = 0; index < loeadeditemsToShow.value.length; index++) {
    const element = loeadeditemsToShow.value[index];
    let loadedArray = JSON.parse(element.trip_batch)
    console.log(loadedArray)
    let actualArray = loadedArray.find(obj => obj.batchNumber === data);
    let loadNumber = actualArray.numberItems
    let itemTotalPrice = element.wholesale_price
    let total = loadNumber * itemTotalPrice
    numberToSum.push(total)
  }
  let tots = numberToSum.reduce((a, b) => a + b, 0)
  return formatToCurrency(tots)
}

const getLoadedItems = (data) => {
  let loadedArray = JSON.parse(data)
  for (let index = 0; index < loadedArray.length; index++) {
    const element = loadedArray[index];
    let itemIndex = loadedArray.indexOf(element) 
    return loadedArray[itemIndex].numberItems
  }
}
const getTeamMembers = (data) =>{
  let trip = props.trips.find(obj => obj.id === data);
  let usersArray = JSON.parse(trip.user_ids)
  let userNames = []
  for (let index = 0; index < usersArray.length; index++) {
    const element = usersArray[index];
    let user = props.users.find(obj => obj.id === element);
    userNames.push(user.name)
  }
  return userNames
}

const getVehicle = (data) =>{
  let trip = props.trips.find(obj => obj.id === data);
  return trip.vehicle_number
}

const getDriver = (data) =>{
  let trip = props.trips.find(obj => obj.id === data);
  return trip.driver_name
}

const getTripLocation = (data) =>{
  let trip = props.trips.find(obj => obj.id === data);
  return trip.trip_location
}

const getTeamLead = (data) =>{
  let trip = props.trips.find(obj => obj.id === data);
  return trip.lead_name
}

const getLoadedProducts = (data) =>{
  let trip = props.trips.find(obj => obj.id === data);
  let productIDs = JSON.parse(trip.products_ids)
  let productsArray = []
  for (let index = 0; index < productIDs.length; index++) {
    const element = productIDs[index];
    let product = props.products.find(obj => obj.id === element);
    productsArray.push(product)
  }
  loeadeditemsToShow.value = productsArray
  tripSummaryModal.value = true
  totals()

}

const getUnitPrice = (data) => {
  return parseInt(data.total) / parseInt(data.productQuantity)
}

const getSKU = (data) => {
  let product = props.products.find((obj) => obj.id == data);
  return product.product_quantity
}

const showItems = (items) => {
  itemsToShow.value = []
  showItemsModal.value = true
  let parsedData = JSON.parse(items) 
  itemsToShow.value = parsedData
  // console.log(parsedData)
}

const getOveralTotal = (productIDS) => {
  let parsedData = JSON.parse(productIDS) 
  // console.log(parsedData[0].overallTotal)
  return parsedData[0].overallTotal
}

const getItems = (productIDS) => {
  let parsedData = JSON.parse(productIDS)
  let productQunatityArray = []
  for (let index = 0; index < parsedData.length; index++) {
    const element = parsedData[index];
    productQunatityArray.push(parseInt(element.productQuantity))
  }
  return productQunatityArray.reduce((a, b) => a + b, 0)
}

const getProducts = (productIDS) => {
  let parsedData = JSON.parse(productIDS) 
  const ids = parsedData.map(o => o.selectedproductID)
  let unique = [...new Set(ids)];
  // const filtered = parsedData.filter(({ id }, index) => !ids.includes(id, index + 1))
  // console.log(unique)
  return unique.length
}

const getLocation = (tripBatch) => {
  let trip = props.trips.find((obj) => obj.id == tripBatch);
  return trip.trip_location
}

const getClient = (clientID) => {
  if(clientID!=undefined){
    let client = props.clients.find((obj) => obj.id == clientID);
    return client.client_name
  }else{
    return 'Anonymous'
  }  
}

const geDateFormat = (time)=>{
  return moment(time).format("LLL")
}

const formatToCurrency = (amount) =>
{ 
  return (amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'); 
} 


const getProductNameDetails = (sale) =>
{ 
  // console.log(JSON.parse(sale))
  let result = JSON.parse(sale).map(a =>
  { 
    return a.selectedproductName
  });
    // console.log(result.length)
  let index = result.length
  for (let i = 0; i < result.length; i++) { 
    // console.log(i)
  }
  // console.log(result[index] )
  // return result[index] 
}
const getProductQuantityDetails = (sale) =>
{ 
  let result = JSON.parse(sale).map(a => a.productSKU);
  return result[0] 
}
const getProductStockQuantityDetails = (sale) =>
{ 
  let result = JSON.parse(sale).map(a => a.productQuantity);
  return result[0] 
}
const getProductPriceDetails = (sale) =>
{ 
  let result = JSON.parse(sale).map(a => a.salePrice);
  return result[0] 
}

const getInvoiceForm = async () => {
  Inertia.reload({ only: ["invoiceLog"] });
  if (activeAgents.value.includes(currentUser.value)) {
    bottomCanvas.value = true;
  } else {
    alert("You are not a member of any trip");
  }
};

const processButtons = (value) => {
  if (value == "existing") {
    defaultClientButtons.value = false;
    selectClientButtons.value = true;
  } else if (value == "previous") {
    selectClientButtons.value = false;
    defaultClientButtons.value = true;
  } else if (value == "selectedClient") {
    selectClientButtons.value = false;
    saleDetails.value = true;
  }
};

const saleItem = (product) => {
  clientTypeModal.value = true;
  selectedItem.value = product;
};

const processSale = () => {
  Inertia.post("/dashboard/register_sale", payload);
};

const addProduct = () => {
  form.post("/add_product", {
    preserveScroll: true,
    onSuccess: () => {
      form.reset();
      // addModal = false
      alert("Product Added");
    },
  });
};
</script>

<template>
  <AppLayout title="Dashboard">
    <div>
      <div
        class="px-4 py-6 sm:py-16 mx-auto sm:max-w-xl md:max-w-full lg:max-w-screen-xl md:px-24 lg:px-8 lg:py-8"
      >
        <div class="max-w-xl mb-10 md:mx-auto sm:text-center lg:max-w-2xl md:mb-12">
          <h2
            class="max-w-lg mb-6 font-sans text-3xl font-bold leading-none tracking-tight text-gray-900 sm:text-4xl md:mx-auto"
          >
            <span class="relative inline-block">
              <svg
                viewBox="0 0 52 24"
                fill="currentColor"
                class="absolute top-0 left-0 z-0 hidden w-32 -mt-8 -ml-20 text-blue-gray-100 lg:w-32 lg:-ml-28 lg:-mt-10 sm:block"
              >
                <defs>
                  <pattern
                    id="34f481be-159a-4846-821d-9ca19fb6bcc5"
                    x="0"
                    y="0"
                    width=".135"
                    height=".30"
                  >
                    <circle cx="1" cy="1" r=".7"></circle>
                  </pattern>
                </defs>
                <rect
                  fill="url(#34f481be-159a-4846-821d-9ca19fb6bcc5)"
                  width="52"
                  height="24"
                ></rect>
              </svg>
              <span class="relative">Sales</span>
            </span>
          </h2>
        </div>

        <div class="text-center">
          <button
            @click="getInvoiceForm"
            class="mr-2 mb-2 inline-flex items-center justify-center w-full h-12 px-6 font-medium tracking-wide text-white transition duration-200 rounded shadow-md md:w-auto bg-light-green-900 hover:bg-white hover:text-light-green-900 focus:shadow-outline focus:outline-none"
          >
            Register Sale
          </button>

          <button
            @click="getLoadedProducts(currentBatch)"
            class="mr-2 mb-2 inline-flex items-center justify-center w-full h-12 px-6 font-medium tracking-wide text-white transition duration-200 rounded shadow-md md:w-auto bg-light-green-900 hover:bg-white hover:text-light-green-900 focus:shadow-outline focus:outline-none"
          >
            Trip Summary
          </button>

          <button
            @click="priceListModal = true"
            class="mr-2 mb-2 inline-flex items-center justify-center w-full h-12 px-6 font-medium tracking-wide text-white transition duration-200 rounded shadow-md md:w-auto bg-light-green-900 hover:bg-white hover:text-light-green-900 focus:shadow-outline focus:outline-none"
          >
            Price List
          </button>

          
        </div>
      </div>

      <div>
        <div class="flex items-center justify-center py-1 px-2">
          <div class="sm:px-6 w-full">
            <div class="px-4 md:px-10 py-4 md:py-7">
              <div class="flex items-center justify-between">
                <p
                  tabindex="0"
                  class="focus:outline-none text-base sm:text-lg md:text-xl lg:text-2xl font-bold leading-normal text-gray-800"
                >
                  Sales Listing
                </p>
                <div
                  class="py-3 px-4 flex items-center text-sm font-medium leading-none text-gray-600 bg-gray-200 hover:bg-gray-300 cursor-pointer rounded"
                >
                  <p>Sort By:</p>
                  <select
                    aria-label="select"
                    class="focus:text-light-green-900 focus:outline-none bg-transparent ml-1"
                  >
                    <option class="text-sm text-black font-bold">Latest</option>
                    <option class="text-sm text-black font-bold">Oldest</option>
                    <option class="text-sm text-black font-bold">Latest</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="bg-white py-4 md:py-7 px-4 md:px-8 xl:px-10">
              <div class="overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                  <tbody>
                    <tr
                      v-for="(sale, index) in sales"
                      :key="index"
                      tabindex="0"
                      class="focus:outline-none h-16 border border-gray-100 rounded"
                    >
                      
                      <td>
                        <div class="ml-1 sm:ml-5">
                          <div
                            class="bg-light-green-100 focus:bg-green-300 font-extrabold rounded-sm w-5 h-5 flex flex-shrink-0 justify-center items-center relative"
                          >
                            {{ index + 1 }}
                          </div>
                        </div>
                      </td>
                      <td class="">
                        <div class="flex items-center pl-1 sm:pl-3">
                          <p
                            class="bold capitalize text-xs sm:text-sm font-medium leading-none text-gray-700 mr-2"
                          >
                            <!-- {{ sale[0].selectedproductName }} -->
                            {{ getClient(sale.client_id) }}
                          </p>
                        </div>
                      </td>
                      <td class="">
                        <div class="flex items-center pl-1 sm:pl-3">
                          <p
                            class="bold capitalize text-xs sm:text-sm font-medium leading-none text-gray-700 mr-2"
                          >
                            <!-- {{ sale[0].selectedproductName }} -->
                            {{ getLocation(sale.trip_batch) }}
                          </p>
                        </div>
                      </td>
                      <td class="">
                        <div class="flex items-center pl-1 sm:pl-3">
                          <p
                            class="bold capitalize text-xs sm:text-sm font-medium leading-none text-gray-700 mr-2"
                          >
                            <!-- {{ sale[0].selectedproductName }} -->
                            <span class="text-green-600 font-bold">{{ getProducts(sale.products) }}</span> Products
                          </p>
                        </div>
                      </td>
                      <td class="">
                        <div class="flex items-center pl-1 sm:pl-3">
                          <button
                          @click="showItems(sale.products)"
                            class="hover:text-lg transform translate hover:scale-125 duration-700  bold capitalize hover:font-extrabold cursor-pointer text-xs sm:text-sm font-medium leading-none text-gray-700 mr-2"
                          >
                            <!-- {{ sale[0].selectedproductName }} -->
                            <button type="button"
                              class="inline-flex items-center rounded border border-transparent bg-green-800 px-2.5 py-1.5 text-xs font-bold text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                              {{ getItems(sale.products) }} items
                            </button>

                            <!-- <span class="text-green-600 font-bold">{{ getItems(sale.products) }}</span> Items -->
                          </button>
                        </div>
                      </td>
                      <td class="">
                        <div class="flex items-center pl-1 sm:pl-3">
                          <p
                            class="bold capitalize text-xs sm:text-sm font-medium leading-none text-gray-700 mr-2"
                          >
                            <!-- {{ sale[0].selectedproductName }} -->
                            KES <span class="text-green-600 font-bold"> {{ getOveralTotal(sale.products) }}</span>
                          </p>
                        </div>
                      </td>
                      <td class="">
                        <div class="flex items-center pl-1 sm:pl-3">
                          <p class="text-xs sm:text-sm font-medium leading-none text-gray-700 mr-2">
                            <!-- {{ sale[0].selectedproductName }} -->
                            {{ geDateFormat(sale.created_at) }}
                          </p>
                        </div>
                      </td>
                    </tr>
                    <tr class="h-3">
                      <!-- <i class="fas fa-user"></i> -->
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    
    
    
    <div v-if="tripSummaryModal"
      class="bg-gray-100  p-2 modal fade fixed top-0 left-0 w-full h-full outline-none overflow-x-hidden overflow-y-auto"
      id="exampleModalLg" tabindex="-1" aria-labelledby="exampleModalLgLabel" aria-modal="true" role="dialog">
      <div class="modal-dialog modal-lg relative w-auto pointer-events-none">
        <div
          class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
          <div
            class="modal-header flex flex-shrink-0 items-center justify-between p-4 border-b border-gray-200 rounded-t-md">
            <h5 class="text-xl font-medium leading-normal text-gray-800" id="exampleModalLgLabel">
              Loaded Products
            </h5>
            <button @click="tripSummaryModal=false" type="button"
              class="btn-close box-content w-4 h-4 p-1 text-black border-none rounded-none opacity-50 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-black hover:opacity-75 hover:no-underline"
              data-bs-dismiss="modal" aria-label="Close">
              <i class="fas fa-xmark"></i>
            </button>
          </div>
          <div class="modal-body relative p-4">
            <div class="px-4 sm:px-6 lg:px-8">
              <div class="sm:flex  sm:items-center">
                
                <div class="sm:flex-auto">
                  <h1 class="text-xl font-semibold text-gray-900"># {{ currentBatch }}</h1>
                  <div clas="grid grid-cols-3 gap-4">
                    <p class="font-bold mt-2 text-lg text-gray-700">
                      Lead: <span class="font-medium text-sm uppercase">{{ getTeamLead(currentBatch) }}</span>
                    </p>
                    <p class="font-bold mt-2 text-lg text-gray-700">
                      Members: <span class="font-medium text-sm uppercase">{{ getTeamMembers(currentBatch) }}</span>
                    </p>
                    <p class="font-bold mt-2 text-lg text-gray-700">
                      Location: <span class="font-medium text-sm uppercase">{{ getTripLocation(currentBatch) }}</span>
                    </p>
                    <p class="font-bold mt-2 text-lg text-gray-700">
                      Driver: <span class="font-medium text-sm uppercase">{{ getDriver(currentBatch) }}</span>
                    </p>
                    <p class="font-bold mt-2 text-lg text-gray-700">
                      Vehicle: <span class="font-medium text-sm uppercase">{{ getVehicle(currentBatch) }}</span>
                    </p>
                    <p class="font-bold mt-2 text-lg text-gray-700">
                      Value: <span class="font-bold text-sm uppercase">KES  <span class="text-green-700">{{ totals(currentBatch) }}</span> </span>
                    </p>
                  </div>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                  <button type="button"
                    class="inline-flex items-center justify-center rounded-md border border-transparent bg-green-800 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:w-auto">
                    Download<i class="fas fa-download ml-2"></i>
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
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                              Product</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">SKU</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Loaded</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Wholesale
                              Price</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Retail Price
                            </th>
                          </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                          <tr v-for="product in loeadeditemsToShow">
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-xs sm:text-sm font-medium text-gray-900 sm:pl-6">
                              {{ product.product_name }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-xs sm:text-sm text-gray-500">
                              {{ product.product_quantity }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-xs sm:text-sm text-gray-500">
                              {{ getLoadedItems(product.trip_batch) }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-xs sm:text-sm text-gray-500 font-bold">
                              KES <span class="text-green-700">{{ product.wholesale_price }}</span>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-xs sm:text-sm text-gray-500 font-bold">
                              KES <span class="text-green-700">{{ product.sales_price }}</span>
                            </td>
                          </tr>
    
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

    <div v-if="priceListModal" class="bg-gray-100  p-2 modal fade fixed top-0 left-0 w-full h-full outline-none overflow-x-hidden overflow-y-auto"
      id="exampleModalLg" tabindex="-1" aria-labelledby="exampleModalLgLabel" aria-modal="true" role="dialog">
      <div class="modal-dialog modal-lg relative w-full    pointer-events-none">
        <div
          class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
          <div
            class="modal-header flex flex-shrink-0 items-center justify-between p-4 border-b border-gray-200 rounded-t-md">
            <h5 class="text-xl font-medium leading-normal text-gray-800" id="exampleModalLgLabel">
              Price List
            </h5>
            <button @click="priceListModal =false" type="button"
              class="btn-close box-content w-4 h-4 p-1 text-black border-none rounded-none opacity-50 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-black hover:opacity-75 hover:no-underline"
              data-bs-dismiss="modal" aria-label="Close">
            <i class="fas fa-xmark"></i>
            </button>
          </div>
          <div class="modal-body relative p-4">
            <div class="px-4 sm:px-6 lg:px-8">
              <div class="sm:flex sm:items-center">
                <div class="mt-4 sm:mt-0 sm:flex-none">
                  <button type="button"
                    class="mb-2 mr-2 inline-flex items-center justify-center rounded-md border border-transparent bg-green-600 px-2 sm:px-4 py-2 text-xs sm:text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:w-auto">
                    Download <i class="ml-2 fas fa-download"></i>
                  </button>
                  <button type="button"
                    class="mb-2 mr-2 inline-flex items-center justify-center rounded-md border border-transparent bg-green-600 px-2 sm:px-4 py-2 text-xs sm:text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:w-auto">
                    Whatsapp <i class="ml-2 fab fa-whatsapp"></i>
                  </button>
                  <button type="button"
                    class="mb-2 mr-2 inline-flex items-center justify-center rounded-md border border-transparent bg-green-600 px-2 sm:px-4 py-2 text-xs sm:text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:w-auto">
                    Mail <i class="ml-2 fas fa-envelope"></i>
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
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Product</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">SKU</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Wholesale Price</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Retail Price</th>
                          </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                          <tr v-for="product in orderedPriceList">
                            <td class="whitespace-nowrap py-4 pr-3 text-xs sm:text-sm font-medium text-gray-900 sm:pl-6">
                              {{ product.product_name  }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-xs sm:text-sm text-gray-500">
                              {{ product.product_quantity }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-xs sm:text-sm text-gray-500 font-bold">
                              KES <span class="text-green-700">{{ product.wholesale_price }}</span>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-xs sm:text-sm text-gray-500 font-bold">
                              KES <span class="text-green-700">{{ product.sales_price }}</span>
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

    <div v-if="showItemsModal" class="bg-gray-100  p-2 modal fade fixed top-0 left-0 w-full h-full outline-none overflow-x-hidden overflow-y-auto"
      id="exampleModalLg" tabindex="-1" aria-labelledby="exampleModalLgLabel" aria-modal="true" role="dialog">
      <div class="modal-dialog modal-lg relative w-auto pointer-events-none">
        <div
          class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
          <div
            class="modal-header flex flex-shrink-0 items-center justify-between p-4 border-b border-gray-200 rounded-t-md">
            <h5 class="text-xl font-medium leading-normal text-gray-800" id="exampleModalLgLabel">
              Sale Item
            </h5>
            <button @click="showItemsModal=false" type="button"
              class="btn-close box-content w-4 h-4 p-1 text-black border-none rounded-none opacity-50 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-black hover:opacity-75 hover:no-underline"
              data-bs-dismiss="modal" aria-label="Close">
            <i class="fas fa-xmark"></i>
            </button>
          </div>
          <div class="modal-body relative p-4">
            <div class="px-4 sm:px-6 lg:px-8">
              <div class="mt-8 flex flex-col">
                <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                  <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                      <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                          <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Product</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">SKU</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Items(s)</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Sale Price</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Total Price</th>
                          </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                          <tr v-for="item in itemsToShow">
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-xs sm:text-sm font-medium text-gray-900 sm:pl-6">
                              {{ item.selectedproductName  }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-xs sm:text-sm text-gray-500">
                              {{ getSKU(item.selectedproductID) }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-xs sm:text-sm text-gray-500">
                              {{ item.productQuantity }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-xs sm:text-sm text-gray-500 font-bold">
                              KES <span class="text-green-700">{{ getUnitPrice(item) }}</span>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-xs sm:text-smtext-gray-500 font-bold">
                              KES <span class="text-green-700">{{ item.total }}</span>
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

    <div v-if="addModal" class="">
      <div
        class="modal fade fixed top-0 px-36 pt-2 w-screen h-screen outline-none overflow-x-hidden overflow-y-auto"
        id="exampleModalCenteredScrollable"
        tabindex="-1"
        aria-labelledby="exampleModalCenteredScrollable"
        aria-modal="true"
        role="dialog"
      >
        <div
          class="modal-dialog modal-dialog-centered modal-dialog-scrollable relative w-auto pointer-events-none"
        >
          <div
            class="modal-content border-none shadow-2xl relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current"
          >
            <div
              class="modal-header flex flex-shrink-0 items-center justify-between p-4 border-b border-gray-200 rounded-t-md"
            >
              <h5
                class="text-xl font-medium leading-normal text-gray-800"
                id="exampleModalCenteredScrollableLabel"
              >
                Add Product
              </h5>
              <button
                type="button"
                class="btn-close box-content w-4 h-4 p-1 text-black border-none rounded-none opacity-50 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-black hover:opacity-75 hover:no-underline"
                data-bs-dismiss="modal"
                aria-label="Close"
              ></button>
            </div>
            <div class="modal-body relative p-4">
              <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
                <div class="md:grid md:grid-cols-2 md:gap-6">
                  <div class="md:col-span-1">
                    <!-- <h3 class="text-lg font-medium leading-6 text-gray-900">Add Product</h3>
        <p class="mt-1 text-sm text-gray-500">Key in product details</p> -->
                  </div>
                  <div class="mt-5 md:mt-0 md:col-span-2">
                    <div class="grid grid-cols-6 gap-6">
                      <div class="col-span-6 sm:col-span-2">
                        <label
                          for="first-name"
                          class="block text-sm font-medium text-gray-700"
                          >Product name</label
                        >
                        <input
                          type="text"
                          name="first-name"
                          id="first-name"
                          v-model="form.product_name"
                          autocomplete="given-name"
                          class="mt-1 focus:ring-green-500 focus:border-light-green-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                        />
                      </div>

                      <div class="col-span-3 sm:col-span-1">
                        <label
                          for="last-name"
                          class="block text-sm font-medium text-gray-700"
                          >Product Quantity</label
                        >
                        <input
                          type="text"
                          name="last-name"
                          id="last-name"
                          v-model="form.product_quantity"
                          autocomplete="family-name"
                          class="mt-1 focus:ring-green-500 focus:border-light-green-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                        />
                      </div>

                      <div class="col-span-3 sm:col-span-1">
                        <label
                          for="last-name"
                          class="block text-sm font-medium text-gray-700"
                          >Stock Quantity</label
                        >
                        <input
                          type="text"
                          name="last-name"
                          id="last-name"
                          v-model="form.stock_quantity"
                          autocomplete="family-name"
                          class="mt-1 focus:ring-green-500 focus:border-light-green-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                        />
                      </div>

                      <div class="col-span-3 sm:col-span-1">
                        <label
                          for="last-name"
                          class="block text-sm font-medium text-gray-700"
                          >Production Price</label
                        >
                        <input
                          type="number"
                          name="last-name"
                          id="last-name"
                          v-model="form.production_price"
                          autocomplete="family-name"
                          class="mt-1 focus:ring-green-500 focus:border-light-green-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                        />
                      </div>

                      <div class="col-span-3 sm:col-span-1">
                        <label
                          for="last-name"
                          class="block text-sm font-medium text-gray-700"
                          >Sales Price</label
                        >
                        <input
                          type="number"
                          name="last-name"
                          id="last-name"
                          v-model="form.sales_price"
                          autocomplete="family-name"
                          class="mt-1 focus:ring-green-500 focus:border-light-green-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                        />
                      </div>

                      <div class="col-span-6 sm:col-span-6">
                        <label
                          for="email-address"
                          class="block text-sm font-medium text-gray-700"
                          >Product Description</label
                        >
                        <textarea
                          id="about"
                          name="about"
                          rows="3"
                          v-model="form.product_description"
                          class="shadow-sm focus:ring-green-500 focus:border-green-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"
                          placeholder=""
                        ></textarea>
                        <!-- <input type="text" name="email-address" id="email-address" autocomplete="email" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"> -->
                      </div>

                      <!-- <div class="col-span-6 sm:col-span-3">
                        <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                        <select id="country" name="country" autocomplete="country-name" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                          <option>United States</option>
                          <option>Canada</option>
                          <option>Mexico</option>
                        </select>
                      </div> -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div
              class="modal-footer flex flex-shrink-0 flex-wrap items-center justify-end p-4 border-t border-gray-200 rounded-b-md"
            >
              <button
                @click="addModal = false"
                type="button"
                class="inline-block px-6 py-2.5 bg-gray-300 text-red-300 font-bold text-xs leading-tight uppercase rounded shadow-md hover:bg-red-700 hover:text-white hover:shadow-lg focus:bg-purple-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-purple-800 active:shadow-lg transition duration-150 ease-in-out"
                data-bs-dismiss="modal"
              >
                Close
              </button>
              <button
                type="button"
                @click="addProduct"
                class="inline-block px-6 py-2.5 bg-light-green-900 text-white font-bold text-xs leading-tight uppercase rounded shadow-md hover:bg-green-900 hover:shadow-lg focus:bg-green-900 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out ml-1"
              >
                Add Product
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div
      v-if="saleModal"
      class="modal fade fixed p-40 w-full h-full outline-none overflow-x-hidden overflow-y-auto"
      id="exampleModalCenter"
      tabindex="-1"
      aria-labelledby="exampleModalCenterTitle"
      aria-modal="true"
      role="dialog"
    >
      <div class="modal-dialog modal-dialog-centered relative w-auto pointer-events-none">
        <div
          v-if="!showInvoice"
          class="modal-content border-none shadow-2xl relative flex flex-col w-full pointer-events-auto bg-gray-50 bg-clip-padding rounded-md outline-none text-current"
        >
          <div
            class="modal-header flex flex-shrink-0 items-center justify-between p-4 border-b border-gray-200 rounded-t-md"
          >
            <h5
              class="text-xl font-medium leading-normal text-gray-800"
              id="exampleModalScrollableLabel"
            >
              <!-- Kindly process your sale -->
            </h5>
            <button
              type="button"
              class="btn-close box-content w-4 h-4 p-1 text-black border-none rounded-none opacity-50 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-black hover:opacity-75 hover:no-underline"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body relative p-4">
            <p class="font-bold mt-2 text-gray-700 text-sm">
              Product Name:
              <span class="text-gray-600 font-medium text-md">
                {{ selectedItem.product_name }}
              </span>
            </p>
            <p class="font-bold mt-2 text-gray-700 text-sm">
              Product Size:
              <span class="text-gray-600 font-medium text-md">
                {{ selectedItem.product_quantity }} Grams
              </span>
            </p>
            <!-- <p class="font-bold mt-2">Minimum Sale Price:  <span class=""> <i class="ml-4 fas fa-eye cursor-pointer text-light-green-900 hover:text-green-900"></i> </span> </p>  -->
            <p class="font-bold mt-2 text-gray-700 text-sm">
              Delivery Batch:
              <span class="text-gray-600 font-medium text-md"> Batch 2</span>
            </p>
            <!-- <p class="font-bold mt-2 text-gray-700 text-sm">Remaining Stock Number: <span class="text-black font-medium text-lg"> {{ }} </span></p> -->
            <!-- <p class="font-bold mt-2 text-gray-700 text-sm">Sales Profit: <span class="text-black font-medium text-lg"> {{ }} </span></p> -->

            <div class="flex flex-row items-center py-8 px-4 mt-5">
              <!-- Code block starts -->
              <div class="flex flex-col lg:mr-16">
                <div class="flex justify-between">
                  <label
                    for="email"
                    class="text-gray-800 dark:text-gray-100 text-sm font-bold leading-tight tracking-normal mb-2"
                    >Select Purchasing Client:</label
                  >
                  <Link
                    href="/dashboard/add_client"
                    class="fas fa-user-plus text-light-green-900 cursor-pointer"
                  ></Link>
                </div>
                <select
                  v-model="purchasingClient"
                  id="country"
                  name="country"
                  autocomplete="country-name"
                  class="text-gray-600 dark:text-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-800 dark:focus:border-green-900 dark:border-gray-700 dark:bg-gray-800 bg-white font-normal w-64 h-10 flex items-center pl-3 text-sm border-gray-300 rounded border shadow"
                >
                  <option v-for="client in clients" :value="client">
                    {{ client.client_name }}
                  </option>
                  <!-- <option>Another Company</option>
                      <option>Bing Ink</option> -->
                </select>
                <!-- <input id="email" autocomplete="off" class="text-gray-600 dark:text-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-700 dark:focus:border-green-700 dark:border-gray-700 dark:bg-gray-800 bg-white font-normal w-64 h-10 flex items-center pl-3 text-sm border-gray-300 rounded border shadow" /> -->
              </div>
              <!-- Code block ends -->

              <!-- Code block starts -->
              <div class="flex flex-col lg:mr-16">
                <label
                  for="email"
                  class="text-gray-800 dark:text-gray-100 text-sm font-bold leading-tight tracking-normal mb-2"
                  >Number of items to sell:</label
                >
                <input
                  v-model="purchasingQuantity"
                  :placeholder="'Available: ' + selectedItem.stock_quantity"
                  type="number"
                  id="email"
                  autocomplete="off"
                  class="text-gray-600 dark:text-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-900 dark:focus:border-green-700 dark:border-gray-700 dark:bg-gray-800 bg-white font-normal w-40 h-10 flex items-center pl-3 text-sm border-gray-300 rounded border shadow"
                />
              </div>
              <!-- Code block ends -->
              <!-- Code block starts -->
              <!-- <div class="flex flex-col lg:mr-16 lg:py-0 py-4">
                    <label for="last_email" class="text-gray-400 text-sm font-bold leading-tight tracking-normal mb-2">Minumum Sale Price</label>
                    <input disabled id="last_email" class="text-gray-600 dark:text-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-700 dark:focus:border-green-900 dark:border-gray-700 dark:bg-gray-700 font-normal w-40 h-10 flex items-center pl-3 text-sm border-gray-300 bg-gray-200 rounded border shadow" placeholder="120" />
                </div> -->
              <!-- Code block ends -->
              <!-- Code block starts -->
              <div class="flex flex-col lg:mr-16 lg:py-0 py-4">
                <label
                  for="email1"
                  class="text-gray-800 text-sm font-bold leading-tight tracking-normal mb-2"
                  >Actual Sale Price:</label
                >
                <input
                  v-model="purchasingPrice"
                  :placeholder="'Min Price: ' + selectedItem.sales_price"
                  type="number"
                  id="email1"
                  class="text-gray-600 dark:text-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-900 dark:focus:border-green-900 dark:border-gray-700 dark:bg-gray-800 bg-white font-normal w-40 h-10 flex items-center pl-3 text-sm border-gray-300 rounded border shadow"
                />
              </div>
              <!-- Code block ends -->
            </div>
          </div>
          <div
            class="modal-footer flex flex-shrink-0 flex-wrap items-center justify-end p-4 border-t border-gray-200 rounded-b-md"
          >
            <button
              @click="saleModal = false"
              type="button"
              class="inline-block px-6 py-2.5 bg-gray-300 text-red-300 font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-red-900 hover:text-white hover:shadow-lg focus:bg-red-900 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-red-900 active:shadow-lg transition duration-150 ease-in-out"
              data-bs-dismiss="modal"
            >
              Close
            </button>
            <button
              @click="showInvoice = true"
              type="button"
              class="inline-block px-6 py-2.5 bg-light-green-900 hover:bg-green-900 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out ml-1"
            >
              Process Invoice <i class="fas fa-clipboard-check ml-2 fa-xl"></i>
            </button>
          </div>
        </div>

        <div
          v-if="showInvoice"
          class="modal-content border-none shadow-2xl relative flex flex-col w-full pointer-events-auto bg-gray-50 bg-clip-padding rounded-md outline-none text-current"
        >
          <div
            class="modal-header flex flex-shrink-0 items-center justify-between p-4 border-b border-gray-200 rounded-t-md"
          >
            <h5
              class="text-xl font-medium leading-normal text-gray-800"
              id="exampleModalScrollableLabel"
            >
              <!-- Kindly process your sale -->
            </h5>
            <button
              type="button"
              class="btn-close box-content w-4 h-4 p-1 text-black border-none rounded-none opacity-50 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-black hover:opacity-75 hover:no-underline"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div>
            <div class="flex items-center justify-center min-h-screen bg-gray-100">
              <div class="w-3/5 bg-white shadow-lg">
                <div class="flex justify-between p-4">
                  <div>
                    <h1 class="text-3xl font-extrabold tracking-widest text-green-900">
                      Brisk Invoice
                    </h1>
                  </div>
                  <div class="p-2">
                    <ul class="flex">
                      <li
                        class="flex flex-col items-center p-2 border-l-2 border-light-green-900"
                      >
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          class="w-6 h-6 text-green-900"
                          fill="none"
                          viewBox="0 0 24 24"
                          stroke="currentColor"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"
                          />
                        </svg>
                        <span class="text-sm"> www.brisk.co.ke </span>
                        <span class="text-sm"> www.biddersportal.com </span>
                      </li>
                      <li
                        class="flex flex-col items-center p-2 border-l-2 border-light-green-900"
                      >
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          class="w-6 h-6 text-green-900"
                          fill="none"
                          viewBox="0 0 24 24"
                          stroke="currentColor"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                          />
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                          />
                        </svg>
                        <span class="text-sm"> Syokimau, Nairobi Kenya </span>
                        <span class="text-sm"> Community road Narnawarayan Go down </span>
                      </li>
                    </ul>
                  </div>
                </div>
                <div class="w-full h-0.5 bg-green-500"></div>
                <div class="flex justify-between p-4">
                  <div>
                    <h6 class="font-bold">
                      Order Date :
                      <span class="text-sm font-medium"> {{ currentTime }}</span>
                    </h6>
                    <!-- <h6 class="font-bold">Order ID : <span class="text-sm font-medium"> 12/12/2022</span></h6> -->
                  </div>
                  <div class="w-auto">
                    <address class="text-sm">
                      <span class="font-bold"> Billed To : </span>
                      {{ purchasingClient.client_name }}
                    </address>
                  </div>
                  <!-- <div class="w-40">
                          <address class="text-sm">
                              <span class="font-bold">Ship To :</span>
                              Client Name
                          </address>
                      </div> -->
                  <div></div>
                </div>
                <div class="flex justify-center p-4">
                  <div class="border-b border-gray-200 shadow">
                    <table class="">
                      <thead class="bg-gray-50">
                        <tr>
                          <th class="px-4 py-2 text-xs text-gray-500">Product Name</th>
                          <th class="px-4 py-2 text-xs text-gray-500">Product Size</th>
                          <th class="px-4 py-2 text-xs text-gray-500">Quantity Sold</th>
                          <th class="px-4 py-2 text-xs text-gray-500">Rate</th>
                          <th class="px-4 py-2 text-xs text-gray-500">Subtotal</th>
                        </tr>
                      </thead>
                      <tbody class="bg-white">
                        <tr class="whitespace-nowrap">
                          <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                              {{ selectedItem.product_name }}
                            </div>
                          </td>
                          <td class="px-6 py-4 text-sm text-gray-500">
                            {{ selectedItem.product_quantity }}
                          </td>
                          <td class="px-6 py-4">
                            <div class="text-sm text-gray-500">
                              {{ purchasingQuantity }}
                            </div>
                          </td>
                          <td class="px-6 py-4 text-sm text-gray-500">
                            KES {{ purchasingPrice }}
                          </td>
                          <td class="px-6 py-4">KES {{ subTotal }}</td>
                        </tr>
                        <tr class="">
                          <td colspan="3"></td>
                          <td class="text-sm font-bold">Sub Total</td>
                          <td class="text-sm font-bold tracking-wider">
                            <b>KES {{ subTotal }}</b>
                          </td>
                        </tr>
                        <!--end tr-->
                        <tr>
                          <th colspan="3"></th>
                          <td class="text-sm font-bold"><b>Tax Rate</b></td>
                          <td class="text-sm font-bold"><b>KES 16%</b></td>
                        </tr>
                        <!--end tr-->
                        <tr class="text-white bg-gray-800">
                          <th colspan="3"></th>
                          <td class="text-sm font-bold"><b>Total</b></td>
                          <td class="text-sm font-bold">
                            <b>KES {{ total }}</b>
                          </td>
                        </tr>
                        <!--end tr-->
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="flex justify-between p-4">
                  <div>
                    <h3 class="text-xl">Terms And Condition :</h3>
                    <ul class="text-xs list-disc list-inside">
                      <li>
                        All accounts are to be paid within 7 days from receipt of invoice.
                      </li>
                      <li>
                        To be paid by cheque or credit card or direct payment online.
                      </li>
                      <li>
                        If account is not paid within 7 days the credits details supplied.
                      </li>
                    </ul>
                  </div>
                  <!-- <div class="p-4">
                          <h3>Signature</h3>
                          <div class="text-4xl italic text-green-500">AAA</div>
                      </div> -->
                </div>
                <div class="w-full h-0.5 bg-green-500"></div>

                <div class="p-4">
                  <div class="flex items-center justify-center">
                    Thank you very much for doing business with us.
                  </div>
                  <!-- <div class="flex items-end justify-end space-x-3">
                          <button class="px-4 py-2 text-sm text-green-600 bg-green-100">Print</button>
                          <button class="px-4 py-2 text-sm text-blue-600 bg-blue-100">Save</button>
                          <button class="px-4 py-2 text-sm text-red-600 bg-red-100">Cancel</button>
                      </div> -->
                </div>
              </div>
            </div>
            <div class="flex items-center justify-center min-h-auto bg-gray-100">
              <div class="w-6/12 mt-4 text-left bg-white shadow-lg">
                <div class="flex justify-between px-8 py-6">
                  <div class="flex items-center">Invoice Options</div>
                  <div class="flex items-center gap-4">
                    <button
                      class="px-2 py-1 bg-gray-200 hover:bg-green-800 hover:text-white"
                    >
                      Send to Client Email <i class="ml-2 fa fa-paper-plane"></i>
                    </button>
                    <button
                      class="px-2 py-1 bg-gray-200 hover:bg-green-800 hover:text-white"
                    >
                      Download <i class="ml-2 fa fa-download"></i>
                    </button>
                  </div>
                </div>
                <div class="w-full h-0.5 bg-gray-800"></div>
              </div>
            </div>
          </div>
          <div
            class="modal-footer flex flex-shrink-0 flex-wrap items-center justify-end p-4 border-t border-gray-200 rounded-b-md"
          >
            <button
              @click="showInvoice = false"
              type="button"
              class="inline-block px-6 py-2.5 bg-gray-300 text-red-300 font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-red-900 hover:text-white hover:shadow-lg focus:bg-green-900 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-purple-800 active:shadow-lg transition duration-150 ease-in-out"
              data-bs-dismiss="modal"
            >
              Back
            </button>
            <button
              @click="addSale"
              type="button"
              class="inline-block px-6 py-2.5 bg-light-green-900 hover:bg-green-900 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out ml-1"
            >
              Finish Sale <i class="fas fa-handshake-angle ml-2 fa-xl"></i>
            </button>
          </div>
        </div>
      </div>
    </div>

    <div
      v-if="clientTypeModal"
      class="relative z-10"
      aria-labelledby="modal-title"
      role="dialog"
      aria-modal="true"
    >
     
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

      <div class="fixed z-10 inset-0 overflow-y-auto">
        <div
          class="flex items-end sm:items-center justify-center min-h-full p-4 text-center sm:p-0"
        >
          
          <div
            class="relative bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full sm:p-6"
          >
            <div
              @click="clientTypeModal = false"
              class="text-gray-500 hover:text-gray-900"
            >
              <i class="flex justify-end fas fa-xmark"></i>
            </div>
            <div>
              <div
                class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100"
              >
                <i class="fas fa-users"></i>
              </div>
              <div class="mt-3 text-center sm:mt-5">
                <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">
                  Choose Client Type
                </h3>
              </div>
            </div>

            <div v-if="!defaultClientButtons">
              <div class="mt-2 col-span-3 sm:col-span-4">
                <div class="mt-1">
                  <select
                    v-model="purchasingClient"
                    id="country"
                    name="country"
                    autocomplete="country-name"
                    class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-light-green-900 focus:border-light-green-900 sm:text-sm"
                  >
                    <option v-for="client in clients" :value="client">
                      {{ client.client_name }}
                    </option>
                  </select>
                </div>
              </div>
            </div>

            <div v-if="saleDetails" class="mt-10">
              <h3 id="shipping-heading" class="text-lg font-medium text-gray-900">
                Sale Details
              </h3>

              <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-3">
                <div class="sm:col-span-3">
                  <label for="address" class="block text-sm font-medium text-gray-700"
                    >Product name</label
                  >
                  <div class="mt-1">
                    <input
                      :placeholder="selectedItem.product_name"
                      disabled
                      type="text"
                      id="address"
                      name="address"
                      autocomplete="street-address"
                      class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm"
                    />
                  </div>
                </div>

                <div>
                  <label for="city" class="block text-sm font-medium text-gray-700"
                    >SKU in MG</label
                  >
                  <div class="mt-1">
                    <input
                      :placeholder="selectedItem.stock_quantity"
                      disabled
                      type="text"
                      id="city"
                      name="city"
                      autocomplete="address-level2"
                      class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm"
                    />
                  </div>
                </div>

                <div>
                  <label for="region" class="block text-sm font-medium text-gray-700"
                    >Sale Price Per Unit</label
                  >
                  <div class="mt-1">
                    <input
                      v-model="purchasingPrice"
                      :placeholder="'Min Price: ' + selectedItem.sales_price"
                      type="number"
                      id="region"
                      name="region"
                      class="border-2 border-solid block w-full rounded-md shadow-sm ring-light-green-900 focus:border-green-900 border-light-green-900 sm:text-sm"
                    />
                  </div>
                </div>

                <div>
                  <label for="postal-code" class="block text-sm font-medium text-gray-700"
                    >Number of Units</label
                  >
                  <div class="mt-1">
                    <input
                      v-model="purchasingQuantity"
                      :placeholder="'Available Units: ' + selectedItem.stock_quantity"
                      type="number"
                      id="postal-code"
                      name="postal-code"
                      class="border-2 border-solid block w-full rounded-md shadow-sm ring-light-green-900 focus:border-green-900 border-light-green-900 sm:text-sm"
                    />
                  </div>
                </div>
              </div>
            </div>

            <div
              v-if="defaultClientButtons"
              class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense"
            >
              <Link
                href="/dashboard/add_client"
                type="button"
                class="group mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white hover:bg-light-green-900 text-base font-bold text-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:col-start-1 sm:text-sm"
              >
                New Client
                <i
                  class="ml-2 mt-1 fas fa-user-plus text-green-900 group-hover:text-white"
                ></i>
              </Link>
              <button
                @click="processButtons('existing')"
                type="button"
                class="group mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white hover:bg-light-green-900 text-base font-bold text-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:col-start-2 sm:text-sm"
              >
                Existing Client
                <i
                  class="ml-2 mt-1 fas fa-user-check text-green-900 group-hover:text-white"
                ></i>
              </button>
            </div>

            <div
              v-if="selectClientButtons"
              class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense"
            >
              <button
                @click="processButtons('previous')"
                type="button"
                class="group mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white hover:bg-light-green-900 text-base font-bold text-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:col-start-1 sm:text-sm"
              >
                <i
                  class="mr-2 mt-1 fas fa-caret-left text-green-900 group-hover:text-white"
                ></i>
                Previous Menu
              </button>
              <button
                @click="processButtons('selectedClient')"
                type="button"
                class="group mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white hover:bg-light-green-900 text-base font-bold text-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:col-start-2 sm:text-sm"
              >
                Select Client
                <i
                  class="ml-2 mt-1 fas fa-caret-right text-green-900 group-hover:text-white"
                ></i>
              </button>
            </div>

            <div
              v-if="saleDetails"
              class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense"
            >
              <button
                @click="processSale()"
                type="button"
                class="group mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white hover:bg-light-green-900 text-base font-bold text-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:col-start-2 sm:text-sm"
              >
                Process Invoice
                <i
                  class="ml-2 mt-1 fas fa-caret-right text-green-900 group-hover:text-white"
                ></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div
      v-if="bottomCanvas"
      class="offcanvas offcanvas-bottom fixed bottom-0 flex flex-col max-w-full bg-gray-100 bg-clip-padding shadow-sm outline-none transition duration-300 ease-in-out text-gray-700 left-0 right-0 border-none h-screen max-h-full"
      tabindex="-1"
      id="offcanvasBottom"
      aria-labelledby="offcanvasBottomLabel"
    >
      <div class="offcanvas-header flex items-center justify-between p-4">
        <!-- <h5 class="offcanvas-title mb-0 leading-normal font-extrabold text-gray-700 text-xl" id="offcanvasBottomLabel">Process Invoice</h5> -->
        <i
          @click="bottomCanvas = false"
          class="fas fa-xmark fa-2x hover:cursor-pointer"
        ></i>
        <!-- <button type="button" class="btn-close box-content w-4 h-4 p-2 -my-5 -mr-2 text-black border-none rounded-none opacity-50 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-black hover:opacity-75 hover:no-underline" data-bs-dismiss="offcanvas" aria-label="Close"></button> -->
      </div>
      <div class="h-screen offcanvas-body flex-grow p-4 overflow-y-auto small">
        <Canvas
          :clients="clients"
          :products="products"
          :trips="trips"
          :currentMessage="currentMessage"
          :invoiceLog="invoiceLog"
        />
      </div>
    </div>

  </AppLayout>
</template>

<style>
.checkbox:checked + .check-icon {
  display: flex;
}
</style>
