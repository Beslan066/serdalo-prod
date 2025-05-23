<template>
    <div>
        <h1 class="px-8 font-semibold text-xl text-gray-800 leading-tight">
            Литературный салон / Список
        </h1>
        <div class="px-8 py-8">
            <div class="flex justify-end mb-4">
                <button-link-primary v-if="is('author')" to="/admin/litsalon_articles/create">Добавить запись</button-link-primary>
            </div>
            <div class="bg-white rounded-md shadow overflow-x-auto">
                <table class="table-fixed w-full">
                    <tr class="text-left font-bold">
                        <th class="pl-6 pr-3 py-3 w-28">#</th>
                        <th class="px-3 py-3 max-w-xs">Заголовок</th>
                        <th class="px-3 py-3 max-w-xs">Поколение</th>
                        <th class="px-3 py-3 max-w-xs">Подзаголовок</th>
                        <th class="px-3 py-3 max-w-xs">Года</th>
                        <th class="px-3 py-3 w-28">Перевод</th>
                        <th class="px-3 py-3">Дата создания</th>
                        <th class="pl-3 pr-6 py-3">Действия</th>
                    </tr>
                    <tr v-for="litsalon_article in litsalon_articles.data" :key="litsalon_article.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                        <td class="border-t pl-6 pr-3 py-3">
                            {{ litsalon_article.id }}
                        </td>
                        <td class="border-t px-3 py-3">
                            <span class="text-sm">{{ litsalon_article.title }}</span>
                        </td>
                        <td class="border-t px-3 py-3">
                            <span class="text-sm">{{ litsalon_article.generation_display }}</span>
                        </td>
                        <td class="border-t px-3 py-3">
                            <span class="text-sm">{{ litsalon_article.subtitle }}</span>
                        </td>
                        <td class="border-t px-3 py-3">
                            <span class="text-sm">{{ litsalon_article.dates }}</span>
                        </td>
                        <td class="border-t px-3 py-3">
                            <div v-if="!litsalon_article.translation || (!litsalon_article.translation.title)">нет</div>
                            <div v-else>есть</div>
                        </td>
                        <td class="border-t px-3 py-3">
                            <span>{{ litsalon_article.created_at | displayDateTime }}</span>
                        </td>
                        <td class="border-t pl-3 pr-6 py-3 flex items-center gap-2">
                            <button-link-primary v-is-my="litsalon_article.user_id" :to="'/admin/litsalon_articles/' + litsalon_article.id + '/edit'"><i class="fas fa-pencil-alt"></i></button-link-primary>
                            <button-link-primary v-if="is('translation_editor') && (litsalon_article.translation && litsalon_article.translation.id)" :to="'/admin/litsalon_articles/litsalon_articles_translations/' + litsalon_article.translation.id + '/edit'"><i class="fas fa-pencil-alt"></i> перевод</button-link-primary>
                            <button-link-primary v-if="is('translation_editor') && (!litsalon_article.translation || !litsalon_article.translation.id)" :to="'/admin/litsalon_articles/' + litsalon_article.id + '/litsalon_articles_translations/create'"><i class="fas fa-plus-circle"></i> перевод</button-link-primary>
                            <button-action-delete :uri="'/api/admin/litsalon_articles/' + litsalon_article.id" @deleted="deleted(litsalon_article.id)" />
                        </td>
                    </tr>
                    <tr v-if="empty_data">
                        <td class="border-t px-3 py-3" colspan="5">No litsalon_articles found.</td>
                    </tr>
                </table>
            </div>
            <pagination v-if="litsalon_articles.links" :pagination="litsalon_articles" @paginate="setDataPagination" />
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
            litsalon_articles: [],
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
                axios.get('/api/admin/litsalon_articles', {params: queryParams})
                .then(res => {
                    if(res.data.data.length === 0) {
                        this.empty_data = true
                    }
                    this.current_page = res.data.current_page
                    this.litsalon_articles = res.data
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
