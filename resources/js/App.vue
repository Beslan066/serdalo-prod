<template>
    <div>
        <flash-message></flash-message>
        <component :is="layout"></component>
    </div>
</template>

<script>
    import { USER_REQUEST } from '@/store/actions/user'

    import AdminDefault from '@/layouts/Admin/AdminDefault'
    import Default from '@/layouts/Default'
    import FlashMessage from '@/components/FlashMessage'

    export default {
        components: {
            AdminDefault,
            Default,
            FlashMessage,
        },
        data: () => ({
            defaultLayout: 'default',
        }),
        created: function() {
            if (this.$store.getters.isAuthenticated) {
                this.$store.dispatch(USER_REQUEST);
            }
        },
        computed: {
            layout() {
                return this.$route.meta.layout ? this.$route.meta.layout : this.defaultLayout
            },
        },
    }
</script>