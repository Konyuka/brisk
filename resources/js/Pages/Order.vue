<script setup>
// import { Head, Link } from "@inertiajs/inertia-vue3";
import { computed } from "vue";
// import { Inertia } from "@inertiajs/inertia";
import AppLayout from "@/Layouts/AppLayout.vue";
import moment from 'moment'


const props = defineProps({
  product: Object,
  client: Object,
  sale: Object,
});

const invoiceTime = computed(() => {
    return moment(props.sale.created_at).format('MMMM Do YYYY, h:mm:ss a');
});
const subTotal = computed(() => {
  return props.sale.sale_amount * props.sale.product_quantity
});
const grandTotal = computed(() => {
  return Math.ceil(subTotal.value * 1.16)
});
const tax = computed(() => {
  return grandTotal.value - subTotal.value;
});



</script>

<template>
<AppLayout>


<div class="bg-gray-50">

  <main class="max-w-2xl mx-auto pt-8 pb-24 sm:pt-16 sm:px-6 lg:max-w-7xl lg:px-8">
    <div class="px-4 space-y-2 sm:px-0 sm:flex sm:items-baseline sm:justify-between sm:space-y-0">
      <div class="flex sm:items-baseline sm:space-x-4">
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:tracking-tight sm:text-3xl">Invoice #{{ sale.invoice_number}}</h1>
        <a href="#" class="hidden text-sm font-medium text-indigo-600 hover:text-indigo-500 sm:block">Print invoice<span aria-hidden="true"> &rarr;</span></a>
      </div>
      <!-- <p class="text-sm text-gray-600">Invoice Generated on  <span class="font-medium text-gray-900">{{ invoiceTime }}</span> </p> -->
      <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 sm:hidden">Print invoice<span aria-hidden="true"> &rarr;</span></a>
    </div>

    <!-- Products -->
    <section aria-labelledby="products-heading" class="mt-6">
      <h2 id="products-heading" class="sr-only">Products purchased</h2>

      <div class="space-y-8">
        <div class="bg-white border-t border-b border-gray-200 shadow-sm sm:border sm:rounded-lg">
          <div class="py-6 px-4 sm:px-6 lg:grid lg:grid-cols-12 lg:gap-x-8 lg:p-8">
            <div class="sm:flex lg:col-span-7">
              <div class="flex-shrink-0 w-full aspect-w-1 aspect-h-1 rounded-lg overflow-hidden sm:aspect-none sm:w-40 sm:h-40">
                <img src="/img/receipt.jpg" alt="Insulated bottle with white base and black snap lid." class="w-full h-full object-center object-cover sm:w-full sm:h-full">
              </div>

              <div class="mt-6 sm:mt-0 sm:ml-6">
                <h3 class="text-base font-medium text-gray-900">
                  <a href="#">{{ product.product_name }}</a>
                </h3>
                <p class="mt-3 text-sm text-gray-500">{{ product.product_description }}</p>
                <p class="mt-2 text-sm text-gray-500">{{ product.product_quantity }} MG</p>
                <p class="mt-2 text-sm text-gray-500">KES {{ sale.sale_amount }}</p>
                <p class="mt-2 text-sm text-gray-500">{{ sale.product_quantity }} Item(s)</p>
              </div>
            </div>

            <div class="mt-6 lg:mt-0 lg:col-span-5">
              <dl class="grid grid-cols-2 gap-x-6 text-sm">
                <div>
                  <dt class="font-medium text-gray-900">Client Details</dt>
                  <dd class="mt-3 text-gray-500">
                    <span class="block">{{ client.client_email }}</span>
                    <span class="block">{{ client.client_name }}</span>
                    <span class="block">{{ client.client_kra }}</span>
                    <span class="block">{{ client.client_contact }}</span>
                  </dd>

                <button type="button" class="mt-3 font-medium text-green-900 hover:text-gray-300"><i class="fas fa-pencil mr-2"></i> Edit Details
                </button>

                </div>
                <div>
                  <dt class="font-medium text-gray-900">Shipping Address</dt>
                  <dd class="mt-3 text-gray-500 space-y-3">
                    <p>{{ client.client_address }}</p>
                    <button type="button" class="font-medium text-green-900 hover:text-gray-300"><i class="fas fa-pencil mr-2"></i> Edit Address
                    </button>
                  </dd>
                </div>
              </dl>
            </div>
          </div>
        </div>

      </div>
    </section>

    <!-- Billing -->
    <section aria-labelledby="summary-heading" class="mt-16">
      <h2 id="summary-heading" class="sr-only">Billing Summary</h2>

      <div class="bg-gray-100 py-6 px-4 sm:px-6 sm:rounded-lg lg:px-8 lg:py-8 lg:grid lg:grid-cols-12 lg:gap-x-8">
        <dl class="grid grid-cols-1 gap-6 text-sm sm:grid-cols-2 md:gap-x-8 lg:col-span-7">
          
          <div>
            <dt class="font-medium text-gray-900">Payment Method</dt>
            <dd class="-ml-4 -mt-1 flex flex-wrap">
            <select id="country" name="country" autocomplete="country-name" class="ml-3 mt-4 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-green-900 focus:border-green-900 sm:text-sm">
                <option>Cash</option>
                <option>Mpesa</option>
                <option>Visa</option>
             </select>

              <!-- <div class="ml-4 mt-4 flex-shrink-0">
                <svg aria-hidden="true" width="36" height="24" viewBox="0 0 36 24" xmlns="http://www.w3.org/2000/svg" class="h-6 w-auto">
                  <rect width="36" height="24" rx="4" fill="#224DBA" />
                  <path d="M10.925 15.673H8.874l-1.538-6c-.073-.276-.228-.52-.456-.635A6.575 6.575 0 005 8.403v-.231h3.304c.456 0 .798.347.855.75l.798 4.328 2.05-5.078h1.994l-3.076 7.5zm4.216 0h-1.937L14.8 8.172h1.937l-1.595 7.5zm4.101-5.422c.057-.404.399-.635.798-.635a3.54 3.54 0 011.88.346l.342-1.615A4.808 4.808 0 0020.496 8c-1.88 0-3.248 1.039-3.248 2.481 0 1.097.969 1.673 1.653 2.02.74.346 1.025.577.968.923 0 .519-.57.75-1.139.75a4.795 4.795 0 01-1.994-.462l-.342 1.616a5.48 5.48 0 002.108.404c2.108.057 3.418-.981 3.418-2.539 0-1.962-2.678-2.077-2.678-2.942zm9.457 5.422L27.16 8.172h-1.652a.858.858 0 00-.798.577l-2.848 6.924h1.994l.398-1.096h2.45l.228 1.096h1.766zm-2.905-5.482l.57 2.827h-1.596l1.026-2.827z" fill="#fff" />
                </svg>
                <p class="sr-only">Visa</p>
              </div>

              <div class="ml-4 mt-4">
                <p class="text-gray-900">Ending with 4242</p>
                <p class="text-gray-600">Expires 02 / 24</p>
              </div> -->

            </dd>
          </div>
        </dl>

        <dl class="mt-8 divide-y divide-gray-200 text-sm lg:mt-0 lg:col-span-5">
          <div class="pb-4 flex items-center justify-between">
            <dt class="text-gray-600">Subtotal</dt>
            <dd class="font-medium text-gray-900">KES {{ subTotal }}</dd>
          </div>
          <div class="py-4 flex items-center justify-between">
            <dt class="text-gray-600">Tax(16%)</dt>
            <dd class="font-medium text-gray-900">KES {{ tax }}</dd>
          </div>
          <div class="pt-4 flex items-center justify-between">
            <dt class="font-medium text-gray-900">Grand total</dt>
            <dd class="font-extrabold text-green-900">KES {{ grandTotal }}</dd>
          </div>
        </dl>
      </div>
      <div class="border-t border-gray-200 py-6 px-4 sm:px-6 lg:p-8">
            <h4 class="sr-only">Status</h4>
            <p class="text-sm font-medium text-gray-900">Sale Timestamp at <span class="font-bold">{{ invoiceTime }}</span> </p>
            <div class="mt-6" aria-hidden="true">
              <div class="bg-gray-200 rounded-full overflow-hidden">
                <div class="h-2 bg-green-900 rounded-full" style="width: calc((1 * 6 + 1) / 8 * 100%)"></div>
              </div>
              <div class="hidden sm:grid grid-cols-4 text-sm font-medium text-gray-600 mt-6">
                <div class="text-light-green-900">Order placed</div>
                <div class="text-center text-light-green-900">Check Stock</div>
                <div class="text-center">Invoice Processing</div>
                <div class="text-right mt-4">
                    <button class="font-extrabold text-green-900 text-2xl underline">Finish Sale <i class="ml-2 fas fa-clipboard-check"></i> </button>
                </div>
              </div>
              <div class="mt-7 flex justify-center">
                <button class="font-extrabold text-green-900 text-2xl underline">Finish Sale <i class="ml-2 fas fa-clipboard-check"></i> </button>
              </div>
            </div>
          </div>
    </section>
  </main>
  
</div>
</AppLayout>   
</template>