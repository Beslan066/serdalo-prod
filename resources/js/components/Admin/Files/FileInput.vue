<template>
    <div class="mb-6">
        <label class="mb-2 block" :for="id">{{ label }}</label>
        <div class="grid grid-cols-4 gap-2 mb-4" v-if="files && files.length > 0" :id="id">
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
                        <i class="fas fa-times-circle p-2 text-red-400 hover:text-red-600" @click="$emit('deleteFile', fileIndex, getFilesConfig(filesConfig).parentEntityIndex)"></i>
                    </div>
                </div>
                <div class="border border-t-0 border-gray-300 rounded-md rounded-t-none p-1 text-xs">{{file.name}}</div>
            </div>
        </div>
        <div class="flex gap-2">
            <button-secondary type="button" @click="$emit('deleteFiles', getFilesConfig(filesConfig).parentEntityIndex)" v-if="files && files.length > 0">
                Удалить
            </button-secondary>
            <button-primary type="button" @click="$emit('openFilesModal', selectEventName, getFilesConfig(filesConfig))">
                <span v-if="files && files.length > 0">Заменить</span>
                <span v-else>Выбрать</span>
            </button-primary>
            <upload-files :eventName="uploadEventName" :filesConfig="getFilesConfig(filesConfig)" />
        </div>
        <div v-if="error" class="mt-2 text-red-400">{{ displayError }}</div>
    </div>
</template>

<script>
    import { eventBus } from '@/app'

    import ButtonPrimary from '@/components/Admin/ButtonPrimary'
    import ButtonSecondary from '@/components/Admin/ButtonSecondary'
    import UploadFiles from '@/components/Admin/Files/UploadFiles'

    export default {
        components: {
            ButtonPrimary,
            ButtonSecondary,
            UploadFiles,
        },
        props: {
            id: {
                type: String,
            },
            label: String,
            error: [String, Array],
            files: [Array],
            selectEventName: {
                type: String,
                default: 'default'
            },
            uploadEventName: {
                type: String,
                default: 'default'
            },
            filesConfig: Object,
        },
        data: () => ({
            
        }),
        computed: {
            displayError() {
                return (Array.isArray(this.error)) ? this.error.join('; ') : this.error
            }
        },
        methods: {
            
        }
    }
</script>
