<template>
    <div>
        <h1 class="px-8 font-semibold text-xl text-gray-800 leading-tight">
            Материалы Ингушские/ Список
        </h1>
        <div class="px-8 py-8">
            <div class="flex justify-between pb-4">
                <div>
                    <button-default @click="isFilterOpen = !isFilterOpen" :classes="{'bg-green-400': isFilterOpen, 'bg-indigo-400': !isFilterOpen}">
                        <i v-if="isFilterOpen" class="fas fa-chevron-up"></i><i v-else class="fas fa-chevron-down"></i>&nbsp;Фильтр
                    </button-default>
                </div>
                <div class="flex justify-end mb-4">
                    <button-link-primary v-if="is('author')" to="/admin/materials-inh/create">Добавить материал</button-link-primary>
                </div>
            </div>
            <div v-if="isFilterOpen" class="mb-4 bg-white rounded-md shadow p-3">
                <form @submit.prevent="setData(1)">
                    <div class="grid grid-cols-4 gap-2">
                        <text-input v-model="filter.title" :error="filterErrors.title" id="title" label="Заголовок" />
                        <text-input v-model="filter.content" :error="filterErrors.content" id="content" label="Описание" />
                        <div class="pb-4 w-full">
                            <label class="mb-2 block">Опубликовано:</label>
                            <select v-model="filter.status" :class="{ 'input-error': filterErrors.status }" class="block w-full p-2 border rounded border-gray-400">
                                <option :value="null" />
                                <option :value="'yes'">Да</option>
                                <option :value="'no'">Нет</option>
                            </select>
                            <div v-if="filterErrors.status" class="text-red-400">{{ filterErrors.status | displayError }}</div>
                        </div>
                        <div class="pb-4 w-full">
                            <label class="mb-2 block">Выводить файл на главной:</label>
                            <select v-model="filter.promote_with_file" :class="{ 'input-error': filterErrors.promote_with_file }" class="block w-full p-2 border rounded border-gray-400">
                                <option :value="null" />
                                <option :value="'yes'">Да</option>
                                <option :value="'no'">Нет</option>
                            </select>
                            <div v-if="filterErrors.promote_with_file" class="text-red-400">{{ filterErrors.promote_with_file | displayError }}</div>
                        </div>
                        <multiselect-input v-model="filter.category" :options="category" label="Категории" />
                        <multiselect-input v-model="filter.authors" :options="authors" label="Авторы" />
                    </div>
                    Теги: {{filter.category}}
                    <button-primary class="mr-2" type="submit" :isLoading="isLoading">Применить</button-primary>
                    <button-secondary @click="resetFilterData">Сбросить</button-secondary>
                </form>
            </div>
            <div class="bg-white rounded-md shadow overflow-x-auto">
                <table class="table-fixed w-full">
                    <tr class="text-left font-bold">
                        <th class="pl-6 pr-3 py-3 w-28">#</th>
                        <th class="px-3 py-3 max-w-xs">Заголовок</th>
                        <th class="px-3 py-3">Дата создания</th>
                        <th class="pl-3 pr-6 py-3">Действия</th>
                    </tr>
                    <tr v-for="material in materials.data" :key="material.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                        <td class="border-t pl-6 pr-3 py-3">
                            {{ material.id }}
                        </td>
                        <td class="border-t px-3 py-3">
                            <span class="text-sm">{{ material.title }}</span>
                        </td>
                        <td class="border-t px-3 py-3">
                            <div v-if="!material.translation || (!material.translation.title && !material.translation.description)">нет</div>
                            <div v-else-if="material.translation.title && !material.translation.description">только заголовок</div>
                            <div v-else-if="material.translation.description && !material.translation.title">только описание</div>
                            <div v-else>есть</div>
                        </td>
                        <td class="border-t px-3 py-3">
                            <span>{{ material.published_at | displayDateTime }}</span>
                        </td>
                        <td class="border-t pl-3 pr-6 py-3 flex items-center gap-2">
                            <button-link-primary v-is-my="material.user_id" :to="'/admin/materials-inh/' + material.id + '/edit'"><i class="fas fa-pencil-alt"></i></button-link-primary>
                            <button-action-delete :uri="'/api/admin/materials-inh/' + material.id" @deleted="deleted(material.id)" />
                        </td>
                    </tr>
                    <tr v-if="empty_data">
                        <td class="border-t px-3 py-3" colspan="5">No materials found.</td>
                    </tr>
                </table>
            </div>
            <pagination v-if="materials.links" :pagination="materials" @paginate="setDataPagination" />
        </div>
    </div>
</template>

<script>
    import ButtonLinkPrimary from '@/components/Admin/ButtonLinkPrimary'
    import Pagination from '@/components/Admin/Pagination'
    import ButtonActionDelete from '@/components/Admin/ButtonActionDelete'
    import TextInput from "@/components/Admin/TextInput";
    import SelectInput from "@/components/Admin/SelectInput";
    import ButtonSecondary from "@/components/Admin/ButtonSecondary";
    import ButtonPrimary from "@/components/Admin/ButtonPrimary";
    import MultiselectInput from "@/components/Admin/MultiselectInput";
    import ButtonDefault from "@/components/Admin/ButtonDefault";
    import SelectInput2 from "@/components/Admin/SelectInput2";

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
            isFilterOpen: false,
            isFilterLoading: false,
            materials: [],
            filter: {
                title: null,
                content: null,
                translation: null,
                status: null,
                promote_with_file: null,
                category: [],
                authors: [],
            },
            filterErrors: {
                title: null,
                content: null,
                translation: null,
                status: null,
                promote_with_file: null,
                category: null,
                authors: null,
            },
            filterOptions: {
                translation: {'has': 'Есть', 'no': 'Нет'},
            },
            empty_data: false,
            category: [],
        }),
        created() {
            this.setData();
        },
        mounted() {
            this.setData();
            axios.get('/api/admin/categories').then(res => {
                this.category = res.data;
            });
        },
        methods: {
            resetFilterData() {
                this.filter = {
                    title: null,
                    content: null,
                    translation: null,
                    status: null,
                    promote_with_file: null,
                    category: [],
                    authors: [],
                }
                this.setData(1)
            },
            setData(page = '') {
                this.isLoading = true;
                var queryParams = {}
                if(page) {
                    queryParams.page = page
                } else if(this.current_page > 0) {
                    queryParams.page = this.current_page
                }
                for(var filter_field in this.filter) {
                    if(this.filter[filter_field]) {
                        queryParams[filter_field] = this.filter[filter_field]
                    }
                }
                axios.get('/api/admin/materials-inh', {params: queryParams})
                    .then(res => {
                        if(res.data.data.length === 0) {
                            this.empty_data = true
                        }
                        this.current_page = res.data.current_page
                        this.materials = res.data
                        this.isLoading = false;
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
