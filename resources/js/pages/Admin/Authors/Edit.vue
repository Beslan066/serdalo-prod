<template>
    <div>
        <h1 class="px-8 font-semibold text-xl text-gray-800 leading-tight">
            Авторы / Редактирование
        </h1>
        <div class="max-w-7xl px-8 py-8">
            <div class="max-w-3xl bg-white rounded-md shadow">
                <div v-if="isloading">Loading</div>
                <div v-else>
                    <form @submit.prevent="store">
                        <div class="p-6">
                            <div class="mb-6">
                                <label class="mb-2 block">Файл:</label>
                                <div class="grid grid-cols-4 gap-2 mb-4" v-if="files && files.length > 0">
                                    <div v-for="(file, fileIndex) in files" :key="fileIndex" :data-index="fileIndex">
                                        <div
                                            class="flex justify-end h-20 border border-gray-300 rounded-md rounded-b-none bg-center bg-no-repeat"
                                            :style="{
                                                'background-image': 'url(\'' + file.full_preview_path + '\')',
                                                'background-size': file.show_icon ? '50%' : 'cover'
                                            }"
                                        >
                                            <a :href="file.full_path" class="flex justify-center items-center w-6 h-6 rounded-md opacity-60 hover:opacity-100 cursor-pointer" target="_blank">
                                                <i class="fas fa-share p-2 text-blue-400 hover:text-blue-600"></i>
                                            </a>
                                            <div class="flex justify-center items-center w-6 h-6 rounded-md opacity-60 hover:opacity-100 cursor-pointer">
                                                <i class="fas fa-times-circle p-2 text-red-400 hover:text-red-600" @click="deleteFile(fileIndex)"></i>
                                            </div>
                                        </div>
                                        <div class="border border-t-0 border-gray-300 rounded-md rounded-t-none p-1 text-xs">{{file.name}}</div>
                                    </div>
                                </div>
                                <button-secondary type="button" @click="deleteFiles" v-if="files && files.length > 0">
                                    Убрать файл
                                </button-secondary>
                                <button-primary type="button" @click="openFilesModal()">
                                    <span v-if="files && files.length > 0">Заменить файл</span>
                                    <span v-else>Выбрать файл</span>
                                </button-primary>
                                <upload-files />
                                <div v-if="errors.file_id" class="mt-2 text-red-400">{{ displayileIdError }}</div>
                            </div>
                            <text-input v-model="form.title" :error="errors.title" id="title" label="Заголовок" />
                            <tiptap :editor="editor" v-model="form.description" :error="errors.description" id="description" label="Описание" />
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
    import Tiptap from '@/components/Admin/Tiptap.vue'
    //import { VueEditor } from 'vue2-editor'

    export default {
        components: {
            TextInput,
            TextareaInput,
            SelectInput,
            ButtonSecondary,
            ButtonPrimary,
            UploadFiles,
            Tiptap,
            //VueEditor,
        },
        data: () => ({
            isloading: true,
            isStoreloading: false,
            form: {
                title: null,
                description: null,
                file_id: null,
            },
            errors: {},
            files: [],
            editor: null,
        }),
        mounted() {
            this.setData()
            eventBus.$on('selectedFiles', (files) => {
                console.log({'edit-selectedFiles': files})
                this.files = [files[0]]
                this.form.file_id = files[0].id
            })
        },
        computed: {
            displayFileIdError() {
                return (Array.isArray(this.errors.file_id)) ? this.errors.file_id.join('; ') : this.errors.file_id
            },
        },
        methods: {
            setData() {
                axios.get('/api/admin/authors/' + this.$route.params.id + '/edit')
                .then(res => {
                    this.form.title = res.data.author.title
                    this.form.description = res.data.author.description
                    this.form.file_id = res.data.author.file_id

                    this.files = res.data.files
                })
                .finally(() => this.isloading = false)
            },
            store() {
                this.isStoreloading = true
                axios.put('/api/admin/authors/' + this.$route.params.id, this.form)
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
            }
        },
    }
</script>