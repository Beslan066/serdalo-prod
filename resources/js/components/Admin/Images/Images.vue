<template>
    <div>
        <div v-if="isloading">Loading</div>
        <div v-else>
            <div class="grid grid-cols-2 gap-6 mb-10">
                <div class="bg-white rounded-md shadow">
                    <form @submit.prevent="store">
                        <div class="p-3">
                            <input type="file" multiple class="hidden" ref="files" @change="updateFilesPreview">
                            <label class="block mb-2 font-medium text-sm text-gray-700" for="files" v-if="!(filesPreview.length > 0)">Upload Files</label>
                            <div class="grid grid-cols-6 gap-2 mb-2" v-if="filesPreview.length > 0">
                                <span v-for="(filePreview, filePreviewIndex) in filesPreview" :key="filePreviewIndex" :data-index="filePreviewIndex" 
                                    class="block w-20 h-20 border border-gray-300 rounded-md bg-center bg-no-repeat bg-cover"
                                    :style="'background-image: url(\'' + filePreview + '\');'">
                                </span>
                            </div>
                            <div class="flex">
                                <button-secondary class="mt-2 mr-2" type="button" @click="selectToUpload">
                                    Select Files
                                </button-secondary>
                                <button-secondary class="mt-2 mr-2" type="button" @click="deleteFiles" v-if="filesPreview.length > 0">
                                    Remove Files
                                </button-secondary>
                                <button-primary type="submit" class="mt-2" v-if="filesPreview.length > 0">Upload</button-primary>
                            </div>
                            <div v-if="errors.files" class="mt-2 text-red-400">{{ displayFilesError }}</div>
                        </div>
                    </form>
                </div>
                <div v-if="selectable && filesSelected.length > 0" class="bg-white rounded-md shadow p-3">
                    <div class="grid grid-cols-6 gap-2 mb-2">
                        <div v-for="(fileSelected, fileSelectedIndex) in filesSelected" :key="fileSelectedIndex" :data-index="fileSelectedIndex" 
                            class="flex justify-end w-20 h-20 border border-gray-300 rounded-md bg-center bg-no-repeat bg-cover"
                            :style="'background-image: url(\'' + fileSelected.full_path + '\');'"
                            >
                            <div class="flex justify-center items-center w-6 h-6 rounded-md opacity-40 bg-gray-200 hover:opacity-100 hover:bg-gray-400 cursor-pointer">
                                <i class="fas fa-times-circle p-2 text-red-400 hover:text-red-600" @click="deleteFromSelected(fileSelectedIndex)"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex">
                        <button-primary type="button" @click="returnSelected()">Paste</button-primary>
                    </div>
                </div>
            </div>
            <div>
                <div class="bg-white rounded-md shadow overflow-x-auto">
                    <table class="w-full whitespace-nowrap">
                        <tr class="text-left font-bold">
                            <th class="px-6 pt-6 pb-4">#</th>
                            <th class="px-6 pt-6 pb-4">Preview</th>
                            <th class="px-6 pt-6 pb-4">User ID</th>
                            <th class="px-6 pt-6 pb-4">Название<br>Путь</th>
                            <th class="px-6 pt-6 pb-4">Size</th>
                            <th class="px-6 pt-6 pb-4">Actions</th>
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
                                <button-primary type="button" @click="select(file)" v-if="selectable && filesSelectedIds.indexOf(file.id) < 0">
                                    Select
                                </button-primary>
                                <button-secondary type="button" @click="destroy(file.id, file_index)">
                                    Destroy
                                </button-secondary>
                            </td>
                        </tr>
                        <tr v-if="empty_data">
                            <td class="border-t px-6 py-4" colspan="4">No files found.</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { eventBus } from '@/app'

    import ButtonPrimary from '@/components/Admin/ButtonPrimary'
    import ButtonSecondary from '@/components/Admin/ButtonSecondary'
    import ErrorMessage from '@/components/Admin/ErrorMessage'

    export default {
        components: {
            ButtonPrimary,
            ButtonSecondary,
            ErrorMessage,
        },
        props: {
            selectable: {
                type: Boolean,
                default: true
            }
        },
        data: () => ({
            isloading: true,
            isStoreloading: false,
            files: [],
            empty_data: false,
            form: {
                files: [],
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
            displayFilesError() {
                return (Array.isArray(this.errors.files)) ? this.errors.files.join('; ') : this.errors.files
            },
        },
        methods: {
            setData() {
                axios.get('/api/admin/files')
                .then(res => {
                    if(res.data.data.length === 0) {
                        this.empty_data = true
                    }
                    this.files = res.data
                })
                .finally(() => this.isloading = false)
            },
            store() {
                var formData = new FormData()
                for(var i = 0; i < this.form.files.length; i++) {
                    var file = this.form.files[i];
                    formData.append('files[' + i + ']', file);
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
                    this.form.files = []
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
            select(file) {
                this.filesSelected.push(file)
                this.filesSelectedIds.push(file.id)
            },
            deleteFromSelected(index) {
                this.filesSelected.splice(index, 1)
                this.filesSelectedIds.splice(index, 1)
            },
            returnSelected() {
                eventBus.$emit('selectedFiles', this.filesSelected)
                this.close()
            },
            selectToUpload() {
                this.$refs.files.click()
            },
            updateFilesPreview() {
                for(var fileI = 0; fileI < this.$refs.files.files.length; fileI++) {
                    const file = this.$refs.files.files[fileI]
                    if (! file) return
                    const reader = new FileReader()
                    reader.onload = (e) => {
                        this.filesPreview.push(e.target.result)
                    }
                    reader.readAsDataURL(file)
                    this.form.files.push(file)
                }
            },
            deleteFiles() {
                this.filesPreview = []
                this.form.files = []
            },
            destroy(id, index) {
                if(confirm('Are you sure you want to delete this file?')) {
                    axios.delete('/api/admin/files/' + id)
                    .then(res => {
                        eventBus.$emit('flash-message', 'success', res.data.success)
                        this.files.data.splice(index, 1)
                    })
                }
            },
            close() {
                eventBus.$emit('closeFilesModal')
            }
        },
    }
</script>
