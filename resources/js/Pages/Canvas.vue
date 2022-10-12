<script setup>
import { useForm, usePage, Link } from "@inertiajs/inertia-vue3";
import { ref, toRefs, computed, watch, toRef, reactive } from "vue";
import { Inertia } from "@inertiajs/inertia";
// import AppLayout from "@/Layouts/AppLayout.vue";
// import { Calendar, DatePicker } from "v-calendar";
import moment from "moment";
import Receipt from "./Receipt.vue";

const props = defineProps({
  clients: Array,
  products: Array,
  currentMessage: String,
  invoiceLog: String,
});

// const invoiceLog = toRefs(props)

const vLowerCase = {
  updated: (el) => {
    el.value = el.value.toLowerCase();
  },
};

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
  return selectedProducts.value.length - 1;
});

const printTrigger = ref(false);
const searchedProductsArray = ref(null);
const clients = computed(() => props.clients);
const currentUser = computed(() => usePage().props.value.user.id);
const currentBatch = computed(() => usePage().props.value.user.trip_batch);

const form = useForm({
  client_name: "",
  client_email: "michael@gmail.com",
  client_contact: "0716202297",
  client_kra: "A87686HDIU5Y",
  client_address: "Ngong Road 30287",
  added_by: currentUser,
  tax_exempt: false,
});

const saleSuccess = ref(false);
const mpesaDialogue = ref(false);
const paymentModal = ref(false);
const clientsCollapseValue = ref("");
const productsCollapseValue = ref("");
const searchedClient = ref([]);
const searchedProduct = ref([]);
const purchasingClient = ref("");
const purchasedProduct = ref("");
const selectedClient = ref({});
const anonymousSale = ref(false);
const mpesaPayment = ref(false);

const overallSubtotal = ref(null);
const overallTax = ref(null);
const overallTotal = ref(null);

const selectedProducts = ref([
  {
    selectedproductID: null,
    productname: "",
    selectedproductName: "",
    productSKU: "",
    productDescription: "",
    productQuantity: "",
    remainingProducts: null,
    productPrice: null,
    wholesalePrice: null,
    wholesaleProductPrice: null,
    fixedPrice: null,
    total: null,
    vat: null,
    salePrice: null,
    productCode: null,
    barCode: null,
    finishedProduct: null,
    inDelivery: null,
    spoiledProduct: null,
    wholesaleCheck: false,
    agentID: currentUser.value,
    anonymousSale: anonymousSale.value,
    client: selectedClient,
    mpesaPayment: mpesaPayment,
    overallTotal: overallTotal,
    invoice_number: props.invoiceLog,
  },
]);

const addClientCollapse = ref(false);
const date = ref(moment().format("MMMM Do YYYY, h:mm:ss a"));

// watch(props.currentMessage, (value) => {
//   if (value == "Sale Registered Successfully") {
//     alert('Molio')
//     paymentModal.value = false;
//     saleSuccess.value = true;

//   }
// });

watch(purchasingClient, (value) => {
  if (value == "") {
    searchedClient.value = [];
    selectedClient.value = {};
  }
  clientsCollapseValue.value = clientsCollapse;
});

