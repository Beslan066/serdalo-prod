<template>
    <div>
        <h1 class="px-8 font-semibold text-xl text-gray-800 leading-tight">
            Фоторепортаж / Список
        </h1>
        <div class="px-8 py-8">
            <div class="flex justify-between pb-4">
              <div>
                  <button-link-primary v-if="is('author')" to="/admin/photoreportage/create">Добавить фоторепортаж</button-link-primary>
              </div>
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
                    <tr v-for="post in photoreportage.data" :key="post.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                        <td class="border-t pl-6 pr-3 py-3">
                            {{ post.id }}
                        </td>
                        <td class="border-t px-3 py-3">
                            <span class="text-sm">{{ post.title }}</span>
                        </td>
                        <td class="border-t px-3 py-3">
                            <div v-if="!post.translation || (!post.translation.title && !post.translation.description)">нет</div>
                            <div v-else-if="post.translation.title && !post.translation.description">только заголовок</div>
                            <div v-else-if="post.translation.description && !post.translation.title">только описание</div>
                            <div v-else>есть</div>
                        </td>
                        <td class="border-t px-3 py-3">
                            <span>{{ post.published_at | displayDateTime }}</span>
                        </td>
                        <td class="border-t pl-3 pr-6 py-3 flex items-center gap-2">
                            <button-link-primary v-is-my="post.user_id" :to="'/admin/photoreportage/' + post.id + '/edit'"><i class="fas fa-pencil-alt"></i></button-link-primary>
                            <button-link-primary v-if="is('translation_editor') && (post.translation && post.translation.id)" :to="'/admin/photoreportage/photo_reportage_translations/' + post.translation.id + '/edit'"><i class="fas fa-pencil-alt"></i> перевод</button-link-primary>
                            <button-link-primary v-if="is('translation_editor') && (!post.translation || !post.translation.id)" :to="'/admin/photoreportage/' + post.id + '/photo_reportage_translations/create'"><i class="fas fa-plus-circle"></i> перевод</button-link-primary>
                            <button-action-delete :uri="'/api/admin/photoreportage/' + post.id" @deleted="deleted(post.id)" />
                        </td>
                    </tr>
                    <tr v-if="empty_data">
                        <td class="border-t px-3 py-3" colspan="5">No posts found.</td>
                    </tr>
                </table>
            </div>
            <pagination v-if="photoreportage.links" :pagination="photoreportage" @paginate="setDataPagination" />
        </div>
    </div>
</template>

<script>
    import ButtonLinkPrimary from '@/components/Admin/ButtonLinkPrimary'
    import Pagination from '@/components/Admin/Pagination'
    import ButtonActionDelete from '@/components/Admin/ButtonActionDelete'
    import TextInput from '@/components/Admin/TextInput'
    import SelectInput from '@/components/Admin/SelectInput'
    import ButtonSecondary from '@/components/Admin/ButtonSecondary'
    import ButtonPrimary from '@/components/Admin/ButtonPrimary'
    import MultiselectInput from '@/components/Admin/MultiselectInput.vue'
    import ButtonDefault from '@/components/Admin/ButtonDefault'
    import SelectInput2 from '@/components/Admin/SelectInput2'

    export default {
        components: {
            ButtonLinkPrimary,
            Pagination,
            ButtonActionDelete,
            TextInput,
            SelectInput,
            ButtonSecondary,
            ButtonPrimary,
            MultiselectInput,
            ButtonDefault,
            SelectInput2,
        },
        data: () => ({
            isLoading: true,
            current_page: null,
            photoreportage: [],

            empty_data: false,
        }),
        mounted() {
            this.setData()
        },
        methods: {

            setData(page = '') {
                this.isLoading = true
                var queryParams = {}
                if(page) {
                    queryParams.page = page
                } else if(this.current_page > 0) {
                    queryParams.page = this.current_page
                }

                axios.get('/api/admin/photoreportage', {params: queryParams})
                .then(res => {
                    if(res.data.data.length === 0) {
                        this.empty_data = true
                    }
                    this.current_page = res.data.current_page
                    this.photoreportage = res.data

                })
                .finally(() => this.isLoading = false)
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
