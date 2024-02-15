<script setup>
import axios from "axios";
import { onMounted, reactive, ref } from "vue";

import Navbar from "@/components/partials/NavBar.vue";
import TopNav from "@/components/partials/TopNav.vue";

// Define reactive variables
let user = ref([]);
let contracts = ref([]);
let accommodations = ref([]);

let error = ref(null); // Assuming error is also reactive
let message = ref(null); // Assuming error is also reactive
let isEditState = ref(false);

let travelAgents = ref([]);

const form = reactive({
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
  await axios
    .post("/api/contract", form, {
      headers: {
        Authorization: `Bearer ${bearerToken}`, // Include bearer token in request headers
      },
    })
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
  console.log(form.travel_agent_id);

  await axios
    .put(`api/contract/update/${form.id}`, form, {
      headers: {
        Authorization: `Bearer ${bearerToken}`, // Include bearer token in request headers
      },
    })
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
    .get(`api/destroy/contract/${item.id}`, {
      headers: {
        Authorization: `Bearer ${bearerToken}`,
      },
    })
    .then(async (response) => {
      if (response.data.success) {
        message.value = response.data.message;
        getContracts();

        console.log(contracts.value);
      } else {
        error.value = response.data.message;
        getContracts();

        console.log(contracts.value);
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
  getContracts();
});

const getContracts = async () => {
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
};

// travelAgent
const updateTravelAgentId = (event) => {
  console.log(event.target.value);
  // Get the selected value from the event
  const selectedValue = event.target.value;
  // Update form.travel_agent_id
  form.travel_agent_id = selectedValue;
};

// accomodation;
const updateAccommodationId = (event) => {
  console.log(event.target.value);
  event.target.value;
  // Get the selected value from the event
  const selectedValue = event.target.value;
  // Update form.travel_agent_id
  form.accommodation_id = selectedValue;
};
</script>

<template>
  <div class="min-h-screen bg-gray-50/50">
    <Navbar />

    <div class="p-4 xl:ml-80">
      <!-- Top Navigation Bar -->

      <top-nav />
      <!-- End Top Navigation Bar -->

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
        <section class="py-20 lg:py-[3px] z-10">
          <div class="container">
            <div class="flex lg:justify-between mx-1">
              <div class="bg-white">
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
                          @change="updateAccommodationId($event)"
                          id="accommodation_id"
                          name="accommodation_id"
                          autocomplete="accommodation_id"
                          required
                          class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                        >
                          <option value="">
                            Please choose an accomodation
                          </option>
                          <option
                            v-for="accommodation in accommodations"
                            id="accommodation_id"
                            name="accommodation_id"
                            autocomplete="accommodation_id"
                            required
                            :key="accommodation.id"
                            :value="
                              !isEditState
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
                          for="travel_agent_id"
                          class="block text-sm font-medium leading-6 text-gray-900"
                          >Travel Agent</label
                        >
                      </div>
                      <div class="mt-2">
                        <select
                          @change="
                            isEditState ? '' : updateTravelAgentId($event)
                          "
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
                              !isEditState
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

              <div class="w-full xl:w-9/12 p-3">
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
                          {{ item.accommodation_name }}
                          <!-- {{ item.accommodation.standard_rack_rate }} -->
                        </td>
                        <td class="px-8 py-4 whitespace-nowrap">
                          {{ item.travel_agent.name }}

                          <small class="text-indigo-600"
                            >({{ item.travel_agent_email }})</small
                          >
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