watch(purchasingClient, (value) => {
  if (value) {
    let allClients = clients.value;
    for (var i = 0; i < allClients.length; i++) {
      if (allClients[i].client_name.indexOf(purchasingClient.value) != -1) {
        if (!searchedClient.value.includes(allClients[i])) {
          searchedClient.value.push(allClients[i]);
        }
      }
    }
  }
});
watch(
  purchasedProduct,
  (value) => {
    if (value.length != 0) {
      let allProducts = props.products;
      searchedProduct.value = [];
      for (var i = 0; i < allProducts.length; i++) {
        // console.log(allProducts[i].trip_batch)
        if (selectedProducts.value != []) {
          if (
            allProducts[i].product_name.indexOf(
              selectedProducts.value[selectedProductIndex.value].productname
            ) != -1 &&
            allProducts[i].trip_batch != null
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
  purchasedProduct,
  (value) => {
    if (value.length != 0 && value[0].productname == "") {
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
  selectedProducts,
  (value) => {
    purchasedProduct.value = value;
    searchedProductsArray.value = true;
  },
  {
    deep: true,
    immediate: true,
  }
);

const cashSale = async () =>
{
  
  selectedProducts.value.pop()
  await Inertia.post("/dashboard/finish_sale", selectedProducts.value);
  paymentModal.value = false;
  saleSuccess.value = true;
  
};

const stkPush = () => {
  // console.log(payload)
  // Inertia.post("/dashboard/finish_sale", payload);
};

const checkwholeSaleChanges = (index) => {
  setCalculations(index);
  addEverything();
  selectedProducts.value[index].wholesaleProductPrice =
    selectedProducts.value[index].wholesalePrice;
};

const wholeSale = (index) => {
  if (selectedProducts.value[index].wholesaleCheck == false) {
    return false;
  } else {
    return true;
  }
};
const numberWithCommas = (x) => {
  let number = x;
  let nf = new Intl.NumberFormat("en-US");
  return nf.format(number); // "1,234,567,890"
};

const printInvoice = () => {
  printTrigger.value = !printTrigger.value;
};
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
  productsCollapseValue.value = false;
  purchasedProduct.value = product.product_name;

  let batchDetails = JSON.parse(product.trip_batch);
  let tripObject = batchDetails.find((obj) => obj.batchNumber == currentBatch.value);
  // console.log(tripObject);
  if (tripObject == undefined) {
    alert("Item is not in your Trip");
  }
  let itemsLoaded = tripObject.numberItems;
  let itemsSold = tripObject.itemsSold;
  let itemsAvailable = itemsLoaded - itemsSold;

  // var checkDuplicate
  for (var i = 0; i < selectedProducts.value.length; i++) {
    if (i > 0) {
      console.log(i);
      console.log(selectedProducts.value[i].selectedproductID);
    }
  }

  if (!selectedProducts.value.includes(product)) {
    // searchedClient.value.push(allClients[i]);
    selectedProducts.value[selectedProductIndex.value].productDescription =
      product.product_description;
    selectedProducts.value[selectedProductIndex.value].productPrice = product.sales_price;
    selectedProducts.value[selectedProductIndex.value].fixedPrice = product.sales_price;
    selectedProducts.value[selectedProductIndex.value].wholesalePrice =
      product.wholesale_price;
    selectedProducts.value[selectedProductIndex.value].wholesaleProductPrice =
      product.wholesale_price;
    selectedProducts.value[selectedProductIndex.value].productQuantity = 1;
    selectedProducts.value[selectedProductIndex.value].remainingProducts = itemsAvailable;
    // selectedProducts.value[selectedProductIndex.value].remainingProducts =
    //   product.stock_quantity;
    selectedProducts.value[selectedProductIndex.value].productSKU =
      product.product_quantity;
    selectedProducts.value[selectedProductIndex.value].selectedproductName =
      product.product_name;
    selectedProducts.value[selectedProductIndex.value].selectedproductID = product.id;
    selectedProducts.value[selectedProductIndex.value].inDelivery = product.in_delivery;
    selectedProducts.value[selectedProductIndex.value].total =
      selectedProducts.value[selectedProductIndex.value].productQuantity *
      selectedProducts.value[selectedProductIndex.value].productPrice;
    selectedProducts.value[selectedProductIndex.value].vat = Math.round(
      selectedProducts.value[selectedProductIndex.value].total * 0.16
    );
    selectedProducts.value[selectedProductIndex.value].salePrice =
      selectedProducts.value[selectedProductIndex.value].total +
      selectedProducts.value[selectedProductIndex.value].vat;

    addEverything();
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
    remainingProducts: null,
    productPrice: null,
    fixedPrice: null,
    total: null,
    vat: null,
    salePrice: null,
  });
};
const deleteTableRow = (index, selectedProduct) => {
  var idx = selectedProducts.value.indexOf(selectedProduct);
  console.log(idx, index);
  if (idx > -1) {
    selectedProducts.value.splice(idx, 1);
  }
  // calculateTotal();
  addEverything();
};
const checkSaleChanges = (index) => {
  const availableStock = selectedProducts.value[index].remainingProducts;
  const leastPrice = selectedProducts.value[index].fixedPrice;
  const wholesalePricing = selectedProducts.value[index].wholesalePrice;

  if (selectedProducts.value[index].productQuantity > availableStock) {
    alert("Check Available Stock");
    selectedProducts.value[index].productQuantity = availableStock;
    setCalculations(index);
    addEverything();
  } else {
    setCalculations(index);
    addEverything();
  }

  if (selectedProducts.value[index].wholesaleCheck == false) {
    if (selectedProducts.value[index].productPrice < leastPrice) {
      alert("Check Product Price");
      selectedProducts.value[index].productPrice = leastPrice;
      setCalculations(index);
      addEverything();
    } else {
      setCalculations(index);
      addEverything();
    }
  } else {
    if (selectedProducts.value[index].wholesaleProductPrice < wholesalePricing) {
      alert("Check Product Price");
      selectedProducts.value[index].wholesaleProductPrice = wholesalePricing;
      setCalculations(index);
      addEverything();
    } else {
      setCalculations(index);
      addEverything();
    }
  }
};
const setCalculations = (index) => {
  // alert(index)
  // console.log(selectedProducts.value[index].total);
  if (selectedProducts.value[index].wholesaleCheck == false) {
    selectedProducts.value[index].total =
      selectedProducts.value[index].productQuantity *
      selectedProducts.value[index].productPrice;
  } else {
    selectedProducts.value[index].total =
      selectedProducts.value[index].productQuantity *
      selectedProducts.value[index].wholesaleProductPrice;
  }

  selectedProducts.value[index].vat = Math.round(
    selectedProducts.value[index].total * 0.16
  );
  selectedProducts.value[index].salePrice =
    selectedProducts.value[index].total + selectedProducts.value[index].vat;

  addEverything();
};

const addEverything = () => {
  var sumSubtotal = selectedProducts.value.reduce((accumulator, object) => {
    return accumulator + object.salePrice;
  }, 0);
  var sumTax = selectedProducts.value.reduce((accumulator, object) => {
    return accumulator + object.vat;
  }, 0);

  overallSubtotal.value = sumSubtotal - sumTax;
  overallTax.value = sumTax;
  overallTotal.value = sumSubtotal;
};
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
          <h5 class="text-md text-black font-semibold" href="#">
            <i class="fa-solid fa-file-invoice-dollar"></i> Invoice no.
            <span class="text-md sm:text-2xl">{{ invoiceLog }}</span>
          </h5>
        </div>
        <div class="container-fluid">
          <h5 class="text-md text-black font-semibold" href="#">
            <i class="fa-solid fa-truck"></i> Trip Batch Number:
            <span class="text-md sm:text-2xl">{{ currentBatch }}</span>
            <!-- <span class="text-md sm:text-2xl">{{ numberWithCommas(overallTotal) }}</span> -->
          </h5>
        </div>
      </div>
    </nav>

    <div class="mt-10 mb-20">
      <button
        v-if="!anonymousSale"
        @click="anonymousSale = true"
        for="exampleFormControlInput1"
        class="bg-orange-700 hover:bg-orange-900 form-label font-bold rounded-md text-xs px-2 py-2 inline-block mb-2 text-white"
      >
        Anonymous Sale
      </button>
      <button
        v-if="anonymousSale"
        @click="anonymousSale = false"
        for="exampleFormControlInput1"
        class="bg-green-700 hover:bg-green-900 form-label font-bold rounded-md text-xs px-2 py-2 inline-block mb-2 text-white"
      >
        Add Client Details
      </button>
      <div
        v-if="!anonymousSale"
        class="grid grid-cols-1 sm:grid-cols-4 sm:gap-6 justify-around"
      >
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
            <div class="block rounded-lg shadow-lg bg-gray-50">
              <div class="block p-4 rounded-lg shadow-lg bg-gray-50 max-w-sm">
                <form>
                  <div class="form-group mb-2">
                    <label
                      for="exampleInputEmail2"
                      class="font-medium form-label inline-block mb-2 text-gray-800 text-sm"
                      >Name</label
                    >
                    <input
                      @input="(e) => (form.client_name = e.target.value)"
                      :value="form.client_name"
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
                      placeholder=""
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
              :value="purchasingClient"
              @input="(e) => (purchasingClient = e.target.value)"
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
      </div>

      <div class="mt-10 grid grid-cols-1 sm:grid-cols-4 sm:gap-6 justify-around">
        <div class="mb-3 xl:w-96">
          <label
            for="exampleFormControlInput1"
            class="form-label inline-block mb-2 text-gray-700"
            >Invoice Number</label
          >
          <input
            disabled
            :value="invoiceLog"
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

        <div class="mb-3 xl:w-96">
          <label
            for="exampleFormControlInput1"
            class="form-label inline-block mb-2 text-gray-700"
            >Invoice Date</label
          >
          <input
            disabled
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
                v-LowerCase
                :value="selectedProducts[selectedProductIndex].productname"
                @input="
                  (e) =>
                    (selectedProducts[selectedProductIndex].productname = e.target.value)
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
                  >
                    <span class="italic">{{ product.product_name }}</span> -
                    {{ product.product_quantity }}
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
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
          </div> -->
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
                        class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900"
                      >
                        <div class="flex flex-row justify-between">Sale Rate</div>
                      </th>
                      <th
                        scope="col"
                        class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900"
                      >
                        Row Amount
                      </th>
                      <th
                        scope="col"
                        class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900"
                      >
                        VAT (16%)
                      </th>
                      <th
                        scope="col"
                        class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900"
                      >
                        Selling Price
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

                      <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">
                        <input
                          disabled
                          v-model="selectedProduct.selectedproductName"
                          type="text"
                          class="form-control block w-full sm:px-3 sm:py-1.5 text-xs sm:text-base font-normal text-gray-700 bg-gray-200 bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-green-600 focus:outline-none"
                          id="exampleFormControlInput1"
                          placeholder=""
                        />
                      </td>
                      <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">
                        <input
                          disabled
                          v-model="selectedProduct.productSKU"
                          type="text"
                          class="form-control block w-full sm:px-3 sm:py-1.5 text-xs sm:text-base font-normal text-gray-700 bg-gray-200 bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-green-600 focus:outline-none"
                          id="exampleFormControlInput1"
                          placeholder=""
                        />
                      </td>
                      <!-- <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">
                        <input
                          disabled
                          v-model="selectedProduct.productDescription"
                          type="text"
                          class="form-control block w-full sm:px-3 sm:py-1.5 text-xs sm:text-base font-normal text-gray-700 bg-gray-200 border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-green-600 focus:outline-none"
                          id="exampleFormControlInput1"
                          placeholder=""
                        />
                      </td> -->
                      <td class="flex whitespace-nowrap px-2 py-2 text-sm text-gray-500">
                        <div class="mt-1 flex rounded-md shadow-sm">
                          <span
                            class="inline-flex items-center rounded-l-md border border-r-0 border-gray-300 bg-gray-200 px-1 text-gray-500 text-xs"
                            >{{ selectedProduct.remainingProducts }} Items</span
                          >
                          <input
                            v-model="selectedProduct.productQuantity"
                            @change="checkSaleChanges(index)"
                            type="text"
                            name="username"
                            id="username"
                            autocomplete="username"
                            class="text-black font-bold block w-full min-w-0 flex-1 rounded-none rounded-r-md border-gray-300 focus:border-green-800 focus:ring-green-800 text-xs"
                          />
                        </div>
                      </td>
                      <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">
                        <div class="flex justify-between">
                          <span class="mt-3 text-green-500"> ws </span>
                          <input
                            v-model="selectedProduct.wholesaleCheck"
                            @change="checkwholeSaleChanges(index)"
                            id="default-checkbox"
                            type="checkbox"
                            value=""
                            class="ml-1 mr-2 mt-4 w-4 h-4 text-green-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                          />
                          <div
                            v-if="wholeSale(index)"
                            class="mt-1 flex jusify-between rounded-md shadow-sm"
                          >
                            <span
                              class="inline-flex items-center rounded-l-md border border-r-0 border-gray-300 bg-gray-200 px-1 text-gray-500 text-xs"
                              >{{ selectedProduct.wholesalePrice }} KES</span
                            >
                            <input
                              v-model="selectedProduct.wholesaleProductPrice"
                              @change="checkSaleChanges(index)"
                              type="text"
                              name="username"
                              id="username"
                              autocomplete="username"
                              class="text-black font-bold block w-full min-w-0 flex-1 rounded-none rounded-r-md border-gray-300 focus:border-green-800 focus:ring-green-800 sm:text-sm"
                            />
                          </div>
                          <div
                            v-else
                            class="mt-1 flex jusify-between rounded-md shadow-sm"
                          >
                            <span
                              class="inline-flex items-center rounded-l-md border border-r-0 border-gray-300 bg-gray-200 px-1 text-gray-500 text-xs"
                              >{{ selectedProduct.fixedPrice }} KES</span
                            >
                            <input
                              v-model="selectedProduct.productPrice"
                              @change="checkSaleChanges(index)"
                              type="text"
                              name="username"
                              id="username"
                              autocomplete="username"
                              class="text-black font-bold block w-full min-w-0 flex-1 rounded-none rounded-r-md border-gray-300 focus:border-green-800 focus:ring-green-800 sm:text-sm"
                            />
                          </div>
                        </div>
                      </td>
                      <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">
                        <input
                          disabled
                          :value="selectedProduct.total"
                          type="text"
                          class="form-control block w-full sm:px-3 sm:py-1.5 text-xs sm:text-base font-normal text-gray-700 bg-gray-200 bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-green-600 focus:outline-none"
                          id="exampleFormControlInput1"
                          placeholder=""
                        />
                      </td>
                      <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">
                        <input
                          disabled
                          v-model="selectedProduct.vat"
                          type="text"
                          class="form-control block w-full sm:px-3 sm:py-1.5 text-xs sm:text-base font-normal text-gray-700 bg-gray-200 bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-green-600 focus:outline-none"
                          id="exampleFormControlInput1"
                          placeholder=""
                        />
                      </td>
                      <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">
                        <input
                          disabled
                          v-model="selectedProduct.salePrice"
                          type="text"
                          class="form-control block w-full sm:px-3 sm:py-1.5 text-xs sm:text-base font-normal text-gray-700 bg-gray-200 bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-green-600 focus:outline-none"
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
                </table>
                <div class="mt-10 m-10 flex flex-row justify-between">
                  <div></div>
                  <div class="flex flex-col font-extrabold">
                    <div>
                      <h3>
                        Subtotal:
                        <span class="text-gray-800 text-xl"
                          >KES {{ numberWithCommas(overallSubtotal) }}
                        </span>
                      </h3>
                      <h3>
                        VAT:
                        <span class="text-gray-800 text-xl"
                          >KES {{ numberWithCommas(overallTax) }}</span
                        >
                      </h3>
                    </div>

                    <div>
                      <h3 class="mt-5">
                        Total:
                        <span class="text-black text-2xl"
                          >KES {{ numberWithCommas(overallTotal) }}</span
                        >
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
        <div class="container-fluid">
          <button
            @click="printInvoice"
            type="button"
            class="mb-2 mr-2 inline-block px-2 py-2 sm:px-6 sm:py-2.5 bg-green-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-green-700 hover:shadow-lg focus:bg-green-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-green-800 active:shadow-lg transition duration-150 ease-in-out"
          >
            <i class="fas fa-print mr-2"></i> Print Invoice
          </button>
          <button
            @click="paymentModal = true"
            type="button"
            class="mb-2 inline-block px-2 py-2 sm:px-6 sm:py-2.5 bg-green-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-green-700 hover:shadow-lg focus:bg-green-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-green-800 active:shadow-lg transition duration-150 ease-in-out"
          >
            <i class="fas fa-money-bill-trend-up mr-2"></i> Process Payment
          </button>
        </div>
      </div>
    </nav>

    <div
      v-if="paymentModal"
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
            class="relative transform overflow-hidden rounded-lg bg-white px-4 pt-5 pb-4 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6"
          >
            <div>
              <button
                @click="paymentModal = false"
                class="group mx-auto flex h-8 w-8 items-center justify-center rounded-full bg-red-100 hover:bg-red-500"
              >
                <i class="group-hover:text-white fas fa-xmark text-black"></i>
              </button>

              <div class="mt-3 text-center sm:mt-5">
                <h3 v-if="!mpesaDialogue" class="text-lg font-bold leading-6 text-gray-600" id="modal-title">
                  Choose Payment Mode
                </h3>
                <h3 v-if="mpesaDialogue" class="text-lg font-bold leading-6 text-gray-600" id="modal-title">
                  M-Pesa Payment 
                </h3>
              </div>
            </div>

            <div v-if="mpesaDialogue">
              <div class="px-5 py-5">
                <label for="phone-number" class="mb-2 block text-sm font-medium text-gray-700"
                  >Enter Mpesa Payment Number</label
                >
                <div class="relative mt-1 rounded-md shadow-sm">
                  <div class="absolute inset-y-0 left-0 flex items-center">
                    <label for="country" class="sr-only">Country</label>
                    <select
                      disabled
                      id="country"
                      name="country"
                      autocomplete="country"
                      class="h-full rounded-md border-transparent bg-transparent py-0 pl-3 pr-7 text-gray-500 focus:border-green-500 focus:ring-green-500 sm:text-sm"
                    >
                      <option selected>KE</option>
                    </select>
                  </div>
                  <input
                    type="number"
                    name="phone-number"
                    id="phone-number"
                    class="text-black font-bold block w-full rounded-md border-gray-300 pl-16 focus:border-green-500 focus:ring-green-500 sm:text-sm"
                    placeholder="254 716 202 298"
                  />
                </div>
              </div>
            </div>

            <div
              v-if="!mpesaDialogue"
              class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3"
            >
              
              <button
                @click="mpesaDialogue = true"
                type="button"
                class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-light-green-600 px-4 py-2 text-base font-bold text-white shadow-sm hover:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:col-start-1 sm:mt-0 sm:text-sm"
              >
                Mpesa
              </button>
              <button
                @click="cashSale"
                type="button"
                class="inline-flex w-full justify-center rounded-md border border-transparent bg-light-green-600 px-4 py-2 text-base font-bold text-white shadow-sm hover:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:col-start-2 sm:text-sm"
              >
                Cash
              </button>
            </div>

            <div
              v-if="mpesaDialogue"
              class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3"
            >
              <button
                @click="stkPush"
                type="button"
                class="inline-flex w-full justify-center rounded-md border border-transparent bg-light-green-600 px-4 py-2 text-base font-bold text-white shadow-sm hover:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:col-start-2 sm:text-sm"
              >
                Send Mpesa Prompt
              </button>
              <button
                @click="mpesaDialogue = false"
                type="button"
                class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-red-600 px-4 py-2 text-base font-bold text-white shadow-sm hover:bg-red-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:col-start-1 sm:mt-0 sm:text-sm"
              >
                Back
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div
      v-if="saleSuccess"
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
            class="relative transform overflow-hidden rounded-lg bg-white px-4 pt-5 pb-4 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6"
          >
            <div>
              <Link
                href="/dashboard"
                class="group mx-auto flex h-8 w-8 items-center justify-center rounded-full bg-red-100 hover:bg-red-500"
              >
                <i class="group-hover:text-white fas fa-xmark text-black"></i>
              </Link>

              <div class="mt-3 text-center sm:mt-5">
                <h3 class="text-xl font-bold leading-6 text-green-600" id="modal-title">
                  Sale was Registered Successfully
                </h3>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <Receipt
      :selectedProducts="selectedProducts"
      :invoiceLog="invoiceLog"
      :overallSubtotal="overallSubtotal"
      :overallTax="overallTax"
      :overallTotal="overallTotal"
      :printTrigger="printTrigger"
      class="hidden"
    />
  </div>
</template>
