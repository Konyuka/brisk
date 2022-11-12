<script setup>
import { useForm, usePage } from "@inertiajs/inertia-vue3";
import { ref, computed, watch, toRefs } from "vue";
import { Inertia } from "@inertiajs/inertia";

const props = defineProps({
  salesAgents: Array,
  teamLead: Array,
  products: Array,
  trips: Array,
  activeAgents: Array,
  activeTeamLeads: Array,
  currentMessage: String,
  tripBatch: String,
});

const { tripBatch } = toRefs(props);
const { activeAgents } = toRefs(props);
const { activeTeamLeads } = toRefs(props);

const productsNumber = computed(() => {
  return selectedProducts.value.length;
});
const brandsNumber = computed(() => {
  return selectedProducts.value.length;
});
const agentsNumber = computed(() => {
  return selectedAgents.value.length;
});

const clientsCollapse = computed(() => {
  if (purchasingClient.value != "") {
    return true;
  } else {
    return false;
  }
});
const productsCollapse = computed(() => {
  let lastProductIndex = productsNumber.value + 1;
  if (selectedProducts.value[0].productname != null) {
    // if (purchasedProduct.value != "") {
    return true;
  } else {
    return false;
  }
});
const agentsCollapse = computed(() => {
  if (selectedAgents.value[0].selectedAgentName != null) {
    // if (purchasedProduct.value != "") {
    return true;
  } else {
    return false;
  }
});
const searchedClientsArray = computed(() => {
  if (searchedClient.value.length != 0) {
    return true;
  } else {
    return false;
  }
});

const selectedProductIndex = computed(() => {
  return selectedProducts.value.length - 1;
});

const selectedAgentIndex = computed(() => {
  return selectedAgents.value.length - 1;
});

const uploadProductModal = ref(false);
const searchedProductsArray = ref(null);
const searchedAgentsArray = ref(null);
const currentUser = computed(() => usePage().props.value.user.id);

const form = useForm({
  client_name: "",
  client_email: "",
  client_contact: "",
  client_kra: "",
  client_address: "",
  added_by: currentUser,
  tax_exempt: false,
});

const clientsCollapseValue = ref("");
const productsCollapseValue = ref("");
const agentsCollapseValue = ref("");
const searchedClient = ref([]);
const searchedProduct = ref([]);
const searchedAgent = ref([]);
const purchasingClient = ref("");
const purchasedProduct = ref("");
const chosenAgent = ref("");

const selectedProducts = ref([
  {
    selectedproductID: null,
    productname: null,
    selectedproductName: null,
    productSKU: null,
    productQuantity: null,
    remainingProducts: null,
    selectedType: null,
  },
]);

const selectedAgents = ref([
  {
    selectedAgentName: null,
    selectedAgentID: null,
    selectedType: null,
  },
]);
const tripDetails = useForm({
  lead: null,
  location: null,
  truckPlate: null,
  driver: null,
  loadedProducts: null,
  returnedProducts: null,
  soldProducts: null,
  spoiledProducts: null,
  missingProducts: null,
  tripBatch: tripBatch.value,
  selectedType: "details",
  added_by: currentUser.value,
  brandsNumber: brandsNumber,
  agentsNumber: agentsNumber,
});

