import Vuex from 'vuex'
import Vue from 'vue'

import Tabs from 'vue-tabs-component';

Vue.use(Tabs);
Vue.use(Vuex);

import state from './state'
import * as getters from './getters'
import * as mutations from './mutations'
import * as actions from './actions'

export const store = new Vuex.Store({
  state,
  getters,
  mutations,
  actions
})
