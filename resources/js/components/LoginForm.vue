<template>
    <form @submit.prevent="login">
        <div class="p-6">
            <text-input v-model="form.email" :error="errors.email" id="email" label="E-Mail" />
            <text-input v-model="form.password" :error="errors.password" id="password" label="Пароль" type="password" />
            <button-primary type="submit">Войти</button-primary>
        </div>
    </form>
</template>

<script>
    import { eventBus } from '@/app'
    import { AUTH_REQUEST } from '@/store/actions/auth'

    import TextInput from '@/components/Admin/TextInput'
    import TextareaInput from '@/components/Admin/TextareaInput'
    import ButtonPrimary from '@/components/Admin/ButtonPrimary'

    export default {
        components: {
            TextInput,
            TextareaInput,
            ButtonPrimary,
        },
        props: {
            afterLogin: {
                type: String,
                default: 'redirect',
                validator: (value) => {
                    return ['redirect', 'closeModal'].indexOf(value) !== -1
                }
            }
        },
        data: () => ({
            form: {
                email: null,
                password: null
            },
            errors: {
                email: null,
                password: null
            },
        }),
        methods: {
            login() {
                this.$store.dispatch(AUTH_REQUEST, this.form).then(() => {
                    if(this.afterLogin === 'redirect') {
                        this.$router.push('/admin')
                    }
                    if(this.afterLogin === 'closeModal') {
                        eventBus.$emit('closeLoginModal')
                    }
                });
            },
        },
    }
</script>