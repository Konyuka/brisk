<script setup>
import { useForm, usePage } from "@inertiajs/inertia-vue3";
import { ref, computed} from "vue";
import { Inertia } from "@inertiajs/inertia";
import AppLayout from "@/Layouts/AppLayout.vue";

defineProps({
  products: Array,
});

const vLowerCase = {
  updated: (el) => {
    el.value = el.value.toLowerCase();
  },
};

const currentUser = computed(() => usePage().props.value.user.id);
const available_products = computed(() => {
  return form.finished_products - form.in_delivery - form.spoiled_products;
});

const success = ref(null);
const filename = ref(null);
const file = ref(null);
const uploadProductModal = ref(false);
const formEdit = ref(false);
const productAddNotification = ref(false);
const productUpdateNotification = ref(false);
const taxable = ref(true);

const form = useForm({
  product_name: null,
  product_quantity: null,
  product_code: null,
  bar_code: null,
  sales_price: null,
  wholesale_price: null,
  finished_products: null,
  in_delivery: null,
  spoiled_products: null,
  missing_products: null,
  added_by: currentUser,
  tax_exempt: false,
  product_id: null,
});

const formatToCurrency = (amount) =>
{ 
  return (amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'); 
} 

const setDownloaded = () =>
{ 
  alert('downloaded')
}

const onFileChange = (e) => {
  // console.log(e.target.files[0]);
  filename.value = "Selected File: " + e.target.files[0].name;
  file.value = e.target.files[0];
};

const uploadFile = (e) => {
  if (filename.value == null) {
    alert("kindly select a file");
  } else {
    // alert("upload");
    e.preventDefault();

    // let currentObj = this;
    const config = {
      headers: {
        "content-type": "multipart/form-data",
        // "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
      },
    };

    // form data
    let formData = new FormData();
    formData.append("file", file.value);

    // send upload request
    axios
      .post("/dashboard/upload_product", formData, config)
      .then(function (response) {
        success.value = response.data.success;
        filename.value = "";
        uploadProductModal.value = false
        alert('Products Uploaded Successfully')
        Inertia.get("/dashboard/product_inventory")
      })
      .catch(function (error) {
        // currentObj.output = error;
        console.log(error.response.data)
      });
  }
};

const editProduct = (product) => {
  formEdit.value = true;
  form.product_name = product.product_name;
  form.product_quantity = product.product_quantity;
  form.product_code = product.product_code;
  form.bar_code = product.bar_code;
  form.sales_price = product.sales_price;
  form.wholesale_price = product.wholesale_price;
  form.finished_products = product.finished_products;
  form.in_delivery = product.in_delivery;
  form.spoiled_products = product.spoiled_products;
  form.missing_products = product.missing_products;
  form.product_id = product.id;
  window.scrollTo(0, 0);

  // console.log(product)
};
const updateProduct = () => {
  form.post("/dashboard/update_product", {
    preserveScroll: true,
    onSuccess: () => {
      productUpdateNotification.value = true;
      form.reset();
      // form.reset();
      // alert("Product Added");
    },
  });
};
const addProduct = () => {
  form.post("/dashboard/add_product", {
    preserveScroll: true,
    onSuccess: () => {
      productAddNotification.value = true;
      form.reset();
      // alert("Product Added");
    },
  });
};
const clearForm = () => {
  form.reset();
};
const setTax = () => {
  taxable.value = !taxable.value;
  form.tax_exempt = !form.tax_exempt;
  // console.log(form.tax_exempt)
};
</script>

<template>
  <AppLayout>
    <div class="mt-8 max-w-xl mb-10 md:mx-auto sm:text-center lg:max-w-2xl md:mb-2">
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
          <span class="relative px-2">Inventory</span>
        </span>
      </h2>
    </div>
    <div
      class="px-0 sm:px-10 modal-content border-none relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current"
    >
      <div
        class="modal-header flex flex-shrink-0 items-center justify-between p-4 border-b border-gray-200 rounded-t-md"
      >
        <h5
          class="text-xl font-medium leading-normal text-gray-800"
          id="exampleModalCentegreenScrollableLabel"
        >
          Add Product
        </h5>
        <button
          @click="uploadProductModal = true"
          type="button"
          class="inline-block px-6 py-2.5 bg-light-green-900 text-white font-bold text-xs leading-tight uppercase rounded shadow-md hover:bg-green-900 hover:shadow-lg focus:bg-green-900 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out ml-1"
        >
          <i class="fas fa-file-excel fa-xl mr-1"></i> Upload Products
        </button>
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
                  <label for="first-name" class="block text-sm font-medium text-gray-700"
                    ><i
                      class="fa-solid fa-file-signature text-light-green-800 fa-lg mr-1"
                    ></i>
                    Product name</label
                  >
                  <input
                    type="text"
                    name="first-name"
                    id="first-name"
                    v-model="form.product_name"
                    autocomplete="given-name"
                    v-LowerCase
                    class="capitalize mt-1 focus:ring-green-500 focus:border-light-green-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                  />
                  <div
                    class="animate-pulse text-sm font-bold italic text-green-500"
                    v-if="form.errors.product_name"
                  >
                    {{ form.errors.product_name }}
                  </div>
                </div>

                <div class="col-span-3 sm:col-span-1">
                  <label for="last-name" class="block text-sm font-medium text-gray-700"
                    ><is
                      class="fa-solid fa-scale-unbalanced-flip text-light-green-800 fa-lg mr-1"
                    ></is>
                    SKU (G/ML/L)</label
                  >
                  <input
                    type="text"
                    name="last-name"
                    id="last-name"
                    v-model="form.product_quantity"
                    autocomplete="family-name"
                    class="uppercase mt-1 focus:ring-green-500 focus:border-light-green-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                  />
                  <div
                    class="animate-pulse text-sm font-bold italic text-green-500"
                    v-if="form.errors.product_quantity"
                  >
                    {{ form.errors.product_quantity }}
                  </div>
                </div>

                <!-- <div class="col-span-3 sm:col-span-1">
                  <label for="last-name" class="block text-sm font-medium text-gray-700"
                    ><i class="fa-solid fa-barcode text-light-green-800 fa-lg mr-1"></i>
                    Product Code</label
                  >
                  <input
                    type="text"
                    name="last-name"
                    id="last-name"
                    v-model="form.product_code"
                    autocomplete="family-name"
                    class="mt-1 focus:ring-green-500 focus:border-light-green-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                  />
                </div> -->

                <!-- <div class="col-span-3 sm:col-span-1">
                  <label for="last-name" class="block text-sm font-medium text-gray-700"
                    ><i class="fa-solid fa-barcode text-light-green-800 fa-lg mr-1"></i>
                    Bar Code</label
                  >
                  <input
                    type="number"
                    name="last-name"
                    id="last-name"
                    v-model="form.bar_code"
                    autocomplete="family-name"
                    class="mt-1 focus:ring-green-500 focus:border-light-green-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                  />
                </div> -->

                <div class="col-span-3 sm:col-span-1">
                  <label for="last-name" class="block text-sm font-medium text-gray-700"
                    ><i
                      class="fa-solid fa-money-bill-trend-up text-light-green-800 fa-lg mr-1"
                    ></i>
                    Retail Price (ksh.)</label
                  >
                  <input
                    type="number"
                    name="last-name"
                    id="last-name"
                    v-model="form.sales_price"
                    autocomplete="family-name"
                    class="mt-1 focus:ring-green-500 focus:border-light-green-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                  />
                  <div
                    class="animate-pulse text-sm font-bold italic text-green-500"
                    v-if="form.errors.sales_price"
                  >
                    {{ form.errors.sales_price }}
                  </div>
                </div>

                <div class="col-span-3 sm:col-span-1">
                  <label for="last-name" class="block text-sm font-medium text-gray-700"
                    ><i
                      class="fa-solid fa-money-bill-trend-up text-light-green-800 fa-lg mr-1"
                    ></i>
                    Wholesales Price (ksh.)</label
                  >
                  <input
                    type="number"
                    name="last-name"
                    id="last-name"
                    v-model="form.wholesale_price"
                    autocomplete="family-name"
                    class="mt-1 focus:ring-green-500 focus:border-light-green-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                  />
                  <div
                    class="animate-pulse text-sm font-bold italic text-green-500"
                    v-if="form.errors.wholesale_price"
                  >
                    {{ form.errors.wholesale_price }}
                  </div>
                </div>

                <div class="col-span-3 sm:col-span-1">
                  <label for="last-name" class="block text-sm font-medium text-gray-700"
                    ><i class="fas fa-square-check text-light-green-800 fa-lg mr-1"></i>
                    Finished Products</label
                  >
                  <input
                    type="number"
                    name="last-name"
                    id="last-name"
                    v-model="form.finished_products"
                    autocomplete="family-name"
                    class="mt-1 focus:ring-green-500 focus:border-light-green-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                  />
                  <div
                    class="animate-pulse text-sm font-bold italic text-green-500"
                    v-if="form.errors.finished_products"
                  >
                    {{ form.errors.finished_products }}
                  </div>
                </div>

                <!-- <div class="col-span-3 sm:col-span-1">
                  <label for="last-name" class="block text-sm font-medium text-gray-700"
                    ><i class="fas fa-check-double text-light-green-800 fa-lg mr-1"></i>
                    Available Products</label
                  >
                  <input
                    disabled
                    type="number"
                    name="last-name"
                    id="last-name"
                    v-model="available_products"
                    autocomplete="family-name"
                    class="bg-gray-300 mt-1 focus:ring-green-500 focus:border-light-green-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                  />
                </div>

                <div class="col-span-3 sm:col-span-1">
                  <label for="last-name" class="block text-sm font-medium text-gray-700"
                    ><i
                      class="fas fa-square-caret-right text-light-green-800 fa-lg mr-1"
                    ></i>
                    In Delivery</label
                  >
                  <input
                    disabled
                    type="number"
                    name="last-name"
                    id="last-name"
                    v-model="form.in_delivery"
                    autocomplete="family-name"
                    class="bg-gray-300 mt-1 focus:ring-green-500 focus:border-light-green-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                  />
                </div>

                <div class="col-span-3 sm:col-span-1">
                  <label for="last-name" class="block text-sm font-medium text-gray-700"
                    ><i
                      class="fas fa-triangle-exclamation text-light-green-800 fa-lg mr-1"
                    ></i>
                    Spoiled Products</label
                  >
                  <input
                    disabled
                    type="number"
                    name="last-name"
                    id="last-name"
                    v-model="form.spoiled_products"
                    autocomplete="family-name"
                    class="bg-gray-300 mt-1 focus:ring-green-500 focus:border-light-green-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                  />
                </div>

                <div class="col-span-3 sm:col-span-1">
                  <label for="last-name" class="block text-sm font-medium text-gray-700"
                    ><i
                      class="fas fa-rectangle-xmark text-green-800 fa-lg mr-1"
                    ></i>
                    Missing Products</label
                  >
                  <input
                    disabled
                    type="number"
                    name="last-name"
                    id="last-name"
                    v-model="form.missing_products"
                    autocomplete="family-name"
                    class="bg-gray-300 mt-1 focus:ring-green-500 focus:border-light-green-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                  />
                </div> -->

                <div>
                  <!-- This example requires Tailwind CSS v2.0+ -->
                  <div class="flex items-center">
                    <!-- Enabled: "bg-green-600", Not Enabled: "bg-gray-200" -->
                    <button
                      @click="setTax"
                      :class="taxable == true ? 'bg-light-green-600' : 'bg-gray-200'"
                      type="button"
                      class="bg-gray-200 relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-light-green-600 focus:ring-offset-2"
                      role="switch"
                      aria-checked="false"
                      aria-labelledby="annual-billing-label"
                    >
                      <!-- Enabled: "translate-x-5", Not Enabled: "translate-x-0" -->
                      <span
                        aria-hidden="true"
                        :class="taxable == true ? 'translate-x-5' : 'translate-x-0'"
                        class="translate-x-5 pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                      ></span>
                    </button>
                    <span class="ml-3" id="annual-billing-label">
                      <span class="text-sm font-bold text-gray-600">Taxable</span>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div
      class="modal-footer flex flex-shrink-0 flex-wrap items-center justify-start p-4 border-t border-gray-200 rounded-b-md"
    >
      <button
        type="button"
        @click="clearForm"
        class="inline-block px-6 py-2.5 bg-gray-400 text-white font-bold text-xs leading-tight uppercase rounded shadow-md hover:bg-green-700 hover:shadow-lg focus:bg-green-900 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out ml-1"
      >
        <i class="fas fa-xmark fa-xl mr-1"></i> Clear Form
      </button>

      <button
        v-if="!formEdit"
        type="button"
        @click="addProduct"
        class="inline-block px-6 py-2.5 bg-light-green-900 text-white font-bold text-xs leading-tight uppercase rounded shadow-md hover:bg-green-900 hover:shadow-lg focus:bg-green-900 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out ml-1"
      >
        <i class="fas fa-plus fa-xl mr-1"></i> Add Product
      </button>
      <button
        v-else
        type="button"
        @click="updateProduct"
        class="inline-block px-6 py-2.5 bg-light-green-900 text-white font-bold text-xs leading-tight uppercase rounded shadow-md hover:bg-green-900 hover:shadow-lg focus:bg-green-900 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out ml-1"
      >
        <i class="fas fa-pencil fa-xl mr-1"></i> Update Product
      </button>
    </div>

    <div v-if="productAddNotification" class="px-20 py-10">
      <!-- This example requires Tailwind CSS v2.0+ -->
      <div class="rounded-md bg-green-50 p-4">
        <div class="flex">
          <div class="flex-shrink-0">
            <!-- Heroicon name: mini/check-circle -->
            <svg
              class="h-5 w-5 text-green-400"
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 20 20"
              fill="currentColor"
              aria-hidden="true"
            >
              <path
                fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                clip-rule="evenodd"
              />
            </svg>
          </div>
          <div class="ml-3">
            <h3 class="text-sm font-medium text-green-800">Product Added Successfully</h3>
            <div class="mt-2 text-sm text-green-700">
              <!-- <p>
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquid pariatur,
                ipsum similique veniam.
              </p> -->
            </div>
            <div class="mt-4">
              <div class="-mx-2 -my-1.5 flex">
                <button
                  @click="productAddNotification = false"
                  type="button"
                  class="ml-3 rounded-md bg-green-50 px-2 py-1.5 text-sm font-medium text-green-800 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 focus:ring-offset-green-50"
                >
                  Dismiss
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-if="productUpdateNotification" class="px-20 py-10">
      <!-- This example requires Tailwind CSS v2.0+ -->
      <div class="rounded-md bg-green-50 p-4">
        <div class="flex">
          <div class="flex-shrink-0">
            <!-- Heroicon name: mini/check-circle -->
            <svg
              class="h-5 w-5 text-green-400"
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 20 20"
              fill="currentColor"
              aria-hidden="true"
            >
              <path
                fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                clip-rule="evenodd"
              />
            </svg>
          </div>
          <div class="ml-3">
            <h3 class="text-sm font-medium text-green-800">
              Product Updated Successfully
            </h3>
            <div class="mt-2 text-sm text-green-700">
              <!-- <p>
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquid pariatur,
                ipsum similique veniam.
              </p> -->
            </div>
            <div class="mt-4">
              <div class="-mx-2 -my-1.5 flex">
                <button
                  @click="productUpdateNotification = false"
                  type="button"
                  class="ml-3 rounded-md bg-green-50 px-2 py-1.5 text-sm font-medium text-green-600 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 focus:ring-offset-green-50"
                >
                  Dismiss
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div
      v-if="uploadProductModal"
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
            class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
          >
            <form @submit="uploadFile" enctype="multipart/form-data">

              <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                  <div
                    class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10"
                  >
                    <i class="fas fa-upload text-green-500"></i>
                  </div>
                  <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h3
                      class="mb-10 text-lg font-medium leading-6 text-gray-900"
                      id="modal-title"
                    >
                      Upload Products
                    </h3>
                    <div class="mb-10">
                      <div class="flex flex-row justify-center">
                        <div>
                          <!-- @click="exportData" -->
                          <button @click="setDownloaded">
                            <a
                              href="/dashboard/download_product"
                              type="button"
                              class="mr-2 inline-flex items-center rounded-sm border border-transparent bg-green-600 px-3 py-2 text-sm font-light leading-4 text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                            >
                              Download Data <i class="ml-4 fas fa-file-csv fa-2x"></i>
                            </a>
                          </button>
                        </div>
                        <div></div>
                      </div>
                    </div>
                    <div>
                      <div class="flex justify-center">
                        <div class="mb-3 w-96">
                          <input
                            @change="onFileChange"
                            id="inputFileUpload"
                            name="filename"
                            class="form-control block w-full px-3 py-1.5 text-base font-bold text-gray-700 bg-gray-50 bg-clip-padding border border-solid border-green-500 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-green-600 focus:outline-none"
                            type="file"
                          />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                <!-- @click="uploadFile" -->
                <button
                  value="upload"
                  type="submit"
                  class="inline-flex w-full justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm"
                >
                  Upload File <i class="fas fa-circle-arrow-up text-white ml-2 mt-1"></i>
                </button>
                <button
                  @click="uploadProductModal = false"
                  type="button"
                  class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white hover:bg-red-600 px-4 py-2 text-base font-medium text-gray-700 hover:text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                >
                  Cancel
                </button>
              </div>
            </form>

          </div>
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
                Products Listing
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
                    v-for="(product, index) in products"
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
                          class="capitalize hover:font-extrabold cursor-help text-xs sm:text-sm font-medium leading-none text-gray-700 mr-2"
                        >
                          {{ product.product_name }}
                        </p>
                      </div>
                    </td>
                    <td class="pl-2">
                      <div class="flex items-center">
                        <i class="fas fa-scale-unbalanced text-green-900"></i>
                        <p
                          class="text-xs sm:text-sm leading-none text-gray-600 sm:ml-1 ml-2"
                        >
                          {{ product.product_quantity }}
                        </p>
                      </div>
                    </td>
                    <td class="pl-2">
                      <div class="flex items-center">
                        <i class="fas fa-box-open text-green-900"></i>
                        <p
                          class="text-xs sm:text-sm leading-none text-gray-600 sm:ml-1 ml-2"
                        >
                          {{ product.finished_products }}
                        </p>
                      </div>
                    </td>
                    <td class="pl-2">
                      <div class="flex items-center">
                        <i class="fas fa-money-bill-trend-up text-green-900"></i>
                        <p
                          class="text-xs sm:text-sm leading-none text-gray-600 sm:ml-1 ml-2"
                        >
                          <span class="m-2 italic text-xs text-green-800">R</span> - {{ formatToCurrency(product.sales_price) }} KES
                        </p>
                      </div>
                    </td>
                    <td class="pl-2">
                      <div class="flex items-center">
                        <i class="fas fa-money-bill-trend-up text-green-900"></i>
                        <p
                          class="text-xs sm:text-sm leading-none text-gray-600 sm:ml-1 ml-2"
                        >
                          <span class="m-2 italic text-xs text-green-800">W</span> - {{ formatToCurrency(product.wholesale_price) }} KES
                        </p>
                      </div>
                    </td>
                    <td>
                      <div class="relative px-5">
                        <i
                          @click="editProduct(product)"
                          class="text-blue-600 fas fa-edit fa-md transform translate hover:scale-150 duration-300 hover:cursor-pointer mr-3"
                        ></i>
                        <i
                          class="text-red-500 fas fa-trash fa-md transform translate hover:scale-150 duration-300 hover:cursor-pointer mr-3"
                        ></i>
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
  </AppLayout>
</template>
