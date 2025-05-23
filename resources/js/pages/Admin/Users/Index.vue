<template>
    <div>
        <h1 class="px-8 font-semibold text-xl text-gray-800 leading-tight">
            Админ/Пользователи/Главная
        </h1>
        <div class="px-8 py-8">
            <div class="flex justify-end mb-4">
                <button-link-primary to="/admin/users/create">Создать пользователя</button-link-primary>
            </div>
            <div class="bg-white rounded-md shadow overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                    <tr class="text-left font-bold">
                        <th class="px-6 pt-6 pb-4">#</th>
                        <th class="px-6 pt-6 pb-4">Имя</th>
                        <th class="px-6 pt-6 pb-4">Email</th>
                        <th class="px-6 pt-6 pb-4">Роль</th>
                        <th class="px-6 pt-6 pb-4">Создан</th>
                        <th class="px-6 pt-6 pb-4">Обновлен</th>
                        <th class="px-6 pt-6 pb-4">Действия</th>
                    </tr>
                    <tr v-for="user in users.data" :key="user.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                        <td class="border-t px-6 pt-6 pb-4">
                            {{ user.id }}
                        </td>
                        <td class="border-t px-6 pt-6 pb-4">
                            {{ user.name }}
                        </td>
                        <td class="border-t px-6 pt-6 pb-4">
                            {{ user.email }}
                        </td>
                        <td class="border-t px-6 pt-6 pb-4">
                            <span v-if="user.roles && user.roles[0]">
                                {{user.roles[0]['name']}}
                            </span>
                        </td>
                        <td class="border-t px-6 pt-6 pb-4">
                            {{ user.created_at | displayDateTime }}
                        </td>
                        <td class="border-t px-6 pt-6 pb-4">
                            {{ user.updated_at | displayDateTime }}
                        </td>
                        <td class="border-t px-6 pt-6 pb-4 flex items-center gap-2">
                            <button-link-primary :to="'/admin/users/' + user.id + '/edit'">Изменить</button-link-primary>
                            <button-action-delete :uri="'/api/admin/users/' + user.id" @deleted="deleted(user.id)" />
                        </td>
                    </tr>
                    <tr v-if="empty_data">
                        <td class="border-t px-6 py-4" colspan="4">No users found.</td>
                    </tr>
                </table>
            </div>
            <pagination v-if="users.links" :pagination="users" @paginate="setDataPagination" />
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
            users: [],
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
                axios.get('/api/admin/users', {params: queryParams})
                .then(res => {
                    if(res.data.data.length === 0) {
                        this.empty_data = true
                    }
                    this.current_page = res.data.current_page
                    this.users = res.data
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
