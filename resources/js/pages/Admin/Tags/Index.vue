<template>
    <div>
        <h1 class="px-8 font-semibold text-xl text-gray-800 leading-tight">
           Админ/Теги/Главная
        </h1>
        <div class="px-8 py-8">
            <div class="flex justify-end mb-4">
                <button-link-primary to="/admin/tags/create">Создать тег</button-link-primary>
            </div>
            <div class="bg-white rounded-md shadow overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                    <tr class="text-left font-bold">
                        <th class="px-6 pt-6 pb-4">#</th>
                        <th class="px-6 pt-6 pb-4">Заголовок</th>
                        <th class="px-6 pt-6 pb-4">Создан</th>
                        <th class="px-6 pt-6 pb-4">Перевод</th>
                        <th class="px-6 pt-6 pb-4">Действие</th>
                    </tr>
                    <tr v-for="tag in tags.data" :key="tag.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                        <td class="border-t px-6 pt-6 pb-4">
                            {{ tag.id }}
                        </td>
                        <td class="border-t px-6 pt-6 pb-4">
                            {{ tag.title }}
                        </td>
                        <td class="border-t px-6 pt-6 pb-4">
                            {{ tag.created_at | displayDateTime }}
                        </td>
                        <td class="border-t px-6 pt-6 pb-4">
                            {{ tag.tag_translate }}
                        </td>
                        <td class="border-t px-6 pt-6 pb-4 flex items-center gap-2">
                            <button-link-primary :to="'/admin/tags/' + tag.id + '/edit'">Изменить</button-link-primary>
                            <button-action-delete :uri="'/api/admin/tags/' + tag.id" @deleted="deleted(tag.id)" />
                        </td>
                    </tr>
                    <tr v-if="empty_data">
                        <td class="border-t px-6 py-4" colspan="4">Не найдено тегов.</td>
                    </tr>
                </table>
            </div>
            <pagination v-if="tags.links" :pagination="tags" @paginate="setDataPagination" />
        </div>
    </div>
</template>

<script>
    import ButtonLinkPrimary from '@/components/Admin/ButtonLinkPrimary'
    import Pagination from '@/components/Admin/Pagination'
    import ButtonActionDelete from '@/components/Admin/ButtonActionDelete'

    export default {
        components: {
            ButtonLinkPrimary,
            Pagination,
            ButtonActionDelete,
        },
        data: () => ({
            current_page: null,
            tags: [],
            empty_data: false,
        }),
        mounted() {
            this.setData()
        },
        methods: {
            setData(page = '') {
                var queryParams = {}
                if(page) {
                    queryParams.page = page
                } else if(this.current_page > 0) {
                    queryParams.page = this.current_page
                }
                axios.get('/api/admin/tags', {params: queryParams})
                .then(res => {
                    if(res.data.data.length === 0) {
                        this.empty_data = true
                    }
                    this.current_page = res.data.current_page
                    this.tags = res.data
                })
            },
            setDataPagination(page) {
                this.setData(page)
            },
            deleted(id) {
                this.setData()
            },
        }
    }
</script>
