import {
    AUTH_REQUEST,
    AUTH_ERROR,
    AUTH_SUCCESS,
    AUTH_LOGOUT
} from '@/store/actions/auth';
import { USER_REQUEST } from '@/store/actions/user';

const state = {
    token: localStorage.getItem('user-token') || '',
    status: '',
    hasLoadedOnce: false
}

const getters = {
    isAuthenticated: state => !!state.token,
    authStatus: state => state.status
}

const actions = {
    [AUTH_REQUEST]: ({ commit, dispatch }, user) => {
        return new Promise((resolve, reject) => {
            commit(AUTH_REQUEST)
            axios.get('/sanctum/csrf-cookie').then(response => {
                axios.post('/api/sanctum/login', user)
                .then(resp => {
                    localStorage.setItem('user-token', 'Bearer ' + resp.data.token)
                    axios.defaults.headers.common['Authorization'] = 'Bearer ' + resp.data.token
                    commit(AUTH_SUCCESS, resp)
                    dispatch(USER_REQUEST)
                    resolve(resp)
                })
                .catch(err => {
                    commit(AUTH_ERROR, err)
                    localStorage.removeItem('user-token')
                    localStorage.removeItem('user-roles')
                    localStorage.removeItem('user-permissions')
                    reject(err)
                })
            })
        })
    },
    [AUTH_LOGOUT]: ({ commit }) => {
        return new Promise(resolve => {
            commit(AUTH_LOGOUT)
            localStorage.removeItem('user-token')
            localStorage.removeItem('user-roles')
            localStorage.removeItem('user-permissions')
            delete axios.defaults.headers.common['Authorization']
            resolve()
        })
    }
}

const mutations = {
    [AUTH_REQUEST]: state => {
        state.status = 'loading'
    },
    [AUTH_SUCCESS]: (state, resp) => {
        state.status = 'success'
        state.token = resp.data.token
        state.hasLoadedOnce = true
    },
    [AUTH_ERROR]: state => {
        state.status = 'error'
        state.hasLoadedOnce = true
    },
    [AUTH_LOGOUT]: state => {
        state.token = ''
    }
};

export default {
    state,
    getters,
    actions,
    mutations
}