<script setup>
// import { Head, Link } from "@inertiajs/inertia-vue3";
// import { ref } from "vue";
// import { Inertia } from "@inertiajs/inertia";
import AppLayout from "@/Layouts/AppLayout.vue";


defineProps({
//   canLogin: Boolean,
});

</script>

<template>
<AppLayout>


<div class="bg-gray-50">

  <div class="max-w-2xl mx-auto pt-8 pb-24 sm:pt-16 sm:px-6 lg:max-w-7xl lg:px-8">
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
              <span class="relative">Process Sale</span>
            </span>
          </h2>
        </div>
    <form>
        <div class="max-w-2xl mx-auto px-4 lg:max-w-none lg:px-0">
          

          <div class="mt-10">
            <h3 id="payment-heading" class="text-lg font-medium text-gray-900">Payment details</h3>

            <div class="mt-6 grid grid-cols-3 sm:grid-cols-4 gap-y-6 gap-x-4">
              <div class="col-span-3 sm:col-span-4">
                <label for="card-number" class="block text-sm font-medium text-gray-700">Card number</label>
                <div class="mt-1">
                  <input type="text" id="card-number" name="card-number" autocomplete="cc-number" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
              </div>

              <div class="col-span-2 sm:col-span-3">
                <label for="expiration-date" class="block text-sm font-medium text-gray-700">Expiration date (MM/YY)</label>
                <div class="mt-1">
                  <input type="text" name="expiration-date" id="expiration-date" autocomplete="cc-exp" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
              </div>

              <div>
                <label for="cvc" class="block text-sm font-medium text-gray-700">CVC</label>
                <div class="mt-1">
                  <input type="text" name="cvc" id="cvc" autocomplete="csc" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
              </div>
            </div>
          </div>

          <div class="mt-10">
            <h3 id="shipping-heading" class="text-lg font-medium text-gray-900">Shipping address</h3>

            <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-3">
              <div class="sm:col-span-3">
                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                <div class="mt-1">
                  <input type="text" id="address" name="address" autocomplete="street-address" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
              </div>

              <div>
                <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                <div class="mt-1">
                  <input type="text" id="city" name="city" autocomplete="address-level2" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
              </div>

              <div>
                <label for="region" class="block text-sm font-medium text-gray-700">State / Province</label>
                <div class="mt-1">
                  <input type="text" id="region" name="region" autocomplete="address-level1" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
              </div>

              <div>
                <label for="postal-code" class="block text-sm font-medium text-gray-700">Postal code</label>
                <div class="mt-1">
                  <input type="text" id="postal-code" name="postal-code" autocomplete="postal-code" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
              </div>
            </div>
          </div>

          <div class="mt-10">
            <h3 class="text-lg font-medium text-gray-900">Billing information</h3>

            <div class="mt-6 flex items-center">
              <input id="same-as-shipping" name="same-as-shipping" type="checkbox" checked class="h-4 w-4 border-gray-300 rounded text-indigo-600 focus:ring-indigo-500">
              <div class="ml-2">
                <label for="same-as-shipping" class="text-sm font-medium text-gray-900">Same as shipping information</label>
              </div>
            </div>
          </div>

          <div class="mt-10 flex justify-end pt-6 border-t border-gray-200">
            <button type="submit" class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 focus:ring-indigo-500">Pay now</button>
          </div>
        </div>
      </form>
  </div>  

  <main class="max-w-2xl mx-auto pt-8 pb-24 sm:pt-16 sm:px-6 lg:max-w-7xl lg:px-8">
    <div class="px-4 space-y-2 sm:px-0 sm:flex sm:items-baseline sm:justify-between sm:space-y-0">
      <div class="flex sm:items-baseline sm:space-x-4">
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:tracking-tight sm:text-3xl">Order #54879</h1>
        <a href="#" class="hidden text-sm font-medium text-indigo-600 hover:text-indigo-500 sm:block">View invoice<span aria-hidden="true"> &rarr;</span></a>
      </div>
      <p class="text-sm text-gray-600">Order placed <time datetime="2021-03-22" class="font-medium text-gray-900">March 22, 2021</time></p>
      <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 sm:hidden">View invoice<span aria-hidden="true"> &rarr;</span></a>
    </div>

    <!-- Products -->
    <section aria-labelledby="products-heading" class="mt-6">
      <h2 id="products-heading" class="sr-only">Products purchased</h2>

      <div class="space-y-8">
        <div class="bg-white border-t border-b border-gray-200 shadow-sm sm:border sm:rounded-lg">
          <div class="py-6 px-4 sm:px-6 lg:grid lg:grid-cols-12 lg:gap-x-8 lg:p-8">
            <div class="sm:flex lg:col-span-7">
              <div class="flex-shrink-0 w-full aspect-w-1 aspect-h-1 rounded-lg overflow-hidden sm:aspect-none sm:w-40 sm:h-40">
                <img src="https://tailwindui.com/img/ecommerce-images/confirmation-page-03-product-01.jpg" alt="Insulated bottle with white base and black snap lid." class="w-full h-full object-center object-cover sm:w-full sm:h-full">
              </div>

              <div class="mt-6 sm:mt-0 sm:ml-6">
                <h3 class="text-base font-medium text-gray-900">
                  <a href="#">Nomad Tumbler</a>
                </h3>
                <p class="mt-2 text-sm font-medium text-gray-900">$35.00</p>
                <p class="mt-3 text-sm text-gray-500">This durable and portable insulated tumbler will keep your beverage at the perfect temperature during your next adventure.</p>
              </div>
            </div>

            <div class="mt-6 lg:mt-0 lg:col-span-5">
              <dl class="grid grid-cols-2 gap-x-6 text-sm">
                <div>
                  <dt class="font-medium text-gray-900">Delivery address</dt>
                  <dd class="mt-3 text-gray-500">
                    <span class="block">Floyd Miles</span>
                    <span class="block">7363 Cynthia Pass</span>
                    <span class="block">Toronto, ON N3Y 4H8</span>
                  </dd>
                </div>
                <div>
                  <dt class="font-medium text-gray-900">Shipping updates</dt>
                  <dd class="mt-3 text-gray-500 space-y-3">
                    <p>f•••@example.com</p>
                    <p>1•••••••••40</p>
                    <button type="button" class="font-medium text-indigo-600 hover:text-indigo-500">Edit</button>
                  </dd>
                </div>
              </dl>
            </div>
          </div>

          <div class="border-t border-gray-200 py-6 px-4 sm:px-6 lg:p-8">
            <h4 class="sr-only">Status</h4>
            <p class="text-sm font-medium text-gray-900">Preparing to ship on <time datetime="2021-03-24">March 24, 2021</time></p>
            <div class="mt-6" aria-hidden="true">
              <div class="bg-gray-200 rounded-full overflow-hidden">
                <div class="h-2 bg-indigo-600 rounded-full" style="width: calc((1 * 2 + 1) / 8 * 100%)"></div>
              </div>
              <div class="hidden sm:grid grid-cols-4 text-sm font-medium text-gray-600 mt-6">
                <div class="text-indigo-600">Order placed</div>
                <div class="text-center text-indigo-600">Processing</div>
                <div class="text-center">Shipped</div>
                <div class="text-right">Delivered</div>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white border-t border-b border-gray-200 shadow-sm sm:border sm:rounded-lg">
          <div class="py-6 px-4 sm:px-6 lg:grid lg:grid-cols-12 lg:gap-x-8 lg:p-8">
            <div class="sm:flex lg:col-span-7">
              <div class="flex-shrink-0 w-full aspect-w-1 aspect-h-1 rounded-lg overflow-hidden sm:aspect-none sm:w-40 sm:h-40">
                <img src="https://tailwindui.com/img/ecommerce-images/confirmation-page-03-product-02.jpg" alt="Arm modeling wristwatch with black leather band, white watch face, thin watch hands, and fine time markings." class="w-full h-full object-center object-cover sm:w-full sm:h-full">
              </div>

              <div class="mt-6 sm:mt-0 sm:ml-6">
                <h3 class="text-base font-medium text-gray-900">
                  <a href="#">Minimalist Wristwatch</a>
                </h3>
                <p class="mt-2 text-sm font-medium text-gray-900">$149.00</p>
                <p class="mt-3 text-sm text-gray-500">This contemporary wristwatch has a clean, minimalist look and high quality components.</p>
              </div>
            </div>

            <div class="mt-6 lg:mt-0 lg:col-span-5">
              <dl class="grid grid-cols-2 gap-x-6 text-sm">
                <div>
                  <dt class="font-medium text-gray-900">Delivery address</dt>
                  <dd class="mt-3 text-gray-500">
                    <span class="block">Floyd Miles</span>
                    <span class="block">7363 Cynthia Pass</span>
                    <span class="block">Toronto, ON N3Y 4H8</span>
                  </dd>
                </div>
                <div>
                  <dt class="font-medium text-gray-900">Shipping updates</dt>
                  <dd class="mt-3 text-gray-500 space-y-3">
                    <p>f•••@example.com</p>
                    <p>1•••••••••40</p>
                    <button type="button" class="font-medium text-indigo-600 hover:text-indigo-500">Edit</button>
                  </dd>
                </div>
              </dl>
            </div>
          </div>

          <div class="border-t border-gray-200 py-6 px-4 sm:px-6 lg:p-8">
            <h4 class="sr-only">Status</h4>
            <p class="text-sm font-medium text-gray-900">Shipped on <time datetime="2021-03-23">March 23, 2021</time></p>
            <div class="mt-6" aria-hidden="true">
              <div class="bg-gray-200 rounded-full overflow-hidden">
                <div class="h-2 bg-indigo-600 rounded-full" style="width: calc((0 * 2 + 1) / 8 * 100%)"></div>
              </div>
              <div class="hidden sm:grid grid-cols-4 text-sm font-medium text-gray-600 mt-6">
                <div class="text-indigo-600">Order placed</div>
                <div class="text-center">Processing</div>
                <div class="text-center">Shipped</div>
                <div class="text-right">Delivered</div>
              </div>
            </div>
          </div>
        </div>

        <!-- More products... -->
      </div>
    </section>

    <!-- Billing -->
    <section aria-labelledby="summary-heading" class="mt-16">
      <h2 id="summary-heading" class="sr-only">Billing Summary</h2>

      <div class="bg-gray-100 py-6 px-4 sm:px-6 sm:rounded-lg lg:px-8 lg:py-8 lg:grid lg:grid-cols-12 lg:gap-x-8">
        <dl class="grid grid-cols-2 gap-6 text-sm sm:grid-cols-2 md:gap-x-8 lg:col-span-7">
          <div>
            <dt class="font-medium text-gray-900">Billing address</dt>
            <dd class="mt-3 text-gray-500">
              <span class="block">Floyd Miles</span>
              <span class="block">7363 Cynthia Pass</span>
              <span class="block">Toronto, ON N3Y 4H8</span>
            </dd>
          </div>
          <div>
            <dt class="font-medium text-gray-900">Payment information</dt>
            <dd class="-ml-4 -mt-1 flex flex-wrap">
              <div class="ml-4 mt-4 flex-shrink-0">
                <svg aria-hidden="true" width="36" height="24" viewBox="0 0 36 24" xmlns="http://www.w3.org/2000/svg" class="h-6 w-auto">
                  <rect width="36" height="24" rx="4" fill="#224DBA" />
                  <path d="M10.925 15.673H8.874l-1.538-6c-.073-.276-.228-.52-.456-.635A6.575 6.575 0 005 8.403v-.231h3.304c.456 0 .798.347.855.75l.798 4.328 2.05-5.078h1.994l-3.076 7.5zm4.216 0h-1.937L14.8 8.172h1.937l-1.595 7.5zm4.101-5.422c.057-.404.399-.635.798-.635a3.54 3.54 0 011.88.346l.342-1.615A4.808 4.808 0 0020.496 8c-1.88 0-3.248 1.039-3.248 2.481 0 1.097.969 1.673 1.653 2.02.74.346 1.025.577.968.923 0 .519-.57.75-1.139.75a4.795 4.795 0 01-1.994-.462l-.342 1.616a5.48 5.48 0 002.108.404c2.108.057 3.418-.981 3.418-2.539 0-1.962-2.678-2.077-2.678-2.942zm9.457 5.422L27.16 8.172h-1.652a.858.858 0 00-.798.577l-2.848 6.924h1.994l.398-1.096h2.45l.228 1.096h1.766zm-2.905-5.482l.57 2.827h-1.596l1.026-2.827z" fill="#fff" />
                </svg>
                <p class="sr-only">Visa</p>
              </div>
              <div class="ml-4 mt-4">
                <p class="text-gray-900">Ending with 4242</p>
                <p class="text-gray-600">Expires 02 / 24</p>
              </div>
            </dd>
          </div>
        </dl>

        <dl class="mt-8 divide-y divide-gray-200 text-sm lg:mt-0 lg:col-span-5">
          <div class="pb-4 flex items-center justify-between">
            <dt class="text-gray-600">Subtotal</dt>
            <dd class="font-medium text-gray-900">$72</dd>
          </div>
          <div class="py-4 flex items-center justify-between">
            <dt class="text-gray-600">Shipping</dt>
            <dd class="font-medium text-gray-900">$5</dd>
          </div>
          <div class="py-4 flex items-center justify-between">
            <dt class="text-gray-600">Tax</dt>
            <dd class="font-medium text-gray-900">$6.16</dd>
          </div>
          <div class="pt-4 flex items-center justify-between">
            <dt class="font-medium text-gray-900">Order total</dt>
            <dd class="font-medium text-indigo-600">$83.16</dd>
          </div>
        </dl>
      </div>
    </section>
  </main>

  <footer aria-labelledby="footer-heading" class="bg-white border-t border-gray-200">
    <h2 id="footer-heading" class="sr-only">Footer</h2>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="py-20 grid grid-cols-2 gap-8 sm:gap-y-0 sm:grid-cols-2 lg:grid-cols-4">
        <div class="grid grid-cols-1 gap-y-10 lg:col-span-2 lg:grid-cols-2 lg:gap-y-0 lg:gap-x-8">
          <div>
            <h3 class="text-sm font-medium text-gray-900">Account</h3>
            <ul role="list" class="mt-6 space-y-6">
              <li class="text-sm">
                <a href="#" class="text-gray-500 hover:text-gray-600"> Manage Account </a>
              </li>

              <li class="text-sm">
                <a href="#" class="text-gray-500 hover:text-gray-600"> Saved Items </a>
              </li>

              <li class="text-sm">
                <a href="#" class="text-gray-500 hover:text-gray-600"> Orders </a>
              </li>

              <li class="text-sm">
                <a href="#" class="text-gray-500 hover:text-gray-600"> Redeem Gift card </a>
              </li>
            </ul>
          </div>
          <div>
            <h3 class="text-sm font-medium text-gray-900">Service</h3>
            <ul role="list" class="mt-6 space-y-6">
              <li class="text-sm">
                <a href="#" class="text-gray-500 hover:text-gray-600"> Shipping &amp; Returns </a>
              </li>

              <li class="text-sm">
                <a href="#" class="text-gray-500 hover:text-gray-600"> Warranty </a>
              </li>

              <li class="text-sm">
                <a href="#" class="text-gray-500 hover:text-gray-600"> FAQ </a>
              </li>

              <li class="text-sm">
                <a href="#" class="text-gray-500 hover:text-gray-600"> Find a store </a>
              </li>

              <li class="text-sm">
                <a href="#" class="text-gray-500 hover:text-gray-600"> Get in touch </a>
              </li>
            </ul>
          </div>
        </div>
        <div class="grid grid-cols-1 gap-y-10 lg:col-span-2 lg:grid-cols-2 lg:gap-y-0 lg:gap-x-8">
          <div>
            <h3 class="text-sm font-medium text-gray-900">Company</h3>
            <ul role="list" class="mt-6 space-y-6">
              <li class="text-sm">
                <a href="#" class="text-gray-500 hover:text-gray-600"> Who we are </a>
              </li>

              <li class="text-sm">
                <a href="#" class="text-gray-500 hover:text-gray-600"> Press </a>
              </li>

              <li class="text-sm">
                <a href="#" class="text-gray-500 hover:text-gray-600"> Careers </a>
              </li>

              <li class="text-sm">
                <a href="#" class="text-gray-500 hover:text-gray-600"> Terms &amp; Conditions </a>
              </li>

              <li class="text-sm">
                <a href="#" class="text-gray-500 hover:text-gray-600"> Privacy </a>
              </li>
            </ul>
          </div>
          <div>
            <h3 class="text-sm font-medium text-gray-900">Connect</h3>
            <ul role="list" class="mt-6 space-y-6">
              <li class="text-sm">
                <a href="#" class="text-gray-500 hover:text-gray-600"> Instagram </a>
              </li>

              <li class="text-sm">
                <a href="#" class="text-gray-500 hover:text-gray-600"> Pinterest </a>
              </li>

              <li class="text-sm">
                <a href="#" class="text-gray-500 hover:text-gray-600"> Twitter </a>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <div class="border-t border-gray-100 py-10 sm:flex sm:items-center sm:justify-between">
        <div class="flex items-center justify-center text-sm text-gray-500">
          <p>Shipping to Canada ($CAD)</p>
          <p class="ml-3 border-l border-gray-200 pl-3">English</p>
        </div>
        <p class="mt-6 text-sm text-gray-500 text-center sm:mt-0">&copy; 2021 Clothing Company, Ltd.</p>
      </div>
    </div>
  </footer>
</div>
</AppLayout>   
</template>