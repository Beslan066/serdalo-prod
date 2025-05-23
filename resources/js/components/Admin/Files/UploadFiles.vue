<template>
    <div>
        <form @submit.prevent="store">
            <input type="file" multiple class="hidden" ref="upload_files" @change="updateFilesPreview">
            <button-primary type="button" :isLoading="isStoreloading" @click="selectToUpload">
                <span>Загрузить</span>
            </button-primary>
            <div v-if="errors.upload_files" class="mt-2 text-red-400">{{ displayUploadFilesError }}</div>
        </form>
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
            eventName: {
                type: String,
                default: 'default'
            },
            filesConfig: Object,
        },
        data: () => ({
            isloading: true,
            isStoreloading: false,
            files: [],
            empty_data: false,
            page: null,
            form: {
                upload_files: [],
            },
            errors: {},
            filesPreview: [],
            filesSelected: [],
        }),
        computed: {
            displayUploadFilesError() {
                return (Array.isArray(this.errors.upload_files)) ? this.errors.upload_files.join('; ') : this.errors.upload_files
            },
        },
        methods: {
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
                    this.files = res.data.files
                    this.form.upload_files = []
                    for(var file of this.files) {
                        this.filesSelected.push(file)
                    }
                    if(this.eventName === 'default') {
                        eventBus.$emit('selectedFiles', this.filesSelected, this.filesConfig.parentEntityIndex)
                    } else {
                        eventBus.$emit('selectedFiles' + this.eventName, this.filesSelected, this.filesConfig.parentEntityIndex)
                    }
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
            selectToUpload() {
                this.$refs.upload_files.click()
            },
            updateFilesPreview() {
                for(var fileI = 0; fileI < this.$refs.upload_files.files.length; fileI++) {
                    const upload_file = this.$refs.upload_files.files[fileI]
                    if (! upload_file) return
                    this.form.upload_files.push(upload_file)
                }
                this.store()
            },
        },
    }
</script>
