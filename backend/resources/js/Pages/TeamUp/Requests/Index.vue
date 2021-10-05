<template>
    <app-layout>
        <div v-if="canAddTeamMembers" class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <jet-action-section class="mt-10 sm:mt-0">
                <template #title>
                    {{ __('Check join requests') }}
                </template>

                <template #description>
                    {{ __('You can accept/decline join requests here.') }}
                </template>

                <template #content>
                    <div v-if="$page.props.hasTeams">
                        <div v-if="hasRequests" class="space-y-6">
                            <p class="">{{ __('You have received the following requests.') }}</p>
                            <ul class="list-inside border border-gray-300 shadow rounded-md">
                                <li v-for="(request, key) in joinRequests" :key="key">
                                    <div :class="{ 'border-b': key !== joinRequests.length -1 , 'border-gray-300': key !== joinRequests.length -1 }" class="p-4">
                                        <div class="flex items-center justify-start">
                                            <inertia-link :href="route('see-profile.show', request.user.id)" class="flex items-center justify-center flex-shrink-0 rounded-full">
                                                <img class="w-12 h-12 rounded-full object-cover hover:opacity-50" :src="request.user.profile_photo_url" :alt="request.user.name"> 
                                            </inertia-link>
                                            <inertia-link :href="route('see-profile.show', request.user.id)" class="ml-3 flex items-center justify-center flex-shrink-0 cursor-pointer hover:opacity-50">
                                                {{ request.user.name }}
                                            </inertia-link>
                                        </div>
                                        <p class="ml-6 mt-4">{{ __('Request to join as ') }}<span class="font-bold">{{ __(request.role) }}</span></p>
                                        <p class="ml-6 mt-4">{{ request.message }}</p>
                                        <div class="flex mt-4">
                                            <div>
                                                <form method="POST" @submit.prevent="acceptJoinRequest(request)">
                                                    <button type="submit" class="cursor-pointer ml-6 text-sm text-blue-500 focus:outline-none">
                                                        {{ __('Accept') }}
                                                    </button>
                                                </form>
                                            </div>
                                            <div>
                                                <button @click="declineJoinRequest(request)" class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none">
                                                    {{ __('Decline') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div v-else>
                            <p class="">{{ __('You have not received any requests yet.') }}</p>
                        </div>
                    </div>
                    <div v-else class="flex flex-col items-center justify-center">
                        <p>{{ __('You don\'t belong to any team yet.') }}</p>
                        <inertia-link :href="route('teams.create')" class="underline text-sm text-blue-500 hover:text-pink-900">
                            {{ __('Let\'s create one now!') }}
                        </inertia-link>
                    </div>
                </template>
            </jet-action-section>
        </div>
        <div v-else class="flex items-center justify-center bg-black h-full w-full">
            <p class="text-3xl text-white">{{ __('You are not authorized to add team members.') }}</p>
        </div>
    </app-layout>
</template>

<script>
    import JetActionSection from '@/Jetstream/ActionSection'

    export default {
        components: {
            JetActionSection,
        },

        props: [
            'joinRequests',
            'canAddTeamMembers',
        ],

        data() {
            return {
                acceptJoinRequestForm: this.$inertia.form ({
                    join_request: null,
                }),
            }
        },

        methods: {
            acceptJoinRequest(join_request) {
                this.acceptJoinRequestForm.join_request = join_request;
                this.acceptJoinRequestForm.post(this.route('handle-join-requests.accept'), {
                    preserveScroll: true
                });
            },
            declineJoinRequest(join_request) {
                this.$inertia.delete(route('handle-join-requests.decline', join_request), {
                    preserveScroll: true
                });
            },
        },

        computed: {
            hasRequests() {
                return Object.keys(this.joinRequests).length > 0;
            }
        },
    }
</script>
