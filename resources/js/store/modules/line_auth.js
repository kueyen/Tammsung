import * as types from '../mutation-types'
import axios from 'axios'

let url = {
  registerCheck: 'https://online-campaigns.com/campaigns/2020/mea/LineBot/api/test'
}

// state
export const state = {
  user: {
    id: 123456,
    image_url: '/assets/images/user-de.jpg',
    email: 'test@gmail.com',
    detail: {}
  }
}

// getters
export const getters = {
  user: state => state.user
}

// mutations
export const mutations = {
  [types.FETCH_USER_SUCCESS](state, { user }) {
    state.user = user
  }
}

// actions
export const actions = {
  saveToken({ commit }, payload) {
    commit(types.SAVE_TOKEN, payload)
  },

  async fetchUser({ commit }, data) {
    try {
      commit(types.FETCH_USER_SUCCESS, { user: data })
    } catch (e) {
      console.log(e)
    }
  },

  async checkRegister(user_id) {
    console.log('start check regis')
    const { data } = await axios.get(`${url.registerCheck}?user_id=${user_id}`).catch(e => {
      console.log(e)
      return false
    })

    console.log(this.user.id, data)
  }
}
