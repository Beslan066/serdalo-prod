<template>
    <div>
        <nav class="bg-white shadow-md py-2">
            <div class="container mx-auto">
                <div class="flex flex-row">
                    <ul class="flex flex-row mr-auto">
                        <li class="py-2 pr-4">
                            <router-link to="/admin" class="nav-link">Главная</router-link>
                        </li>
                    </ul>
                    <navbar-right />
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container mx-auto">
                <router-view></router-view>
            </div>
        </main>
        <modal-login v-if="modalLoginOpen"></modal-login>
        <modal-register v-if="modalRegisterOpen"></modal-register>
    </div>
</template>

<script>
    import { eventBus } from '@/app'

    import NavbarRight from '@/components/NavbarRight'
    import ModalLogin from '@/components/ModalLogin'
    import ModalRegister from '@/components/ModalRegister'

    export default {
        components: {
            NavbarRight,
            ModalLogin,
            ModalRegister,
        },
        data: () => ({
            modalLoginOpen: false,
            modalRegisterOpen: false,
        }),
        mounted() {
            eventBus.$on('openLoginModal', () => {
                this.modalLoginOpen = true
            })
            eventBus.$on('closeLoginModal', () => {
                this.modalLoginOpen = false
            })
            eventBus.$on('openRegisterModal', () => {
                this.modalRegisterOpen = true
            })
            eventBus.$on('closeRegisterModal', () => {
                this.modalRegisterOpen = false
            })
        },
    }
</script>