watch(
  chosenAgent,
  (value) => {
    if (value.length != 0) {
      let allAgents = props.salesAgents;
      searchedAgent.value = [];
      for (var i = 0; i < allAgents.length; i++) {
        if (selectedAgents.value != []) {
          if (
            allAgents[i].name.indexOf(
              selectedAgents.value[selectedAgentIndex.value].selectedAgentName
            ) != -1
          ) {
            searchedAgent.value.push(allAgents[i]);
          }
        }
      }
    }
  },
  {
    deep: true,
    immediate: true,
  }
);
watch(
  purchasedProduct,
  (value) => {
    if (value.length != 0) {
      let allProducts = props.products;
      searchedProduct.value = [];
      for (var i = 0; i < allProducts.length; i++) {
        if (selectedProducts.value != []) {
          if (
            allProducts[i].product_name.indexOf(
              selectedProducts.value[selectedProductIndex.value].productname
            ) != -1
          ) {
            searchedProduct.value.push(allProducts[i]);
          }
        }
      }
    }
  },
  {
    deep: true,
    immediate: true,
  }
);
watch(
  chosenAgent,
  (value) => {
    // console.log(value);
    if (value.length != 0 && value[0].selectedAgentName == null) {
      // alert('hi')
      searchedAgent.value = [];
      // selectedProducts.value = [];
      searchedAgentsArray.value = null;
    }
    agentsCollapseValue.value = agentsCollapse;
  },
  {
    deep: true,
    immediate: true,
  }
);
watch(
  purchasedProduct,
  (value) => {
    // console.log(value[0].productname)
    if (value.length != 0 && value[0].productname == "") {
      // alert('hi')
      searchedProduct.value = [];
      // selectedProducts.value = [];
      searchedProductsArray.value = null;
    }
    productsCollapseValue.value = productsCollapse;
  },
  {
    deep: true,
    immediate: true,
  }
);
watch(
  selectedAgents,
  (value) => {
    // console.log(value)
    chosenAgent.value = value;
    searchedAgentsArray.value = true;
  },
  {
    deep: true,
    immediate: true,
  }
);
watch(
  selectedProducts,
  (value) => {
    // console.log(value)
    purchasedProduct.value = value;
    searchedProductsArray.value = true;
  },
  {
    deep: true,
    immediate: true,
  }
);
watch(
  selectedProducts,
  (value) => {
    // console.log(value)
    let numberOfItems = [];
    selectedProducts.value.forEach(function (item, index) {
      numberOfItems.push(parseInt(item.productQuantity));
    });
    numberOfItems.pop();
    tripDetails.loadedProducts = numberOfItems.reduce((a, b) => a + b, 0);
  },
  {
    deep: true,
    immediate: true,
  }
);


const reloadData = () => {
  Inertia.reload({
    only: [
      "salesAgents",
      "teamLead",
      "products",
      "trips",
      "activeAgents",
      "activeTeamLeads",
      "tripBatch",
    ],
  });
};

