<template>
    <form @submit.prevent="store">
        <div class="p-6">
            <text-input v-model="form.name" :error="errors.name" id="name" label="Name" />
            <text-input v-model="form.email" :error="errors.email" id="email" label="E-Mail" />
            <text-input v-model="form.password" :error="errors.password" id="password" label="Password" type="password" />
            <button-primary type="submit">Register</button-primary>
        </div>
    </form>
</template>

<script>
    import TextInput from '@/components/Admin/TextInput'
    import TextareaInput from '@/components/Admin/TextareaInput'
    import ButtonPrimary from '@/components/Admin/ButtonPrimary'

    export default {
        components: {
            TextInput,
            TextareaInput,
            ButtonPrimary,
        },
        data: () => ({
            form: {
                name: null,
                email: null,
                password: null
            },
            errors: {
                name: null,
                email: null,
                password: null
            },
        }),
        methods: {
            store() {
                axios.post('/api/sanctum/register', this.form)
                .then(res => {
                    console.log(res.data)
                    if(res.data.errors) {
                        this.errors = res.data.errors
                    } else {
                        Storage.set('auth.token', response.data.token)
                        this.$router.push({name: 'index'})
                    }
                })
            },
        },
    }
</script>