<template>
    <div>
        <h1 class="px-8 font-semibold text-xl text-gray-800 leading-tight">
            Эксперты / Список
        </h1>
        <div class="px-8 px-8">
            <div class="flex justify-end mb-4">
                <button-link-primary v-if="is('expert')" to="/admin/experts/create">Добавить эксперта</button-link-primary>
            </div>
            <div class="bg-white rounded-md shadow overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                    <tr class="text-left font-bold">
                        <th class="px-6 pt-6 pb-4">#</th>
                        <th class="px-6 pt-6 pb-4">Заголовок</th>
                        <th class="px-6 pt-6 pb-4">Перевод</th>
                        <th class="px-6 pt-6 pb-4">Дата создания</th>
                        <th class="px-6 pt-6 pb-4">Действия</th>
                    </tr>
                    <tr v-for="expert in experts.data" :key="expert.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                        <td class="border-t px-6 pt-6 pb-4">
                            {{ expert.id }}
                        </td>
                        <td class="border-t px-6 pt-6 pb-4">
                            {{ expert.title }}
                        </td>
                        <td class="border-t px-6 pt-6 pb-4">
                            <div v-if="!expert.translation || (!expert.translation.title && !expert.translation.description)">нет</div>
                            <div v-else-if="expert.translation.title && !expert.translation.description">только заголовок</div>
                            <div v-else-if="expert.translation.description && !expert.translation.title">только описание</div>
                            <div v-else>есть</div>
                        </td>
                        <td class="border-t px-6 pt-6 pb-4">
                            {{ expert.created_at | displayDateTime }}
                        </td>
                        <td class="border-t px-6 pt-6 pb-4 flex items-center gap-2">
                            <button-link-primary v-is-my="expert.user_id" :to="'/admin/experts/' + expert.id + '/edit'">Редактировать</button-link-primary>
                            <button-link-primary v-if="is('translation_editor') && (expert.translation && expert.translation.id)" :to="'/admin/experts/experts_translations/' + expert.translation.id + '/edit'">Редактировать перевод</button-link-primary>
                            <button-link-primary v-if="is('translation_editor') && (!expert.translation || !expert.translation.id)" :to="'/admin/experts/' + expert.id + '/experts_translations/create'">Добавить перевод</button-link-primary>
                            <button-action-delete :uri="'/api/admin/experts/' + expert.id" @deleted="deleted(expert.id)" />
                        </td>
                    </tr>
                    <tr v-if="empty_data">
                        <td class="border-t px-6 py-4" colspan="5">No experts found.</td>
                    </tr>
                </table>
            </div>
            <pagination v-if="experts.links" :pagination="experts" @paginate="setDataPagination" />
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
            experts: [],
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
                axios.get('/api/admin/experts', {params: queryParams})
                .then(res => {
                    if(res.data.data.length === 0) {
                        this.empty_data = true
                    }
                    this.current_page = res.data.current_page
                    this.experts = res.data
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
