<script setup>
import axios from "axios";
import { onMounted, ref } from "vue";

import Navbar from "@/components/partials/NavBar.vue";
import TopNav from "@/components/partials/TopNav.vue";

// Define reactive variables
let user = ref([]);
let bookings = ref([]);
let error = ref(null); // Assuming error is also reactive

// Define your bearer token
const bearerToken = localStorage.getItem("token");

// Define mounted hook
onMounted(async () => {
  //   console.log(">>>>>>>>>>>>>>>>> " + bearerToken);

  try {
    const response = await axios.get("/api/user", {
      headers: {
        Authorization: `Bearer ${bearerToken}`, // Include bearer token in request headers
      },
    }); // Assuming form is defined somewhere

    if (response.status == 200) {
      user.value = response.data;
    } else {
      error.value = response.data.message;
    }
  } catch (err) {
    error.value = err.message;
  }

  //bookings
  try {
    const response = await axios.get("/api/booking/list", {
      headers: {
        Authorization: `Bearer ${bearerToken}`, // Include bearer token in request headers
      },
    }); // Assuming form is defined somewhere

    if (response.status == 200) {
      bookings.value = response.data;

      console.log(">>>>>>>>>>>>>>>>>>>>>>>" + user);
    } else {
      error.value = response.data.message;
    }
  } catch (err) {
    error.value = err.message;

    console.log(">>>>>>>>>>>>>>>>>>>>>>>" + err.message);
  }
});
</script>

<template>
  <div class="min-h-screen bg-gray-50/50">
    <Navbar />

    <div class="p-4 xl:ml-80">
      <TopNav />

      <div class="container mx-auto my-32">
        <div>
          <div
            class="bg-white relative shadow rounded-lg w-5/6 md:w-5/6 lg:w-4/6 xl:w-3/6 mx-auto"
          >
            <div class="mt-16">
              <h1
                class="font-bold text-center text-3xl text-gray-900 uppercase"
              >
                {{ user.name }}
              </h1>
              <p class="text-center text-sm text-gray-400 font-medium"></p>
              <p>
                <span> </span>
              </p>
              <div class="my-5 px-6">
                <a
                  href="#"
                  class="text-gray-200 block rounded-lg text-center font-medium leading-6 px-6 py-3 bg-gray-900 hover:bg-black hover:text-white"
                >
                  <span class="font-bold"> {{ user.email }}</span></a
                >
              </div>

              <div class="w-full">
                <h3
                  class="flex justify-center mx-auto font-medium text-gray-900"
                >
                  Recent Bookings
                </h3>
                <div
                  class="flex-col space-x-8 justify-end m-5 p-3 w-full text-sm"
                >
                  <table
                    class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
                  >
                    <thead
                      class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                    >
                      <tr>
                        <th scope="col" class="px-6 py-3">Accommodation</th>
                        <th scope="col" class="px-6 py-3">Start Date</th>
                        <th scope="col" class="px-6 py-3">End Date</th>
                      </tr>
                    </thead>

                    <tbody v-for="booking in bookings.data" :key="booking.id">
                      <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
                      >
                        <th
                          scope="row"
                          class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white"
                        >
                          <img
                            class="w-10 h-10 rounded-full"
                            src="../../../assets/logo.png"
                            alt="Jese image"
                          />
                          <div class="ps-3">
                            <div class="text-base font-semibold">
                              {{ booking.accomodation.name }}
                            </div>
                            <div class="font-normal text-gray-500">
                              kes {{ booking.contract.contract_rates }}
                            </div>
                          </div>
                        </th>
                        <td class="px-6 py-4">
                          <div class="flex items-center">
                            <div
                              class="h-2.5 w-2.5 rounded-full bg-green-500 me-2"
                            ></div>
                            {{ booking.start_date }}
                          </div>
                        </td>
                        <td class="px-6 py-4">
                          <div class="flex items-center">
                            <div
                              class="h-2.5 w-2.5 rounded-full bg-red-500 me-2"
                            ></div>
                            {{ booking.end_date }}
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
