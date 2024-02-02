const state = {
  // Define your state here
  count: 0,
};

const mutations = {
//   // Define your mutations here
//   increment(state) {
//     state.count++;
//   },
//   decrement(state) {
//     state.count--;
//   },
};

const actions = {
  // Define your actions here
  incrementAsync({ commit }) {
    setTimeout(() => {
      commit('increment');
    }, 1000);
  },
};

const getters = {
  // Define your getters here
//   getCount: state => state.count,
};

const exampleModule = {
  state,
  mutations,
  actions,
  getters,
};

export default userStore;
