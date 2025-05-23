<template>
    <div>
        <h1 class="px-8 font-semibold text-xl text-gray-800 leading-tight">
            Админ/Пользователи/Создать
        </h1>
        <div class="max-w-7xl px-8 py-8">
            <div class="max-w-3xl bg-white rounded-md shadow">
                <form @submit.prevent="store">
                    <div class="p-6">
                        <text-input v-model="form.name" :error="errors.name" id="name" label="Имя" />
                        <text-input v-model="form.email" :error="errors.email" id="email" label="Email" />
                        <div class="pb-4 w-full">
                            <label class="mb-2 block">Статус:</label>
                            <select v-model="form.status" :class="{ 'input-error': errors.status }" class="block w-full p-2 border rounded border-gray-400">
                                <option :value="'active'">Активен</option>
                                <option :value="'blocked'">Заблокирован</option>
                            </select>
                            <div v-if="errors.status" class="text-red-400">{{ displayStatusError }}</div>
                        </div>
                        <div class="pb-4 w-full">
                            <label class="mb-2 block">Роль:</label>
                            <select v-model="form.role" :class="{ 'input-error': errors.role }" class="block w-full p-2 border rounded border-gray-400">
                                <option :value="'author'">Автор</option>
                                <option :value="'translation_editor'">Редактор переводов</option>
                                <option :value="'admin'">Администратор</option>
                            </select>
                            <div v-if="errors.role" class="text-red-400">{{ displayRoleError }}</div>
                        </div>
                        <text-input v-model="form.password" :error="errors.password" id="password" label="Пароль" type="password" />
                        <button-primary type="submit" :isLoading="isStoreloading">Создать</button-primary>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import { eventBus } from '@/app'

    import TextInput from '@/components/Admin/TextInput'
    import ButtonPrimary from '@/components/Admin/ButtonPrimary'

    export default {
        components: {
            TextInput,
            ButtonPrimary,
        },
        data: () => ({
            isStoreloading: false,
            form: {
                name: null,
                email: null,
                status: 'active',
                role: 'author',
                password: null,
            },
            errors: {},
        }),
        computed: {
            displayStatusError() {
                return (Array.isArray(this.errors.status)) ? this.errors.status.join('; ') : this.errors.status
            },
            displayRoleError() {
                return (Array.isArray(this.errors.role)) ? this.errors.role.join('; ') : this.errors.role
            },
        },
        methods: {
            store() {
                this.isStoreloading = true
                axios.post('/api/admin/users', this.form)
                .then(res => {
                    this.errors = {}
                    eventBus.$emit('flash-message', 'success', res.data.success);
                    this.$router.push({name: 'admin-users-index'})
                })
                .catch(err => {
                    this.errors = {}
                    if(err.response.data.errors) {
                        eventBus.$emit('flash-message', 'error', 'The given data was invalid.');
                        this.errors = err.response.data.errors
                    }
                })
                .finally(() => this.isStoreloading = false)
            },
        },
    }
</script>
