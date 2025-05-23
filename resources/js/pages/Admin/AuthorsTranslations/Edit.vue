<template>
    <div>
        <h1 class="px-8 font-semibold text-xl text-gray-800 leading-tight">
            Авторы / Перевод / Редактирование
        </h1>
        <div class="px-8 py-8">
            <div v-if="isloading">Loading</div>
            <div v-else class="flex gap-6">
                <div class="w-full max-w-3xl bg-gray-100 rounded-md p-6">
                    <div class="pb-4 w-full">
                        <span class="mb-2 block">Заголовок:</span>
                        <div class="block w-full p-2 border rounded border-gray-400">{{author.title}}</div>
                    </div>
                    <div class="pb-4 w-full">
                        <span class="mb-2 block">Описание:</span>
                        <div class="block w-full p-2 border rounded border-gray-400">{{author.description}}</div>
                    </div>
                </div>
                <div class="w-full max-w-3xl bg-white rounded-md shadow">
                    <form @submit.prevent="store">
                        <div class="p-6">
                            <text-input v-model="form.title" :error="errors.title" id="title" label="Заголовок" />
                            <text-input v-model="form.slug" :error="errors.slug" id="slug" label="URL (оставьте пустым для автоматического формирования)" />
                            <textarea-input v-model="form.description" :error="errors.description" id="description" label="Описание" />
                            <button-primary type="submit" :isLoading="isStoreloading">Сохранить</button-primary>
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
    import TextareaInput from '@/components/Admin/TextareaInput'
    import SelectInput from '@/components/Admin/SelectInput'
    import ButtonSecondary from '@/components/Admin/ButtonSecondary'
    import ButtonPrimary from '@/components/Admin/ButtonPrimary'

    export default {
        components: {
            TextInput,
            TextareaInput,
            SelectInput,
            ButtonSecondary,
            ButtonPrimary,
        },
        data: () => ({
            isloading: true,
            isStoreloading: false,
            author: {},
            form: {
                title: null,
                description: null,
                slug: null,
            },
            errors: {},
        }),
        mounted() {
            this.setData()
        },
        computed: {
          
        },
        methods: {
            setData() {
                axios.get('/api/admin/authors/authors_translations/' + this.$route.params.id + '/edit')
                .then(res => {
                    this.author = res.data.author
                    this.form.title = res.data.author_translation.title
                    this.form.description = res.data.author_translation.description
                })
                .finally(() => this.isloading = false)
            },
            store() {
                this.isStoreloading = true
                axios.put('/api/admin/authors/authors_translations/' + this.$route.params.id, this.form)
                .then(res => {
                    this.errors = {}
                    eventBus.$emit('flash-message', 'success', res.data.success)
                    this.$router.push({name: 'admin-authors-index'})
                })
                .catch(err => {
                    this.errors = {}
                    if(err.response.data.errors) {
                        eventBus.$emit('flash-message', 'error', 'The given data was invalid.')
                        this.errors = err.response.data.errors
                    }
                })
                .finally(() => this.isStoreloading = false)
            },
        },
    }
</script>