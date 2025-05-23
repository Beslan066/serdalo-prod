<template>
    <div class="flex flex-row">
        <div class="w-60 min-h-screen">
            <div class="h-full pt-28 relative bg-white shadow-md p-2 text-sm">
                <div class="fixed">
                    <ul class="flex flex-col">
                        <li class="py-2 px-2">
                            <router-link to="/admin" class="nav-link">Главная</router-link>
                        </li>
                        <li class="py-2 px-2">
                            <router-link to="/admin/posts" class="nav-link">Новости</router-link>
                        </li>
                        <li class="py-2 px-2">
                            <router-link to="/admin/tags" class="nav-link">Теги</router-link>
                        </li>
                        <li class="py-2 px-2">
                            <router-link to="/admin/materials" class="nav-link">Материалы</router-link>
                        </li>
                        <li class="py-2 px-2">
                            <router-link to="/admin/materials-inh" class="nav-link">Материалы Ингушские</router-link>
                        </li>
                        <li class="py-2 px-2">
                            <router-link to="/admin/categories" class="nav-link">Категории</router-link>
                        </li>
                        <li class="py-2 px-2">
                            <router-link to="/admin/authors" class="nav-link">Авторы</router-link>
                        </li>
                        <li class="py-2 px-2">
                            <router-link to="/admin/experts" class="nav-link">Эксперты</router-link>
                        </li>
                        <li class="py-2 px-2">
                            <router-link to="/admin/newspapers" class="nav-link">Выпуски газет</router-link>
                        </li>
                        <li class="py-2 px-2">
                            <router-link to="/admin/files" class="nav-link">Файлы</router-link>
                        </li>
                        <li class="py-2 px-2">
                            <router-link to="/admin/users" class="nav-link">Пользователи</router-link>
                        </li>
                        <li class="py-2 px-2">
                            <router-link to="/admin/statistics" class="nav-link">Статистика - новости</router-link>
                        </li>
                        <li class="py-2 px-2">
                            <router-link to="/admin/statistics/materials" class="nav-link">Статистика - материалы</router-link>
                        </li>
                        <li class="py-2 px-2">
                            <router-link to="/admin/video_articles" class="nav-link">Видеостудия</router-link>
                        </li>
                        <li class="py-2 px-2">
                            <router-link to="/admin/documents" class="nav-link">Документы</router-link>
                        </li>
                        <li class="py-2 px-2">
                            <router-link to="/admin/litsalon_articles" class="nav-link">Литературный салон</router-link>
                        </li>

                        <li class="py-2 px-2">
                            <router-link to="/admin/photoreportage" class="nav-link">Фоторепортажи</router-link>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="w-full">
            <nav class="bg-white shadow-md py-2 text-sm">
                <div class="container mx-auto">
                    <div class="flex flex-row">
                        <ul class="flex flex-row mr-auto">
                            <li class="py-2 pr-2">
                                <router-link to="/admin" class="nav-link">Главная</router-link>
                            </li>
                        </ul>
                        <navbar-right />
                    </div>
                </div>
            </nav>
            <main class="py-4">
                <div class="container mx-auto">
                    <router-view></router-view>
                </div>
            </main>
            <modal-files
                v-if="modalFilesOpen"
                :eventName="filesEventName"
                :filesConfig="filesConfig"
            ></modal-files>
        </div>
    </div>
</template>

<script>
    import { eventBus } from '@/app'

    import NavbarRight from '@/components/NavbarRight'
    import ModalFiles from '@/components/Admin/Files/ModalFiles'

    export default {
        components: {
            NavbarRight,
            ModalFiles,
        },
        data: () => ({
            modalFilesOpen: false,
            filesEventName: 'default',
            filesConfig: {},
        }),
        mounted() {
            eventBus.$on('openFilesModal', (eventName = 'default', filesConfig) => {
                this.filesEventName = eventName
                this.filesConfig = this.getFilesConfig(filesConfig)
                this.modalFilesOpen = true
            })
            eventBus.$on('closeFilesModal', () => {
                this.filesEventName = 'default'
                this.filesConfig = this.getFilesConfig()
                this.modalFilesOpen = false
            })
        },
    }
</script>
