<template>
    <div>
        <h1 class="px-8 font-semibold text-xl text-gray-800 leading-tight">
            Материалы / Редактирование
        </h1>
        <div class="max-w-7xl px-8 py-8">
            <div class="max-w-3xl bg-white rounded-md shadow">
                <div v-if="isloading">Loading</div>
                <div v-else>
                    <form @submit.prevent="store">
                        <div class="p-6">
                            <div v-if="entity && entity.source_id" class="pb-4 w-full">
                              <a class="text-blue-400" :href="'https://serdalo.ru/node/' + entity.source_id + '/edit'" target="_blank">Открыть редактирование оригинала</a>
                            </div>
                            <text-input v-model="form.title" :error="errors.title" id="title" label="Заголовок" />
                            <text-input v-model="form.subtitle" :error="errors.subtitle" id="subtitle" label="Номинальный заголовок" :value="form.subtitle"/>
                            <text-input v-model="form.slug" :error="errors.slug" id="slug" label="URL (оставьте пустым для автоматического формирования)" />
                            <file-input
                                :files="files"
                                :error="errors.file_id"
                                id="file_id"
                                label="Файл:"
                                @openFilesModal="openFilesModal"
                                @deleteFile="deleteFile(fileIndex)"
                                @deleteFiles="deleteFiles"
                            />
                            <file-input
                                v-if="files && files[0] && files[0].type === 'video'"
                                :files="thumb ? [thumb] : []"
                                :error="errors.thumb_id"
                                id="file_id"
                                label="Изображение-превью для видео:"
                                @openFilesModal="openFilesModalForThumb"
                                @deleteFile="deleteThumb"
                                @deleteFiles="deleteThumb"
                                uploadEventName="Thumb"
                            />

                            <div class="d-flex">
                                <text-input v-model="form.photo_title" :error="errors.photo_title" id="photo_title" label="Автор фотографии" />
                                <textarea-input class="h-96"  v-model="form.photo_description" :error="errors.photo_description"  id="photo_description" rows="10" label="Описание к фотографии" />
                            </div>
                            <div class="pb-4 w-full">
                                <label for="status">Опубликовано: </label>
                                <input type="checkbox" v-model="form.status" id="status" value="1">
                                <div v-if="errors.status" class="text-red-400">{{ errors.status | displayError }}</div>
                            </div>
                            <div class="pb-4 w-full">
                                <label for="promote">Выводить на главной: </label>
                                <input type="checkbox" v-model="form.promote" id="promote" value="1">
                                <div v-if="errors.promote" class="text-red-400">{{ errors.promote | displayError }}</div>
                            </div>
                            <div class="pb-4 w-full">
                                <label for="promote_with_file">Выводить файл на главной: </label>
                                <input type="checkbox" v-model="form.promote_with_file" id="promote_with_file" value="1">
                                <div v-if="errors.promote_with_file" class="text-red-400">{{ errors.promote_with_file | displayError }}</div>
                            </div>
                            <div class="pb-4 w-full">
                                <label for="sticky">Выводить на главной и закрепить сверху: </label>
                                <input type="checkbox" v-model="form.sticky" id="sticky" value="1">
                                <div v-if="errors.sticky" class="text-red-400">{{ errors.sticky | displayError }}</div>
                            </div>
                            <div class="pb-4 w-full">
                                <label for="popular">Популярный: </label>
                                <input type="checkbox" v-model="form.popular" id="popular" value="1">
                                <div v-if="errors.popular" class="text-red-400">{{ errors.popular | displayError }}</div>
                            </div>
                            <textarea-input v-model="form.lead" :error="errors.lead" id="lead" label="Короткое опиание" />
                            <tiptap :editor="editor" v-model="form.description" :error="errors.description" id="description" label="Описание" />
                            <div class="pb-4 w-full">
                                <label class="mb-2 block">Категория:</label>
                                <select v-model="form.category_id" :class="{ 'input-error': errors.category_id }" label="Categories" class="block w-full p-2 border rounded border-gray-400">
                                    <option :value="null" />
                                    <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.title }}</option>
                                </select>
                                <div v-if="errors.category_id" class="text-red-400">{{ errors.category_id | displayError }}</div>
                            </div>

                            <div class="pb-4 w-full">
                                <label for="published_at" class="mb-2 block">Опубликован:</label>
                                <input v-model="form.published_at"  id="published_at" type="datetime-local" class="block w-full p-2 border rounded border-gray-400" v-bind="form.published_at">
                            </div>

                            <div class="pb-4 w-full">
                                <label class="mb-2 block">Автор:</label>
                                <select v-model="form.author_id" :class="{ 'input-error': errors.author_id }" label="Authors" class="block w-full p-2 border rounded border-gray-400">
                                    <option :value="null" />
                                    <option v-for="author in authors" :key="author.id" :value="author.id">{{ author.title }}</option>
                                </select>
                                <div v-if="errors.author_id" class="text-red-400">{{ errors.author_id | displayError }}</div>
                            </div>
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
    import UploadFiles from '@/components/Admin/Files/UploadFiles'
    import FileInput from '@/components/Admin/Files/FileInput'
    import Tiptap from '@/components/Admin/Tiptap.vue'

    export default {
        components: {
            TextInput,
            TextareaInput,
            SelectInput,
            ButtonSecondary,
            ButtonPrimary,
            UploadFiles,
            FileInput,
            Tiptap,
        },
        data: () => ({
            isloading: true,
            isStoreloading: false,
            entity: {},
            form: {
                title: null,
                subtitle: null,
                slug: null,
                status: null,
                promote: null,
                promote_with_file: null,
                sticky: null,
                popular: null,
                lead: null,
                description: null,
                category_id: null,
                author_id: null,
                file_id: null,
                photo_description: null,
                photo_title: null,
                thumb_id: null,
            },
            errors: {},
            categories: [],
            authors: [],
            files: [],
            thumb: null,
            editor: null,
        }),
        mounted() {
            this.setData()
            eventBus.$on('selectedFiles', (files) => {
                this.files = [files[0]]
                this.form.file_id = files[0].id
            })
            eventBus.$on('selectedFilesThumb', (files) => {
                this.thumb = files[0]
                this.form.thumb_id = files[0].id
            })
        },
        methods: {
            setData() {
                axios.get('/api/admin/materials-inh/' + this.$route.params.id + '/edit')
                .then(res => {
                    this.entity = res.data.material
                    this.form.title = res.data.material.title
                    this.form.photo_title = res.data.material.photo_title
                    this.form.subtitle = res.data.material.subtitle
                    this.form.slug = res.data.material.slug
                    this.form.status = res.data.material.status
                    this.form.promote = res.data.material.promote
                    this.form.promote_with_file = res.data.material.promote_with_file
                    this.form.sticky = res.data.material.sticky
                    this.form.popular = res.data.material.popular
                    this.form.lead = res.data.material.lead
                    this.form.description = res.data.material.description
                    this.form.category_id = res.data.material.category_id
                    this.form.author_id = res.data.material.author_id
                    this.form.file_id = res.data.material.file_id
                    this.form.thumb_id = res.data.material.thumb_id
                    this.form.published_at = res.data.material.published_at
                    this.form.photo_description = res.data.material.photo_description

                    this.categories = res.data.categories
                    this.authors = res.data.authors
                    this.files = res.data.files
                    this.thumb = res.data.thumb
                })
                .finally(() => this.isloading = false)
            },
            store() {
                this.isStoreloading = true
                axios.put('/api/admin/materials-inh/' + this.$route.params.id, this.form)
                .then(res => {
                    this.errors = {}
                    eventBus.$emit('flash-message', 'success', res.data.success)
                    this.$router.push({name: 'admin-materials-inh-index'})
                })
                .catch(err => {
                    this.errors = {}
                    if(err.response.data.errors) {
                        eventBus.$emit('flash-message', 'error', 'The given data was invalid.')
                        this.errors = err.response.data.errors
                    }
                    if(err.response.data.error) {
                        eventBus.$emit('flash-message', 'error', err.response.data.error);
                    }
                })
                .finally(() => this.isStoreloading = false)
            },
            deleteFile(index) {
                this.files = []
                this.form.file_id = null
            },
            deleteFiles() {
                this.files = []
                this.form.file_id = null
            },
            openFilesModal() {
                eventBus.$emit('openFilesModal', 'default', {selectMode: 'single'})
            },
            deleteThumb() {
                this.thumb = null
                this.form.thumb_id = null
            },
            openFilesModalForThumb() {
                eventBus.$emit('openFilesModal', 'Thumb', {selectMode: 'single'})
            },
        },
    }
</script>
