<script setup>
import { useForm, usePage } from "@inertiajs/inertia-vue3";
import { ref, computed } from "vue";
import { Inertia } from "@inertiajs/inertia";
import AppLayout from "@/Layouts/AppLayout.vue";
import moment from 'moment'

defineProps({
    products: Array,
    clients: Array,
});

const currentUser = computed(() => usePage().props.value.user.id);
const currentTime = computed(() => moment().format('LLL'));
const subTotal = computed(() =>
{
  return purchasingQuantity.value*purchasingPrice.value
});
const total = computed(() =>
{
  return subTotal.value*1.16
});

const form = useForm({
  product_name: "",
  product_quantity: 20,
  stock_quantity: 10,
  production_price: 120,
  sales_price: 150,
  product_description: "SoftSol Shampoo",
  added_by: currentUser,
});
const selectedItem = ref(null);
const purchasingClient = ref(null);
const purchasingQuantity = ref(null);
const purchasingPrice = ref(null);

const addModal = ref(false);
const saleModal = ref(false);
const showInvoice = ref(false);

const saleItem = (product) =>
{ 
  // console.log(product)
  saleModal.value = true
  selectedItem.value=product
  
}

const addSale = () => {
  // alert('hey')
  // return
  const payload = {
    added_by: currentUser,
    client_id: purchasingClient.id,
    product_id: selectedItem.id,
    product_quantity: purchasingQuantity,
    sale_amount: purchasingPrice,
    payment_status: false,
    invoice_number: 1,
  };
  Inertia.post('/dashboard/register_sale', payload)

  // payload.post("/dashboard/register_sale", {
  //   preserveScroll: true,
  //   onSuccess: () =>
  //   {
  //     selectedItem.reset()
  //     purchasingClient.reset()
  //     purchasingQuantity.reset()
  //     purchasingPrice.reset()
  //     // addModal = false
  //     alert('Sale Registered')
  //    },
  // });
};

const addProduct = () => {
  // Inertia.post('/add_product', form)
  form.post("/add_product", {
    preserveScroll: true,
    onSuccess: () =>
    {
      form.reset()
      // addModal = false
      alert('Product Added')
     },
  });
};
</script>

