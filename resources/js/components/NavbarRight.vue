<template>
    <ul class="flex flex-row ml-auto">
        <li class="py-2 px-4" v-if="isProfileLoaded">
            <router-link to="/admin" class="nav-link"><i class="fas fa-user"></i> {{ name }}</router-link>
        </li>
        <li class="py-2 px-4" v-if="isAuthenticated">
            <a href="#" class="nav-link" @click.prevent="logout"><i class="fas fa-sign-out-alt"></i> Выйти</a>
        </li>
        <li class="py-2 px-4" v-if="!isAuthenticated && !authLoading">
            <router-link to="/sanctum/login" class="nav-link"><i class="fas fa-sign-in-alt"></i> Вход</router-link>
        </li>
        <!--<li class="py-2 px-4" v-if="!isAuthenticated && !authLoading">
            <router-link to="/sanctum/register" class="nav-link">Регистрация</router-link>
        </li>-->
        <li class="py-2 pl-4">
            <a href="/" class="nav-link" target="_blank"><i class="fas fa-share"></i> Перейти на сайт</a>
        </li>
    </ul>
</template>

<script>
    import { mapGetters, mapState } from 'vuex'
    import { AUTH_LOGOUT } from '@/store/actions/auth'

    export default {
        computed: {
            ...mapGetters(["getProfile", "isAuthenticated", "isProfileLoaded"]),
            ...mapState({
                authLoading: state => state.auth.status === "loading",
                name: state => state.user.profile.name
            })
        },
        methods: {
            logout: function() {
                this.$store.dispatch(AUTH_LOGOUT).then(() => this.$router.push("/sanctum/login"))
            }
        },
    }
</script>