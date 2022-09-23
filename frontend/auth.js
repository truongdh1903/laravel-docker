import axios from 'axios'
import router from './router'

export default {
    namespaced: true,
    state:{
        authenticated:false,
        user:{}
    },
    getters:{
        authenticated(state){
            return state.authenticated
        },
        user(state){
            return state.user
        }
    },
    mutations:{
        SET_AUTHENTICATED (state, value) {
            state.authenticated = value
        },
        SET_USER (state, value) {
            state.user = value
        }
    },
    actions:{
        login({commit}, token){
            return window.myAxios.get('user', {
                headers: {Authorization: 'Bearer ' + token}
            }).then(res => {
                commit('SET_USER', res)
                commit('SET_AUTHENTICATED', true)
                router.push({name:'dashboard'})
            }).catch(err => {
                commit('SET_USER', {})
                commit('SET_AUTHENTICATED',false)
            })
        },
        logout({commit}){
            window.localStorage.removeItem('token')
            commit('SET_USER', {})
            commit('SET_AUTHENTICATED', false)
        }
    }
}