<template>
  <AppLayout title="Dashboard">
    <div>
      <div
        class="px-4 py-16 mx-auto sm:max-w-xl md:max-w-full lg:max-w-screen-xl md:px-24 lg:px-8 lg:py-8"
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
              <span class="relative">Stock</span>
            </span>
          </h2>
        </div>
        <div class="grid gap-8 row-gap-5 mb-8 md:row-gap-8 lg:grid-cols-4 sm:grid-cols-2">
          <div
            class="duration-300 transform bg-white border-l-4 border-light-green-900 hover:-translate-y-2"
          >
            <div class="h-full p-5 border border-l-0 rounded-r shadow-sm">
              <h6 class="mb-2 font-semibold leading-5 flex pr-2 justify-between">
                Available Stock
                <svg
                  class="h-16 w-10"
                  xmlns="http://www.w3.org/2000/svg"
                  width="24"
                  height="24"
                  viewBox="0 0 24 24"
                >
                  <path
                    d="M19 8.025v-3.025h2v3.181c-.482-.114-.982-.181-1.5-.181l-.5.025zm5 1.791v-4.816h-1v4.028c.359.231.693.494 1 .788zm-7-1.316v-3.5h-1v4.028c.316-.202.649-.382 1-.528zm-13 8.5h2v-12h-2v12zm9-2.5c0-1.271.37-2.452 1-3.453v-6.047h-3v12h2.5c-.321-.77-.5-1.614-.5-2.5zm-5 2.5h1v-12h-1v12zm-8 0h2v-12h-2v12zm24-2.5c0 2.485-2.016 4.5-4.5 4.5-2.486 0-4.5-2.015-4.5-4.5s2.014-4.5 4.5-4.5c2.484 0 4.5 2.015 4.5 4.5zm-2.156-.882l-.697-.696-2.115 2.169-.992-.94-.695.697 1.688 1.637 2.811-2.867z"
                  />
                </svg>
              </h6>
              <p class="text-sm text-gray-900">
                Sportacus andrew weatherall goose Refined gentlemen super mario des lynam
                alpha trion zap rowsdower.
              </p>
            </div>
          </div>
          <div
            class="duration-300 transform bg-white border-l-4 border-light-green-900 hover:-translate-y-2"
          >
            <div class="h-full p-5 border border-l-0 rounded-r shadow-sm">
              <h6 class="mb-2 font-semibold leading-5 flex pr-2 justify-between">
                Delivered Stock
                <svg
                  class="h-16 w-10"
                  xmlns="http://www.w3.org/2000/svg"
                  width="24"
                  height="24"
                  viewBox="0 0 24 24"
                >
                  <path
                    d="M23 0l-4.5 16.5-6.097-5.43 5.852-6.175-7.844 5.421-5.411-1.316 18-9zm-11 12.501v5.499l2.193-3.323-2.193-2.176zm-2.789 3.857l.94.934c-.875.885-1.773 1.933-2.125 3.375l-1.286-.314c.434-1.774 1.491-3.004 2.471-3.995zm-1.565 7.642c-.715 0-1.353-.279-1.887-.83-1.458.515-2.844-.044-3.576-1.084-.575-.817-.703-1.853-.353-2.845-.552-.534-.83-1.166-.83-1.884 0-1.562 1.16-2.803 3.24-2.586l.195 1.117c-.664.062-1.277.097-1.674.494-.668.668-.467 2.063.787 2.433-.832.836-.751 2.037-.127 2.696.654.69 1.903.799 2.765-.059.385 1.305 1.798 1.422 2.433.787.392-.392.431-.995.492-1.649l1.125.229c.2 1.979-1.009 3.181-2.59 3.181zm-3.318-7.032l.314 1.287c1.755-.43 2.953-1.45 3.989-2.471l-.938-.931c-.876.866-1.927 1.764-3.365 2.115z"
                  />
                </svg>
                <!-- <svg class="h-16 w-10" width="24" height="24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd"><path d="M7.919 17.377l-4.869-13.377h-2.05c-.266 0-.52-.105-.707-.293-.188-.187-.293-.442-.293-.707 0-.552.447-1 1-1h3.45l5.469 15.025c.841.101 1.59.5 2.139 1.088l11.258-4.097.684 1.879-11.049 4.021c.032.19.049.385.049.584 0 1.932-1.569 3.5-3.5 3.5-1.932 0-3.5-1.568-3.5-3.5 0-1.363.781-2.545 1.919-3.123zm1.581 1.811c.724 0 1.312.588 1.312 1.312 0 .724-.588 1.313-1.312 1.313-.725 0-1.313-.589-1.313-1.313s.588-1.312 1.313-1.312zm5.799-12.29l4.767-1.735 2.736 7.517-11.406 4.152-2.736-7.518 4.759-1.732 1.325 3.639 1.879-.684-1.324-3.639zm.537-1.26l-7.518 2.736-2.052-5.638 7.518-2.736 2.052 5.638z"/></svg> -->
              </h6>
              <p class="text-sm text-gray-900">
                Bulbasaur Lorem ipsum dolor sit amet, consectetur adipiscing elit.
              </p>
            </div>
          </div>
          <div
            class="duration-300 transform bg-white border-l-4 border-light-green-900 hover:-translate-y-2"
          >
            <div class="h-full p-5 border border-l-0 rounded-r shadow-sm">
              <h6 class="mb-2 font-semibold leading-5 flex pr-2 justify-between">
                Low Stock
                <svg
                  class="h-16 w-10"
                  xmlns="http://www.w3.org/2000/svg"
                  width="24"
                  height="24"
                  viewBox="0 0 24 24"
                >
                  <path
                    d="M19 7v10h-5.952l-5.087-10h11.039zm2-2h-21v14h21v-14zm1 11h.75c.69 0 1.25-.56 1.25-1.25v-5.5c0-.69-.56-1.25-1.25-1.25h-.75v8z"
                  />
                </svg>
              </h6>
              <p class="text-sm text-gray-900">
                Bro ipsum dolor sit amet gaper backside single track, manny Bike epic
                clipless. Schraeder drop gondy.
              </p>
            </div>
          </div>
          <div
            class="duration-300 transform bg-white border-l-4 border-light-green-900 hover:-translate-y-2"
          >
            <div class="h-full p-5 border border-l-0 rounded-r shadow-sm">
              <h6 class="mb-2 font-semibold leading-5 flex pr-2 justify-between">
                Out of Stock
                <svg
                  class="h-16 w-10"
                  xmlns="http://www.w3.org/2000/svg"
                  width="24"
                  height="24"
                  viewBox="0 0 24 24"
                >
                  <path
                    d="M19 8.025v-3.025h2v3.181c-.482-.114-.982-.181-1.5-.181l-.5.025zm5 1.791v-4.816h-1v4.028c.359.231.693.494 1 .788zm-7-1.316v-3.5h-1v4.028c.316-.202.649-.382 1-.528zm-13 8.5h2v-12h-2v12zm9-2.5c0-1.271.37-2.452 1-3.453v-6.047h-3v12h2.5c-.321-.77-.5-1.614-.5-2.5zm-5 2.5h1v-12h-1v12zm-8 0h2v-12h-2v12zm19.5-7c-2.483 0-4.5 2.015-4.5 4.5s2.017 4.5 4.5 4.5 4.5-2.015 4.5-4.5-2.017-4.5-4.5-4.5zm2.122 3.085l-1.414 1.415 1.414 1.414-.708.708-1.414-1.415-1.414 1.414-.707-.708 1.414-1.414-1.414-1.414.707-.708 1.414 1.414 1.414-1.414.708.708z"
                  />
                </svg>
              </h6>
              <p class="text-sm text-gray-900">
                A flower in my garden, a mystery in my panties. Heart attack never stopped
                old Big Bear.
              </p>
            </div>
          </div>
        </div>
        <div class="text-center">
          <button
            @click="addModal = true"
            class="inline-flex items-center justify-center w-full h-12 px-6 font-medium tracking-wide text-white transition duration-200 rounded shadow-md md:w-auto bg-light-green-900 hover:bg-white hover:text-light-green-900 focus:shadow-outline focus:outline-none"
          >
            Add Product
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
                  Product Listing
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
              <div class="sm:flex items-center justify-between">
                <div class="flex items-center">
                  <a
                    class="rounded-full focus:outline-none focus:ring-2 focus:bg-indigo-50 focus:ring-light-green-900"
                    href=" javascript:void(0)"
                  >
                    <div
                      class="py-2 px-8 bg-light-green-900 text-white font-bold rounded-md"
                    >
                      <p>Available Stock</p>
                    </div>
                  </a>
                  <a
                    class="rounded-full focus:outline-none focus:ring-2 focus:bg-indigo-50 focus:ring-light-green-900 ml-4 sm:ml-8"
                    href="javascript:void(0)"
                  >
                    <div
                      class="py-2 px-8 text-gray-600 hover:bg-light-green-900 hover:text-white font-bold rounded-md"
                    >
                      <p>Delivered Stock</p>
                    </div>
                  </a>
                  <a
                    class="rounded-full focus:outline-none focus:ring-2 focus:bg-indigo-50 focus:ring-light-green-900 ml-4 sm:ml-8"
                    href="javascript:void(0)"
                  >
                    <div
                      class="py-2 px-8 text-gray-600 hover:bg-light-green-900 hover:text-white font-bold rounded-md"
                    >
                      <p>Low Stock</p>
                    </div>
                  </a>
                  <a
                    class="rounded-full focus:outline-none focus:ring-2 focus:bg-indigo-50 focus:ring-light-green-900 ml-4 sm:ml-8"
                    href="javascript:void(0)"
                  >
                    <div
                      class="py-2 px-8 text-gray-600 hover:bg-light-green-900 hover:text-white font-bold rounded-md"
                    >
                      <p>Out of Stock</p>
                    </div>
                  </a>
                </div>
                <!-- <button onclick="popuphandler(true)" class="focus:ring-2 focus:ring-offset-2 focus:ring-light-green-900 mt-4 sm:mt-0 inline-flex items-start justify-start px-6 py-3 bg-light-green-900 hover:bg-white text-white font-bold hover:text-black focus:outline-none rounded">
                        <p class="text-sm font-medium leading-none">Batch Options</p>
                    </button> -->
              </div>
              <div class="mt-7 overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                  <tbody>
                    <tr
                      v-for="(product, index) in products" 
                      :key="index"
                      tabindex="0"
                      class="focus:outline-none h-16 border border-gray-100 rounded"
                    >
                      <td>
                        <div class="ml-5">
                          <div
                            class="bg-light-green-200 focus:bg-green-300 rounded-sm w-5 h-5 flex flex-shrink-0 justify-center items-center relative"
                          >
                            <!-- <input
                              placeholder="checkbox"
                              type="checkbox"
                              class="focus:opacity-100 checkbox opacity-0 absolute cursor-pointer w-full h-full"
                            /> -->
                            <i class="fa fa-caret-right"></i>
                          </div>
                        </div>
                      </td>
                      <td class="">
                        <div class="flex items-center pl-5">
                          <p
                            class="text-base font-medium leading-none text-gray-700 mr-2"
                          >
                            {{ product.product_name }}
                          </p>
                          <i class="fas fa-eye text-green-900"></i>
                        </div>
                      </td>
                      <td class="pl-24">
                        <div class="flex items-center">
                          <i class="fas fa-scale-unbalanced text-green-900"></i>
                          <p class="text-sm leading-none text-gray-600 ml-2">{{ product.product_quantity }} Grams</p>
                        </div>
                      </td>
                      <td class="pl-5">
                        <div class="flex items-center">
                          <i class="fas fa-boxes-packing text-green-900"></i>
                          <p class="text-sm leading-none text-gray-600 ml-2">{{ product.stock_quantity }} items</p> 
                        </div>
                      </td>
                      <td class="pl-5">
                        <div class="flex items-center">
                          <i class="fas fa-wallet text-green-900"></i>
                          <p class="text-sm leading-none text-gray-600 ml-2">{{ product.production_price }} KES</p>
                        </div>
                      </td>
                      <td class="pl-5">
                        <div class="flex items-center">
                          <i class="fas fa-money-bill-trend-up text-green-900"></i>
                          <p class="text-sm leading-none text-gray-600 ml-2">{{ product.sales_price }} KES</p>
                        </div>
                      </td>
                      <td class="pl-5">
                        <button
                          class="font-bold py-3 px-3 text-sm focus:outline-none leading-none text-white bg-light-green-900 hover:bg-green-900 rounded"
                        >
                          Process Delivery <i class="ml-4 fas fa-truck"></i>
                        </button>
                      </td>
                      <td class="pl-4">
                        <button
                          class="font-bold py-3 px-3 text-sm focus:outline-none leading-none text-white bg-light-green-900 hover:bg-green-900 rounded"
                        >
                          Update Details <i class="ml-4 fas fa-file-pen"></i>
                        </button>
                      </td>
                      <td>
                        <div class="relative px-5 pt-2">
                          <button
                            @click="saleItem(product)"
                            class="font-bold py-3 px-3 text-sm focus:outline-none leading-none text-white bg-light-green-900 hover:bg-green-900 rounded"
                          >
                             Sale(s) Hooray! <i class="ml-4 fas fa-handshake"></i>
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
                          class="mt-1 focus:ring-indigo-500 focus:border-light-green-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
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
                          class="mt-1 focus:ring-indigo-500 focus:border-light-green-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
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
                          class="mt-1 focus:ring-indigo-500 focus:border-light-green-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
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
                          class="mt-1 focus:ring-indigo-500 focus:border-light-green-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
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
                          class="mt-1 focus:ring-indigo-500 focus:border-light-green-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
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
                          class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"
                          placeholder=""
                        ></textarea>
                        <!-- <input type="text" name="email-address" id="email-address" autocomplete="email" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"> -->
                      </div>

                      <!-- <div class="col-span-6 sm:col-span-3">
                        <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                        <select id="country" name="country" autocomplete="country-name" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
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

    <div v-if="saleModal" class="modal fade fixed p-40 w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="exampleModalCenter" tabindex="-1" aria-labelledby="exampleModalCenterTitle" aria-modal="true" role="dialog">
      <div class="modal-dialog modal-dialog-centered relative w-auto pointer-events-none">

        <div v-if="!showInvoice" class="modal-content border-none shadow-2xl relative flex flex-col w-full pointer-events-auto bg-gray-50 bg-clip-padding rounded-md outline-none text-current">
          <div class="modal-header flex flex-shrink-0 items-center justify-between p-4 border-b border-gray-200 rounded-t-md">
            <h5 class="text-xl font-medium leading-normal text-gray-800" id="exampleModalScrollableLabel">
              <!-- Kindly process your sale -->
            </h5>
            <button type="button"
              class="btn-close box-content w-4 h-4 p-1 text-black border-none rounded-none opacity-50 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-black hover:opacity-75 hover:no-underline"
              data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body relative p-4">
            <p class="font-bold mt-2 text-gray-700 text-sm">Product Name: <span class="text-gray-600 font-medium text-md"> {{ selectedItem.product_name }} </span> </p>
            <p class="font-bold mt-2 text-gray-700 text-sm">Product Size: <span class="text-gray-600 font-medium text-md"> {{ selectedItem.product_quantity }} Grams </span></p>
            <!-- <p class="font-bold mt-2">Minimum Sale Price:  <span class=""> <i class="ml-4 fas fa-eye cursor-pointer text-light-green-900 hover:text-green-900"></i> </span> </p>  -->
            <p class="font-bold mt-2 text-gray-700 text-sm">Delivery Batch: <span class="text-gray-600 font-medium text-md"> Batch 2</span></p>
            <!-- <p class="font-bold mt-2 text-gray-700 text-sm">Remaining Stock Number: <span class="text-black font-medium text-lg"> {{ }} </span></p> -->
            <!-- <p class="font-bold mt-2 text-gray-700 text-sm">Sales Profit: <span class="text-black font-medium text-lg"> {{ }} </span></p> -->
            
            <div class="flex flex-row items-center py-8 px-4 mt-5">
                <!-- Code block starts -->
                <div class="flex flex-col lg:mr-16">
                    <div class="flex justify-between">
                    <label for="email" class="text-gray-800 dark:text-gray-100 text-sm font-bold leading-tight tracking-normal mb-2">Select Purchasing Client:</label>
                    <Link href="/dashboard/add_client" class="fas fa-user-plus text-light-green-900 cursor-pointer"></Link>
                    </div>
                    <select v-model="purchasingClient" id="country" name="country" autocomplete="country-name" class="text-gray-600 dark:text-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-800 dark:focus:border-green-900 dark:border-gray-700 dark:bg-gray-800 bg-white font-normal w-64 h-10 flex items-center pl-3 text-sm border-gray-300 rounded border shadow">
                      <option v-for="client in clients" :value="client">{{ client.client_name }}</option>
                      <!-- <option>Another Company</option>
                      <option>Bing Ink</option> -->
                    </select>
                    <!-- <input id="email" autocomplete="off" class="text-gray-600 dark:text-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-700 dark:focus:border-indigo-700 dark:border-gray-700 dark:bg-gray-800 bg-white font-normal w-64 h-10 flex items-center pl-3 text-sm border-gray-300 rounded border shadow" /> -->
                </div>
                <!-- Code block ends -->
                
                <!-- Code block starts -->
                <div class="flex flex-col lg:mr-16">
                    <label for="email" class="text-gray-800 dark:text-gray-100 text-sm font-bold leading-tight tracking-normal mb-2">Number of items to sell:</label>
                    <input v-model="purchasingQuantity" :placeholder="'Available: '+selectedItem.stock_quantity" type="number" id="email" autocomplete="off" class="text-gray-600 dark:text-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-900 dark:focus:border-indigo-700 dark:border-gray-700 dark:bg-gray-800 bg-white font-normal w-40 h-10 flex items-center pl-3 text-sm border-gray-300 rounded border shadow" />
                </div>
                <!-- Code block ends -->
                <!-- Code block starts -->
                <!-- <div class="flex flex-col lg:mr-16 lg:py-0 py-4">
                    <label for="last_email" class="text-gray-400 text-sm font-bold leading-tight tracking-normal mb-2">Minumum Sale Price</label>
                    <input disabled id="last_email" class="text-gray-600 dark:text-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-700 dark:focus:border-green-900 dark:border-gray-700 dark:bg-gray-700 font-normal w-40 h-10 flex items-center pl-3 text-sm border-gray-300 bg-gray-200 rounded border shadow" placeholder="120" />
                </div> -->
                <!-- Code block ends -->
                <!-- Code block starts -->
                <div class="flex flex-col lg:mr-16 lg:py-0 py-4">
                    <label for="email1" class="text-gray-800 text-sm font-bold leading-tight tracking-normal mb-2">Actual Sale Price:</label>
                    <input v-model="purchasingPrice" :placeholder="'Min Price: '+selectedItem.sales_price" type="number" id="email1" class="text-gray-600 dark:text-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-900 dark:focus:border-green-900 dark:border-gray-700 dark:bg-gray-800 bg-white font-normal w-40 h-10 flex items-center pl-3 text-sm border-gray-300 rounded border shadow"  />
                </div>
                <!-- Code block ends -->
            </div>
    
          </div>
          <div
            class="modal-footer flex flex-shrink-0 flex-wrap items-center justify-end p-4 border-t border-gray-200 rounded-b-md">
            <button 
              @click="saleModal=false"
              type="button"
              class="inline-block px-6 py-2.5 bg-gray-300 text-red-300 font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-red-900 hover:text-white hover:shadow-lg focus:bg-red-900 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-red-900 active:shadow-lg transition duration-150 ease-in-out"
              data-bs-dismiss="modal">
              Close
            </button>
            <button 
              @click="showInvoice=true"
              type="button"
              class="inline-block px-6 py-2.5 bg-light-green-900 hover:bg-green-900 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out ml-1">
              Process Invoice <i class="fas fa-clipboard-check ml-2 fa-xl"></i>
            </button>
          </div>
        </div>

        <div v-if="showInvoice" class="modal-content border-none shadow-2xl relative flex flex-col w-full pointer-events-auto bg-gray-50 bg-clip-padding rounded-md outline-none text-current">
          <div class="modal-header flex flex-shrink-0 items-center justify-between p-4 border-b border-gray-200 rounded-t-md">
            <h5 class="text-xl font-medium leading-normal text-gray-800" id="exampleModalScrollableLabel">
              <!-- Kindly process your sale -->
            </h5>
            <button type="button"
              class="btn-close box-content w-4 h-4 p-1 text-black border-none rounded-none opacity-50 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-black hover:opacity-75 hover:no-underline"
              data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div>
            <div class="flex items-center justify-center min-h-screen bg-gray-100">
              <div class="w-3/5 bg-white shadow-lg">
                  <div class="flex justify-between p-4">
                      <div>
                          <h1 class="text-3xl font-extrabold tracking-widest text-green-900">Brisk Invoice</h1>
                      </div>
                      <div class="p-2">
                          <ul class="flex">
                              <li class="flex flex-col items-center p-2 border-l-2 border-light-green-900">
                                  <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-900" fill="none"
                                      viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                  </svg>
                                  <span class="text-sm">
                                      www.brisk.co.ke
                                  </span>
                                  <span class="text-sm">
                                      www.biddersportal.com
                                  </span>
                              </li>
                              <li class="flex flex-col items-center p-2 border-l-2 border-light-green-900">
                                  <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-900" fill="none"
                                      viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                  </svg>
                                  <span class="text-sm">
                                      Syokimau, Nairobi Kenya
                                  </span>
                                  <span class="text-sm">
                                      Community road Narnawarayan Go down
                                  </span>
                              </li>
                          </ul>
                      </div>
                  </div>
                  <div class="w-full h-0.5 bg-indigo-500"></div>
                  <div class="flex justify-between p-4">
                      <div>
                          <h6 class="font-bold">Order Date : <span class="text-sm font-medium"> {{ currentTime }}</span></h6>
                          <!-- <h6 class="font-bold">Order ID : <span class="text-sm font-medium"> 12/12/2022</span></h6> -->
                      </div>
                      <div class="w-auto">
                          <address class="text-sm">
                              <span class="font-bold"> Billed To : </span>
                              {{  purchasingClient.client_name}}
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
                                      <th class="px-4 py-2 text-xs text-gray-500 ">
                                          Product Name
                                      </th>
                                      <th class="px-4 py-2 text-xs text-gray-500 ">
                                          Product Size
                                      </th>
                                      <th class="px-4 py-2 text-xs text-gray-500 ">
                                          Quantity Sold
                                      </th>
                                      <th class="px-4 py-2 text-xs text-gray-500 ">
                                          Rate
                                      </th>
                                      <th class="px-4 py-2 text-xs text-gray-500 ">
                                          Subtotal
                                      </th>
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
                                          <div class="text-sm text-gray-500">{{ purchasingQuantity }}</div>
                                      </td>
                                      <td class="px-6 py-4 text-sm text-gray-500">
                                          KES {{ purchasingPrice }}
                                      </td>
                                      <td class="px-6 py-4">
                                          KES {{ subTotal }}
                                      </td>
                                  </tr>
                                  <tr class="">
                                      <td colspan="3"></td>
                                      <td class="text-sm font-bold">Sub Total</td>
                                      <td class="text-sm font-bold tracking-wider"><b>KES {{ subTotal }}</b></td>
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
                                      <td class="text-sm font-bold"><b>KES {{ total}}</b></td>
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
                              <li>All accounts are to be paid within 7 days from receipt of invoice.</li>
                              <li>To be paid by cheque or credit card or direct payment online.</li>
                              <li>If account is not paid within 7 days the credits details supplied.</li>
                          </ul>
                      </div>
                      <!-- <div class="p-4">
                          <h3>Signature</h3>
                          <div class="text-4xl italic text-indigo-500">AAA</div>
                      </div> -->
                  </div>
                  <div class="w-full h-0.5 bg-indigo-500"></div>

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
                      <div class="flex items-center">
                          Invoice Options
                      </div>
                      <div class="flex items-center gap-4">
                          <button class="px-2 py-1 bg-gray-200 hover:bg-green-800 hover:text-white">Send to Client Email <i class="ml-2 fa fa-paper-plane"></i> </button>
                          <button class="px-2 py-1 bg-gray-200 hover:bg-green-800 hover:text-white">Download <i class="ml-2 fa fa-download"></i> </button>
                      </div>
                  </div>
                  <div class="w-full h-0.5 bg-gray-800"></div>

              </div>
          </div>
          </div>
          <div
            class="modal-footer flex flex-shrink-0 flex-wrap items-center justify-end p-4 border-t border-gray-200 rounded-b-md">
            <button 
              @click="showInvoice=false"
              type="button"
              class="inline-block px-6 py-2.5 bg-gray-300 text-red-300 font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-red-900 hover:text-white hover:shadow-lg focus:bg-green-900 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-purple-800 active:shadow-lg transition duration-150 ease-in-out"
              data-bs-dismiss="modal">
              Back
            </button>
            <button 
              @click="addSale"
              type="button"
              class="inline-block px-6 py-2.5 bg-light-green-900 hover:bg-green-900 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out ml-1">
              Finish Sale <i class="fas fa-handshake-angle ml-2 fa-xl"></i>
            </button>
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
