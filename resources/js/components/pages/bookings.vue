<script setup>
import { onMounted, reactive, ref } from "vue";

import Navbar from "@/components/partials/NavBar.vue";
import TopNav from "@/components/partials/TopNav.vue";

// Define reactive variables
let contracts = ref([]);
let accommodations = ref([]);
let travelAgents = ref([]);

let error = ref(null); // Assuming error is also reactive
let message = ref(null); // Assuming error is also reactive

// Define your bearer token
const bearerToken = localStorage.getItem("token");

let bookingForm = reactive({
  start_date: "",
  end_date: "",
  contract_id: "",
});

// Define mounted hook
onMounted(async () => {
  getAccomodation();

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

const getAccomodation = async () => {
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
};

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

const removeErrorMessage = () => {
  error.value = null;
};

const removeMessage = () => {
  message.value = null;
};

const filteredContracts = ref([]);

const filterAccomodationContracts = (accommodation) => {
  filteredContracts.value = contracts.value.filter(
    (contract) => contract.accommodation_id === accommodation.id
  );
};

const checkOutThisAccomodation = (contract) => {
  console.log(contract);
  bookingForm.start_date = contract.start_date;
  bookingForm.end_date = contract.end_date;
  bookingForm.contract_id = contract.id;
};

const createBooking = async () => {
  try {
    const response = await axios.post("/api/bookings", bookingForm, {
      headers: {
        Authorization: `Bearer ${bearerToken}`, // Include bearer token in request headers
      },
    });

    if (response.status == 200) {
      contracts.value = response.data.data;
      message.value = response.data.message;
    } else {
      error.value = response.data.message;
    }
  } catch (err) {
    error.value = err.message;
  }
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

      <div class="container mx-auto my-4">
        <!-- ====== Contact Section Start -->
        <section class="flex lg:py-[3px] z-10">
          <div class="flex flex-col lg:justify-between mx-1 my-10">
            <!-- <div>
              <label for="table-search" class="sr-only">Search</label>
              <div class="relative">
                <div
                  class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none"
                >
                  <svg
                    class="w-4 h-4 text-gray-500 dark:text-gray-400"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 20 20"
                  >
                    <path
                      stroke="currentColor"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"
                    />
                  </svg>
                </div>
                <input
                  type="text"
                  id="table-search"
                  class="block pt-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                  placeholder="Search for items"
                />
              </div>
            </div> -->

            <div class="flex shadow-md sm:rounded-lg">
              <table
                class="w-[500px] text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-900"
              >
                <thead
                  class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                >
                  <tr>
                    <th scope="col" class="px-6 py-3">name</th>
                    <th scope="col" class="px-6 py-3">desc</th>
                    <th scope="col" class="px-6 py-3">std rate</th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="accommodation in accommodations"
                    :key="accommodation.id"
                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
                  >
                    <th
                      scope="row"
                      class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                    >
                      {{ accommodation.name }}
                    </th>
                    <td class="px-6 py-4">{{ accommodation.description }}</td>
                    <td class="px-6 py-4">
                      <a
                        @click="filterAccomodationContracts(accommodation)"
                        class="cursor-pointer font-medium text-blue-600 dark:text-blue-500 hover:underline"
                        >view contracts</a
                      >
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <div class="container ml-3">
            <div class="w-full max-h-full">
              <div class="flex flex-wrap justify-center space-x-3 space-y-3">
                <div
                  v-for="contract in filteredContracts"
                  :key="contract.id"
                  class="w-[300px] h-[200px] hover:shadow-lg p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700"
                >
                  <a href="#">
                    <h5
                      class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white"
                    >
                      kes {{ contract.contract_rates }}
                    </h5>
                  </a>
                  <p class="mb-3 font-normal text-slate-700 dark:text-gray-400">
                    Start Date: ... {{ contract.start_date }}
                    <br />
                  </p>
                  <p class="mb-3 font-normal text-slate-700 dark:text-gray-400">
                    End Date: ... {{ contract.end_date }}

                    <br />
                  </p>

                  <button
                    @click="checkOutThisAccomodation(contract)"
                    type="button"
                    class="rounded inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-gradient-to-tr from-indigo-600 to-indigo-400 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                  >
                    Check-Out this contract
                    <svg
                      class="rtl:rotate-180 w-3.5 h-3.5 ms-2 animate-bounce"
                      aria-hidden="true"
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 14 10"
                    >
                      <path
                        stroke="currentColor"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M1 5h12m0 0L9 1m4 4L9 9"
                      />
                    </svg>
                  </button>
                </div>

                <div
                  v-if="filteredContracts.length == 0"
                  class="flex flex-col justify-center text-indigo-600 hover:text-indigo-900 mx-auto mt-20"
                >
                  <img
                    src="../../../assets/no_data.png"
                    alt=""
                    class="h-20 w-20 mx-auto"
                  />
                  <p class="mx-auto">Please Select An accomdation ...</p>
                  <p class="mx-auto">
                    The accomodation does not have any contracts
                  </p>
                </div>
              </div>
            </div>
          </div>

          <div
            class="overflow-y-auto items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
          >
            <div class="relative p-4 w-full max-w-md max-h-full">
              <!-- Modal content -->
              <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div
                  class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600"
                >
                  <h3
                    class="text-xl font-semibold text-gray-900 dark:text-white"
                  >
                    Enter Dates for this booking.
                  </h3>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5">
                  <form
                    @submit.prevent="createBooking()"
                    class="space-y-4"
                    action="#"
                  >
                    <div>
                      <label
                        for="start_date"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                        >Start Date</label
                      >
                      <input
                        v-model="bookingForm.start_date"
                        type="date"
                        name="date"
                        id="date"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                        required
                      />
                    </div>
                    <div>
                      <label
                        for="end_date"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                        >End Date</label
                      >
                      <input
                        v-model="bookingForm.end_date"
                        type="date"
                        name="date"
                        id="date"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                        required
                      />
                    </div>

                    <button
                      type="submit"
                      class="w-full text-white bg-gradient-to-tr from-indigo-600 to-indigo-400 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                    >
                      Checkout The Accommodation
                    </button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
  </div>
</template>
