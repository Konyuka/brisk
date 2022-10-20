<script setup>
import { useForm, usePage, Link } from "@inertiajs/inertia-vue3";
import { ref, toRefs, computed, reactive, watch, onMounted } from "vue";
import { Inertia } from "@inertiajs/inertia";
import AppLayout from "@/Layouts/AppLayout.vue";
import Canvass from "./Canvass.vue";
import moment from "moment";

const props = defineProps({
  products: Array,
  salesAgents: Array,
  teamLead: Array,
  sales: Array,
  message: String,
  tripBatch: String,
  trips: Array,
  activeAgents: Array,
  activeTeamLeads: Array,
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

const currentProduct = ref([]);
const selectedTripID = ref(null);
const tripProducts = ref([]);
watch(
  tripProducts,
  (newX) => {
    for (let index = 0; index < newX.length; index++) {
      let object = {
        productID: newX[index].id,
        restocked: null,
        spoiledProducts: null,
        expectedProducts: null,
      };
      currentProduct.value.push(object);
    }
  },
  {
    deep: true,
    immediate: true,
  }
);
const closingTripModal = ref(false);
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
    bottomCanvas.value = true;
  } else {
    bottomCanvas.value = false;
  }
});

const spoiledProducts = ref(null);
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

const setExpected = (value) => {
  let objIndex = currentProduct.value.findIndex((obj) => obj.productID == value);
  let itemsToDeduct =
    parseInt(currentProduct.value[objIndex].restocked) +
    parseInt(currentProduct.value[objIndex].spoiledProducts);
  let losItems =
    parseInt(currentProduct.value[objIndex].expectedProducts) - itemsToDeduct;
  currentProduct.value[objIndex].spoiledProducts = losItems;
  console.log(losItems);
};
const checkLoadedItems = (value) => {
  let parseJson = JSON.parse(value);
  let expectedItems = parseJson[0].numberItems - parseJson[0].itemsSold;
  let soldItems = parseJson[0].itemsSold;
  return parseInt(expectedItems) + parseInt(soldItems);
};
const checkExpectedItems = (value, value2) => {
  let parseJson = JSON.parse(value);
  let expectedItems = parseJson[0].numberItems - parseJson[0].itemsSold;
  let objIndex = currentProduct.value.findIndex((obj) => obj.productID == value2);
  currentProduct.value[objIndex].expectedProducts = expectedItems;
  // console.log(currentProduct.value[objIndex].restocked);
  // return;

  // for (let index = 0; index < parseJson.length; index++) {
  //   return;
  // }
  // return;
  return expectedItems;
};
const checkSoldItems = (value) => {
  let parseJson = JSON.parse(value);
  return parseJson[0].itemsSold;
};
const checkMissingItems = (value) => {
  let parseJson = JSON.parse(value);
  let expectedItems = parseJson[0].numberItems - parseJson[0].itemsSold;
  let missingItems = expectedItems - spoiledProducts.value;
  if (missingItems == expectedItems) {
    return 0;
  } else {
    return missingItems;
  }
};

const loadTripItems = (trip) => {
  selectedTripID.value = trip.id;
  let productIDS = JSON.parse(trip.products_ids);
  for (let index = 0; index < productIDS.length; index++) {
    let found = props.products.find((item) => item.id === productIDS[index]);
    tripProducts.value.push(found);
  }
  closingTripModal.value = true;
};

const formatTime = (value) => {
  return moment(value).format("MMMM Do YYYY, h:mm:ss a");
};

