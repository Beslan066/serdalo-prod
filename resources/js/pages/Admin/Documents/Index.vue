<template>
    <div>
        <h1 class="px-8 font-semibold text-xl text-gray-800 leading-tight">
            Документы / Список
        </h1>
        <div class="px-8 py-8">
            <div class="flex justify-end mb-4">
                <button-link-primary v-if="is('author')" to="/admin/documents/create">Добавить документ</button-link-primary>
            </div>
            <div class="bg-white rounded-md shadow overflow-x-auto">
                <table class="table-fixed w-full">
                    <tr class="text-left font-bold">
                        <th class="pl-6 pr-3 py-3 w-28">#</th>
                        <th class="px-3 py-3 max-w-xs">Заголовок</th>
                        <th class="px-3 py-3 w-28">Перевод</th>
                        <th class="px-3 py-3">Дата создания</th>
                        <th class="pl-3 pr-6 py-3">Действия</th>
                    </tr>
                    <tr v-for="document in documents.data" :key="document.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                        <td class="border-t pl-6 pr-3 py-3">
                            {{ document.id }}
                        </td>
                        <td class="border-t px-3 py-3">
                            <span class="text-sm">{{ document.title }}</span>
                        </td>
                        <td class="border-t px-3 py-3">
                            <div v-if="!document.translation || (!document.translation.title)">нет</div>
                            <div v-else>есть</div>
                        </td>
                        <td class="border-t px-3 py-3">
                            <span>{{ document.created_at | displayDateTime }}</span>
                        </td>
                        <td class="border-t pl-3 pr-6 py-3 flex items-center gap-2">
                            <button-link-primary v-is-my="document.user_id" :to="'/admin/documents/' + document.id + '/edit'"><i class="fas fa-pencil-alt"></i></button-link-primary>
                            <button-link-primary v-if="is('translation_editor') && (document.translation && document.translation.id)" :to="'/admin/documents/documents_translations/' + document.translation.id + '/edit'"><i class="fas fa-pencil-alt"></i> перевод</button-link-primary>
                            <button-link-primary v-if="is('translation_editor') && (!document.translation || !document.translation.id)" :to="'/admin/documents/' + document.id + '/documents_translations/create'"><i class="fas fa-plus-circle"></i> перевод</button-link-primary>
                            <button-action-delete :uri="'/api/admin/documents/' + document.id" @deleted="deleted(document.id)" />
                        </td>
                    </tr>
                    <tr v-if="empty_data">
                        <td class="border-t px-3 py-3" colspan="5">No documents found.</td>
                    </tr>
                </table>
            </div>
            <pagination v-if="documents.links" :pagination="documents" @paginate="setDataPagination" />
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
            documents: [],
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
                axios.get('/api/admin/documents', {params: queryParams})
                .then(res => {
                    if(res.data.data.length === 0) {
                        this.empty_data = true
                    }
                    this.current_page = res.data.current_page
                    this.documents = res.data
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
