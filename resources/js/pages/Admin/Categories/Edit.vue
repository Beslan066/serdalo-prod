<template>
    <div>
        <h1 class="px-8 font-semibold text-xl text-gray-800 leading-tight">
            Админ/Категории/Изменить
        </h1>
        <div class="max-w-7xl px-8 py-8">
            <div class="max-w-3xl bg-white rounded-md shadow">
                <div v-if="isloading">Loading</div>
                <div v-else>
                    <form @submit.prevent="store">
                        <div class="p-6">
                            <text-input v-model="form.title" :error="errors.title" id="title" label="Заголовок" />
                            <text-input v-model="form.tag_translate" :error="errors.tag_translate" id="tag_translate" label="Перевод" />
                            <button-primary type="submit" :isLoading="isStoreloading">Обновить</button-primary>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { eventBus } from '@/app'

    import TextInput from '@/components/Admin/TextInput'
    import ButtonPrimary from '@/components/Admin/ButtonPrimary'

    export default {
        components: {
            TextInput,
            ButtonPrimary,
        },
        data: () => ({
            isloading: true,
            isStoreloading: false,
            form: {
                title: null,
                tag_translate: null,
            },
            errors: {},
        }),
        mounted() {
            this.setData()
        },
        methods: {
            setData() {
                axios.get('/api/admin/categories/' + this.$route.params.id + '/edit')
                .then(res => {
                    this.form.title = res.data.category.title
                })
                .finally(() => this.isloading = false)
            },
            store() {
                this.isStoreloading = true
                axios.put('/api/admin/categories/' + this.$route.params.id, this.form)
                .then(res => {
                    this.errors = {}
                    eventBus.$emit('flash-message', 'success', res.data.success);
                    this.$router.push({name: 'admin-categories-index'})
                })
                .catch(err => {
                    this.errors = {}
                    if(err.response.data.errors) {
                        eventBus.$emit('flash-message', 'error', 'The given data was invalid.');
                        this.errors = err.response.data.errors
                    }
                })
                .finally(() => this.isStoreloading = false)
            },
        },
    }
</script>
