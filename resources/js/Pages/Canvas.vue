<script setup>
import { useForm, usePage } from "@inertiajs/inertia-vue3";
import { ref, computed, watch, toRef } from "vue";
// import { Inertia } from "@inertiajs/inertia";
// import AppLayout from "@/Layouts/AppLayout.vue";
// import { Calendar, DatePicker } from "v-calendar";
import moment from "moment";

const props = defineProps({
  clients: Array,
  products: Array,
  currentMessage: String,
});

const clientsCollapse = computed(() => {
  if (purchasingClient.value != "") {
    return true;
  } else {
    return false;
  }
});
const productsCollapse = computed(() => {
  if (selectedProducts.value[0].productname != "") {
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
  return selectedProducts.value.length - 1
});

// console.log(selectedProducts.value.length)
// const searchedProductsArray = computed(() => {
//   if (searchedProduct.value.length != 0) {
//     return true;
//   } else {
//     return false;
//   }
// });
const searchedProductsArray = ref(null);
const clients = computed(() => props.clients);
const currentUser = computed(() => usePage().props.value.user.id);

const form = useForm({
  client_name: "",
  client_email: "michael@gmail.com",
  client_contact: "0716202297",
  client_kra: "A87686HDIU5Y",
  client_address: "Ngong Road 30287",
  added_by: currentUser,
});
const clientsCollapseValue = ref("");
const productsCollapseValue = ref("");
const searchedClient = ref([]);
const searchedProduct = ref([]);
const purchasingClient = ref("");
const purchasedProduct = ref("");
const selectedClient = ref({});
const selectedProducts = ref([
  {
    productname: "",
    selectedproductName:"",
    productSKU: "",
    productDescription: "",
    productQuantity: "",
    productPrice: "",
    total: "",
    vat: "",
  },
]);
const tableCounter = ref(1);
const currentrows = ref([]);
// const currentrows = ref([1]);
const addClientCollapse = ref(false);
// const clientsCollapse = ref(true);
const date = ref(moment().format("MMMM Do YYYY, h:mm:ss a"));

watch(purchasingClient, (value) => {
  if (value == "") {
    searchedClient.value = [];
    selectedClient.value = {};
  }
  clientsCollapseValue.value = clientsCollapse;
});

watch(purchasingClient, (value) => {
  if (value) {
    // selectedProducts.value.length - 1
    // alert('twende')
    let allClients = clients.value;
    // console.log(clients.value)
    for (var i = 0; i < allClients.length; i++) {
      // console.log(allClients[i].client_name)
      if (allClients[i].client_name.indexOf(purchasingClient.value) != -1) {
        if (!searchedClient.value.includes(allClients[i])) {
          //  arr.push(str);
          searchedClient.value.push(allClients[i]);
        }
        // console.log(allClients[i].client_name)
        // alert(allClients.clients[i]);
      }
    }
  }
});
watch(
  purchasedProduct,
  (value) => {
    // console.log(value.length);
    if (value.length != 0) {
      // alert('twende')
      let allProducts = props.products;
      // console.log(allProducts)
      searchedProduct.value = [];
      for (var i = 0; i < allProducts.length; i++) {
        // console.log(allClients[i].client_name)
        // console.log(purchasedProduct.value[i].productname)
        // if (allProducts[i].product_name.startsWith(selectedProducts.value[0].productname) != -1) {
        if (selectedProducts.value != []) {
          if (
            allProducts[i].product_name.indexOf(selectedProducts.value[selectedProductIndex.value].productname) !=
            -1
          ) {
            // if (allProducts[i].product_name.indexOf(purchasedProduct.value.productname) != -1) {
            // alert('hi 1')

            searchedProduct.value.push(allProducts[i]);

            // if (!searchedProduct.value.includes(allProducts[i])) {
            //   //  arr.push(str);
            //   alert('hi 2')
            //   searchedProduct.value.push(allProducts[i]);
            // }
            // console.log(allClients[i].client_name)
            // alert(allClients.clients[i]);
          }
        }
      }
    }
  },
  { deep: true }
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
  { deep: true }
);
watch(
  selectedProducts,
  (value) => {
    purchasedProduct.value = value;
    searchedProductsArray.value = true;
  },
  { deep: true }
);

const addClient = () => {
  // Inertia.post('/add_product', form)
  form.post("/dashboard/register_client", {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      form.reset();
      // addModal = false
      alert("Client Added");
    },
  });
};
const setClient = (client) => {
  // alert('yess')
  // console.log(client)
  clientsCollapseValue.value = false;
  purchasingClient.value = client.client_name;
  selectedClient.value = client;
};
const setProduct = (product) => {
  // alert('yess')
  console.log(product)
  productsCollapseValue.value = false;
  purchasedProduct.value = product.product_name;
  if (!selectedProducts.value.includes(product)) {
    // searchedClient.value.push(allClients[i]);
    selectedProducts.value[selectedProductIndex.value].productDescription = product.product_description
    selectedProducts.value[selectedProductIndex.value].productPrice = product.sales_price;
    selectedProducts.value[selectedProductIndex.value].productQuantity = 1
    selectedProducts.value[selectedProductIndex.value].productSKU = product.product_quantity
    selectedProducts.value[selectedProductIndex.value].selectedproductName = product.product_name
    // selectedProducts.value[selectedProductIndex.value].total = 
    // selectedProducts.value[selectedProductIndex.value].vat = 
    // selectedProducts.value[selectedProductIndex].productDescription = product.product_description
    // selectedProducts.value.push(product);
  }
  addTableRow();
  productsCollapseValue.value = false;
};
const addTableRow = () => {
  selectedProducts.value.push({
    productname: "",
    productSKU: "",
    productDescription: "",
    productQuantity: "",
    productPrice: "",
    total: "",
    vat: "",
  });
};
const deleteTableRow = (index, selectedProduct) => {
  var idx = selectedProducts.value.indexOf(selectedProduct);
  console.log(idx, index);
  if (idx > -1) {
    selectedProducts.value.splice(idx, 1);
  }
  calculateTotal();
};
const calculateTotal = () => {};
</script>