const checkTeamLead = () => {
  // if (activeTeamLeads.value.includes(tripDetails.lead)) {
  //   tripDetails.lead = null;
  //   alert("Team Lead Assigned Team Already");
  // }
};
const clearForm = () => {
  Inertia.get("/dashboard/product_delivery");
};
const processTrip = async () => {
  if (selectedAgents.value.length == 1) {
    alert("No Agent has been Addeed");
  } else if (selectedProducts.value.length == 1) {
    alert("No product has been Addeed");
  } else if (tripDetails.lead == null) {
    alert("No Team Lead assigned");
  } else {
    selectedProducts.value.pop();
    selectedAgents.value.pop();

    let missingQunatities = false;
    selectedProducts.value.forEach(function (item, index) {
      if (
        typeof item.productQuantity === "undefined" ||
        typeof item.productQuantity === ""
      ) {
        missingQunatities = true;
      }
    });
    let missingAgents = false;
    selectedAgents.value.forEach(function (item, index) {
      if (
        typeof item.selectedAgentName === null ||
        typeof item.selectedAgentName === "null"
      ) {
        missingAgents = true;
      }
    });

    if (missingQunatities === true || missingAgents == true) {
      alert("Set Product Quantitie(s) to proceed");
      addAgentRow();
      addTableRow();
    } else {
      let productsAgents = selectedProducts.value.concat(selectedAgents.value);
      let productsAgentsDetails = productsAgents.concat(tripDetails);

      await Inertia.post("/dashboard/process_delivery", productsAgentsDetails);

      setTimeout(() => {
        Inertia.get("/dashboard/product_delivery");
      }, 3000);
    }
  }
};
const setAgent = (agent) => {
  // console.log(agent)
  // return
  if (activeAgents.value.includes(agent.id)) {
    alert("Already a member of another trip");
  } else {
    agentsCollapseValue.value = false;
    chosenAgent.value = agent.name;

    for (var i = 0; i < selectedAgents.value.length; i++) {
      if (i > 0) {
        // console.log(i);
        // console.log(selectedAgents.value[i].selectedAgentID);
      }
    }

    if (!selectedAgents.value.includes(agent)) {
      selectedAgents.value[selectedAgentIndex.value].selectedAgentName = agent.name;
      selectedAgents.value[selectedAgentIndex.value].selectedAgentID = agent.id;
      selectedAgents.value[selectedAgentIndex.value].selectedType = "agent";
    }
    addAgentRow();
    agentsCollapseValue.value = false;
  }
};
const setProduct = (product) => {
  productsCollapseValue.value = false;
  purchasedProduct.value = product.product_name;

  for (var i = 0; i < selectedProducts.value.length; i++) {
    if (i > 0) {
      // console.log(i);
      // console.log(selectedProducts.value[i].selectedproductID);
    }
  }

  if (!selectedProducts.value.includes(product)) {
    selectedProducts.value[selectedProductIndex.value].productQuantity =
      selectedProducts.value.productQuantity;
    selectedProducts.value[selectedProductIndex.value].remainingProducts =
      product.finished_products;
    selectedProducts.value[selectedProductIndex.value].productSKU =
      product.product_quantity;
    selectedProducts.value[selectedProductIndex.value].selectedproductName =
      product.product_name;
    selectedProducts.value[selectedProductIndex.value].selectedproductID = product.id;
    selectedProducts.value[selectedProductIndex.value].selectedType = "product";
  }
  addTableRow();
  productsCollapseValue.value = false;

  // let lastProductIndex = productsNumber.value - 1
  // console.log(lastProductIndex)
};
const addAgentRow = () => {
  selectedAgents.value.push({
    selectedAgentName: null,
    selectedAgentID: null,
  });
};
const addTableRow = () => {
  selectedProducts.value.push({
    productname: "",
    productSKU: "",
    productQuantity: "",
    remainingProducts: null,
  });
};
const deleteTableRow = (index, selectedProduct) => {
  var idx = selectedProducts.value.indexOf(selectedProduct);
  var productID = selectedProduct.selectedproductID;
  if (productID != undefined) {
    selectedProducts.value.splice(idx, 1);
  }
};
const deleteAgentRow = (index, selectedAgent) => {
  var idx = selectedAgents.value.indexOf(selectedAgent);
  console.log(idx, index);
  if (idx > 0) {
    selectedAgents.value.splice(idx, 1);
  }
};
</script>

