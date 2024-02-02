import { createStore } from 'vuex';

// Import your modules
// import exampleModule from './modules/exampleModule'; // Import your Vuex modules

// Create store
const store = createStore({
  modules: {
    // Add your modules here
    example: userStore,
  },
  strict: process.env.NODE_ENV !== 'production', // Enable strict mode in development
});

export default store;