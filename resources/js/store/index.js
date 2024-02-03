import { createStore } from 'vuex';

// Import your modules
import authStore from './authStore';

// Create store
const store = createStore({
    modules: {
        auth: authStore
    }

});

export default store;