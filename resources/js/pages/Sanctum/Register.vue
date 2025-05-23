<template>
    <div>
        <h1 class="font-semibold text-xl text-gray-800 leading-tight">
            Sanctum/Register
        </h1>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="max-w-3xl bg-white rounded-md shadow">
                <register-form />
            </div>
        </div>
    </div>
</template>

<script>
    import RegisterForm from '@/components/RegisterForm'

    export default {
        components: {
            RegisterForm,
        },
    }
</script>