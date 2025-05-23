<template>
    <router-link 
        :to="to" 
        class="min-h-9 inline-flex items-center px-4 py-2 bg-indigo-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition"
    >
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
