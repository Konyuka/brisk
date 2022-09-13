<script setup>
import { useForm, usePage, Link } from "@inertiajs/inertia-vue3";
import { ref, computed, reactive, watch, onMounted } from "vue";
import { Inertia } from "@inertiajs/inertia";
import AppLayout from "@/Layouts/AppLayout.vue";

defineProps({
  products: Array,
});

const currentUser = computed(() => usePage().props.value.user.id);

const form = useForm({
  finished_products: 250,  
  spoiled_products: 50,  
  in_delivery: 50,  
  production_code: null,
  bar_code: 717400099200,
  product_name: null,
  product_quantity: "200G",
  stock_quantity: 300,
  production_price: null,
  sales_price: 300,
  product_description: null,
  added_by: currentUser,
});

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
  <AppLayout>
    <div
      class="modal-content border-none relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current"
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
                    ><i class="fa-solid fa-file-signature text-light-green-800 fa-lg mr-1"></i> Product name</label
                  >
                  <input
                    type="text"
                    name="first-name"
                    id="first-name"
                    v-model="form.product_name"
                    autocomplete="given-name"
                    class="lowercase mt-1 focus:ring-indigo-500 focus:border-light-green-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                  />
                </div>

                <div class="col-span-3 sm:col-span-1">
                  <label for="last-name" class="block text-sm font-medium text-gray-700"
                    ><i class="fa-solid fa-scale-unbalanced-flip text-light-green-800 fa-lg mr-1"></i> SKU (g/mg)</label
                  >
                  <input
                    type="text"
                    name="last-name"
                    id="last-name"
                    v-model="form.product_quantity"
                    autocomplete="family-name"
                    class="uppercase mt-1 focus:ring-indigo-500 focus:border-light-green-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                  />
                </div>

                <div class="col-span-3 sm:col-span-1">
                  <label for="last-name" class="block text-sm font-medium text-gray-700"
                    ><i class="fa-solid fa-signature text-light-green-800 fa-lg mr-1"></i> Product Code</label
                  >
                  <input
                    type="text"
                    name="last-name"
                    id="last-name"
                    v-model="form.production_code"
                    autocomplete="family-name"
                    class="mt-1 focus:ring-indigo-500 focus:border-light-green-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                  />
                </div>

                <div class="col-span-3 sm:col-span-1">
                  <label for="last-name" class="block text-sm font-medium text-gray-700"
                    ><i class="fa-solid fa-barcode text-light-green-800 fa-lg mr-1"></i> Bar Code</label
                  >
                  <input
                    type="number"
                    name="last-name"
                    id="last-name"
                    v-model="form.bar_code"
                    autocomplete="family-name"
                    class="mt-1 focus:ring-indigo-500 focus:border-light-green-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                  />
                </div>

                <div class="col-span-3 sm:col-span-1">
                  <label for="last-name" class="block text-sm font-medium text-gray-700"
                    ><i class="fa-solid fa-money-bill-trend-up text-light-green-800 fa-lg mr-1"></i> Sales Price (ksh.)</label
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

                <div class="col-span-3 sm:col-span-2">
                  <label for="last-name" class="block text-sm font-medium text-gray-700"
                    ><i class="fas fa-square-check text-light-green-800 fa-lg mr-1"></i> Finished Products</label
                  >
                  <input
                    type="number"
                    name="last-name"
                    id="last-name"
                    v-model="form.finished_products"
                    autocomplete="family-name"
                    class="mt-1 focus:ring-indigo-500 focus:border-light-green-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                  />
                </div>

                <div class="col-span-3 sm:col-span-2">
                  <label for="last-name" class="block text-sm font-medium text-gray-700"
                    ><i class="fas fa-square-caret-right text-light-green-800 fa-lg mr-1"></i> In Delivery</label
                  >
                  <input
                    type="number"
                    name="last-name"
                    id="last-name"
                    v-model="form.in_delivery"
                    autocomplete="family-name"
                    class="mt-1 focus:ring-indigo-500 focus:border-light-green-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                  />
                </div>

                <div class="col-span-3 sm:col-span-2">
                  <label for="last-name" class="block text-sm font-medium text-gray-700"
                    ><i class="fas fa-rectangle-xmark text-light-green-800 fa-lg mr-1"></i> Spoiled Products</label
                  >
                  <input
                    type="number"
                    name="last-name"
                    id="last-name"
                    v-model="form.spoiled_products"
                    autocomplete="family-name"
                    class="mt-1 focus:ring-indigo-500 focus:border-light-green-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                  />
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
        @click="addProduct"
        class="inline-block px-6 py-2.5 bg-light-green-900 text-white font-bold text-xs leading-tight uppercase rounded shadow-md hover:bg-green-900 hover:shadow-lg focus:bg-green-900 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out ml-1"
      >
        <i class="fas fa-plus fa-xl mr-1"></i> Add Product
      </button>
      
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
                          class="hover:font-extrabold cursor-help text-xs sm:text-sm font-medium leading-none text-gray-700 mr-2"
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
                          {{ product.product_quantity }} Grams
                        </p>
                      </div>
                    </td>
                    <td class="pl-2">
                      <div class="flex items-center">
                        <i class="fas fa-boxes-packing text-green-900"></i>
                        <p
                          class="text-xs sm:text-sm leading-none text-gray-600 sm:ml-1 ml-2"
                        >
                          {{ product.stock_quantity }} items
                        </p>
                      </div>
                    </td>
                    <td class="pl-2">
                      <div class="flex items-center">
                        <i class="fas fa-money-bill-trend-up text-green-900"></i>
                        <p
                          class="text-xs sm:text-sm leading-none text-gray-600 sm:ml-1 ml-2"
                        >
                          {{ product.sales_price }} KES
                        </p>
                      </div>
                    </td>
                    <!-- <td>
                        <div class="relative px-5 pt-2">
                          <button
                            @click="saleItem(product)"
                            class="font-bold py-2 px-2 text-sm focus:outline-none leading-none text-white bg-light-green-900 hover:bg-green-900 rounded"
                          >
                            Sell <i class="ml-1 sm:ml-2 fas fa-handshake"></i>
                          </button>
                        </div>
                      </td> -->
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
