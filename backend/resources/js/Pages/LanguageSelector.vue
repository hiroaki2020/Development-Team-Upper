<template>
    <div class="flex text-sm text-gray-700">
        <div v-if="selectable_language === 'en'" class="pr-3 border-black border-r">日本語</div>
        <inertia-link :href="route('language', { lang: selectable_language })">
            <div v-if="selectable_language === 'en'" class="pl-3 underline text-blue-600 hover:text-purple-700">English</div>
            <div v-else class="pr-3 border-black border-r underline text-blue-600 hover:text-purple-700">日本語</div>
        </inertia-link>
        <div v-if="selectable_language === 'ja'" class="pl-3">English</div>
    </div>
</template>

<script>
export default {
    computed: {
        selectable_language() {
            if(this.$page.props.locale === 'en') {
                return 'ja';
            }
            return 'en';
        },
    }
}
</script>