<template>
  <div>
    <div
      v-if="props.currentMessage != null"
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
        <span class="font-medium">{{ props.currentMessage }}</span>
      </div>
    </div>
    <nav
      class="relative w-full flex flex-wrap items-center justify-between py-3 bg-gray-200 text-gray-500 hover:text-gray-700 focus:text-gray-700 shadow-lg"
    >
      <div
        class="container-fluid w-full flex flex-wrap items-center justify-between px-6"
      >
        <div class="container-fluid">
          <h5 class="text-xl text-black font-semibold" href="#">
            <i class="fa-solid fa-file-invoice-dollar"></i> Invoice no.S4567
          </h5>
        </div>
        <div class="container-fluid">
          <h5 class="text-xl text-black font-semibold" href="#">
            <i class="fa-solid fa-coins"></i> Balance Due KES.200
          </h5>
        </div>
      </div>
    </nav>

    <div class="mt-10 mb-20">
      <div class="grid grid-cols-1 sm:grid-cols-4 sm:gap-6 justify-around">
        <div class="mb-3 xl:w-96">
          <div class="flex flex-row justify-between">
            <label
              for="exampleFormControlInput1"
              class="form-label inline-block mb-2 text-gray-700"
              >Customer Name</label
            >
            <button
              v-if="!addClientCollapse"
              @click="addClientCollapse = !addClientCollapse"
              for="exampleFormControlInput1"
              class="bg-green-600 hover:bg-green-900 form-label rounded-md text-xs px-2 py-1 inline-block mb-2 text-white"
            >
              <i class="fas fa-plus"></i> Add Client
              <!-- <i v-if="addClientCollapse" class="fas fa-xmark"></i> Close Dropdown -->
            </button>
            <button
              v-if="addClientCollapse"
              @click="addClientCollapse = !addClientCollapse"
              for="exampleFormControlInput1"
              class="bg-red-600 hover:bg-red-900 form-label rounded-md text-xs px-2 py-1 inline-block mb-2 text-white"
            >
              <i class="fas fa-xmark"></i> Close Dropdown
              <!-- <i v-if="addClientCollapse" class="fas fa-xmark"></i> Close Dropdown -->
            </button>
          </div>
          <div v-if="addClientCollapse" class="collapse mb-2" id="collapseExample">
            <div class="block rounded-lg shadow-lg bg-white">
              <div class="block p-4 rounded-lg shadow-lg bg-white max-w-sm">
                <form>
                  <div class="form-group mb-2">
                    <label
                      for="exampleInputEmail2"
                      class="font-medium form-label inline-block mb-2 text-gray-800 text-sm"
                      >Name</label
                    >
                    <input
                      v-model="form.client_name"
                      type="text"
                      class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-green-600 focus:outline-none"
                      id="exampleInputEmail2"
                      aria-describedby="emailHelp"
                      placeholder=""
                    />
                  </div>
                  <div class="form-group mb-2">
                    <label
                      for="exampleInputEmail2"
                      class="font-medium form-label inline-block mb-2 text-gray-800 text-sm"
                      >Email</label
                    >
                    <input
                      v-model="form.client_email"
                      type="email"
                      class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-green-600 focus:outline-none"
                      id="exampleInputEmail2"
                      aria-describedby="emailHelp"
                      placeholder=""
                    />
                  </div>
                  <div class="form-group mb-2">
                    <label
                      for="exampleInputPassword2"
                      class="font-medium form-label inline-block mb-2 text-gray-800 text-sm"
                      >Phone</label
                    >
                    <input
                      v-model="form.client_contact"
                      type="number"
                      class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-green-600 focus:outline-none"
                      id="exampleInputPassword2"
                      placeholder=""
                    />
                  </div>
                  <div class="form-group mb-2">
                    <label
                      for="exampleInputPassword2"
                      class="font-medium form-label inline-block mb-2 text-gray-800 text-sm"
                      >PIN</label
                    >
                    <input
                      v-model="form.client_kra"
                      type="text"
                      class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-green-600 focus:outline-none"
                      id="exampleInputPassword2"
                      placeholder=""
                    />
                  </div>

                  <div class="form-group mb-2">
                    <label
                      for="exampleInputPassword2"
                      class="font-medium form-label inline-block mb-2 text-gray-800 text-sm"
                      >Address</label
                    >
                    <textarea
                      v-model="form.client_address"
                      class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-green-600 focus:outline-none"
                      id="exampleFormControlTextarea1"
                      rows="3"
                      placeholder="Your message"
                    ></textarea>

                    <!-- <input
                      v-model="form.client_kra"
                      type="number"
                      class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-green-600 focus:outline-none"
                      id="exampleInputPassword2"
                      placeholder=""
                    /> -->
                  </div>

                  <button
                    @click="addClient"
                    type="submit"
                    class="w-full px-6 py-2.5 bg-green-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-green-700 hover:shadow-lg focus:bg-green-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-green-800 active:shadow-lg transition duration-150 ease-in-out"
                  >
                    Save
                  </button>
                </form>
              </div>
            </div>
          </div>
          <div class="mb-3 xl:w-96">
            <!-- <select
              class="form-select appearance-none block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding bg-no-repeat border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-green-600 focus:outline-none"
              aria-label="Default select example"
            >
              <option selected></option>
              <option v-for="client in props.clients" :value="client">
                {{ client.client_name }}
              </option>
            </select> -->

            <input
              v-model="purchasingClient"
              type="text"
              class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-green-600 focus:outline-none"
              id="exampleFormControlInput1"
              placeholder=""
            />

            <div
              v-if="clientsCollapseValue"
              class="mt-2 collapse mb-2"
              id="collapseExample"
            >
              <div class="block p-3 rounded-lg shadow-lg bg-white">
                <div
                  v-if="searchedClientsArray"
                  @click="setClient(client)"
                  v-for="client in searchedClient"
                  class="hover:cursor-pointer hover:bg-light-green-100 border-b-2 border-light-green-900 block px-4 py-2 rounded-lg shadow-lg hover:shadow-3xl bg-white max-w-sm"
                >
                  {{ client.client_name }}
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="mb-3 xl:w-96">
          <label
            for="exampleFormControlInput1"
            class="form-label inline-block mb-2 text-gray-700"
            >Customer Phone</label
          >
          <input
            disabled
            v-model="selectedClient.client_contact"
            type="text"
            class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-green-600 focus:outline-none"
            id="exampleFormControlInput1"
            placeholder=""
          />
        </div>
        <div class="mb-3 xl:w-96">
          <label
            for="exampleFormControlInput1"
            class="form-label inline-block mb-2 text-gray-700"
            >Customer Email</label
          >
          <input
            disabled
            v-model="selectedClient.client_email"
            type="text"
            class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-green-600 focus:outline-none"
            id="exampleFormControlInput1"
            placeholder=""
          />
        </div>
        <div class="mb-3 xl:w-96">
          <label
            for="exampleFormControlInput1"
            class="form-label inline-block mb-2 text-gray-700"
            >Customer Pin</label
          >
          <input
            disabled
            v-model="selectedClient.client_kra"
            type="text"
            class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-green-600 focus:outline-none"
            id="exampleFormControlInput1"
            placeholder=""
          />
        </div>
      </div>

      <div class="mb-3 xl:w-96">
        <label
          for="exampleFormControlTextarea1"
          class="form-label inline-block mb-2 text-gray-700"
          >Customer Address</label
        >
        <textarea
          v-model="selectedClient.client_address"
          class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-green-600 focus:outline-none"
          id="exampleFormControlTextarea1"
          rows="3"
          placeholder="Your message"
        ></textarea>
      </div>

      <div class="mt-10 grid grid-cols-1 sm:grid-cols-4 sm:gap-6 justify-around">
        <div class="mb-3 xl:w-96">
          <label
            for="exampleFormControlInput1"
            class="form-label inline-block mb-2 text-gray-700"
            >Invoice Number</label
          >
          <input
            type="text"
            class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-green-600 focus:outline-none"
            id="exampleFormControlInput1"
            placeholder=""
          />
        </div>
        <div class="mb-3 xl:w-96">
          <label
            for="exampleFormControlInput1"
            class="form-label inline-block mb-2 text-gray-700"
            >Terms</label
          >
          <div class="mb-3 xl:w-96">
            <select
              disabled
              class="form-select appearance-none block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding bg-no-repeat border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-green-600 focus:outline-none"
              aria-label="Default select example"
            >
              <!-- <option selected></option> -->
              <option selected value="1">Due on Receipt</option>
            </select>
          </div>
        </div>

        <!-- <v-date-picker v-model="date" /> -->

        <!-- <div class="mb-3 xl:w-96">
          <label
            for="exampleFormControlInput1"
            class="form-label inline-block mb-2 text-gray-700"
            >Invoice Date</label
          >
          <input
            type="text"
            class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-green-600 focus:outline-none"
            id="exampleFormControlInput1"
            placeholder=""
            v-model="date"
          />
          <DatePicker disable v-model="date" />
        </div> -->
        <div class="mb-3 xl:w-96">
          <label
            for="exampleFormControlInput1"
            class="form-label inline-block mb-2 text-gray-700"
            >Invoice Date</label
          >
          <input
            type="text"
            class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-green-600 focus:outline-none"
            id="exampleFormControlInput1"
            placeholder=""
            v-model="date"
          />

          <!-- <DatePicker v-model="date" /> -->
        </div>
      </div>

      <!-- This example requires Tailwind CSS v2.0+ -->
      <div class="mt-10 sm:mt-20 px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
          <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900">Sale Items</h1>
            <!-- <p class="mt-2 text-sm text-gray-700">
              A table of placeholder stock market data that does not make any sense.
            </p> -->
            
            <div class="mt-4 whitespace-nowrap py-2 text-sm text-gray-900">
              <input
              v-model="selectedProducts[selectedProductIndex].productname"
              type="text"
                class="form-control block w-1/2 px-3 py-1.5 text-base font-bold text-gray-900 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-green-600 focus:outline-none"
                id="exampleFormControlInput1"
                placeholder="Search Products"
                />
              <div
                v-if="productsCollapseValue"
                class="mt-2 collapse mb-2 w-1/2 max-h-52 overflow-y-auto"
                id="collapseExample"
              >
                <div class="block p-3 rounded-lg shadow-lg bg-white">
                  <div
                    v-if="searchedProductsArray"
                    @click="setProduct(product)"
                    v-for="product in searchedProduct"
                    class="text-black hover:cursor-pointer hover:bg-light-green-100 border-b-2 border-light-green-900 block px-4 py-2 rounded-lg shadow-lg hover:shadow-3xl bg-white max-w-sm"
                  >
                    {{ product.product_name }}
                  </div>
                </div>
              </div>
            </div>

          </div>
          <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <div class="mb-3 xl:w-96">
              <label
                for="exampleFormControlInput1"
                class="form-label inline-block mb-2 text-gray-700"
                >Amounts are</label
              >
              <div class="mb-3 xl:w-96">
                <select
                  class="form-select appearance-none block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding bg-no-repeat border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-green-600 focus:outline-none"
                  aria-label="Default select example"
                >
                  <option value="2">Inclusive of VAT</option>
                  <option value="1">Exclusive of VAT</option>
                  <option value="3">Out of scope of task</option>
                </select>
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
                      <!-- <th
                        scope="col"
                        class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900"
                      >
                        Service Date
                      </th> -->
                      <th
                        scope="col"
                        class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900"
                      >
                        Product/Service
                      </th>
                      <th
                        scope="col"
                        class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900"
                      >
                        SKU
                      </th>
                      <th
                        scope="col"
                        class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900"
                      >
                        Description
                      </th>
                      <th
                        scope="col"
                        class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900"
                      >
                        QTY
                      </th>
                      <th
                        scope="col"
                        class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900"
                      >
                        Rate
                      </th>
                      <th
                        scope="col"
                        class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900"
                      >
                        Amount
                      </th>
                      <th
                        scope="col"
                        class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900"
                      >
                        VAT
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

                      <!-- <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-900">
                        <input
                          v-model="selectedProduct.productname"
                          type="text"
                          class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-green-600 focus:outline-none"
                          id="exampleFormControlInput1"
                          placeholder=""
                        />
                        <div
                          v-if="productsCollapseValue"
                          class="mt-2 collapse mb-2"
                          id="collapseExample"
                        >
                          <div class="block p-3 rounded-lg shadow-lg bg-white">
                            <div
                              v-if="searchedProductsArray"
                              @click="setProduct(product)"
                              v-for="product in searchedProduct"
                              class="text-black hover:cursor-pointer hover:bg-light-green-100 border-b-2 border-light-green-900 block px-4 py-2 rounded-lg shadow-lg hover:shadow-3xl bg-white max-w-sm"
                            >
                              {{ product.product_name }}
                            </div>
                          </div>
                        </div>
                      </td> -->

                      <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">
                        <input
                          v-model="selectedProduct.selectedproductName"
                          type="text"
                          class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-green-600 focus:outline-none"
                          id="exampleFormControlInput1"
                          placeholder=""
                        />
                      </td>
                      <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">
                        <input
                          v-model="selectedProduct.productSKU"
                          type="text"
                          class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-green-600 focus:outline-none"
                          id="exampleFormControlInput1"
                          placeholder=""
                        />
                      </td>
                      <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">
                        <input
                          v-model="selectedProduct.productDescription"
                          type="text"
                          class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-green-600 focus:outline-none"
                          id="exampleFormControlInput1"
                          placeholder=""
                        />
                      </td>
                      <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">
                        <input
                          v-model="selectedProduct.productQuantity"
                          type="text"
                          class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-green-600 focus:outline-none"
                          id="exampleFormControlInput1"
                          placeholder=""
                        />
                      </td>
                      <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">
                        <input
                          v-model="selectedProduct.productPrice"
                          type="text"
                          class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-green-600 focus:outline-none"
                          id="exampleFormControlInput1"
                          placeholder=""
                        />
                      </td>
                      <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">
                        <input
                          v-model="selectedProduct.total"
                          type="text"
                          class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-green-600 focus:outline-none"
                          id="exampleFormControlInput1"
                          placeholder=""
                        />
                      </td>
                      <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">
                        <input
                          v-model="selectedProduct.vat"
                          type="text"
                          class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-green-600 focus:outline-none"
                          id="exampleFormControlInput1"
                          placeholder=""
                        />
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
                  <!-- <tbody  class="divide-y divide-gray-200 bg-white">
                    <tr  v-for="(rowNumber, index) in currentrows">
                      <td
                        class="whitespace-nowrap py-2 pl-4 pr-3 text-sm text-gray-500 sm:pl-6"
                      >
                        {{ index + 1 }}
                      </td>
                      
                      <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-900">
                        <input
                          v-model="purchasedProduct"
                          type="text"
                          class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-green-600 focus:outline-none"
                          id="exampleFormControlInput1"
                          placeholder=""
                        />
                        <div
                          v-if="productsCollapseValue"
                          class="mt-2 collapse mb-2"
                          id="collapseExample"
                        >
                          <div class="block p-3 rounded-lg shadow-lg bg-white">
                            <div
                              v-if="searchedProductsArray"
                              @click="setProduct(product)"
                              v-for="product in searchedProduct"
                              class="text-black hover:cursor-pointer hover:bg-light-green-100 border-b-2 border-light-green-900 block px-4 py-2 rounded-lg shadow-lg hover:shadow-3xl bg-white max-w-sm"
                            >
                              {{ product.product_name }}
                            </div>
                          </div>
                        </div>
                       
                      </td>
                      
                      <td v-for="product in selectedProducts" class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">
                        {{ product.product_quantity }} Liters
                      </td>
                      <td v-for="product in selectedProducts" class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">
                        {{ product.product_description }}
                      </td>
                      <td  class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">1</td>
                      <td v-for="product in selectedProducts" class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">
                        KES. {{ product.sales_price }}
                      </td>
                      <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">
                        KES. 200
                      </td>
                      <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">
                        KES. 60
                      </td>
                      <td
                        class="relative whitespace-nowrap py-2 pl-3 pr-4 text-right text-sm font-medium sm:pr-6"
                      >
                        <a
                          @click.prevent="addTableRow('minus')"
                          href="#"
                          class="text-red-600 hover:text-red-900"
                        >
                          <i class="fas fa-trash"></i>
                          <span class="sr-only">, AAPS0L</span></a
                        >
                      </td>
                    </tr>
                  </tbody> -->
                </table>
                <div class="mt-10 m-10 flex flex-row justify-between">
                  <div>
                    <!-- <button
                      @click="addTableRow()"
                      type="button"
                      class="inline-block px-2 py-2 bg-green-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-green-700 hover:shadow-lg focus:bg-green-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-green-800 active:shadow-lg transition duration-150 ease-in-out"
                    >
                      <i class="fas fa-plus mr-1"></i> Add Lines
                    </button> -->
                  </div>
                  <div class="flex flex-col font-extrabold">
                    <div>
                      <h3>
                        Subtotal: <span class="text-gray-800 text-xl">KES 100</span>
                      </h3>
                      <h3>VAT: <span class="text-gray-800 text-xl">KES 100</span></h3>
                    </div>

                    <div>
                      <h3 class="mt-5">
                        Total: <span class="text-black text-2xl">KES 100</span>
                      </h3>
                      <h3>
                        Balance Due: <span class="text-black text-2xl">KES 100</span>
                      </h3>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <nav
      class="relative w-full flex flex-wrap items-center justify-between py-3 bg-light-green-100 text-gray-500 hover:text-gray-700 focus:text-gray-700 shadow-lg"
    >
      <div
        class="mt-4 container-fluid w-full flex flex-wrap items-center justify-between px-6"
      >
        <div class="mb-2 container-fluid">
          <button
            type="button"
            class="inline-block px-2 py-2 sm:px-6 sm:py-2.5 bg-red-300 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-red-700 hover:shadow-lg focus:bg-green-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-green-800 active:shadow-lg transition duration-150 ease-in-out"
          >
            <i class="fas fa-broom mr-2"></i> Clear
          </button>
        </div>
        <div class="container-fluid">
          <button
            type="button"
            class="mb-2 mr-2 inline-block px-2 py-2 sm:px-6 sm:py-2.5 bg-green-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-green-700 hover:shadow-lg focus:bg-green-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-green-800 active:shadow-lg transition duration-150 ease-in-out"
          >
            <i class="fas fa-print mr-2"></i> Print Invoice
          </button>
          <button
            type="button"
            class="mb-2 inline-block px-2 py-2 sm:px-6 sm:py-2.5 bg-green-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-green-700 hover:shadow-lg focus:bg-green-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-green-800 active:shadow-lg transition duration-150 ease-in-out"
          >
            <i class="fas fa-check-double mr-2"></i> Register Sale
          </button>
        </div>
      </div>
    </nav>
  </div>
</template>
