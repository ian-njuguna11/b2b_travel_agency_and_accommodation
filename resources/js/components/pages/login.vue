<script setup>
import { reactive, ref } from "vue";
import { useRouter } from "vue-router";

import { useStore } from "vuex";

const store = useStore();

const router = useRouter();

let error = ref("");
let message = ref("");
let token = ref("");
let loading = ref(false);

let form = reactive({
  email: "",
  password: "",
});

const login = async () => {
  loading.value = true;
  console.log("store.getters >>>>>>>>>>>>>>>>>>");
  console.log(store.getters.getToken);

  await axios.post("/api/login", form).then((response) => {
    console.log(">>>>>>>>>>>response", response.data.message);
    if (response.data.success === true) {
      localStorage.setItem("token", response.data.data.token);
      store.dispatch("auth/setToken", response.data.data.token);
      message.value = response.data.message;

      loading.value = false;

      setTimeout(() => {
        router.push({
          path: "/dashboard",
        });
      }, 1000);
    } else if (response.data.success === false) {
      console.log("action failed");
      error.value = response.data.message;
      loading.value = false;
    }
  });
};
</script>

<template>
  <div class="pt-10 flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
      <img
        class="h-10 w-[500px] h-[250px] hover:transform hover:-translate-y-2 transition ease-in duration-300"
        src="../../../assets/logo.png"
        alt="Your Company"
      />
      <h2
        class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900"
      >
        Login to your account <br />
      </h2>
      <h4 class="text-center font-medium tracking-tight text-indigo-600">
        & start booking trips
      </h4>
      {{ token }}
    </div>

    <div
      v-if="error"
      class="sm:mx-auto sm:w-full sm:max-w-sm flex items-center justify-between mt-10 p-3 rounded-md bg-red-100 gap-4"
      role="alert"
    >
      <div class="text-sm text-slate-900 justify-center">{{ error }}</div>
    </div>

    <div
      v-if="message"
      class="sm:mx-auto sm:w-full sm:max-w-sm flex items-center justify-between mt-10 p-3 rounded-md bg-green-100 gap-4"
      role="alert"
    >
      <div>{{ message }}</div>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
      <form @submit.prevent="login" class="space-y-6" action="#" method="POST">
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
          <button
            type="submit"
            class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
            :class="loading ? 'disabled bg-indigo-500   ' : ''"
          >
            {{ loading ? "Loading ...." : "Login" }}
          </button>
        </div>
      </form>

      <p class="mt-10 text-center text-sm text-gray-500">
        Already have an account?
        <router-link
          to="/register"
          class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500"
          >Register</router-link
        >
      </p>
    </div>
  </div>
</template>

<style scoped>
@keyframes levitate {
  0% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(
      -10px
    ); /* Adjust the value for desired levitation height */
  }
  100% {
    transform: translateY(0);
  }
}
</style>
