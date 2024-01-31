<script setup>
import axios from "axios";
import { onMounted, reactive, ref } from "vue";

import Navbar from "@/components/partials/NavBar.vue";

// Define reactive variables
let user = ref([]);
let contracts = ref([]);
let accommodations = ref([]);

let error = ref(null); // Assuming error is also reactive
let message = ref(null); // Assuming error is also reactive
let isEditState = ref(false);

let travelAgents = ref([]);

let form = reactive({
  id: "",
  contract_rates: "",
  start_date: "",
  end_date: "",
  accommodation_id: "",
  travel_agent_id: "",
});

// Define your bearer token
const bearerToken = localStorage.getItem("token");

const addContract = async () => {
  await axios.post("/api/contract", form).then(async (response) => {
    if (response.data.success) {
      message.value = response.data.message;

      // fectch all new data
      try {
        const response = await axios.get("/api/contract/list", {
          headers: {
            Authorization: `Bearer ${bearerToken}`, // Include bearer token in request headers
          },
        }); // Assuming form is defined somewhere

        if (response.status == 200) {
          console.log("Contracts ................");
          console.log(response.data.data);
          contracts.value = response.data.data;
        } else {
          error.value = response.data.message;
        }
      } catch (err) {
        error.value = err.message;
      }
    } else {
      error.value = response.data.message;
    }
  });
};

const updateContract = async () => {
  await axios
    .put("api/contract/update/" + form.id, form)
    .then(async (response) => {
      if (response.data.success) {
        message.value = response.data.message;

        // fectch all new data
        try {
          const response = await axios.get("/api/contract/list", {
            headers: {
              Authorization: `Bearer ${bearerToken}`, // Include bearer token in request headers
            },
          }); // Assuming form is defined somewhere

          if (response.status == 200) {
            contracts.value = response.data.data;
          } else {
            error.value = response.data.message;
          }
        } catch (err) {
          error.value = err.message;
        }
      } else {
        error.value = response.data.message;
      }
    });
};

const removeErrorMessage = () => {
  error.value = null;
};

const removeMessage = () => {
  message.value = null;
};

const editItem = async (item) => {
  isEditState.value = true;

  form.id = item.id;
  form.contract_rates = item.contract_rates;
  form.start_date = item.start_date;
  form.end_date = item.end_date;
  form.accommodation_id = item.accommodation_id;
  form.travel_agent_id = item.travel_agent_id;
};

const deleteItem = async (item) => {
  await axios
    .delete("api/destroy/contract/" + item.id)
    .then(async (response) => {
      if (response.data.success) {
        message.value = response.data.message;
        contracts.value = response.data.data;

        console.log(contracts.value);
      } else {
        error.value = response.data.message;
        console.log(response.data.message);
      }
    });
};

// Define mounted hook
onMounted(async () => {
  try {
    const response = await axios.get("/api/accommodation/list", {
      headers: {
        Authorization: `Bearer ${bearerToken}`,
      },
    }); // Assuming form is defined somewhere

    if (response.status == 200) {
      accommodations.value = response.data.data;
    } else {
      error.value = response.data.message;
    }
  } catch (err) {
    error.value = err.message;
  }

  //travel agents
  try {
    const response = await axios.get("/api/users/list", {
      headers: {
        Authorization: `Bearer ${bearerToken}`,
      },
    }); // Assuming form is defined somewhere

    if (response.status == 200) {
      travelAgents.value = response.data.data;
    } else {
      error.value = response.data.message;
    }
  } catch (err) {
    error.value = err.message;
  }

  //contracts
  // fectch all new data
  try {
    const response = await axios.get("/api/contract/list", {
      headers: {
        Authorization: `Bearer ${bearerToken}`, // Include bearer token in request headers
      },
    }); // Assuming form is defined somewhere

    if (response.status == 200) {
      contracts.value = response.data.data;
    } else {
      error.value = response.data.message;
    }
  } catch (err) {
    error.value = err.message;
  }
});

// travelAgent
</script>

