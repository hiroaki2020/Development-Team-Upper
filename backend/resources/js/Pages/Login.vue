<template>
        <div class="hidden px-6 py-4 sm:block">
            <div class="flex">
                <inertia-link href="/" class="text-sm text-gray-700 underline mr-4">
                    {{ __('Home') }}
                </inertia-link>

                <template v-if="!$page.props.user" class="flex">
                    <inertia-link :href="route('login')" class="text-sm text-gray-700 underline">
                        {{ __('Log in') }}
                    </inertia-link>

                    <inertia-link :href="route('register')" class="ml-4 text-sm text-gray-700 underline">
                        {{ __('Register') }}
                    </inertia-link>
                </template>
            </div>
        </div>
</template>

<script>
    export default {
        props: {

        }
    }
</script>