const getInvoiceForm = async () => {
  Inertia.reload({ only: ["tripBatch"] });
  bottomCanvas.value = true;
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

      <div class="px-20">
        <div
          v-if="currentMessage != null"
          class="flex p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
          role="alert"
        >
          <svg
            aria-hidden="true"
            class="flex-shrink-0 inline w-5 h-5 mr-3"
            fill="currentColor"
            viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              fill-rule="evenodd"
              d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
              clip-rule="evenodd"
            ></path>
          </svg>
          <span class="sr-only">Info</span>
          <div>
            <span class="font-medium">{{ currentMessage }}</span>
          </div>
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
                            # {{ trip.id }}
                          </div>
                        </div>
                      </td>
                      <td class="">
                        <div class="flex items-center pl-1 sm:pl-3">
                          <p
                            class="hover:font-extrabold cursor-help text-xs sm:text-sm font-medium leading-none text-gray-700 mr-2"
                          >
                            <span class="capitalize">{{ trip.lead_name }}'s</span> Group
                          </p>
                        </div>
                      </td>
                      <td class="pl-2">
                        <div class="flex items-center">
                          <i class="fas fa-location-dot text-green-900"></i>
                          <p
                            class="text-xs sm:text-sm leading-none text-gray-600 sm:ml-1 ml-2"
                          >
                            Location: {{ trip.trip_location }}
                          </p>
                        </div>
                      </td>
                      <td class="pl-2">
                        <div class="flex items-center">
                          <i class="fas fa-truck text-green-900"></i>
                          <p
                            class="text-xs sm:text-sm leading-none text-gray-600 sm:ml-1 ml-2"
                          >
                            Truck {{ trip.vehicle_number }}
                          </p>
                        </div>
                      </td>
                      <td class="pl-2">
                        <div class="flex items-center">
                          <i class="fas fa-money-timer text-green-900"></i>
                          <p
                            class="text-xs sm:text-sm leading-none text-gray-600 sm:ml-1 ml-2"
                          >
                            {{ formatTime(trip.created_at) }}
                          </p>
                        </div>
                      </td>
                      <td class="pl-2">
                        <div class="flex items-center">
                          <i
                            class="transform translate hover:scale-150 duration-600 fas fa-eye text-green-900"
                          ></i>
                        </div>
                      </td>
                      <td class="pl-2">
                        <div class="flex items-center">
                          <button
                            @click="loadTripItems(trip)"
                            type="button"
                            class="inline-flex items-center rounded border border-transparent bg-green-900 px-2.5 py-1.5 text-xs font-medium text-white shadow-sm hover:bg-light-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                          >
                            Finish Trip <i class="ml-2 fas fa-flag-checkered"></i>
                          </button>
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
        <Canvass
          :teamLead="teamLead"
          :salesAgents="salesAgents"
          :products="products"
          :currentMessage="currentMessage"
          :tripBatch="tripBatch"
          :activeAgents="activeAgents"
          :activeTeamLeads="activeTeamLeads"
        />
      </div>
    </div>

    <div
      v-if="closingTripModal"
      class="relative z-10"
      aria-labelledby="modal-title"
      role="dialog"
      aria-modal="true"
    >
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

      <div class="fixed inset-0 z-10 overflow-y-auto">
        <div
          class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0"
        >
          <div
            class="relative transform overflow-hidden rounded-lg bg-white px-4 pt-5 pb-4 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-3xl sm:p-6"
          >
            <div class="sm:flex sm:items-start">
              <div
                class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10"
              >
                <i class="fas fa-chart-pie"></i>
              </div>
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                  Finish Trip #{{ selectedTripID }}
                </h3>
                <div class="mt-2">
                  <div
                    class="mb-4"
                    v-for="(product, i) in tripProducts"
                    :key="product.id"
                  >
                    <div
                      class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-1 sm:border-t sm:border-gray-200 sm:pt-5"
                    >
                      <div class="flex flex-row justify-around">
                        <label
                          for="first-name"
                          class="block text-xs font-medium text-gray-700 sm:mt-px sm:pt-2"
                          >{{ product.product_name }}</label
                        >
                        <label
                          for="first-name"
                          class="block text-xs font-medium text-gray-700 sm:mt-px sm:pt-2"
                          >{{ product.product_quantity }}</label
                        >
                      </div>
                      <div class="mt-1 sm:col-span-1 sm:mt-0">
                        <input
                          disabled
                          type="text"
                          name="first-name"
                          id="first-name"
                          autocomplete="given-name"
                          class="bg-gray-300 font-bold italic text-xs block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:max-w-xs sm:text-sm"
                          :placeholder="'Loaded:' + checkLoadedItems(product.trip_batch)"
                        />
                      </div>
                      <div class="mt-1 sm:col-span-1 sm:mt-0">
                        <input
                          disabled
                          type="text"
                          name="first-name"
                          id="first-name"
                          autocomplete="given-name"
                          class="bg-gray-300 font-bold italic text-xs block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:max-w-xs sm:text-sm"
                          :placeholder="'Sold:' + checkSoldItems(product.trip_batch)"
                        />
                      </div>
                      <div class="mt-1 sm:col-span-1 sm:mt-0">
                        <input
                          disabled
                          type="text"
                          name="first-name"
                          id="first-name"
                          autocomplete="given-name"
                          class="bg-gray-300 font-bold italic block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:max-w-xs sm:text-sm"
                          :placeholder="
                            'Expected Stock: ' +
                            checkExpectedItems(product.trip_batch, product.id)
                          "
                        />
                      </div>
                      <div class="mt-1 sm:col-span-1 sm:mt-0"></div>
                      <div class="mt-1 sm:col-span-1 sm:mt-0">
                        <input
                          @change="setExpected(product.id)"
                          v-model="currentProduct[i].restocked"
                          type="text"
                          name="first-name"
                          id="first-name"
                          autocomplete="given-name"
                          class="font-bold italic text-xs block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:max-w-xs sm:text-sm"
                          :placeholder="'Restocked:'"
                        />
                      </div>
                      <div class="mt-1 sm:col-span-1 sm:mt-0">
                        <!-- v-model="spoiledProducts[i]" -->
                        <!-- v-model="spoiledProducts[getIndex(tripProducts, product.id)]" -->
                        <input
                          @change="setExpected(product.id)"
                          v-model="currentProduct[i].spoiledProducts"
                          type="text"
                          name="first-name"
                          id="first-name"
                          autocomplete="given-name"
                          class="font-bold italic text-xs block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:max-w-xs sm:text-sm"
                          :placeholder="'Spolled:'"
                        />
                      </div>
                      <div class="mt-1 sm:col-span-1 sm:mt-0">
                        <input
                          disabled
                          type="text"
                          name="first-name"
                          id="first-name"
                          autocomplete="given-name"
                          class="font-bold italic text-xs bg-gray-300 block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:max-w-xs sm:text-sm"
                          :placeholder="
                            'Missing:' + checkMissingItems(product.trip_batch)
                          "
                        />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="mt-5 sm:mt-4 sm:ml-10 sm:flex justify-between sm:pl-4">
              <button
                type="button"
                class="inline-flex w-full justify-center rounded-md border border-transparent bg-green-800 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-light-green-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:w-auto sm:text-sm"
              >
                Finish Trip
              </button>
              <button
                type="button"
                class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white hover:bg-red-600 px-4 py-2 text-base font-medium text-gray-700 hover:text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
              >
                Cancel
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<style>
.checkbox:checked + .check-icon {
  display: flex;
}
</style>
