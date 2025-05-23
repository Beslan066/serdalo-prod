<template>
    <router-link :to="to" class="min-h-9 inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition">
        <span>
            <slot></slot>
        </span>
    </router-link>
</template>

<script>
    export default {
        props: {
            to: String,
        }
    }
</script>
