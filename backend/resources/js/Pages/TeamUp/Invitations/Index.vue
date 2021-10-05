<template>
    <app-layout>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <jet-action-section class="mt-10 sm:mt-0">
                <template #title>
                    {{ __('Check invitations') }}
                </template>

                <template #description>
                    {{ __('You can accept/decline invitations here.') }}
                </template>

                <template #content>
                    <div v-if="hasInvitations" class="space-y-6">
                        <p class="">{{ __('You have received the following invitations.') }}</p>
                        <ul class="list-inside border border-gray-300 shadow rounded-md">
                            <li v-for="(invitation, key) in invitations" :key="key">
                                <div :class="{ 'border-b': key !== invitations.length -1 , 'border-gray-300': key !== invitations.length -1 }" class="p-4">
                                    <div class="flex items-center justify-start">
                                        <inertia-link :href="route('see-team-profile.show', invitation.team.id)" class="flex items-center justify-center flex-shrink-0 rounded-full">
                                            <img class="w-12 h-12 rounded-full object-cover hover:opacity-50" :src="invitation.team.team_profile_photo_url" :alt="invitation.team.name">
                                        </inertia-link>
                                        <inertia-link :href="route('see-team-profile.show', invitation.team.id)" class="ml-3 flex items-center justify-center flex-shrink-0 cursor-pointer hover:opacity-50">
                                            {{ invitation.team.name }}
                                        </inertia-link>
                                    </div>
                                    <p class="ml-6 mt-4">{{ __('Invitation to join as ') }}<span class="font-bold">{{ __(invitation.role) }}</span></p>
                                    <p class="ml-6 mt-4">{{ invitation.message }}</p>
                                    <div class="flex mt-4">
                                        <div>
                                            <form method="POST" @submit.prevent="acceptTeamInvitation(invitation)">
                                                <button type="submit" class="cursor-pointer ml-6 text-sm text-blue-500 focus:outline-none">
                                                    {{ __('Accept') }}
                                                </button>
                                            </form>
                                        </div>
                                        <div>
                                            <button @click="declineTeamInvitation(invitation)" class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none">
                                                {{ __('Decline') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div v-else>
                        <p class="">{{ __('You have not received any invitations yet.') }}</p>
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

        props: [
            'invitations',
        ],
        data() {
            return {
                acceptTeamInvitationForm: this.$inertia.form ({
                    invitation: null,
                }),
            }
        },
        methods: {
            acceptTeamInvitation(invitation) {
                this.acceptTeamInvitationForm.invitation = invitation;
                this.acceptTeamInvitationForm.post(this.route('team-invitations.accept'), {
                    preserveScroll: true
                });
            },
            declineTeamInvitation(invitation) {
                this.$inertia.delete(route('invitations.destroy', invitation), {
                    preserveScroll: true
                });
            },
        },
        computed: {
            hasInvitations() {
                return Object.keys(this.invitations).length > 0;
            }
        },
    }
</script>
