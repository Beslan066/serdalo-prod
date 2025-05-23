<template>
    <div>
        <h1 class="px-8 font-semibold text-xl text-gray-800 leading-tight">
            Документы / Редактирование
        </h1>
        <div class="max-w-7xl px-8 py-8">
            <div class="max-w-3xl bg-white rounded-md shadow">
                <div v-if="isloading">Loading</div>
                <div v-else>
                    <form @submit.prevent="store">
                        <div class="p-6">
                            <text-input v-model="form.title" :error="errors.title" id="title" label="Заголовок" />
                            <div class="pb-4 w-full">
                                <label class="mb-2 block">Тип документа:</label>
                                <select v-model="form.type" :class="{ 'input-error': errors.type }" class="block w-full p-2 border rounded border-gray-400">
                                    <option :value="'1'">Постановление</option>
                                    <option :value="'2'">Приказ</option>
                                    <option :value="'3'">Указ</option>
                                    <option :value="'4'">Сообщение</option>
                                    <option :value="'5'">Распоряжение</option>
                                    <option :value="'6'">Закон</option>
                                    <option :value="'7'">Законопроект</option>
                                </select>
                                <div v-if="errors.role" class="text-red-400">{{ displayTypeError }}</div>
                            </div>
                            <div class="pb-4 w-full">
                                <label for="signed_at" class="mb-2 block">Подписан:</label>
                                <input v-model="form.signed_at" id="signed_at" type="date" class="block w-full p-2 border rounded border-gray-400">
                            </div>
                            <div class="pb-4 w-full">
                                <label for="published_at" class="mb-2 block">Опубликован:</label>
                                <input v-model="form.published_at" id="published_at" type="date" class="block w-full p-2 border rounded border-gray-400">
                            </div>
                            <div class="pb-4 w-full">
                                <label for="enter_into_force_at" class="mb-2 block">Вступает в силу:</label>
                                <input v-model="form.enter_into_force_at" id="enter_into_force_at" type="date" class="block w-full p-2 border rounded border-gray-400">
                            </div>
                            <file-input
                                :files="files"
                                :error="errors.file_id"
                                id="file_id"
                                label="Файл:"
                                @openFilesModal="openFilesModal"
                                @deleteFile="deleteFile(fileIndex)"
                                @deleteFiles="deleteFiles"
                            />
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
                type: null,
                file_id: null,
                signed_at: null,
                published_at: null,
                enter_into_force_at: null,
                description: null,
            },
            errors: {},
            files: [],
            types: [],
            editor: null,
        }),
        computed: {
            displayTypeError() {
                return (Array.isArray(this.errors.type)) ? this.errors.type.join('; ') : this.errors.type
            },
        },
        mounted() {
            this.setData()
            eventBus.$on('selectedFiles', (files) => {
                this.files = [files[0]]
                this.form.file_id = files[0].id
            })
        },
        methods: {
            setData() {
                axios.get('/api/admin/documents/' + this.$route.params.id + '/edit')
                .then(res => {
                    this.entity = res.data.document
                    this.form.title = res.data.document.title
                    this.form.type = res.data.document.type
                    this.form.file_id = res.data.document.file_id
                    this.form.signed_at = res.data.document.signed_at
                    this.form.published_at = res.data.document.published_at
                    this.form.enter_into_force_at = res.data.document.enter_into_force_at
                    this.form.description = res.data.document.description
                    
                    this.files = res.data.files
                })
                .finally(() => this.isloading = false)
            },
            store() {
                this.isStoreloading = true
                axios.put('/api/admin/documents/' + this.$route.params.id, this.form)
                .then(res => {
                    this.errors = {}
                    eventBus.$emit('flash-message', 'success', res.data.success)
                    this.$router.push({name: 'admin-documents-index'})
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
        },
    }
</script>