<script setup>
import axios from "axios";
import { onMounted, reactive, ref } from "vue";

import Navbar from "@/components/partials/NavBar.vue";
import TopNav from "@/components/partials/TopNav.vue";

// Define reactive variables
let user = ref([]);

let error = ref(null); // Assuming error is also reactive
let message = ref(null); // Assuming error is also reactive
let isEditState = ref(false);

let form = reactive({
  id: "",
  name: "",
  description: "",
  standard_rack_rate: "",
});

let accommodations = ref([]);

// Define your bearer token
const bearerToken = localStorage.getItem("token");

const addAccommodation = async () => {
  await axios
    .post("/api/accommodations", form, {
      headers: {
        Authorization: `Bearer ${bearerToken}`, // Include bearer token in request headers
      },
    })
    .then(async (response) => {
      if (response.data.success) {
        message.value = response.data.message;

        // fectch all new data
        try {
          const response = await axios.get("/api/accommodation/list", {
            headers: {
              Authorization: `Bearer ${bearerToken}`, // Include bearer token in request headers
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
      } else {
        error.value = response.data.message;
      }
    });
};

const updateAccommodation = async () => {
  await axios
    .put("api/accommodation/update/" + form.id, form, {
      headers: {
        Authorization: `Bearer ${bearerToken}`, // Include bearer token in request headers
      },
    })
    .then(async (response) => {
      if (response.data.success) {
        message.value = response.data.message;

        // fectch all new data
        try {
          const response = await axios.get("/api/accommodation/list", {
            headers: {
              Authorization: `Bearer ${bearerToken}`, // Include bearer token in request headers
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
  form.name = item.name;
  form.description = item.description;
  form.standard_rack_rate = item.standard_rack_rate;
};

const deleteItem = async (item) => {
  await axios
    .delete("api/destroy/accommodation/" + item.id)
    .then(async (response) => {
      if (response.data.success) {
        message.value = response.data.message;

        // fectch all new data
        try {
          const response = await axios.get("/api/accommodation/list", {
            headers: {
              Authorization: `Bearer ${bearerToken}`, // Include bearer token in request headers
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
      } else {
        error.value = response.data.message;
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
});
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
        <section class="py-20 lg:py-[3px] overflow-hidden relative z-10">
          <div class="container">
            <div class="flex flex-wrap lg:justify-between mx-4">
              <div class="lg:w-1/4 xl:w-3/12 px-4">
                <div class="bg-white relative rounded-lg p-8 sm:p-12">
                  <form
                    @submit.prevent="
                      `${
                        isEditState ? updateAccommodation() : addAccommodation()
                      }`
                    "
                    class="space-y-6"
                    method="POST"
                  >
                    <div>
                      <label
                        for="name"
                        class="block text-sm font-medium leading-6 text-gray-900"
                        >Name</label
                      >
                      <div class="mt-2">
                        <input
                          v-model="form.name"
                          id="name"
                          name="name"
                          type="name"
                          autocomplete="name"
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
                          >Description</label
                        >
                      </div>
                      <div class="mt-2">
                        <input
                          v-model="form.description"
                          id="description"
                          name="description"
                          type="textarea"
                          autocomplete="description"
                          required
                          class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                        />
                      </div>
                    </div>

                    <div>
                      <div class="flex items-center justify-between">
                        <label
                          for="standard_rack_rate"
                          class="block text-sm font-medium leading-6 text-gray-900"
                          >Standard Rack Rate</label
                        >
                      </div>
                      <div class="mt-2">
                        <input
                          v-model="form.standard_rack_rate"
                          id="standard_rack_rate"
                          name="standard_rack_rate"
                          type="number"
                          autocomplete="standard_rack_rate"
                          required
                          class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                        />
                      </div>
                    </div>

                    <div>
                      <button
                        type="submit"
                        class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                      >
                        {{ isEditState ? "Update" : "Add" }} accommodation
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
                          Name
                        </th>
                        <th
                          class="px-8 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          Description
                        </th>
                        <th
                          class="px-8 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          Rack Rate
                        </th>
                        <th
                          class="px-10 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          Actions
                        </th>
                      </tr>
                    </thead>
                    <tbody
                      v-if="accommodations.length > 0"
                      class="bg-white divide-y divide-gray-200"
                    >
                      <!-- Use v-for to loop over items -->
                      <tr v-for="item in accommodations" :key="item.id">
                        <td class="px-8 py-4 whitespace-nowrap">
                          {{ item.name }}
                        </td>
                        <td class="px-8 py-4 whitespace-nowrap">
                          {{ item.description }}
                        </td>
                        <td class="px-8 py-4 whitespace-nowrap">
                          {{ item.standard_rack_rate }}
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
                    v-if="accommodations.length == 0"
                    class="text-indigo-600 hover:text-indigo-900 mx-40 mt-20"
                  >
                    <img
                      src="../../../assets/no_data.png"
                      alt=""
                      class="h-25"
                    />
                    No Accommodations Avaible
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
