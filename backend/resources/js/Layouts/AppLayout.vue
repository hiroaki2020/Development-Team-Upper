<template>
    <div class="bg-blue-100 w-screen min-h-screen relative">
        <nav-bar />
        <!-- Page Heading -->
        <header class="" v-if="$slots.header">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <slot name="header"></slot>
            </div>
        </header>

        <!-- Page Content -->
        <main class="h-full">
            <slot class="h-full"></slot>
        </main>
        <get-started-modal v-if="!route().current('home')" />
    </div>
</template>

<script>
    import NavBar from '@/Pages/NavBar'
    import GetStartedModal from '@/Pages/GetStartedModal'

    export default {
        components: {
            NavBar,
            GetStartedModal,
        },
    }
</script>
