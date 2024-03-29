<script setup>
import { reactive, ref } from "vue";

let error = ref("");
let message = ref("");

let form = reactive({
  name: "",
  email: "",
  password: "",
  confirm_password: "",
});

const register = async () => {
  if (form.password != form.confirm_password) {
    error.value = "Please ensure password and confirm password are the same .";
  }

  await axios.post("/api/register", form).then((response) => {
    if (response.data.success) {
      localStorage.setItem("token", response.data.data.token);
      message.value = response.data.message;
    } else {
      error.value = response.data.message;
    }
  });
};
</script>

<template>
  <div class="pt-10 flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
      <img
        class="mx-auto h-10 w-[500px] h-[250px] transform scale-x-[-1] hover:transform hover:-translate-y-2 transition ease-in duration-300"
        src="../../../assets/logo.png"
        alt="Your Company"
      />
      <h2
        class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900"
      >
        Register your account <br />
      </h2>
      <h4 class="text-center font-medium tracking-tight text-indigo-600">
        to become a travel b2b travel agent
      </h4>
    </div>

    <div
      v-if="error"
      class="sm:mx-auto sm:w-full sm:max-w-sm flex items-center justify-between mt-10 p-3 rounded-md bg-red-100 gap-4"
      role="alert"
    >
      <div>{{ error }}</div>
    </div>

    <div
      v-if="message"
      class="sm:mx-auto sm:w-full sm:max-w-sm flex items-center justify-between mt-10 p-3 rounded-md bg-green-100 gap-4"
      role="alert"
    >
      <div>{{ message }}</div>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
      <form
        @submit.prevent="register"
        class="space-y-6"
        action="#"
        method="POST"
      >
        <div>
          <label
            for="name"
            class="block text-sm font-medium leading-6 text-gray-900"
            >name</label
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
          <label
            for="email"
            class="block text-sm font-medium leading-6 text-gray-900"
            >Email address</label
          >
          <div class="mt-2">
            <input
              v-model="form.email"
              id="email"
              name="email"
              type="email"
              autocomplete="email"
              required
              class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
            />
          </div>
        </div>

        <div>
          <div class="flex items-center justify-between">
            <label
              for="password"
              class="block text-sm font-medium leading-6 text-gray-900"
              >Password</label
            >
          </div>
          <div class="mt-2">
            <input
              v-model="form.password"
              id="password"
              name="password"
              type="password"
              autocomplete="current-password"
              required
              class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
            />
          </div>
        </div>

        <div>
          <div class="flex items-center justify-between">
            <label
              for="confirm_password"
              class="block text-sm font-medium leading-6 text-gray-900"
              >Confirm_password</label
            >
          </div>
          <div class="mt-2">
            <input
              v-model="form.confirm_password"
              id="confirm_password"
              name="confirm_password"
              type="password"
              autocomplete="confirm_password"
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
            Register
          </button>
        </div>
      </form>

      <p class="mt-10 text-center text-sm text-gray-500">
        Already have an account?
        <router-link
          to="/login"
          class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500"
          >Login</router-link
        >
      </p>
    </div>
  </div>
</template>
