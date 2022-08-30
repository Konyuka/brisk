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
  <div>
    <nav
      class="relative w-full flex flex-wrap items-center justify-between py-3 bg-light-green-100 text-gray-500 hover:text-gray-700 focus:text-gray-700 shadow-lg"
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
          <label
            for="exampleFormControlInput1"
            class="form-label inline-block mb-2 text-gray-700"
            >Customer Name</label
          >
          <div class="mb-3 xl:w-96">
            <select
              class="form-select appearance-none block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding bg-no-repeat border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-green-600 focus:outline-none"
              aria-label="Default select example"
            >
              <option selected></option>
              <option value="1">One</option>
              <option value="2">Two</option>
              <option value="3">Three</option>
            </select>
          </div>
        </div>
        <div class="mb-3 xl:w-96">
          <label
            for="exampleFormControlInput1"
            class="form-label inline-block mb-2 text-gray-700"
            >Customer Phone</label
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
            >Customer Email</label
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
            >Customer Pin</label
          >
          <input
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
              class="form-select appearance-none block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding bg-no-repeat border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-green-600 focus:outline-none"
              aria-label="Default select example"
            >
              <option selected></option>
              <option value="1">One</option>
              <option value="2">Two</option>
              <option value="3">Three</option>
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
            >Due Date</label
          >
          <input
            type="text"
            class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-green-600 focus:outline-none"
            id="exampleFormControlInput1"
            placeholder=""
          />
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
                  <option value="2">Inclusive of Tax</option>
                  <option value="1">Exclusive of Tax</option>
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
                      <th
                        scope="col"
                        class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900"
                      >
                        Service Date
                      </th>
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
                        Tax
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
                    <tr>
                      <td
                        class="whitespace-nowrap py-2 pl-4 pr-3 text-sm text-gray-500 sm:pl-6"
                      >
                        1
                      </td>
                      <td
                        class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900"
                      >
                        <input
                          type="text"
                          class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-green-600 focus:outline-none"
                          id="exampleFormControlInput1"
                          placeholder=""
                        />
                      </td>
                      <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-900">
                        <select
                          class="form-select appearance-none block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding bg-no-repeat border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-green-600 focus:outline-none"
                          aria-label="Default select example"
                        >
                          <option selected></option>
                          <option value="1">One</option>
                          <option value="2">Two</option>
                          <option value="3">Three</option>
                        </select>
                      </td>
                      <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">
                        20 Liters
                      </td>
                      <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">
                        Multipurpose Soap
                      </td>
                      <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">1</td>
                      <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">
                        KES. 200
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
                        <a href="#" class="text-green-600 hover:text-green-900">
                          <i class="fas fa-trash"></i>
                          <span class="sr-only">, AAPS0L</span></a
                        >
                      </td>
                    </tr>

                    <!-- More transactions... -->
                  </tbody>
                </table>
                <div class="mt-10 m-10 flex flex-row justify-between">
                  <div>
                    <button
                      type="button"
                      class="inline-block px-2 py-2 bg-green-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-green-700 hover:shadow-lg focus:bg-green-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-green-800 active:shadow-lg transition duration-150 ease-in-out"
                    >
                      <i class="fas fa-plus mr-1"></i> Add Lines
                    </button>
                  </div>
                  <div class="flex flex-col font-extrabold">
                    <div>
                      <h3>
                        Subtotal: <span class="text-gray-800 text-xl">KES 100</span>
                      </h3>
                      <h3>Tax: <span class="text-gray-800 text-xl">KES 100</span></h3>
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
        class="container-fluid w-full flex flex-wrap items-center justify-between px-6"
      >
        <div class="container-fluid">
          <button
            type="button"
            class="inline-block px-6 py-2.5 bg-red-300 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-red-700 hover:shadow-lg focus:bg-green-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-green-800 active:shadow-lg transition duration-150 ease-in-out"
          >
            <i class="fas fa-broom mr-2"></i> Clear
          </button>
        </div>
        <div class="container-fluid">
          <button
            type="button"
            class="mr-2 inline-block px-6 py-2.5 bg-green-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-green-700 hover:shadow-lg focus:bg-green-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-green-800 active:shadow-lg transition duration-150 ease-in-out"
          >
            <i class="fas fa-print mr-2"></i> Print Invoice
          </button>
          <button
            type="button"
            class="inline-block px-6 py-2.5 bg-green-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-green-700 hover:shadow-lg focus:bg-green-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-green-800 active:shadow-lg transition duration-150 ease-in-out"
          >
            <i class="fas fa-check-double mr-2"></i> Register Sale
          </button>
        </div>
      </div>
    </nav>
  </div>
</template>
