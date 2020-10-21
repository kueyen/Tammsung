import axios from 'axios'
import * as types from '../mutation-types'
import { sumBy, findKey } from 'lodash'

// state
export const state = {
  carts: []
}

// getters
export const getters = {
  carts: state => state.carts,
  sum: state => {
    return sumBy(state.carts, el => el.real_price * el.amount)
  },
  sumAmount: state => {
    return sumBy(state.carts, el => el.amount)
  }
}

// mutations
export const mutations = {
  [types.SET_CART](state, food) {
    let findAddList = findKey(state.carts, el => el.id == food.id)
    if (findAddList != undefined) {
      if (!(food.amount > 1)) {
        food.amount = state.carts[findAddList].amount + 1
      }
      state.carts.splice(findAddList, 1)
    }
    state.carts.push(food)
  }
}

// actions
export const actions = {
  addToCart({ commit }, food) {
    commit(types.SET_CART, food)
  },
  async addToBill({ state, rootState }) {
    const { data } = await axios.post('/api/addbill', {
      line_user_id: rootState.line_auth.user.id,
      carts: state.carts
    })
  }
}
