import axios from 'axios'
import * as types from '../mutation-types'

// state
export const state = {
  items: [],
  show: {}
}

// getters
export const getters = {
  items: state => state.items,
  show: state => state.show
}

// mutations
export const mutations = {
  [types.SET_RES](state, data) {
    state.show = data.result
  }
}

// actions
export const actions = {
  async show({ commit }, id) {
    const { data } = await axios.get('/api/foods/' + id)
    commit(types.SET_RES, data)
  }
}
