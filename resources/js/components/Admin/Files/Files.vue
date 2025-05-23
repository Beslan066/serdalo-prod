<template>
    <div>
        <div v-if="isloading">Loading</div>
        <div v-else>
            <div class="grid grid-cols-2 gap-6 mb-10">
                <div class="bg-white rounded-md shadow">
                    <form @submit.prevent="store">
                        <div class="p-3">
                            <input type="file" multiple class="hidden" ref="upload_files" @change="updateFilesPreview">
                            <label class="block mb-2 font-medium text-sm text-gray-700" for="upload_files" v-if="!(filesPreview.length > 0)">Загрузить файл</label>
                            <div class="grid grid-cols-4 gap-2 mb-2" v-if="filesPreview.length > 0">
                                <div v-for="(filePreview, filePreviewIndex) in filesPreview" :key="filePreviewIndex" :data-index="filePreviewIndex">
                                    <div
                                        class="flex justify-end h-20 border border-gray-300 rounded-md rounded-b-none bg-center bg-no-repeat"
                                        :style="{
                                            'background-image': 'url(\'' + filePreview.preview + '\')',
                                            'background-size': filePreview.show_icon ? '50%' : 'cover'
                                        }"
                                    >
                                    </div>
                                    <div class="border border-t-0 border-gray-300 rounded-md rounded-t-none p-1 text-xs">{{filePreview.name}}</div>
                                </div>
                            </div>
                            <div class="flex">
                                <button-secondary class="mt-2 mr-2" type="button" @click="selectToUpload">
                                    Выберите файл с компьютера
                                </button-secondary>
                                <button-secondary class="mt-2 mr-2" type="button" @click="deleteFiles" v-if="filesPreview.length > 0">
                                    Удалить файлы
                                </button-secondary>
                                <button-primary type="submit" class="mt-2" v-if="filesPreview.length > 0">Загрузить</button-primary>
                            </div>
                            <div v-if="errors.files" class="mt-2 text-red-400">{{ displayUploadFilesError }}</div>
                        </div>
                    </form>
                </div>
                <div v-if="selectable && filesSelected.length > 0" class="bg-white rounded-md shadow p-3">
                    <div class="grid grid-cols-4 gap-2 mb-2" v-if="selectable && filesSelected.length > 0">
                        <div v-for="(fileSelected, fileSelectedIndex) in filesSelected" :key="fileSelectedIndex" :data-index="fileSelectedIndex">
                            <div
                                class="flex justify-end h-20 border border-gray-300 rounded-md rounded-b-none bg-center bg-no-repeat"
                                :style="{
                                    'background-image': 'url(\'' + fileSelected.full_preview_path + '\')',
                                    'background-size': fileSelected.show_icon ? '50%' : 'cover'
                                }"
                            >
                                <a :href="fileSelected.full_path" class="flex justify-center items-center w-6 h-6 rounded-md opacity-60 hover:opacity-100 cursor-pointer" target="_blank">
                                    <i class="fas fa-share p-2 text-blue-400 hover:text-blue-600"></i>
                                </a>
                                <div class="flex justify-center items-center w-6 h-6 rounded-md opacity-60 hover:opacity-100 cursor-pointer">
                                    <i class="fas fa-times-circle p-2 text-red-400 hover:text-red-600" @click="deleteFromSelected(fileSelectedIndex)"></i>
                                </div>
                            </div>
                            <div class="border border-t-0 border-gray-300 rounded-md rounded-t-none p-1 text-xs">{{fileSelected.name}}</div>
                        </div>
                    </div>
                    <div class="flex">
                        <button-primary type="button" @click="returnSelected()">Вставить</button-primary>
                    </div>
                </div>
            </div>
            <div>
                <div class="bg-white rounded-md shadow overflow-x-auto">
                    <table class="w-full whitespace-nowrap">
                        <tr class="text-left font-bold">
                            <th class="px-6 pt-6 pb-4">#</th>
                            <th class="px-6 pt-6 pb-4">Фото</th>
                            <th class="px-6 pt-6 pb-4">User ID</th>
                            <th class="px-6 pt-6 pb-4">Название<br>Путь</th>
                            <th class="px-6 pt-6 pb-4">Размер</th>
                            <th class="px-6 pt-6 pb-4">Действие</th>
                            <th class="px-6 pt-6 pb-4">Действие</th>
                        </tr>
                        <tr v-for="(file, file_index) in files.data" :key="file.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                            <td class="border-t px-6 pt-6 pb-4">
                                {{ file.id }}
                            </td>
                            <td class="border-t px-6 pt-6 pb-4">
                                <div v-if="file.show_icon" class="block w-40 p-10 border border-gray-300">
                                    <img :src="file.full_preview_path">
                                </div>
                                <div v-else class="block w-40 border border-gray-300">
                                    <img :src="file.full_preview_path">
                                </div>
                            </td>
                            <td class="border-t px-6 pt-6 pb-4">
                                {{ file.user_id }}
                            </td>
                            <td class="border-t px-6 pt-6 pb-4">
                                <div>
                                    {{ file.name }}
                                </div>
                                <div>
                                    <a :href="file.full_path" target="_blank" class="text-blue-400">{{ file.path }}</a>
                                </div>
                            </td>
                            <td class="border-t px-6 pt-6 pb-4">
                                {{ file.size_display }}
                            </td>
                            <td class="border-t px-6 pt-6 pb-4">
                                <div class="flex justify-center items-center gap-2">
                                    <button-primary
                                        type="button"
                                        @click="select(file)"
                                        v-if="filesConfig.selectMode == 'multiple' && selectable && filesSelectedIds.indexOf(file.id) < 0"
                                    >
                                        Выбрать
                                    </button-primary>
                                    <button-primary
                                        type="button"
                                        @click="select(file, true)"
                                        v-if="filesConfig.selectMode == 'single' && selectable && filesSelectedIds.indexOf(file.id) < 0"
                                    >
                                        Вставить
                                    </button-primary>
                                    <button-action-delete :uri="'/api/admin/files/' + file.id" @deleted="deleted(file.id)" />
                                </div>
                            </td>
                        </tr>
                        <tr v-if="empty_data">
                            <td class="border-t px-6 py-4" colspan="4">No files found.</td>
                        </tr>
                    </table>
                </div>
                <pagination v-if="files.links" :pagination="files" @paginate="setDataPagination" />
            </div>
        </div>
    </div>
