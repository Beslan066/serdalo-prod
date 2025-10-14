<template>
    <div>
        <h1 class="px-8 font-semibold text-xl text-gray-800 leading-tight">
            Выпуски газет / Создание
        </h1>
        <div class="max-w-7xl px-8 py-8">
            <div class="max-w-3xl bg-white rounded-md shadow">
                <div v-if="isloading">Loading</div>
                <div v-else>
                    <form @submit.prevent="store">
                        <div class="p-6">
                            <text-input v-model="form.title" :error="errors.title" id="title" label="Заголовок" />
                            <text-input type="date" v-model="form.release_at" :error="errors.release_at" id="release_at" label="Дата выпуска" />

                            <div class="pb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Файл газеты:</label>
                                <file-input
                                    :files="files"
                                    :error="errors.file_id"
                                    id="file_id"
                                    label="PDF файл:"
                                    @openFilesModal="openFilesModal('file')"
                                    @deleteFile="deleteFile"
                                    @deleteFiles="deleteFiles"
                                />
                            </div>

                            <div class="pb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Превью изображение:</label>
                                <file-input
                                    :files="thumbFiles"
                                    :error="errors.thumb_id"
                                    id="thumb_id"
                                    label="Изображение:"
                                    @openFilesModal="openFilesModal('thumb')"
                                    @deleteFile="deleteThumb"
                                    @deleteFiles="deleteThumbs"
                                />
                                <p class="text-xs text-gray-500 mt-1">Рекомендуемый размер: 650x650px</p>
                            </div>

                            <div class="pb-4 w-full">
                                <label for="status">Опубликовано: </label>
                                <input type="checkbox" v-model="form.status" id="status" value="1">
                                <div v-if="errors.status" class="text-red-400">{{ errors.status | displayError }}</div>
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
            release_at: null,
            status: 1,
            file_id: null,
            thumb_id: null, // Добавлено поле для превью
        },
        errors: {},
        files: [],
        thumbFiles: [], // Отдельный массив для превью изображений
        currentFileType: 'file', // Для отслеживания типа файла при выборе
    }),
    mounted() {
        this.setData()
        eventBus.$on('selectedFiles', (files) => {
            if (this.currentFileType === 'file') {
                this.files = [files[0]]
                this.form.file_id = files[0].id
            } else if (this.currentFileType === 'thumb') {
                this.thumbFiles = [files[0]]
                this.form.thumb_id = files[0].id
            }
        })
    },
    methods: {
        setData() {
            axios.get('/api/admin/newspapers/create')
                .then(res => {
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
            axios.post('/api/admin/newspapers', this.form)
                .then(res => {
                    this.errors = {}
                    eventBus.$emit('flash-message', 'success', res.data.success)
                    this.$router.push({name: 'admin-newspapers-index'})
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
        deleteThumb(index) {
            this.thumbFiles = []
            this.form.thumb_id = null
        },
        deleteThumbs() {
            this.thumbFiles = []
            this.form.thumb_id = null
        },
        openFilesModal(type) {
            this.currentFileType = type
            const options = {
                selectMode: 'single',
                // Можно добавить фильтрацию по типам файлов
                fileTypes: type === 'thumb' ? ['image'] : ['document']
            }
            eventBus.$emit('openFilesModal', 'default', options)
        },
    },
}
</script>
