<template>
    <div>
        <h3 v-if="title" class="px-6 pb-2 font-semibold text-lg text-gray-800 leading-tight">
            {{ title }}
        </h3>
        <div class="mb-6 p-6 bg-gray-200">
            <div 
                v-for="(entity, entityIndex) in entities" 
                :key="entityIndex" 
                class="mb-3 bg-gray-100 border border-gray-300 rounded-md"
            >
                <div class="flex justify-between items-center p-3 rounded-t-md border-b border-gray-300 bg-white">
                    <div>
                        <span class="font-semibold mr-6"># {{ entityIndex + 1 }}</span>
                        <span v-if="entity.created_at">{{ entity.created_at | displayDateTime }}</span>
                    </div>
                    <div>
                        <button-action-delete 
                            v-if="deleteUrl && entity.id"
                            :uri="{id: entity.id, url: deleteUrl} | getDeleteUrl"
                            @deleted="deletedEntity(entityIndex)" 
                        />
                        <button-danger 
                            v-else 
                            @click="deletedEntity(entityIndex)"
                        >
                            <i class="fas fa-trash-alt"></i>
                        </button-danger>
                    </div>
                </div>
                <div class="p-3">
                    <file-input
                        :files="entity.file ? [entity.file] : []"
                        id="entity_file_id"
                        label="Изображение:"
                        @openFilesModal="openEntityFilesModal"
                        @deleteFile="deleteEntityFile"
                        @deleteFiles="deleteEntityFiles"
                        uploadEventName="Entity"
                        :parentEntityIndex="entityIndex"
                    />
                    <div>
                        <text-input v-model="entity.title" id="entity_title" label="Заголовок" />
                    </div>
                    <div>
                        <text-input v-model="entity.subtitle" id="entity_subtitle" label="Подзаголовок" />
                    </div>
                </div>
            </div>
            <button-primary type="button" @click="addEntity"><i class="fas fa-pencil-alt"></i>Добавить элемент</button-primary>
        </div>
    </div>
</template>

<script>
    import { eventBus } from '@/app'
    
    import SpinSmall from '@/components/SpinSmall'
    import TextInput from '@/components/Admin/TextInput'
    import TextareaInput from '@/components/Admin/TextareaInput'
    import SelectInput from '@/components/Admin/SelectInput'
    import ButtonSecondary from '@/components/Admin/ButtonSecondary'
    import ButtonPrimary from '@/components/Admin/ButtonPrimary'
    import UploadFiles from '@/components/Admin/Files/UploadFiles'
    import FileInput from '@/components/Admin/Files/FileInput'
    import Tiptap from '@/components/Admin/Tiptap.vue'
    import ButtonActionDelete from '@/components/Admin/ButtonActionDelete'
    import ButtonDanger from '@/components/Admin/ButtonDanger'

    export default {
        components: {
            SpinSmall,
            TextInput,
            TextareaInput,
            SelectInput,
            ButtonSecondary,
            ButtonPrimary,
            UploadFiles,
            FileInput,
            Tiptap,
            ButtonActionDelete,
            ButtonDanger,
        },
        props: {
            title: String,
            entities: Array,
            deleteUrl: String,
        },
        data: () => ({
            
        }),
        filters: {
            getDeleteUrl(value) {
                return value.url.replace('[[entity_id]]', value.id)
            },
        },
        mounted() {
            eventBus.$on('selectedFilesEntity', (files, entity_index) => {
                this.entities[entity_index].file = files[0]
                this.entities[entity_index].file_id = files[0].id
                this.$emit('onUpdateEntities', this.entities)
            })
        },
        methods: {
            deleteEntityFile(index, entity_index) {
                this.entities[entity_index].file = null
                this.entities[entity_index].file_id = null
                this.$emit('onUpdateEntities', this.entities)
            },
            deleteEntityFiles(entity_index) {
                this.entities[entity_index].file = null
                this.entities[entity_index].file_id = null
                this.$emit('onUpdateEntities', this.entities)
            },
            openEntityFilesModal(entity_index) {
                eventBus.$emit('openFilesModal', 'Entity', entity_index)
                this.$emit('onUpdateEntities', this.entities)
            },
            deletedEntity(entity_index) {
                this.entities.splice(entity_index, 1)
                this.$emit('onUpdateEntities', this.entities)
            },
            addEntity() {
                this.entities.push({
                    title: '',
                    subtitle: '',
                    file: null,
                    file_id: null,
                })
                this.$emit('onUpdateEntities', this.entities)
            },
        }
    }
</script>