<template>
  <div>
    <nav
      class="relative w-full flex flex-wrap items-center justify-between py-3 bg-gray-200 text-gray-500 hover:text-gray-700 focus:text-gray-700 shadow-lg"
    >
      <div
        class="container-fluid w-full flex flex-wrap items-center justify-between px-6"
      >
        <div class="container-fluid">
          <div class="grid grid-cols-2 gap-6">
            <div>
              <h5 class="text-md text-black font-semibold" href="#">
                <i class="fa-solid fa-truck"></i> Trip Batch.
                <span class="text-md sm:text-2xl">{{ tripBatch }}</span>
              </h5>
            </div>

            <button @click="reloadData" class="mt-2">
              <i
                class="fas fa-refresh text-green-800 transfrom translate hover:scale-150 duration-700 hover:animate-spin"
              ></i>
            </button>
          </div>
        </div>
      </div>
    </nav>

    <div>
      <div class="modal-body relative sm:p-4">
        <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
          <div class="md:grid md:grid-cols-2 md:gap-6">
            <div class="md:col-span-1">
              <!-- <h3 class="text-lg font-medium leading-6 text-gray-900">Add Product</h3>
        <p class="mt-1 text-sm text-gray-500">Key in product details</p> -->
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
              <div class="grid grid-cols-6 gap-6">
                <div class="col-span-6 sm:col-span-1">
                  <label for="first-name" class="block text-sm font-medium text-gray-700">
                    <i class="fas fa-address-card text-light-green-800 fa-lg mr-1"></i>
                    Trip Team Lead</label
                  >
                  <select
                    v-model="tripDetails.lead"
                    @change="checkTeamLead"
                    id="location"
                    name="location"
                    class="uppercase mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-green-500 focus:outline-none focus:ring-green-500 text-xs sm:text-sm"
                  >
                    <option
                      v-for="(lead, index) in teamLead"
                      :key="index"
                      :value="lead.id"
                    >
                      {{ lead.name }}
                    </option>
                  </select>
                </div>

                <div class="col-span-3 sm:col-span-2">
                  <label for="last-name" class="block text-sm font-medium text-gray-700">
                    <i
                      class="fas fa-location-crosshairs text-light-green-800 fa-lg mr-1"
                    ></i>
                    Trip Location</label
                  >
                  <input
                    type="text"
                    name="last-name"
                    id="last-name"
                    v-model="tripDetails.location"
                    autocomplete="family-name"
                    class="uppercase mt-1 focus:ring-green-500 focus:border-light-green-900 block w-full shadow-sm text-xs sm:text-sm border-gray-300 rounded-md"
                  />
                </div>

                <div class="col-span-3 sm:col-span-1">
                  <label for="last-name" class="block text-sm font-medium text-gray-700">
                    <i class="fas fa-truck text-light-green-800 fa-lg mr-1"></i>
                    Truck # Plate</label
                  >
                  <input
                    type="text"
                    name="last-name"
                    id="last-name"
                    v-model="tripDetails.truckPlate"
                    autocomplete="family-name"
                    class="uppercase mt-1 focus:ring-green-500 focus:border-light-green-900 block w-full shadow-sm text-xs sm:text-sm border-gray-300 rounded-md"
                  />
                </div>

                <div class="col-span-3 sm:col-span-1">
                  <label for="last-name" class="block text-sm font-medium text-gray-700">
                    <i class="fas fa-user-secret text-light-green-800 fa-lg mr-1"></i>
                    Truck Driver</label
                  >
                  <input
                    type="text"
                    name="last-name"
                    id="last-name"
                    v-model="tripDetails.driver"
                    autocomplete="family-name"
                    class="uppercase mt-1 focus:ring-green-500 focus:border-light-green-900 block w-full shadow-sm text-xs sm:text-sm border-gray-300 rounded-md"
                  />
                </div>

                <!-- <div class="col-span-3 sm:col-span-1">
                  <button
                    type="button"
                    className="mt-6 inline-flex items-center rounded-md border border-transparent bg-green-600 px-3 py-2 text-sm font-medium leading-4 text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                  >
                    Refresh Trip Data
                  </button>
                </div> -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="mt-2 mb-20">
      <div class="sm:mt-20 px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
          <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900">Trip Sales Agents</h1>
            <div class="mt-4 whitespace-nowrap py-2 text-sm text-gray-900">
              <!-- v-LowerCase -->
              <input
                :value="selectedAgents[selectedAgentIndex].selectedAgentName"
                @input="
                  (e) =>
                    (selectedAgents[selectedAgentIndex].selectedAgentName =
                      e.target.value)
                "
                type="text"
                class="capitalize form-control block w-full sm:w-1/2 px-3 py-1.5 text-base font-medium text-gray-900 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-green-600 focus:outline-none"
                id="exampleFormControlInput1"
                placeholder="Search Agents"
              />
              <div
                v-if="agentsCollapseValue"
                class="mt-2 collapse mb-2 w-full sm:w-1/2 max-h-52 overflow-y-auto"
                id="collapseExample"
              >
                <div class="block p-3 rounded-lg shadow-lg bg-white">
                  <div
                    v-if="searchedAgentsArray"
                    @click="setAgent(agent)"
                    v-for="agent in searchedAgent"
                    class="font-bold text-black hover:cursor-pointer hover:bg-light-green-100 border-b-2 border-light-green-900 block px-4 py-2 rounded-lg shadow-lg hover:shadow-3xl bg-white max-w-sm"
                  >
                    <span class="italic">{{ agent.name }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="mt-8 flex flex-col">
          <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
              <div
                class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg"
              >
                <table class="min-w-full divide-y divide-gray-300">
                  <thead class="bg-gray-50">
                    <tr>
                      <th
                        scope="col"
                        class="whitespace-nowrap py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6"
                      >
                        #
                      </th>
                      <th
                        scope="col"
                        class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900"
                      >
                        Agent Name
                      </th>
                      <th
                        scope="col"
                        class="relative whitespace-nowrap py-3.5 pl-3 pr-4 sm:pr-6"
                      >
                        <span class="sr-only"></span>
                      </th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200 bg-white">
                    <tr v-for="(selectedAgent, index) in selectedAgents" :key="index">
                      <td
                        class="whitespace-nowrap py-2 pl-4 pr-3 text-sm text-gray-500 sm:pl-6"
                      >
                        {{ index + 1 }}
                      </td>

                      <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">
                        <input
                          disabled
                          v-model="selectedAgent.selectedAgentName"
                          type="text"
                          class="capitalize form-control block w-full sm:px-3 sm:py-1.5 text-xs sm:text-base font-normal text-gray-700 bg-gray-200 bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-green-600 focus:outline-none"
                          id="exampleFormControlInput1"
                          placeholder=""
                        />
                      </td>
                      <td
                        class="relative whitespace-nowrap py-2 pl-3 pr-4 text-right text-sm font-medium sm:pr-6"
                      >
                        <a
                          @click.prevent="deleteAgentRow(index, selectedAgent)"
                          class="text-red-600 hover:text-red-900"
                        >
                          <i class="fas fa-trash"></i>
                          <span class="sr-only">, Remove Button</span></a
                        >
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="mt-10 sm:mt-20 px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
          <div class="sm:flex-auto">
            <div class="flex justify-between">
              <h1 class="text-xl font-semibold text-gray-900">Trip Products</h1>
              <button
                @click="uploadProductModal=true"
                type="button"
                className="inline-flex items-center rounded-md border border-transparent bg-green-600 px-3 py-2 text-sm font-medium leading-4 text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
              >
                Upload Product
              </button>
              <!-- <button class="text-xl font-semibold text-gray-900">Trip Products</button> -->
            </div>
            <div class="mt-4 whitespace-nowrap py-2 text-sm text-gray-900">
              <!-- v-LowerCase -->
              <input
                :value="selectedProducts[selectedProductIndex].productname"
                @input="
                  (e) =>
                    (selectedProducts[
                      selectedProductIndex
                    ].productname = e.target.value.toUpperCase())
                "
                type="text"
                class="capitalize form-control block w-full sm:w-1/2 px-3 py-1.5 text-base font-medium text-gray-900 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-green-600 focus:outline-none"
                id="exampleFormControlInput1"
                placeholder="Search Products"
              />
              <div
                v-if="productsCollapseValue"
                class="mt-2 collapse mb-2 w-full sm:w-1/2 max-h-52 overflow-y-auto"
                id="collapseExample"
              >
                <div class="block p-3 rounded-lg shadow-lg bg-white">
                  <div
                    v-if="searchedProductsArray"
                    @click="setProduct(product)"
                    v-for="product in searchedProduct"
                    class="font-bold text-black hover:cursor-pointer hover:bg-light-green-100 border-b-2 border-light-green-900 block px-4 py-2 rounded-lg shadow-lg hover:shadow-3xl bg-white max-w-sm"
                    rfl
                  >
                    <span class="italic">{{ product.product_name }}</span> -
                    {{ product.product_quantity }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="mt-8 flex flex-col">
          <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
              <div
                class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg"
              >
                <table class="min-w-full divide-y divide-gray-300">
                  <thead class="bg-gray-50">
                    <tr>
                      <th
                        scope="col"
                        class="whitespace-nowrap py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6"
                      >
                        #
                      </th>
                      <th
                        scope="col"
                        class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900"
                      >
                        Product Name
                      </th>
                      <th
                        scope="col"
                        class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900"
                      >
                        Product Unit (SKU)
                      </th>
                      <!-- <th
                        scope="col"
                        class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900"
                      >
                        Product Description
                      </th> -->
                      <th
                        scope="col"
                        class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900"
                      >
                        Number of Items
                      </th>
                      <th
                        scope="col"
                        class="relative whitespace-nowrap py-3.5 pl-3 pr-4 sm:pr-6"
                      >
                        <span class="sr-only"></span>
                      </th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200 bg-white">
                    <tr v-for="(selectedProduct, index) in selectedProducts" :key="index">
                      <td
                        class="whitespace-nowrap py-2 pl-4 pr-3 text-sm text-gray-500 sm:pl-6"
                      >
                        {{ index + 1 }}
                      </td>

                      <td class="whitespace-nowrap py-2 text-sm text-gray-500">
                        <input
                          disabled
                          v-model="selectedProduct.selectedproductName"
                          type="text"
                          class="capitalize form-control block w-full sm:px-3 sm:py-1.5 text-xs sm:text-base font-normal text-gray-700 bg-gray-200 bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-green-600 focus:outline-none"
                          id="exampleFormControlInput1"
                          placeholder=""
                        />
                      </td>
                      <td class="whitespace-nowrap py-2 text-sm text-gray-500">
                        <input
                          disabled
                          v-model="selectedProduct.productSKU"
                          type="text"
                          class="uppercase form-control block w-full sm:px-3 sm:py-1.5 text-xs sm:text-base font-normal text-gray-700 bg-gray-200 bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-green-600 focus:outline-none"
                          id="exampleFormControlInput1"
                          placeholder=""
                        />
                      </td>
                      <td class="flex whitespace-nowrap py-2 text-sm text-gray-500">
                        <div class="mt-1 flex rounded-md shadow-sm">
                          <span
                            class="inline-flex items-center rounded-l-md border border-r-0 border-gray-300 bg-gray-200 px-1 text-gray-500 text-xs"
                            >{{ selectedProduct.remainingProducts }} Items</span
                          >
                          <input
                            v-model="selectedProduct.productQuantity"
                            type="number"
                            name="username"
                            id="username"
                            autocomplete="username"
                            class="text-black font-bold block w-full min-w-0 flex-1 rounded-none rounded-r-md border-gray-300 focus:border-green-800 focus:ring-green-800 text-xs"
                          />
                        </div>
                      </td>
                      <td
                        class="relative whitespace-nowrap py-2 pl-3 pr-4 text-right text-sm font-medium sm:pr-6"
                      >
                        <a
                          @click.prevent="deleteTableRow(index, selectedProduct)"
                          class="text-red-600 hover:text-red-900"
                        >
                          <i class="fas fa-trash"></i>
                          <span class="sr-only">, Remove Button</span></a
                        >
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

    <div>
      <div
        v-if="props.currentMessage != null"
        class="flex justify-center p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
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
        <div class="">
          <span class="font-medium">{{ props.currentMessage }}</span>
        </div>
      </div>
    </div>

    <nav
      class="relative w-full flex flex-wrap items-center justify-between py-3 bg-light-green-100 text-gray-500 hover:text-gray-700 focus:text-gray-700 shadow-lg"
    >
      <div
        class="mt-4 container-fluid w-full flex flex-wrap items-center justify-between px-6"
      >
        <!-- <div class="mb-2 container-fluid">
          <button
            @click="clearForm"
            type="button"
            class="inline-block px-2 py-2 sm:px-6 sm:py-2.5 bg-red-300 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-red-700 hover:shadow-lg focus:bg-green-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-green-800 active:shadow-lg transition duration-150 ease-in-out"
          >
            <i class="fas fa-broom mr-2"></i> Clear
          </button>
        </div> -->
        <div class="container-fluid">
          <button
            @click="processTrip"
            type="button"
            class="mb-2 inline-block px-2 py-2 sm:px-6 sm:py-2.5 bg-green-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-green-700 hover:shadow-lg focus:bg-green-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-green-800 active:shadow-lg transition duration-150 ease-in-out"
          >
            <i class="fas fa-check-double mr-2"></i> Register Trip
          </button>
        </div>
      </div>
    </nav>
  </div>
</template>
