<template>
    <app-layout>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <jet-action-section>
                <template #title>
                    {{ __('No Team') }}
                </template>

                <template #description>
                    {{ __('You need to own/belong to at least one team to do it.') }}
                </template>

                <template #content>
                    <div class="flex items-center">
                        <p>{{ __('You do not own/belong to any team.') }}&nbsp;</p>
                        <inertia-link :href="route('teams.create')" class="underline block text-blue-500 hover:text-purple-700">
                            {{ __('Let\'s create one now!') }}
                        </inertia-link>
                    </div>
                </template>
            </jet-action-section>
        </div>
    </app-layout>
</template>

<script>
    import JetActionSection from '@/Jetstream/ActionSection'

    export default {
        components: {
            JetActionSection,
        },    
    }
</script>
