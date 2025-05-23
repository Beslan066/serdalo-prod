<template>
    <div>
        <h1 class="px-8 font-semibold text-xl text-gray-800 leading-tight">
            Авторы / Создание
        </h1>
        <div class="max-w-7xl px-8 py-8">
            <div class="max-w-3xl bg-white rounded-md shadow">
                <div v-if="isloading">Loading</div>
                <div v-else>
                    <form @submit.prevent="store">
                        <div class="p-6">
                            <div class="mb-6">
                                <label class="mb-2 block">Файл:</label>
                                <div class="grid grid-cols-4 gap-2 mb-2" v-if="files && files.length > 0">
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
                                <button-secondary type="button" class="mt-2" @click="deleteFiles" v-if="files.length > 0">
                                    Убрать файл
                                </button-secondary>
                                <button-primary type="button" @click="openFilesModal()">
                                    <span v-if="files.length > 0">Заменить файл</span>
                                    <span v-else>Выбрать файл</span>
                                </button-primary>
                                <div v-if="errors.author_files" class="mt-2 text-red-400">{{ displayAuthorFilesError }}</div>
                            </div>
                            <text-input v-model="form.title" :error="errors.title" id="title" label="Заголовок" />
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
            form: {
                title: null,
                description: null,
                author_files: [],
            },
            errors: {},
            categories: [],
            files: [],
        }),
        mounted() {
            this.setData()
            eventBus.$on('selectedFiles', (files) => {
                this.files = files
                this.form.author_files = []
                for(var file of files) {
                    this.form.author_files.push(file.id)
                }
            })
        },
        computed: {
            displayAuthorCategoriesError() {
                return (Array.isArray(this.errors.category_id)) ? this.errors.category_id.join('; ') : this.errors.category_id
            },
            displayAuthorFilesError() {
                return (Array.isArray(this.errors.author_files)) ? this.errors.author_files.join('; ') : this.errors.author_files
            },
        },
        methods: {
            setData() {
                axios.get('/api/admin/authors/create')
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
                axios.post('/api/admin/authors', this.form)
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
                    if(err.response.data.error) {
                        eventBus.$emit('flash-message', 'error', err.response.data.error);
                    }
                })
                .finally(() => this.isStoreloading = false)
            },
            deleteFile(index) {
                this.files.splice(index, 1)
                this.form.author_files.splice(index, 1)
            },
            deleteFiles() {
                this.files = []
                this.form.author_files = []
            },
            openFilesModal() {
                eventBus.$emit('openFilesModal', 'default', {selectMode: 'single'})
            }
        },
    }
</script>