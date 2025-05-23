<template>
    <div>
        <h1 class="font-semibold text-xl text-gray-800 leading-tight">
            Вход
        </h1>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="max-w-3xl bg-white rounded-md shadow">
                <login-form />
            </div>
        </div>
    </div>
</template>

<script>
    import LoginForm from '@/components/LoginForm'

    export default {
        components: {
            LoginForm,
        },
    }
</script>