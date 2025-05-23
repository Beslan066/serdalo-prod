import {
    USER_REQUEST,
    USER_ERROR,
    USER_SUCCESS,
    USER_SUCCESS_ROLES,
    USER_SUCCESS_PERMISSIONS
} from '@/store/actions/user'
import { AUTH_LOGOUT } from '@/store/actions/auth'
import Vue from 'vue'

const state = {
    status: '',
    profile: {},
    roles: {},
    cacheRoles: localStorage.getItem('user-roles') ? JSON.parse(localStorage.getItem('user-roles')) || [] : [],
    permissions: {},
    cachePermissions: localStorage.getItem('user-permissions') ? JSON.parse(localStorage.getItem('user-permissions')) || [] : [],
}

const getters = {
    getProfile: state => state.profile,
    isProfileLoaded: state => !!state.profile.name,
    getRoles: state => state.roles,
    cacheRoles: state => state.cacheRoles,
    getPermissions: state => state.permissions,
    cachePermissions: state => state.cachePermissions,
}

const actions = {
    [USER_REQUEST]: ({ commit, dispatch }) => {
        commit(USER_REQUEST);
        axios.get('/api/sanctum/user')
        .then(resp => {
            commit(USER_SUCCESS, resp.data.user)
            localStorage.setItem('user-roles', JSON.stringify(resp.data.roles))
            commit(USER_SUCCESS_ROLES, resp.data.roles)
            localStorage.setItem('user-permissions', JSON.stringify(resp.data.permissions))
            commit(USER_SUCCESS_PERMISSIONS, resp.data.permissions)
        })
        .catch(() => {
            commit(USER_ERROR)
            dispatch(AUTH_LOGOUT)
        })
    }
}

const mutations = {
    [USER_REQUEST]: state => {
        state.status = 'loading'
    },
    [USER_SUCCESS]: (state, user) => {
        state.status = 'success'
        Vue.set(state, 'profile', user)
    },
    [USER_SUCCESS_ROLES]: (state, roles) => {
        Vue.set(state, 'roles', roles)
    },
    [USER_SUCCESS_PERMISSIONS]: (state, permissions) => {
        Vue.set(state, 'permissions', permissions)
    },
    [USER_ERROR]: state => {
        state.status = 'error'
    },
    [AUTH_LOGOUT]: state => {
        state.profile = {}
    }
}

export default {
    state,
    getters,
    actions,
    mutations
}