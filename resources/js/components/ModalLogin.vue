<template>
    <div class="fixed flex w-full h-full left-0 top-0 bg-gray-500 bg-opacity-50">
        <div class="w-full h-11/12 m-8 p-4 overflow-x-auto overscroll-contain bg-gray-200">
            <div class="mb-4">
                Login modal
                <button-primary type="button" @click="close()">Close</button-primary>
            </div>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="max-w-3xl bg-white rounded-md shadow">
                    <login-form :afterLogin="'closeModal'" />
                </div>
            </div>
        </div>
    </div>    
</template>

<script>
    import { eventBus } from '@/app'

    import ButtonPrimary from '@/components/Admin/ButtonPrimary'
    import LoginForm from '@/components/LoginForm'

    export default {
        components: {
            ButtonPrimary,
            LoginForm,
        },
        props: [],
        data: () => ({

        }),
        mounted() {
            
        },
        methods: {
            close() {
                eventBus.$emit('closeLoginModal')
            }
        }
    }
</script>