</template>

<script>
    import { eventBus } from '@/app'

    import ButtonPrimary from '@/components/Admin/ButtonPrimary'
    import ButtonSecondary from '@/components/Admin/ButtonSecondary'
    import ErrorMessage from '@/components/Admin/ErrorMessage'
    import Pagination from '@/components/Admin/Pagination'
    import ButtonActionDelete from '@/components/Admin/ButtonActionDelete'

    export default {
        components: {
            ButtonPrimary,
            ButtonSecondary,
            ErrorMessage,
            Pagination,
            ButtonActionDelete,
        },
        props: {
            selectable: {
                type: Boolean,
                default: true
            },
            eventName: {
                type: String,
                default: 'default'
            },
            filesConfig: Object,
        },
        data: () => ({
            isloading: true,
            isStoreloading: false,
            current_page: null,
            files: [],
            empty_data: false,
            form: {
                upload_files: [],
            },
            errors: {},
            filesPreview: [],
            filesSelected: [],
            filesSelectedIds: [],
        }),
        mounted() {
            this.setData()
        },
        computed: {
            displayUploadFilesError() {
                return (Array.isArray(this.errors.upload_files)) ? this.errors.upload_files.join('; ') : this.errors.upload_files
            },
        },
        methods: {
            setData(page = '') {
                var queryParams = {}
                if(page) {
                    queryParams.page = page
                } else if(this.current_page > 0) {
                    queryParams.page = this.current_page
                }
                if(this.filesConfig && this.filesConfig.fileTypes) {
                    queryParams.types = this.filesConfig.fileTypes
                }
                axios.get('/api/admin/files', {params: queryParams})
                .then(res => {
                    if(res.data.data.length === 0) {
                        this.empty_data = true
                    }
                    this.current_page = res.data.current_page
                    this.files = res.data
                })
                .finally(() => this.isloading = false)
            },
            setDataPagination(page) {
                this.setData(page)
            },
            store() {
                var formData = new FormData()
                for(var i = 0; i < this.form.upload_files.length; i++) {
                    var upload_file = this.form.upload_files[i];
                    formData.append('upload_files[' + i + ']', upload_file);
                }
                this.isStoreloading = true
                axios.post('/api/admin/files', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(res => {
                    this.errors = {}
                    eventBus.$emit('flash-message', 'success', res.data.success)
                    this.files.data = res.data.files.concat(this.files.data)
                    this.filesPreview = []
                    this.form.upload_files = []
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
            select(file, return_selected = false) {
                this.filesSelected.push(file)
                this.filesSelectedIds.push(file.id)
                if(return_selected) {
                    this.returnSelected()
                }
            },
            deleteFromSelected(index) {
                this.filesSelected.splice(index, 1)
                this.filesSelectedIds.splice(index, 1)
            },
            returnSelected() {
                if(this.eventName === 'default') {
                    eventBus.$emit('selectedFiles', this.filesSelected, this.filesConfig.parentEntityIndex)
                } else {
                    eventBus.$emit('selectedFiles' + this.eventName, this.filesSelected, this.filesConfig.parentEntityIndex)
                }
                this.close()
            },
            selectToUpload() {
                this.$refs.upload_files.click()
            },
            updateFilesPreview() {
                for(var fileI = 0; fileI < this.$refs.upload_files.files.length; fileI++) {
                    const upload_file = this.$refs.upload_files.files[fileI]
                    if (! upload_file) return
                    const reader = new FileReader()
                    reader.onload = (e) => {
                        if( upload_file.type.includes('image/') ) {
                            var preview = e.target.result
                            var show_icon = false
                        } else if( upload_file.type.includes('video/') ) {
                            var preview = '/images/file-icons/video.png'
                            var show_icon = true
                        } else if( upload_file.type.includes('audio/') ) {
                            var preview = '/images/file-icons/audio.png'
                            var show_icon = true
                        } else if( upload_file.type.includes('application/pdf') ) {
                            var preview = '/images/file-icons/pdf.png'
                            var show_icon = true
                        } else {
                            var preview = '/images/file-icons/audio.png'
                            var show_icon = true
                        }

                        this.filesPreview.push({
                            type: upload_file.type,
                            size: upload_file.size,
                            name: upload_file.name,
                            show_icon: show_icon,
                            preview: preview
                        })
                    }
                    reader.readAsDataURL(upload_file)
                    this.form.upload_files.push(upload_file)
                }
            },
            deleteFiles() {
                this.filesPreview = []
                this.form.upload_files = []
            },
            close() {
                eventBus.$emit('closeFilesModal')
            },
            deleted(id) {
                this.setData()
            },
        },
    }
</script>
