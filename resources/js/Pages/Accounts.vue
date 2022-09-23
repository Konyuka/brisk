<script setup>
import { useForm, usePage } from "@inertiajs/inertia-vue3";
import { ref, computed } from "vue";
// import { Inertia } from "@inertiajs/inertia";
import AppLayout from "@/Layouts/AppLayout.vue";
import User from "@/Pages/Auth/RegisterModal.vue";

defineProps({
  clients: Array,
  users: Array,
});

const vLowerCase = {
  updated: (el) => {
    el.value = el.value.toLowerCase();
  },
};

const currentUser = computed(() => usePage().props.value.user.id);
const currentTable = ref("usersTable");

const form = useForm({
  client_name: "",
  client_email: "micel@gmail.com",
  client_contact: "071620229",
  client_kra: "A87686HDIU5Y",
  client_address: "Ngong Road 30287",
  added_by: currentUser,
  tax_exempt: false,
});

const addUserModal = ref(false);
const addClientModal = ref(false);
const tax_exempt = ref(false);

const addClient = () => {
  // Inertia.post('/add_product', form)
  form.post("/dashboard/register_client", {
    preserveScroll: true,
    onSuccess: () => {
      form.reset();
      // addModal = false
      alert("Client Added");
    },
  });
};
const setTax = () => {
  tax_exempt.value = !tax_exempt.value;
  form.tax_exempt = !form.tax_exempt;
  // console.log(form.tax_exempt)
};
const getRole = (value) => {
  if (value == 1) {
    return "Super Admin";
  }
  else if (value == 2) {
    return "Sales Manager";
  }
  else if (value == 3) {
    return "Team Lead";
  }
  else if (value == 4) {
    return "Sales Person";
  }
};
const setCurrentMenu = (value) => {
  currentTable.value = value;
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
              <span class="relative">Users & Clients</span>
            </span>
            <!-- Summary -->
          </h2>
        </div>

        <div class="text-center">
          <button
            @click="addUserModal = true"
            class="mr-2 inline-flex items-center justify-center w-full h-12 px-6 font-medium tracking-wide text-white transition duration-200 rounded shadow-md md:w-auto bg-light-green-900 hover:bg-white hover:text-light-green-900 focus:shadow-outline focus:outline-none"
          >
            Add User
          </button>
          <button
            @click="addClientModal = true"
            class="inline-flex items-center justify-center w-full h-12 px-6 font-medium tracking-wide text-white transition duration-200 rounded shadow-md md:w-auto bg-light-green-900 hover:bg-white hover:text-light-green-900 focus:shadow-outline focus:outline-none"
          >
            Add Client
          </button>
        </div>
      </div>

      <div>
        <div class="flex items-center justify-center py-1 px-2">
          <div class="sm:px-6 w-full">
            <div class="bg-white py-4 md:py-7 px-4 md:px-8 xl:px-10">
              <div class="sm:flex items-center justify-between">
                <div class="flex items-center">
                  <button
                    @click="setCurrentMenu('usersTable')"
                    class="rounded-md focus:outline-none focus:ring-2 focus:bg-indigo-50 focus:ring-light-green-900"
                    href=" javascript:void(0)"
                  >
                    <div
                      :class="
                        currentTable == 'usersTable'
                          ? 'bg-light-green-900 text-white'
                          : 'text-gray-600'
                      "
                      class="py-2 px-8 hover:bg-light-green-900 hover:text-white font-bold rounded-md"
                    >
                      <p>User Accounts</p>
                    </div>
                  </button>
                  <button
                    @click="setCurrentMenu('clientsTable')"
                    class="rounded-md focus:outline-none focus:ring-2 focus:bg-indigo-50 focus:ring-light-green-900 ml-4 sm:ml-8"
                    href="javascript:void(0)"
                  >
                    <div
                      :class="
                        currentTable == 'clientsTable'
                          ? 'bg-light-green-900 text-white'
                          : 'text-gray-600'
                      "
                      class="py-2 px-8 hover:bg-light-green-900 hover:text-white font-bold rounded-md"
                    >
                      <p>Client Accounts</p>
                    </div>
                  </button>
                  <button
                    @click="setCurrentMenu('adminTable')"
                    class="rounded-md focus:outline-none focus:ring-2 focus:bg-indigo-50 focus:ring-light-green-900 ml-4 sm:ml-8"
                    href="javascript:void(0)"
                  >
                    <div
                      :class="
                        currentTable == 'adminTable'
                          ? 'bg-light-green-900 text-white'
                          : 'text-gray-600'
                      "
                      class="py-2 px-8 hover:bg-light-green-900 hover:text-white font-bold rounded-md"
                    >
                      <p>Admin Accounts</p>
                    </div>
                  </button>
                </div>
                <!-- <button onclick="popuphandler(true)" class="focus:ring-2 focus:ring-offset-2 focus:ring-light-green-900 mt-4 sm:mt-0 inline-flex items-start justify-start px-6 py-3 bg-light-green-900 hover:bg-white text-white font-bold hover:text-black focus:outline-none rounded">
                        <p class="text-sm font-medium leading-none">Batch Options</p>
                    </button> -->
              </div>

              <div v-if="currentTable == 'usersTable'" class="mt-7 overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                  <tbody>
                    <tr
                      v-for="(user, index) in users"
                      :key="index"
                      tabindex="0"
                      class="focus:outline-none h-16 border border-gray-100 rounded"
                    >
                      <td>
                        <div class="ml-5">
                          <div
                            class="bg-light-green-200 focus:bg-green-300 rounded-sm w-5 h-5 flex flex-shrink-0 justify-center items-center relative"
                          >
                            <i class="fa fa-caret-right"></i>
                          </div>
                        </div>
                      </td>
                      <td class="">
                        <div class="flex items-center pl-5">
                          <p
                            class="text-base font-medium leading-none text-gray-700 mr-2"
                          >
                            {{ user.name }}
                          </p>
                        </div>
                      </td>
                      <td class="pl-24">
                        <div class="flex items-center">
                          <i class="fas fa-message text-green-900"></i>
                          <p class="text-sm leading-none text-gray-600 ml-2">
                            {{ user.email }}
                          </p>
                        </div>
                      </td>
                      <td class="pl-24">
                        <div class="flex items-center">
                          <i class="fas fa-shield-halved text-green-900"></i>
                          <p class="text-sm leading-none text-gray-600 ml-2">
                            {{ getRole(user.admin) }}
                          </p>
                        </div>
                      </td>
                      <td class="pl-4">
                        <button
                          class="font-bold py-3 px-3 text-sm focus:outline-none leading-none text-white bg-light-green-900 hover:bg-green-900 rounded"
                        >
                          Update Details <i class="ml-4 fas fa-file-pen"></i>
                        </button>
                      </td>
                      <!-- <td class="pl-4">
                        <button
                          class="font-bold py-3 px-3 text-sm focus:outline-none leading-none text-white bg-light-green-900 hover:bg-green-900 rounded"
                        >
                          Payment Status <i class="ml-4 fas fa-file-pen"></i>
                        </button>
                      </td> -->
                    </tr>
                    <tr class="h-3"></tr>
                  </tbody>
                </table>
              </div>

              <div v-if="currentTable == 'clientsTable'" class="mt-7 overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                  <tbody>
                    <tr
                      v-for="(client, index) in clients"
                      :key="index"
                      tabindex="0"
                      class="focus:outline-none h-16 border border-gray-100 rounded"
                    >
                      <td>
                        <div class="ml-5">
                          <div
                            class="bg-light-green-200 focus:bg-green-300 rounded-sm w-5 h-5 flex flex-shrink-0 justify-center items-center relative"
                          >
                            <i class="fa fa-caret-right"></i>
                          </div>
                        </div>
                      </td>
                      <td class="">
                        <div class="flex items-center pl-5">
                          <p
                            class="text-base font-medium leading-none text-gray-700 mr-2"
                          >
                            {{ client.client_name }}
                          </p>
                        </div>
                      </td>
                      <td class="pl-24">
                        <div class="flex items-center">
                          <i class="fas fa-message text-green-900"></i>
                          <p class="text-sm leading-none text-gray-600 ml-2">
                            {{ client.client_email }}
                          </p>
                        </div>
                      </td>
                      <td class="pl-5">
                        <div class="flex items-center">
                          <i class="fas fa-phone text-green-900"></i>
                          <p class="text-sm leading-none text-gray-600 ml-2">
                            {{ client.client_contact }}
                          </p>
                        </div>
                      </td>
                      <td class="pl-5">
                        <div class="flex items-center">
                          <i class="fas fa-file-invoice text-green-900"></i>
                          <p class="text-sm leading-none text-gray-600 ml-2">
                            {{ client.client_kra }}
                          </p>
                        </div>
                      </td>
                      <td class="pl-5">
                        <div class="flex items-center">
                          <i class="fas fa-location text-green-900"></i>
                          <p class="text-sm leading-none text-gray-600 ml-2">
                            {{ client.client_address }}
                          </p>
                        </div>
                      </td>
                      <td class="pl-4">
                        <button
                          class="font-bold py-3 px-3 text-sm focus:outline-none leading-none text-white bg-light-green-900 hover:bg-green-900 rounded"
                        >
                          Update Details <i class="ml-4 fas fa-file-pen"></i>
                        </button>
                      </td>
                      <td class="pl-4">
                        <button
                          class="font-bold py-3 px-3 text-sm focus:outline-none leading-none text-white bg-light-green-900 hover:bg-green-900 rounded"
                        >
                          Payment Status <i class="ml-4 fas fa-file-pen"></i>
                        </button>
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

    <div v-if="addClientModal">
      <!-- This example requires Tailwind CSS v2.0+ -->
      <div
        class="relative z-10"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true"
      >
        <!--
    Background backdrop, show/hide based on modal state.

    Entering: "ease-out duration-300"
      From: "opacity-0"
      To: "opacity-100"
    Leaving: "ease-in duration-200"
      From: "opacity-100"
      To: "opacity-0"
  -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

        <div class="fixed inset-0 z-10 overflow-y-auto">
          <div
            class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0"
          >
            <!--
        Modal panel, show/hide based on modal state.

        Entering: "ease-out duration-300"
          From: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          To: "opacity-100 translate-y-0 sm:scale-100"
        Leaving: "ease-in duration-200"
          From: "opacity-100 translate-y-0 sm:scale-100"
          To: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
      -->
            <div
              class="relative transform overflow-hidden rounded-lg bg-white px-4 pt-5 pb-4 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6"
            >
              <div class="absolute top-0 right-0 hidden pt-4 pr-4 sm:block">
                <button
                  @click="addClientModal = false"
                  type="button"
                  class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                  <span class="sr-only">Close</span>
                  <!-- Heroicon name: outline/x-mark -->
                  <svg
                    class="h-6 w-6"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    aria-hidden="true"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M6 18L18 6M6 6l12 12"
                    />
                  </svg>
                </button>
              </div>
              <div class="sm:flex sm:items-start">
                <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
                <div class="md:grid md:grid-cols-2 md:gap-6">
                  <div class="md:col-span-1">
                    <!-- <h3 class="text-lg font-medium leading-6 text-gray-900">Add Product</h3>
        <p class="mt-1 text-sm text-gray-500">Key in product details</p> -->
                  </div>
                  <div class="mt-5 md:mt-0 md:col-span-2">
                    <div class="grid grid-cols-6 gap-6">
                      <div class="col-span-6 sm:col-span-6">
                        <label
                          for="first-name"
                          class="block text-sm font-medium text-gray-700"
                          >Client name</label
                        >
                        <input
                          type="text"
                          name="first-name"
                          id="first-name"
                          v-LowerCase
                          v-model="form.client_name"
                          autocomplete="given-name"
                          class="mt-1 focus:ring-indigo-500 focus:border-light-green-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                        />
                      </div>

                      <div class="col-span-3 sm:col-span-6">
                        <label
                          for="last-name"
                          class="block text-sm font-medium text-gray-700"
                          >Client Email</label
                        >
                        <input
                          type="email"
                          name="last-name"
                          id="last-name"
                          v-model="form.client_email"
                          autocomplete="family-name"
                          class="mt-1 focus:ring-indigo-500 focus:border-light-green-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                        />
                      </div>

                      <div class="col-span-3 sm:col-span-3">
                        <label
                          for="last-name"
                          class="block text-sm font-medium text-gray-700"
                          >Client Contact</label
                        >
                        <input
                          type="text"
                          name="last-name"
                          id="last-name"
                          v-model="form.client_contact"
                          autocomplete="family-name"
                          class="mt-1 focus:ring-indigo-500 focus:border-light-green-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                        />
                      </div>

                      <div class="col-span-3 sm:col-span-3">
                        <label
                          for="last-name"
                          class="block text-sm font-medium text-gray-700"
                          >Client KRA</label
                        >
                        <input
                          type="text"
                          name="last-name"
                          id="last-name"
                          v-model="form.client_kra"
                          autocomplete="family-name"
                          class="mt-1 focus:ring-indigo-500 focus:border-light-green-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                        />
                      </div>

                      <div class="col-span-6 sm:col-span-6">
                        <label
                          for="email-address"
                          class="block text-sm font-medium text-gray-700"
                          >Client Address</label
                        >
                        <textarea
                          id="about"
                          name="about"
                          rows="3"
                          v-model="form.client_address"
                          class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"
                          placeholder=""
                        ></textarea>
                        <!-- <input type="text" name="email-address" id="email-address" autocomplete="email" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"> -->
                      </div>

                      <div class="col-span-6 sm:col-span-6">
                        <!-- This example requires Tailwind CSS v2.0+ -->
                        <div class="flex items-center">
                          <!-- Enabled: "bg-indigo-600", Not Enabled: "bg-gray-200" -->
                          <button
                            @click="setTax"
                            :class="
                              tax_exempt == true ? 'bg-light-green-600' : 'bg-gray-200'
                            "
                            type="button"
                            class="bg-gray-200 relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-light-green-600 focus:ring-offset-2"
                            role="switch"
                            aria-checked="false"
                            aria-labelledby="annual-billing-label"
                          >
                            <!-- Enabled: "translate-x-5", Not Enabled: "translate-x-0" -->
                            <span
                              aria-hidden="true"
                              :class="
                                tax_exempt == true ? 'translate-x-5' : 'translate-x-0'
                              "
                              class="translate-x-5 pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                            ></span>
                          </button>
                          <span class="ml-3" id="annual-billing-label">
                            <span class="text-sm font-bold text-gray-600 w-auto"
                              >Exempted From Tax</span
                            >
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                </div>
                

              </div>
              </div>
              <div
              class="modal-footer flex flex-shrink-0 flex-wrap items-center justify-end p-4 border-t border-gray-200 rounded-b-md"
            >
              <!-- <button
                @click="addClientModal = false"
                type="button"
                class="inline-block px-6 py-2.5 bg-gray-300 text-red-300 font-bold text-xs leading-tight uppercase rounded shadow-md hover:bg-red-700 hover:text-white hover:shadow-lg focus:bg-purple-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-purple-800 active:shadow-lg transition duration-150 ease-in-out"
                data-bs-dismiss="modal"
              >
                Close
              </button> -->
              <button
                type="button"
                @click="addClient"
                class="inline-block px-6 py-2.5 bg-light-green-900 text-white font-bold text-xs leading-tight uppercase rounded shadow-md hover:bg-green-900 hover:shadow-lg focus:bg-green-900 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out ml-1"
              >
                Register Client <i class="ml-2 fa fa-person-circle-plus fa-xl"></i>
              </button>
            </div>
              <!-- <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                <button
                  @click="addUserModal=!addUserModal"
                  type="button"
                  class="inline-flex w-full justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm"
                >
                  Deactivate
                </button>
                <button
                  type="button"
                  class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:w-auto sm:text-sm"
                >
                  Cancel
                </button>
              </div> -->
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-if="addUserModal">
      <!-- This example requires Tailwind CSS v2.0+ -->
      <div
        class="relative z-10"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true"
      >
        <!--
    Background backdrop, show/hide based on modal state.

    Entering: "ease-out duration-300"
      From: "opacity-0"
      To: "opacity-100"
    Leaving: "ease-in duration-200"
      From: "opacity-100"
      To: "opacity-0"
  -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

        <div class="fixed inset-0 z-10 overflow-y-auto">
          <div
            class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0"
          >
            <!--
        Modal panel, show/hide based on modal state.

        Entering: "ease-out duration-300"
          From: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          To: "opacity-100 translate-y-0 sm:scale-100"
        Leaving: "ease-in duration-200"
          From: "opacity-100 translate-y-0 sm:scale-100"
          To: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
      -->
            <div
              class="relative transform overflow-hidden rounded-lg bg-white px-4 pt-5 pb-4 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6"
            >
              <div class="absolute top-0 right-0 hidden pt-4 pr-4 sm:block">
                <button
                  @click="addUserModal = !addUserModal"
                  type="button"
                  class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                  <span class="sr-only">Close</span>
                  <!-- Heroicon name: outline/x-mark -->
                  <svg
                    class="h-6 w-6"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    aria-hidden="true"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M6 18L18 6M6 6l12 12"
                    />
                  </svg>
                </button>
              </div>
              <div class="sm:flex sm:items-start">
                
                <div class="w-full mt-3 text-center sm:mt-0 sm:text-left">
                  <h3
                    class="text-center mt-16 text-lg font-medium leading-6 text-gray-900"
                    id="modal-title"
                  >
                    Enter User Details
                  </h3>
                  <div class="mt-2">
                    <User />
                  </div>
                </div>
              </div>
              <!-- <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                <button
                  @click="addUserModal=!addUserModal"
                  type="button"
                  class="inline-flex w-full justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm"
                >
                  Deactivate
                </button>
                <button
                  type="button"
                  class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:w-auto sm:text-sm"
                >
                  Cancel
                </button>
              </div> -->
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
