<template>
    <div>
        <h1 class="px-8 font-semibold text-xl text-gray-800 leading-tight">
            Литературный салон / Редактирование
        </h1>
        <div class="max-w-7xl px-8 py-8">
            <div class="max-w-3xl bg-white rounded-md shadow">
                <div v-if="isloading">Loading</div>
                <div v-else>
                    <form @submit.prevent="store">
                        <div class="p-6">
                            <text-input v-model="form.title" :error="errors.title" id="title" label="Заголовок" />
                            <div class="pb-4 w-full">
                                <label class="mb-2 block">Поколение:</label>
                                <select v-model="form.generation" :class="{ 'input-error': errors.generation }" label="Поколение:" class="block w-full p-2 border rounded border-gray-400">
                                    <option :value="null" />
                                    <option v-for="(generation_title, generation_index) in generations" :key="generation_index" :value="generation_index">{{ generation_title }}</option>
                                </select>
                                <div v-if="errors.generation" class="text-red-400">{{ errors.generation | displayError }}</div>
                            </div>
                            <file-input
                                :files="files"
                                :error="errors.file_id"
                                id="file_id"
                                label="Файл:"
                                @openFilesModal="openFilesModal"
                                @deleteFile="deleteFile"
                                @deleteFiles="deleteFiles"
                                :filesConfig="{selectMode: 'single'}"
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
                                :filesConfig="{selectMode: 'single'}"
                            />
                            <text-input v-model="form.subtitle" :error="errors.subtitle" id="subtitle" label="Подзаголовок(должность)" />
                            <text-input v-model="form.dates" :error="errors.dates" id="dates" label="Года" />
                            <textarea-input v-model="form.short_description" :error="errors.short_description" id="short_description" label="Короткое описание" />
                            <tiptap v-model="form.description" :error="errors.description" id="description" label="Описание" />
                            <tiptap v-model="form.biography" :error="errors.biography" id="biography" label="Биография" />
                            <tiptap v-model="form.about_creativity" :error="errors.about_creativity" id="about_creativity" label="О творчестве" />
                        </div>
                        <repeater-input 
                            title="Публикации:" 
                            entityName="LitsalonPublication" 
                            selectEventName="LitsalonPublication" 
                            :entities="form.publications" 
                            :deleteUrl="'/api/admin/litsalon_articles/' + entity.id + '/[[entity_id]]/delete_publication'" 
                            @onUpdateEntities="onUpdatePublications"
                        />
                        <repeater-input 
                            title="Награды:" 
                            entityName="LitsalonAwards" 
                            selectEventName="LitsalonAwards" 
                            :entities="form.awards" 
                            :deleteUrl="'/api/admin/litsalon_articles/' + entity.id + '/[[entity_id]]/delete_award'" 
                            @onUpdateEntities="onUpdateAwards"
                        />
                        <div class="p-6">
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
    import ButtonActionDelete from '@/components/Admin/ButtonActionDelete'
    import RepeaterInput from '@/components/Admin/RepeaterInput'

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
            ButtonActionDelete,
            RepeaterInput,
        },
        data: () => ({
            isloading: true,
            isStoreloading: false,
            entity: {},
            form: {
                title: null,
                generation: null,
                file_id: null,
                thumb_id: null,
                subtitle: null,
                dates: null,
                short_description: null,
                description: null,
                biography: null,
                about_creativity: null,
                publications: [],
                awards: [],
            },
            errors: {},
            generations: [],
            files: [],
            thumb: null,
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
                axios.get('/api/admin/litsalon_articles/' + this.$route.params.id + '/edit')
                .then(res => {
                    this.entity = res.data.litsalon_article
                    this.form.title = res.data.litsalon_article.title
                    this.form.generation = res.data.litsalon_article.generation
                    this.form.file_id = res.data.litsalon_article.file_id
                    this.form.thumb_id = res.data.litsalon_article.thumb_id
                    this.form.subtitle = res.data.litsalon_article.subtitle
                    this.form.dates = res.data.litsalon_article.dates
                    this.form.short_description = res.data.litsalon_article.short_description
                    this.form.description = res.data.litsalon_article.description
                    this.form.biography = res.data.litsalon_article.biography
                    this.form.about_creativity = res.data.litsalon_article.about_creativity
                    this.form.publications = res.data.publications
                    this.form.awards = res.data.awards
                    
                    this.generations = res.data.generations
                    this.files = res.data.files
                    this.thumb = res.data.thumb
                })
                .finally(() => this.isloading = false)
            },
            store() {
                this.isStoreloading = true
                axios.put('/api/admin/litsalon_articles/' + this.$route.params.id, this.form)
                .then(res => {
                    this.errors = {}
                    eventBus.$emit('flash-message', 'success', res.data.success)
                    this.$router.push({name: 'admin-litsalon_articles-index'})
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
                eventBus.$emit('openFilesModal', 'default', {selectMode: 'single', fileTypes: 'image'})
            },
            deleteThumb() {
                this.thumb = null
                this.form.thumb_id = null
            },
            openFilesModalForThumb() {
                eventBus.$emit('openFilesModal', 'Thumb', {selectMode: 'single', fileTypes: 'image'})
            },
            onUpdatePublications(publications) {
                this.form.publications = publications
            },
            onUpdateAwards(awards) {
                this.form.awards = awards
            },
        },
    }
</script>