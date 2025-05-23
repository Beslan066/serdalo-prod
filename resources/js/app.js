/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('app', require('./App.vue').default);
//Vue.component('modal-images', require('./components/Admin/Images/ModalImages.vue').default);
Vue.component('button-primary', require('./components/Admin/ButtonPrimary.vue').default);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import store from './store';
import router from './router';

export const eventBus = new Vue();


const token = localStorage.getItem('user-token')
if (token) {
    axios.defaults.headers.common['Authorization'] = token
}

Vue.filter('displayDateTime', function (value) {
    var dateObj = new Date(value)

    var date = dateObj.toLocaleDateString()
    var time = dateObj.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})

    return date + ' ' + time
})
Vue.filter('displayError', function (value) {
    return (Array.isArray(value)) ? value.join('; ') : value
})

Vue.directive('is-my', function (el, binding, vnode) {
    if(!store.getters.isProfileLoaded) {
        return vnode.elm.hidden = true
    }
    if(!store.getters.getProfile || store.getters.getProfile.id != binding.value) {
        return vnode.elm.hidden = true
    }
    return vnode.elm.hidden = false
})
Vue.directive('has-role1111', function (el, binding, vnode) {
    if (binding.value && binding.value instanceof Array && binding.value.length > 0) {
        if( store.getters.getRoles && store.getters.getRoles[binding.value] ) {
            var has_role = false;
            const requiredRoles = binding.value;
            for(var role in store.getters.getRoles) {
                if(binding.value.includes(role)) {
                    has_role = true
                }
            }
            if(has_role) {
              return vnode.elm.hidden = false
            }
            return vnode.elm.hidden = true
        }
        return vnode.elm.hidden = true
    } else {
        throw new Error(`Roles are required! Example: v-has-role="['admin','editor']"`);
    }
})
Vue.directive('can111', function (el, binding, vnode) {
    if( store.getters.cachePermissions && store.getters.cachePermissions[binding.value] ) {
        return vnode.elm.hidden = false
    }
    if( store.getters.getPermissions && store.getters.getPermissions[binding.value] ) {
        return vnode.elm.hidden = false
    }
    if( store.getters.cachePermissions && store.getters.cachePermissions['all'] ) {
        return vnode.elm.hidden = false
    }
    if( store.getters.getPermissions && store.getters.getPermissions['all'] ) {
        return vnode.elm.hidden = false
    }
    return vnode.elm.hidden = true
})


Vue.mixin({
    methods:{
        is(value) {
            return true
            var roles = store.getters.getRoles
            if( !roles || !value ) {
                return false
            }
            var output = false
            if( value.includes('|') ) {
                value.split('|').forEach(function (item) {
                    if( roles[item.trim()] ) {
                        output = true
                    }
                })
            } else if( value.includes('&') ) {
                output = true
                value.split('&').forEach(function (item) {
                    if( !roles[item.trim()] ) {
                        output = false
                    }
                })
            } else {
                output = roles[value.trim()]
            }
            return output
        },
        can(value) {
            return true
            var permissions = store.getters.getPermissions
            if( !permissions || !value ) {
                return false
            }
            var output = false
            if( value.includes('|') ) {
                value.split('|').forEach(function (item) {
                    if( permissions[item.trim()] ) {
                        output = true
                    }
                })
            } else if( value.includes('&') ) {
                output = true
                value.split('&').forEach(function (item) {
                    if( !permissions[item.trim()] ) {
                        output = false
                    }
                })
            } else {
                output = permissions[value.trim()]
            }
            return output
        },
        getFilesConfig(value = {}) {
            var initial = {
                parentEntityIndex: 0,
                selectMode: 'multiple',
                fileTypes: null,
            }
            var confirmedFileTypes = ['image', 'video', 'document']
            if(!value) {
                return initial
            }
            if(value.selectMode && (value.selectMode === 'multiple' || value.selectMode === 'single')) {
                initial.selectMode = value.selectMode
            }
            if(value.parentEntityIndex) {
                initial.parentEntityIndex = value.parentEntityIndex
            }
            if(value.fileTypes) {
                if(typeof value.fileTypes === 'string' && confirmedFileTypes.includes(value.fileTypes)) {
                    initial.fileTypes = [value.fileTypes]
                } else {
                    initial.fileTypes = value.fileTypes
                }
            }
            return initial
        },
    }
})

const app = new Vue({
    el: '#app',
    store,
    router,
});
