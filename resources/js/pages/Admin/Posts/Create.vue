<template>
    <div>
        <h1 class="px-8 font-semibold text-xl text-gray-800 leading-tight">
            Новости / Создание
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
                               <textarea-input class="h-96"  v-model="form.photo_description" :error="errors.photo_description" id="photo_description" rows="10" label="Описание к фотографии" />
                           </div>

                            <div class="pb-4 w-full">
                                <label for="status">Опубликовано: </label>
                                <input type="checkbox" v-model="form.status" id="status" value="1">
                                <div v-if="errors.status" class="text-red-400">{{ errors.status | displayError }}</div>
                            </div>

                            <div class="pb-4 w-full">
                                <label for="topNews">Топ-новость: </label>
                                <input type="checkbox" v-model="form.topNews" id="topNews" value="0">
                                <div v-if="errors.topNews" class="text-red-400">{{ errors.topNews | displayError }}</div>
                            </div>
                            <!--<div class="pb-4 w-full">
                                <label for="promote">Выводить на главной: </label>
                                <input type="checkbox" v-model="form.promote" id="promote" value="1">
                                <div v-if="errors.promote" class="text-red-400">{{ errors.promote | displayError }}</div>
                            </div>-->
                            <div class="pb-4 w-full">
                                <label for="promote_with_file">Выводить файл на главной: </label>
                                <input type="checkbox" v-model="form.promote_with_file" id="promote_with_file" value="1">
                                <div v-if="errors.promote_with_file" class="text-red-400">{{ errors.promote_with_file | displayError }}</div>
                            </div>
                            <textarea-input class="h-96"  v-model="form.lead" :error="errors.lead" id="lead" rows="10" label="Короткое опиание" />
                            <tiptap :editor="editor" v-model="form.description" :error="errors.description"  id="description" label="Описание" />
                            <multiselect-input
                                v-model="form.post_tags"
                                :options="tags"
                                label="Теги"
                            />

                            <div class="pb-4 w-full">
                                <label for="published_at" class="mb-2 block">Опубликован:</label>
                                <input v-model="form.published_at" id="published_at" type="datetime-local" class=" block w-full p-2 border rounded border-gray-400">
                            </div>

                            <div class="pb-4 w-full">
                                <label class="mb-2 block">Автор:</label>
                                <select v-model="form.author_id" :class="{ 'input-error': errors.author_id }" label="Authors" class="block w-full p-2 border rounded border-gray-400">
                                    <option :value="null" />
                                    <option v-for="author in authors" :key="author.id" :value="author.id">{{ author.title }}</option>
                                </select>
                                <div v-if="errors.author_id" class="text-red-400">{{ errors.author_id | displayError }}</div>
                            </div>


                            <div>
                                <textarea-input class="h-96"  v-model="form.comment" :error="errors.comment" id="lead" rows="10" label="Комментарий эксперта" />
                                <div class="pb-4 w-full">
                                    <label class="mb-2 block">Выберите экспрета:</label>
                                    <select v-model="form.expert_id" :class="{ 'input-error': errors.expert_id }" label="Experts" class="block w-full p-2 border rounded border-gray-400">
                                        <option :value="null" />
                                        <option v-for="expert in experts" :key="expert.id" :value="expert.id">{{ expert.title }}</option>
                                    </select>
                                    <div v-if="errors.expert_id" class="text-red-400">{{ errors.expert_id | displayError }}</div>
                                </div>
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
    import MultiselectInput from '@/components/Admin/MultiselectInput.vue'

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
            MultiselectInput,
        },
        data: () => ({
            isloading: true,
            isStoreloading: false,
            entity: {},
            form: {
                title: null,
                slug: null,
                status: 1,
                topNews: 0,
                promote: 1,
                promote_with_file: 0,
                lead: null,
                description: null,
                comment: null,
                post_tags: [],
                author_id: null,
                expert_id: null,
                file_id: null,
                thumb_id: null,
                published_at: null,
                photo_title: null,
                photo_description: null,
            },
            errors: {},
            tags: [],
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
                axios.get('/api/admin/posts/create')
                .then(res => {
                    this.tags = res.data.tags
                    this.authors = res.data.authors
                    this.experts = res.data.experts
                })
                .catch(err => {
                    if(err.response.data.error) {
                        eventBus.$emit('flash-message', 'error', err.response.data.error);
                    }
                })
                .finally(() => this.isloading = false)
            },
            store() {
                this.isStoreloading = true
                axios.post('/api/admin/posts', this.form)
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