<template>
  <div class="min-h-screen bg-gray-50/50">
    <Navbar />

    <div class="p-4 xl:ml-80">
      <nav
        class="block w-full max-w-full bg-transparent text-white shadow-none rounded-xl transition-all px-0 py-1"
      >
        <div
          class="flex flex-col-reverse justify-between gap-6 md:flex-row md:items-center"
        >
          <div class="capitalize">
            <nav aria-label="breadcrumb" class="w-max">
              <ol
                class="flex flex-wrap items-center w-full bg-opacity-60 rounded-md bg-transparent p-0 transition-all"
              >
                <li
                  class="flex items-center text-blue-gray-900 antialiased font-sans text-sm font-normal leading-normal cursor-pointer transition-colors duration-300 hover:text-light-blue-500"
                >
                  <a href="#">
                    <p
                      class="block antialiased font-sans text-sm leading-normal text-blue-900 font-normal opacity-50 transition-all hover:text-blue-500 hover:opacity-100"
                    >
                      dashboard
                    </p>
                  </a>
                  <span
                    class="text-gray-500 text-sm antialiased font-sans font-normal leading-normal mx-2 pointer-events-none select-none"
                    >/</span
                  >
                </li>
                <li
                  class="flex items-center text-blue-900 antialiased font-sans text-sm font-normal leading-normal cursor-pointer transition-colors duration-300 hover:text-blue-500"
                >
                  <p
                    class="block antialiased font-sans text-sm leading-normal text-blue-gray-900 font-normal"
                  >
                    home
                  </p>
                </li>
              </ol>
            </nav>
            <h6
              class="block antialiased tracking-normal font-sans text-base font-semibold leading-relaxed text-gray-900 lowercase"
            >
              {{ $route.path }}
            </h6>
          </div>
          <div class="flex items-center">
            <div class="mr-auto md:mr-4 md:w-56">
              <div class="relative w-full min-w-[200px] h-10">
                <input
                  class="peer w-full h-full bg-transparent text-gray-700 font-sans font-normal outline outline-0 focus:outline-0 disabled:bg-blue-gray-50 disabled:border-0 transition-all placeholder-shown:border placeholder-shown:border-blue-gray-200 placeholder-shown:border-t-blue-gray-200 border focus:border-2 border-t-transparent focus:border-t-transparent text-sm px-3 py-2.5 rounded-[7px] border-blue-gray-200 focus:border-blue-500"
                  placeholder=" "
                />
                <label
                  class="flex w-full h-full select-none pointer-events-none absolute left-0 font-normal peer-placeholder-shown:text-gray-500 leading-tight peer-focus:leading-tight peer-disabled:text-transparent peer-disabled:peer-placeholder-shown:text-blue-gray-500 transition-all -top-1.5 peer-placeholder-shown:text-sm text-[11px] peer-focus:text-[11px] before:content[' '] before:block before:box-border before:w-2.5 before:h-1.5 before:mt-[6.5px] before:mr-1 peer-placeholder-shown:before:border-transparent before:rounded-tl-md before:border-t peer-focus:before:border-t-2 before:border-l peer-focus:before:border-l-2 before:pointer-events-none before:transition-all peer-disabled:before:border-transparent after:content[' '] after:block after:flex-grow after:box-border after:w-2.5 after:h-1.5 after:mt-[6.5px] after:ml-1 peer-placeholder-shown:after:border-transparent after:rounded-tr-md after:border-t peer-focus:after:border-t-2 after:border-r peer-focus:after:border-r-2 after:pointer-events-none after:transition-all peer-disabled:after:border-transparent peer-placeholder-shown:leading-[3.75] text-blue-gray-400 peer-focus:text-blue-500 before:border-blue-gray-200 peer-focus:before:border-blue-500 after:border-blue-gray-200 peer-focus:after:border-blue-500"
                  >Type here</label
                >
              </div>
            </div>
            <button
              class="relative middle none font-sans font-medium text-center uppercase transition-all disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none w-10 max-w-[40px] h-10 max-h-[40px] rounded-lg text-xs text-gray-500 hover:bg-blue-gray-500/10 active:bg-blue-gray-500/30 grid xl:hidden"
              type="button"
            >
              <span
                class="absolute top-1/2 left-1/2 transform -translate-y-1/2 -translate-x-1/2"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 24 24"
                  fill="currentColor"
                  aria-hidden="true"
                  stroke-width="3"
                  class="h-6 w-6 text-blue-gray-500"
                >
                  <path
                    fill-rule="evenodd"
                    d="M3 6.75A.75.75 0 013.75 6h16.5a.75.75 0 010 1.5H3.75A.75.75 0 013 6.75zM3 12a.75.75 0 01.75-.75h16.5a.75.75 0 010 1.5H3.75A.75.75 0 013 12zm0 5.25a.75.75 0 01.75-.75h16.5a.75.75 0 010 1.5H3.75a.75.75 0 01-.75-.75z"
                    clip-rule="evenodd"
                  ></path>
                </svg>
              </span>
            </button>
            <a href="#">
              <button
                class="middle none font-sans font-bold center uppercase transition-all disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none text-xs py-3 rounded-lg text-gray-500 hover:bg-blue-gray-500/10 active:bg-blue-gray-500/30 hidden items-center gap-1 px-4 xl:flex"
                type="button"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 24 24"
                  fill="currentColor"
                  aria-hidden="true"
                  class="h-5 w-5 text-blue-gray-500"
                >
                  <path
                    fill-rule="evenodd"
                    d="M18.685 19.097A9.723 9.723 0 0021.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 003.065 7.097A9.716 9.716 0 0012 21.75a9.716 9.716 0 006.685-2.653zm-12.54-1.285A7.486 7.486 0 0112 15a7.486 7.486 0 015.855 2.812A8.224 8.224 0 0112 20.25a8.224 8.224 0 01-5.855-2.438zM15.75 9a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"
                    clip-rule="evenodd"
                  ></path>
                </svg>
                >Sign In
              </button>
              <button
                class="relative middle none font-sans font-medium text-center uppercase transition-all disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none w-10 max-w-[40px] h-10 max-h-[40px] rounded-lg text-xs text-gray-500 hover:bg-blue-gray-500/10 active:bg-blue-gray-500/30 grid xl:hidden"
                type="button"
              >
                <span
                  class="absolute top-1/2 left-1/2 transform -translate-y-1/2 -translate-x-1/2"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="currentColor"
                    aria-hidden="true"
                    class="h-5 w-5 text-blue-gray-500"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M18.685 19.097A9.723 9.723 0 0021.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 003.065 7.097A9.716 9.716 0 0012 21.75a9.716 9.716 0 006.685-2.653zm-12.54-1.285A7.486 7.486 0 0112 15a7.486 7.486 0 015.855 2.812A8.224 8.224 0 0112 20.25a8.224 8.224 0 01-5.855-2.438zM15.75 9a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"
                      clip-rule="evenodd"
                    ></path>
                  </svg>
                </span>
              </button>
            </a>
            <button
              class="relative middle none font-sans font-medium text-center uppercase transition-all disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none w-10 max-w-[40px] h-10 max-h-[40px] rounded-lg text-xs text-gray-500 hover:bg-blue-gray-500/10 active:bg-blue-gray-500/30"
              type="button"
            >
              <span
                class="absolute top-1/2 left-1/2 transform -translate-y-1/2 -translate-x-1/2"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 24 24"
                  fill="currentColor"
                  aria-hidden="true"
                  class="h-5 w-5 text-blue-gray-500"
                >
                  <path
                    fill-rule="evenodd"
                    d="M11.078 2.25c-.917 0-1.699.663-1.85 1.567L9.05 4.889c-.02.12-.115.26-.297.348a7.493 7.493 0 00-.986.57c-.166.115-.334.126-.45.083L6.3 5.508a1.875 1.875 0 00-2.282.819l-.922 1.597a1.875 1.875 0 00.432 2.385l.84.692c.095.078.17.229.154.43a7.598 7.598 0 000 1.139c.015.2-.059.352-.153.43l-.841.692a1.875 1.875 0 00-.432 2.385l.922 1.597a1.875 1.875 0 002.282.818l1.019-.382c.115-.043.283-.031.45.082.312.214.641.405.985.57.182.088.277.228.297.35l.178 1.071c.151.904.933 1.567 1.85 1.567h1.844c.916 0 1.699-.663 1.85-1.567l.178-1.072c.02-.12.114-.26.297-.349.344-.165.673-.356.985-.57.167-.114.335-.125.45-.082l1.02.382a1.875 1.875 0 002.28-.819l.923-1.597a1.875 1.875 0 00-.432-2.385l-.84-.692c-.095-.078-.17-.229-.154-.43a7.614 7.614 0 000-1.139c-.016-.2.059-.352.153-.43l.84-.692c.708-.582.891-1.59.433-2.385l-.922-1.597a1.875 1.875 0 00-2.282-.818l-1.02.382c-.114.043-.282.031-.449-.083a7.49 7.49 0 00-.985-.57c-.183-.087-.277-.227-.297-.348l-.179-1.072a1.875 1.875 0 00-1.85-1.567h-1.843zM12 15.75a3.75 3.75 0 100-7.5 3.75 3.75 0 000 7.5z"
                    clip-rule="evenodd"
                  ></path>
                </svg>
              </span>
            </button>
            <button
              aria-expanded="false"
              aria-haspopup="menu"
              id=":r2:"
              class="relative middle none font-sans font-medium text-center uppercase transition-all disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none w-10 max-w-[40px] h-10 max-h-[40px] rounded-lg text-xs text-gray-500 hover:bg-blue-gray-500/10 active:bg-blue-gray-500/30"
              type="button"
            >
              <span
                class="absolute top-1/2 left-1/2 transform -translate-y-1/2 -translate-x-1/2"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 24 24"
                  fill="currentColor"
                  aria-hidden="true"
                  class="h-5 w-5 text-blue-gray-500"
                >
                  <path
                    fill-rule="evenodd"
                    d="M5.25 9a6.75 6.75 0 0113.5 0v.75c0 2.123.8 4.057 2.118 5.52a.75.75 0 01-.297 1.206c-1.544.57-3.16.99-4.831 1.243a3.75 3.75 0 11-7.48 0 24.585 24.585 0 01-4.831-1.244.75.75 0 01-.298-1.205A8.217 8.217 0 005.25 9.75V9zm4.502 8.9a2.25 2.25 0 104.496 0 25.057 25.057 0 01-4.496 0z"
                    clip-rule="evenodd"
                  ></path>
                </svg>
              </span>
            </button>
          </div>
        </div>
      </nav>

      <!-- flash messages -->
      <div
        v-if="error"
        class="flex items-center justify-between mt-4 p-3 rounded-md bg-red-100 gap-4"
        role="alert"
      >
        <div>{{ error ?? "something went wrong" }}</div>
        <button
          @click="removeErrorMessage"
          type="button"
          class="flex items-center justify-center rounded-full transition-all duration-300 p-1.5 hover:bg-black/10"
          data-dismiss="alert"
          aria-label="Close"
        >
          <svg
            width="22"
            height="22"
            viewBox="0 0 24 24"
            fill="none"
            stroke="#000000"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          >
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
        </button>
      </div>

      <div
        v-if="message"
        class="flex items-center justify-between mt-4 p-3 rounded-md bg-green-100 gap-4"
        role="alert"
      >
        <div>{{ message }}</div>
        <button
          @click="removeMessage"
          type="button"
          class="flex items-center justify-center rounded-full transition-all duration-300 p-1.5 hover:bg-black/10"
          data-dismiss="alert"
          aria-label="Close"
        >
          <svg
            width="22"
            height="22"
            viewBox="0 0 24 24"
            fill="none"
            stroke="#000000"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          >
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
        </button>
      </div>

      <div class="container mx-auto my-20">
        <!-- ====== Contact Section Start -->
        <section
          class="bg-white py-20 lg:py-[3px] overflow-hidden relative z-10"
        >
          <div class="container">
            <div class="flex flex-wrap lg:justify-between mx-4">
              <div class="lg:w-1/4 xl:w-3/12 px-4">
                <div class="bg-white relative rounded-lg p-8 sm:p-12">
                  <form
                    @submit.prevent="
                      `${isEditState ? updateContract() : addContract()}`
                    "
                    class="space-y-6"
                    method="POST"
                  >
                    <div>
                      <label
                        for="name"
                        class="block text-sm font-medium leading-6 text-gray-900"
                        >Contract Rates</label
                      >
                      <div class="mt-2">
                        <input
                          v-model="form.contract_rates"
                          id="contract_rates"
                          name="contract_rates"
                          type="number"
                          autocomplete="contract_rates"
                          required
                          class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                        />
                      </div>
                    </div>

                    <div>
                      <div class="flex items-center justify-between">
                        <label
                          for="description"
                          class="block text-sm font-medium leading-6 text-gray-900"
                          >Start Date</label
                        >
                      </div>
                      <div class="mt-2">
                        <input
                          v-model="form.start_date"
                          id="start_date"
                          name="start_date"
                          type="date"
                          autocomplete="start_date"
                          required
                          class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                        />
                      </div>
                    </div>

                    <div>
                      <div class="flex items-center justify-between">
                        <label
                          for="description"
                          class="block text-sm font-medium leading-6 text-gray-900"
                          >End Date</label
                        >
                      </div>
                      <div class="mt-2">
                        <input
                          v-model="form.end_date"
                          id="end_date"
                          name="end_date"
                          type="date"
                          autocomplete="end_date"
                          required
                          class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                        />
                      </div>
                    </div>

                    <div>
                      <div class="flex items-center justify-between">
                        <label
                          for="accommodation_id"
                          class="block text-sm font-medium leading-6 text-gray-900"
                          >Accommodation</label
                        >
                      </div>
                      <div class="mt-2">
                        <select
                          v-model="form.accommodation_id"
                          id="accommodation_id"
                          name="accommodation_id"
                          autocomplete="accommodation_id"
                          required
                          class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                        >
                          <option value="">
                            Please select an accommodation
                          </option>
                          <option
                            v-for="accommodation in accommodations"
                            :key="accommodation.id"
                            :value="
                              isEditState
                                ? form.accommodation_id
                                : accommodation.id
                            "
                            class="hover:bg-indigo-600"
                          >
                            {{ accommodation.name }}
                            ${{ accommodation.standard_rack_rate }}
                          </option>
                        </select>
                      </div>
                    </div>

                    <div>
                      <div class="flex items-center justify-between">
                        <label
                          for="accommodation_id"
                          class="block text-sm font-medium leading-6 text-gray-900"
                          >Travel Agent</label
                        >
                      </div>
                      <div class="mt-2">
                        <select
                          v-model="form.travel_agent_id"
                          id="travel_agent_id"
                          name="travel_agent_id"
                          autocomplete="travel_agent_id"
                          required
                          class="block w-full rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                        >
                          <option value="">Please choose a travel agent</option>
                          <option
                            v-for="travelAgent in travelAgents"
                            :key="travelAgent.id"
                            :value="
                              isEditState
                                ? form.travel_agent_id
                                : travelAgent.id
                            "
                            class="hover:bg-indigo-600"
                          >
                            {{ travelAgent.id }}
                            {{ travelAgent.name }}
                            <span class="bg-indigo-500 text-white text-sm"
                              >({{ travelAgent.email }})</span
                            >
                          </option>
                        </select>
                      </div>
                    </div>

                    <div>
                      <button
                        type="submit"
                        class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                      >
                        {{ isEditState ? "Update" : "Add" }} Contract
                      </button>
                    </div>
                  </form>
                </div>
              </div>

              <div class="w-full lg:w-3/2 xl:w-8/12 px-4">
                <div class="max-w-[570px] mb-12 lg:mb-0">
                  <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                      <tr>
                        <th
                          class="px-8 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          Contract Rates
                        </th>
                        <th
                          class="px-8 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          start_date
                        </th>
                        <th
                          class="px-8 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          end_date
                        </th>
                        <th
                          class="px-8 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          accommodation
                        </th>
                        <th
                          class="px-8 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          travel_agent
                        </th>
                        <th
                          class="px-10 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          Actions
                        </th>
                      </tr>
                    </thead>
                    <tbody
                      v-if="contracts.length > 0"
                      class="bg-white divide-y divide-gray-200"
                    >
                      <!-- Use v-for to loop over items -->
                      <tr v-for="item in contracts" :key="item.id">
                        <td class="px-8 py-4 whitespace-nowrap">
                          {{ item.contract_rates }}
                        </td>
                        <td class="px-8 py-4 whitespace-nowrap">
                          {{ item.start_date }}
                        </td>
                        <td class="px-8 py-4 whitespace-nowrap">
                          {{ item.end_date }}
                        </td>
                        <td class="px-8 py-4 whitespace-nowrap">
                          {{ item.accommodation_id }}
                        </td>
                        <td class="px-8 py-4 whitespace-nowrap">
                          {{ item.travel_agent_id }}
                        </td>
                        <td class="px-10 py-4 whitespace-nowrap">
                          <button
                            @click="editItem(item)"
                            class="text-indigo-600 hover:text-indigo-900"
                          >
                            Edit
                          </button>
                          <button
                            @click="deleteItem(item)"
                            class="ml-2 text-red-600 hover:text-red-900"
                          >
                            Delete
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <div
                    v-if="contracts.length == 0"
                    class="text-indigo-600 hover:text-indigo-900 mx-40 mt-20"
                  >
                    <img
                      src="../../../assets/no_data.png"
                      alt=""
                      class="h-25"
                    />
                    <span class="ml-20"> No contracts Avaible</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        <!-- ====== Contact Section End -->
      </div>
    </div>
  </div>
</template>
