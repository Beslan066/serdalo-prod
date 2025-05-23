<template>
    <div>
        <h1 class="px-8 font-semibold text-xl text-gray-800 leading-tight">
            Переводы новостей / Создание
        </h1>
        <div class="px-8 py-8">
            <div v-if="isloading">Loading</div>
            <div v-else class="flex gap-6">
                <div class="w-full max-w-3xl bg-gray-100 rounded-md p-6">
                    <div class="pb-4 w-full">
                        <span class="mb-2 block">Заголовок:</span>
                        <div class="block w-full p-2 border rounded border-gray-400">{{post.title}}</div>
                    </div>
                    <div class="pb-4 w-full">
                        <span class="mb-2 block">URL:</span>
                        <div class="block w-full p-2 border rounded border-gray-400">{{post.slug}}</div>
                    </div>

                    <div class="pb-4 w-full">
                        <span class="mb-2 block">Короткое опиание:</span>
                        <div class="block w-full p-2 border rounded border-gray-400">{{post.lead}}</div>
                    </div>
                    <div class="pb-4 w-full">
                        <span class="mb-2 block">Описание:</span>
                        <div class="block w-full p-2 border rounded border-gray-400">{{post.description}}</div>
                    </div>

                </div>
                <div class="w-full max-w-3xl bg-white rounded-md shadow">
                    <form @submit.prevent="store">
                        <div class="p-6">
                            <text-input v-model="form.title" :error="errors.title" id="title" label="Заголовок" />
                            <div class="d-flex">
                                <text-input v-model="form.photo_title" :error="errors.image_title" id="photo_title" label="Автор фотографии" />
                                <textarea-input class="h-96"  v-model="form.image_description" :error="errors.photo_description" id="photo_description" rows="10" label="Описание к фотографии" />
                            </div>

                            <text-input v-model="form.slug" :error="errors.slug" id="slug" label="URL (оставьте пустым для автоматического формирования)" />
                            <textarea-input v-model="form.lead" :error="errors.lead" id="lead" label="Короткое опиание" />
                            <tiptap :editor="editor" v-model="form.description" :error="errors.description"  id="description" label="Описание" />
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
    import Tiptap from "@/components/Admin/Tiptap.vue";

    export default {
        components: {
            Tiptap,
            TextInput,
            TextareaInput,
            SelectInput,
            ButtonSecondary,
            ButtonPrimary,
        },
        data: () => ({
            isloading: true,
            isStoreloading: false,
            post: {},
            form: {
                title: null,
                slug: null,
                lead: null,
                description: null,
                image_title: null,
                image_description: null,
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
                axios.get('/api/admin/posts/' + this.$route.params.post_id + '/posts_translations/create')
                .then(res => {
                    this.post = res.data.post
                })
                .finally(() => this.isloading = false)
            },
            store() {
                this.isStoreloading = true
                axios.post('/api/admin/posts/' + this.$route.params.post_id + '/posts_translations', this.form)
                .then(res => {
                    this.errors = {}
                    eventBus.$emit('flash-message', 'success', res.data.success)
                    this.$router.push({name: 'admin-posts-index'})
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
