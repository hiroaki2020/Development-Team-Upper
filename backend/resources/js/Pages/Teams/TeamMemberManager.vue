<template>
    <div>
        <div v-if="team.join_requests.length > 0 && userPermissions.canAddTeamMembers">
            <!-- Join Requests -->
            <jet-action-section class="mt-10 sm:mt-0">
                <template #title>
                    {{ __('Pending Join Requests') }}
                </template>

                <template #description>
                    {{ __('These people have sent a request to join your current team and waiting for your reply. They join the team if you accept the request.') }}
                </template>

                <!-- Pending Join Requests List -->
                <template #content>
                    <div class="space-y-6">
                        <div class="flex items-center justify-between" v-for="join_request in team.join_requests" :key="join_request.id">
                            <inertia-link :href="route('see-profile.show', join_request.user.id)" class="block flex items-center">
                                <img class="w-12 h-12 rounded-full" :src="join_request.user.profile_photo_url" :alt="join_request.user.name">
                                <div class="ml-4 flex flex-col items-start justify-center">
                                    <div>{{ join_request.user.name }}</div>
                                    <div class="text-sm">{{ __('Request to join as ') }}<span class="font-bold">{{ __(join_request.role) }}</span></div>
                                    <div class="break-words whitespace-pre-wrap text-sm text-gray-700">{{ join_request.message }}</div>
                                </div>
                            </inertia-link>

                            <div class="flex items-center">
                                <!-- Accept Join Request -->
                                <form method="POST" @submit.prevent="acceptJoinRequest(join_request)">
                                    <button v-if="userPermissions.canAddTeamMembers" type="submit" class="cursor-pointer ml-6 text-sm text-blue-500 focus:outline-none">
                                        {{ __('Accept') }}
                                    </button>
                                </form>
                                
                                <!-- Decline Join Request -->
                                <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none"
                                                    @click="declineJoinRequest(join_request)"
                                                    v-if="userPermissions.canRemoveTeamMembers">
                                    {{ __('Decline') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </jet-action-section>
            <jet-section-border />
        </div>

        <div v-if="team.team_invitations.length > 0 && userPermissions.canAddTeamMembers">

            <!-- Team Member Invitations -->
            <jet-action-section class="mt-10 sm:mt-0">
                <template #title>
                    {{ __('Pending Team Invitations') }}
                </template>

                <template #description>
                    {{ __('These people have been invited to your current team and have been sent an invitation. They may join the team by accepting the invitation.') }}
                </template>

                <!-- Pending Team Member Invitation List -->
                <template #content>
                    <div class="space-y-6">
                        <div class="flex items-center justify-between" v-for="invitation in team.team_invitations" :key="invitation.id">
                            <inertia-link :href="route('see-profile.show', invitation.user.id)" class="block flex items-center">
                                <img class="w-12 h-12 rounded-full" :src="invitation.user.profile_photo_url" :alt="invitation.user.name">
                                <div class="ml-4 flex flex-col items-start justify-center">
                                    <div class="flex items-center justify-start">{{ invitation.user.name }}</div>
                                    <div class="text-sm">{{ __('Invitation to join as') }}&nbsp;<span class="font-bold">{{ __(invitation.role) }}</span></div>
                                    <div class="break-words whitespace-pre-wrap text-sm text-gray-700">{{ invitation.message }}</div>
                                </div>
                            </inertia-link>

                            <div class="flex items-center">
                                <!-- Cancel Team Invitation -->
                                <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none"
                                                    @click="cancelTeamInvitation(invitation)"
                                                    v-if="userPermissions.canRemoveTeamMembers">
                                    {{ __('Cancel') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </jet-action-section>
            <jet-section-border />
        </div>

        <div v-if="team.users.length > 0">

            <!-- Manage Team Members -->
            <jet-action-section class="mt-10 sm:mt-0">
                <template #title>
                    {{ __('Team Members') }}
                </template>

                <template #description>
                    {{ __('All of the people that are part of this team.') }}
                </template>

                <!-- Team Member List -->
                <template #content>
                    <div class="space-y-6">
                        <div class="flex items-center justify-between" v-for="user in team.users" :key="user.id">
                            <inertia-link :href="route('see-profile.show', user.id)" class="block flex items-center">
                                <img class="w-12 h-12 rounded-full" :src="user.profile_photo_url" :alt="user.name">
                                <div class="ml-4">{{ user.name }}</div>
                            </inertia-link>

                            <div class="flex items-center">
                                <!-- Manage Team Member Role -->
                                <button class="ml-2 text-sm text-gray-400 underline"
                                        @click="manageRole(user)"
                                        v-if="userPermissions.canUpdateTeamMemberRole && availableRoles.length">
                                    {{ __(displayableRole(user.membership.role)) }}
                                </button>

                                <div class="ml-2 text-sm text-gray-400" v-else-if="availableRoles.length">
                                    {{ __(displayableRole(user.membership.role)) }}
                                </div>

                                <!-- Leave Team -->
                                <button class="cursor-pointer ml-6 text-sm text-red-500"
                                                    @click="confirmLeavingTeam"
                                                    v-if="$page.props.user.id === user.id">
                                    {{ __('Leave') }}
                                </button>

                                <!-- Remove Team Member -->
                                <button class="cursor-pointer ml-6 text-sm text-red-500"
                                                    @click="confirmTeamMemberRemoval(user)"
                                                    v-if="userPermissions.canRemoveTeamMembers && $page.props.user.id !== user.id">
                                    {{ __('Remove') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </jet-action-section>
            <jet-section-border v-if="userPermissions.canDeleteTeam" />
        </div>

        <!-- Role Management Modal -->
        <jet-dialog-modal :show="currentlyManagingRole" @close="currentlyManagingRole = false">
            <template #title>
                {{ __('Manage Role') }}
            </template>

            <template #content>
                <div v-if="managingRoleFor">
                    <div class="relative z-0 mt-1 border border-gray-200 rounded-lg cursor-pointer">
                        <button type="button" class="relative px-4 py-3 inline-flex w-full rounded-lg focus:z-10 focus:outline-none"
                                        :class="{'border-t border-gray-200 rounded-t-none': i > 0, 'rounded-b-none': i !== Object.keys(availableRoles).length - 1}"
                                        @click="updateRoleForm.role = role.key"
                                        v-for="(role, i) in availableRoles"
                                        :key="role.key">
                            <div :class="{'opacity-50': updateRoleForm.role && updateRoleForm.role !== role.key}">
                                <!-- Role Name -->
                                <div class="flex items-center">
                                    <div class="text-sm text-gray-600" :class="{'font-semibold': updateRoleForm.role === role.key}">
                                        {{ __(role.name) }}
                                    </div>

                                    <svg v-if="updateRoleForm.role === role.key" class="ml-2 h-5 w-5 text-green-400" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>

                                <!-- Role Description -->
                                <div class="mt-2 text-xs text-gray-600">
                                    {{ __(role.description) }}
                                </div>
                            </div>
                        </button>
                    </div>
                </div>
            </template>

            <template #footer>
                <jet-secondary-button @click="currentlyManagingRole = false">
                    {{ __('Cancel') }}
                </jet-secondary-button>

                <jet-button class="ml-2" @click="updateRole" :class="{ 'opacity-25': updateRoleForm.processing }" :disabled="updateRoleForm.processing">
                    {{ __('Save') }}
                </jet-button>
            </template>
        </jet-dialog-modal>

        <!-- Leave Team Confirmation Modal -->
        <jet-confirmation-modal :show="confirmingLeavingTeam" @close="confirmingLeavingTeam = false">
            <template #title>
                {{ __('Leave Team') }}
            </template>

            <template #content>
                {{ __('Are you sure you would like to leave this team?') }}
            </template>

            <template #footer>
                <jet-secondary-button @click="confirmingLeavingTeam = false">
                    {{ __('Cancel') }}
                </jet-secondary-button>

                <jet-danger-button class="ml-2" @click="leaveTeam" :class="{ 'opacity-25': leaveTeamForm.processing }" :disabled="leaveTeamForm.processing">
                    {{ __('Leave') }}
                </jet-danger-button>
            </template>
        </jet-confirmation-modal>

        <!-- Remove Team Member Confirmation Modal -->
        <jet-confirmation-modal :show="teamMemberBeingRemoved" @close="teamMemberBeingRemoved = null">
            <template #title>
                {{ __('Remove Team Member') }}
            </template>

            <template #content>
                {{ __('Are you sure you would like to remove this user from the team?') }}
            </template>

            <template #footer>
                <jet-secondary-button @click="teamMemberBeingRemoved = null">
                    {{ __('Cancel') }}
                </jet-secondary-button>

                <jet-danger-button class="ml-2" @click="removeTeamMember" :class="{ 'opacity-25': removeTeamMemberForm.processing }" :disabled="removeTeamMemberForm.processing">
                    {{ __('Remove') }}
                </jet-danger-button>
            </template>
        </jet-confirmation-modal>
    </div>
</template>

<script>
    import JetActionMessage from '@/Jetstream/ActionMessage'
    import JetActionSection from '@/Jetstream/ActionSection'
    import JetButton from '@/Jetstream/Button'
    import JetConfirmationModal from '@/Jetstream/ConfirmationModal'
    import JetDangerButton from '@/Jetstream/DangerButton'
    import JetDialogModal from '@/Jetstream/DialogModal'
    import JetFormSection from '@/Jetstream/FormSection'
    import JetInput from '@/Jetstream/Input'
    import JetInputError from '@/Jetstream/InputError'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton'
    import JetSectionBorder from '@/Jetstream/SectionBorder'
    import JetTextArea from '@/Jetstream/TextArea'

    export default {
        components: {
            JetActionMessage,
            JetActionSection,
            JetButton,
            JetConfirmationModal,
            JetDangerButton,
            JetDialogModal,
            JetFormSection,
            JetInput,
            JetInputError,
            JetSecondaryButton,
            JetSectionBorder,
            JetTextArea,
        },

        props: [
            'team',
            'availableRoles',
            'userPermissions'
        ],

        data() {
            return {
                acceptJoinRequestForm: this.$inertia.form ({
                    join_request: null,
                }),

                updateRoleForm: this.$inertia.form({
                    role: null,
                }),

                leaveTeamForm: this.$inertia.form(),
                removeTeamMemberForm: this.$inertia.form(),

                currentlyManagingRole: false,
                managingRoleFor: null,
                confirmingLeavingTeam: false,
                teamMemberBeingRemoved: null,
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

            cancelTeamInvitation(invitation) {
                this.$inertia.delete(route('team-invitations.destroy', invitation), {
                    preserveScroll: true
                });
            },

            manageRole(teamMember) {
                this.managingRoleFor = teamMember
                this.updateRoleForm.role = teamMember.membership.role
                this.currentlyManagingRole = true
            },

            updateRole() {
                this.updateRoleForm.put(route('team-members.update', [this.team, this.managingRoleFor]), {
                    preserveScroll: true,
                    onSuccess: () => (this.currentlyManagingRole = false),
                })
            },

            confirmLeavingTeam() {
                this.confirmingLeavingTeam = true
            },

            leaveTeam() {
                this.leaveTeamForm.delete(route('team-members.destroy', [this.team, this.$page.props.user]))
            },

            confirmTeamMemberRemoval(teamMember) {
                this.teamMemberBeingRemoved = teamMember
            },

            removeTeamMember() {
                this.removeTeamMemberForm.delete(route('team-members.destroy', [this.team, this.teamMemberBeingRemoved]), {
                    errorBag: 'removeTeamMember',
                    preserveScroll: true,
                    preserveState: true,
                    onSuccess: () => (this.teamMemberBeingRemoved = null),
                })
            },

            displayableRole(role) {
                return this.availableRoles.find(r => r.key === role).name
            },
        },
    }
</script>
