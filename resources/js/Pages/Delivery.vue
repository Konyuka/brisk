<script setup>
import { useForm, usePage, Link } from "@inertiajs/inertia-vue3";
import { ref, computed, reactive, watch, onMounted  } from "vue";
import { Inertia } from "@inertiajs/inertia";
import AppLayout from "@/Layouts/AppLayout.vue";
import Canvass from "./Canvass.vue";
import moment from "moment";

defineProps({
  products: Array,
  salesAgents: Array,
  sales: Array,
  message: String,
  tripBatch: String
});

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
onMounted(() => {
  if (currentMessage.value != null) {
    bottomCanvas.value = true
  } else { 
    bottomCanvas.value = false
  }
      
})
const addModal = ref(false);
const saleModal = ref(false);
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

const getInvoiceForm = async () =>
{ 
  Inertia.reload({ only: ['tripBatch'] })
  bottomCanvas.value = true
}

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
              <span class="relative">Trips</span>
            </span>
          </h2>
        </div>
       
        <div class="text-center">
          <!-- <button
            @click="addModal = true"
            class="mr-2 inline-flex items-center justify-center w-full h-12 px-6 font-medium tracking-wide text-white transition duration-200 rounded shadow-md md:w-auto bg-light-green-900 hover:bg-white hover:text-light-green-900 focus:shadow-outline focus:outline-none"
          >
            Add Product
          </button> -->

          <button
            @click="getInvoiceForm"
            class="inline-flex items-center justify-center w-full h-12 px-6 font-medium tracking-wide text-white transition duration-200 rounded shadow-md md:w-auto bg-light-green-900 hover:bg-white hover:text-light-green-900 focus:shadow-outline focus:outline-none"
          >
            Register Trip
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
                  Trips Listing
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
                      v-for="(trip, index) in trips"
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
                            class="hover:font-extrabold cursor-help text-xs sm:text-sm font-medium leading-none text-gray-700 mr-2"
                          >
                            {{ trip.product_name }}
                          </p>
                        </div>
                      </td>
                      <td class="pl-2">
                        <div class="flex items-center">
                          <i class="fas fa-scale-unbalanced text-green-900"></i>
                          <p
                            class="text-xs sm:text-sm leading-none text-gray-600 sm:ml-1 ml-2"
                          >
                            {{ trip.product_quantity }} Grams
                          </p>
                        </div>
                      </td>
                      <td class="pl-2">
                        <div class="flex items-center">
                          <i class="fas fa-boxes-packing text-green-900"></i>
                          <p
                            class="text-xs sm:text-sm leading-none text-gray-600 sm:ml-1 ml-2"
                          >
                            {{ trip.stock_quantity }} items
                          </p>
                        </div>
                      </td>
                      <td class="pl-2">
                        <div class="flex items-center">
                          <i class="fas fa-money-bill-trend-up text-green-900"></i>
                          <p
                            class="text-xs sm:text-sm leading-none text-gray-600 sm:ml-1 ml-2"
                          >
                            {{ trip.sales_price }} KES
                          </p>
                        </div>
                      </td>
                    </tr>
                    <tr class="h-3"></tr>
                  </tbody>
                </table>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>

    <div v-if="bottomCanvas" class="offcanvas offcanvas-bottom fixed bottom-0 flex flex-col max-w-full bg-gray-100 bg-clip-padding shadow-sm outline-none transition duration-300 ease-in-out text-gray-700 left-0 right-0 border-none h-screen max-h-full" tabindex="-1" id="offcanvasBottom" aria-labelledby="offcanvasBottomLabel">
      <div class="offcanvas-header flex items-center justify-between p-4">
        <!-- <h5 class="offcanvas-title mb-0 leading-normal font-extrabold text-gray-700 text-xl" id="offcanvasBottomLabel">Process Invoice</h5> -->
        <i @click="bottomCanvas=false" class="fas fa-xmark fa-2x hover:cursor-pointer"></i>
        <!-- <button type="button" class="btn-close box-content w-4 h-4 p-2 -my-5 -mr-2 text-black border-none rounded-none opacity-50 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-black hover:opacity-75 hover:no-underline" data-bs-dismiss="offcanvas" aria-label="Close"></button> -->
      </div>
      <div class="h-screen offcanvas-body flex-grow p-4 overflow-y-auto small">
        <Canvass :salesAgents="salesAgents" :products="products" :currentMessage="currentMessage" :tripBatch="tripBatch"/>
      </div>
    </div>

  </AppLayout>
</template>

<style>
.checkbox:checked + .check-icon {
  display: flex;
}
</style>
