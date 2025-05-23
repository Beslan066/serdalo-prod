<template>
    <div>
        <h1 class="px-8 font-semibold text-xl text-gray-800 leading-tight">
            Админ/Файлы/Главная
        </h1>
        <div class="px-8 py-8">
            <files :selectable="false" :filesConfig="getFilesConfig()"></files>
        </div>
    </div>
</template>

<script>
    import Files from '@/components/Admin/Files/Files'

    export default {
        components: {
            Files,
        },
    }
</script>
