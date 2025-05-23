<template>
    <div>
        <h1 class="px-8 font-semibold text-xl text-gray-800 leading-tight">
            Видеостудия / Редактирование
        </h1>
        <div class="max-w-7xl px-8 py-8">
            <div class="max-w-3xl bg-white rounded-md shadow">
                <div v-if="isloading">Loading</div>
                <div v-else>
                    <form @submit.prevent="store">
                        <div class="p-6">
                            <text-input v-model="form.title" :error="errors.title" id="title" label="Заголовок" />
                            <file-input
                                :files="files"
                                :error="errors.file_id"
                                id="file_id"
                                label="Файл:"
                                @openFilesModal="openFilesModal"
                                @deleteFile="deleteFile(fileIndex)"
                                @deleteFiles="deleteFiles"
                                :filesConfig="{selectMode: 'single', fileTypes: 'video'}"
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
                                :filesConfig="{selectMode: 'single', fileTypes: 'image'}"
                            />
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
                file_id: null,
                thumb_id: null,
            },
            errors: {},
            files: [],
            thumb: null,
        }),
        mounted() {
            this.setData()
            eventBus.$on('selectedFiles', (files) => {
                console.log('selectedFiles')
                console.log(files)
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
                axios.get('/api/admin/video_articles/' + this.$route.params.id + '/edit')
                .then(res => {
                    this.entity = res.data.video_article
                    this.form.title = res.data.video_article.title
                    this.form.file_id = res.data.video_article.file_id
                    this.form.thumb_id = res.data.video_article.thumb_id
                    
                    this.files = res.data.files
                    this.thumb = res.data.thumb
                })
                .finally(() => this.isloading = false)
            },
            store() {
                this.isStoreloading = true
                axios.put('/api/admin/video_articles/' + this.$route.params.id, this.form)
                .then(res => {
                    this.errors = {}
                    eventBus.$emit('flash-message', 'success', res.data.success)
                    this.$router.push({name: 'admin-video_articles-index'})
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
                eventBus.$emit('openFilesModal', 'default', {selectMode: 'single', fileTypes: 'video'})
            },
            deleteThumb() {
                this.thumb = null
                this.form.thumb_id = null
            },
            openFilesModalForThumb() {
                eventBus.$emit('openFilesModal', 'Thumb', {selectMode: 'single', fileTypes: 'image'})
            },
        },
    }
</script>