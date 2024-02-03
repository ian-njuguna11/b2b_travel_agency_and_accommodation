// authStore.js
const state = {
  token: localStorage.getItem('token') || null
};

const mutations = {
    UPDATE_TOKEN(state, payload) {
        state.token = payload
    }
};

const actions = {
    setToken(context, payload) {
        context.commit('UPDATE_TOKEN', payload)
    },
    removeToken(context) {
        localStorage.removeItem("token");

        context.commit('UPDATE_TOKEN', null)
    }
};

const getters = {
  getToken: state => state.token,
  isLoggedIn: state => !!state.token
};

export default {
  state,
  mutations,
  actions,
  getters
};
