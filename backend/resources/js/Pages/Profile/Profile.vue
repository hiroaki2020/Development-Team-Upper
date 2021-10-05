<template>
    <app-layout>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <div>
                <jet-action-section class="mt-10 sm:mt-0">
                    <template #title>
                        {{ __('User Profile') }}
                    </template>

                    <template #description>
                        {{ __('You can check this user\'s profile.') }}
                    </template>

                    <template #content>
                        <div v-if="$page.props.jetstream.managesProfilePhotos" class="flex-shrink-0">
                            <img @click="openModal=true" class="h-52 w-52 rounded-lg object-cover cursor-pointer" :src="profile.profile_photo_url" :alt="profile.name" />
                            <modal v-if="openModal===true" @close-modal="openModal=false">
                                <img class="object-cover w-auto" style="height: 70vh;" :src="profile.profile_photo_url" :alt="profile.name" />
                            </modal>
                        </div>
                        <div v-if="$page.props.locale === 'en'" class="flex items-center mt-4">
                            <p class="text-2xl">{{ profile.name }}</p>
                            <button v-if="$page.props.user && profile.id !== $page.props.user.id" type="button" @click="goToChat" class="ml-4 rounded-md flex items-center justify-center focus:outline-none hover:opacity-50">
                                <p class="bg-speech-balloon h-12 w-12 object-cover flex items-center justify-center font-black text-sm">{{ __('Chat') }}</p>
                            </button>
                            <button v-if="!$page.props.user" type="button" @click="goToChat" class="ml-4 rounded-md flex items-center justify-center focus:outline-none hover:opacity-50">
                                {{ __('Login to') }}&nbsp;
                                <p class="bg-speech-balloon h-12 w-12 object-cover flex items-center justify-center font-black text-sm">{{ __('Chat') }}</p>
                            </button>
                        </div>
                        <div v-else class="flex items-center mt-4">
                            <p class="text-2xl">{{ profile.name }}</p>
                            <button v-if="$page.props.user && profile.id !== $page.props.user.id" type="button" @click="goToChat" class="ml-4 rounded-md flex items-center justify-center focus:outline-none hover:opacity-50">
                                <p class="bg-speech-balloon h-12 w-12 object-cover flex items-center justify-center font-black tracking-tighter" style="font-size: 0.5rem;">{{ __('Chat') }}</p>
                            </button>
                            <button v-if="!$page.props.user" type="button" @click="goToChat" class="ml-4 rounded-md flex items-center justify-center focus:outline-none hover:opacity-50">
                                {{ __('Login to') }}&nbsp;
                                <p class="bg-speech-balloon h-12 w-12 object-cover flex items-center justify-center font-black tracking-tighter" style="font-size: 0.5rem;">{{ __('Chat') }}</p>
                            </button>
                        </div>
                        <div class="mt-4">
                            <h3 v-if="profile.description !== null" class="text-lg underline">{{ __('About this user') }}</h3>
                            <p class="break-words whitespace-pre-wrap">{{ profile.description }}</p>
                        </div>
                        <div class="mt-4">
                            <h3 v-if="profile.url !== null" class="text-lg underline">URL</h3>
                            <p>{{ profile.url }}</p>
                        </div>
                        <h3 v-if="profile.skills.length !== 0" class="text-lg underline mt-4">{{ __('Skills') }}</h3>
                        <div v-for="(skill, key) in profile.skills" :key="key" class="flex items-center flex-wrap">
                            <p class="mt-1 block">
                                {{ skill.skill }}
                            </p>
                        </div>
                    </template>
                </jet-action-section>
            </div>
            <div v-if="$page.props.user && profile.id !== $page.props.user.id">
                <jet-section-border />
                <jet-action-section class="mt-10 sm:mt-0">
                    <template #title>
                        {{ __('Team Participation Management') }}
                    </template>

                    <template #description>
                        {{ __('Authorized user can manage this user\'s participation in your current team.') }}
                    </template>

                    <template #content>
                        <div v-if="$page.props.hasTeams">
                            <div v-if="member_or_not">
                                <p>{{ __('This user belongs to your current team.') }}</p>
                            </div>
                            
                            <div v-else-if="(requested ?? false) && permissions.canAddTeamMembers"> 
                                <div class="space-y-6">
                                    <div>
                                        <h3 class="text-lg underline">{{ __('Pending Join Request') }}</h3>
                                        <p class="">{{ __('This user has sent a request to join your current team and waiting for your reply. The user joins the team if you accept the request.') }}</p>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="flex flex-col items-start justify-center">
                                            <p>{{ __('Request to join as ') }}<span class="font-bold">{{ __(requested.role) }}</span></p>
                                            <p class="text-sm text-gray-700">{{ requested.message }}</p>
                                        </div>

                                        <div class="flex items-center flex-shrink-0">
                                            <!-- Accept Join Request -->
                                            <form method="POST" @submit.prevent="acceptJoinRequest(requested)">
                                                <button v-if="permissions.canAddTeamMembers" type="submit" class="cursor-pointer ml-6 text-sm text-blue-500 focus:outline-none">
                                                    {{ __('Accept') }}
                                                </button>
                                            </form>
                                        
                                            <!-- Decline Join Request -->
                                            <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none"
                                                                @click="declineJoinRequest(requested)"
                                                                v-if="permissions.canRemoveTeamMembers">
                                                {{ __('Decline') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-else-if="(inviting ?? false) && permissions.canAddTeamMembers">    
                                <div class="space-y-6">
                                    <div class="">
                                        <h3 class="text-lg underline">{{ __('Pending Invitation') }}</h3>
                                        <p>{{ __('You are currently inviting this user to your current team. Do you want to cancel the invitation?') }}</p>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="flex flex-col items-start justify-center">
                                            <p>{{ __('Invitation to join as') }}&nbsp;<span class="font-bold">{{ __(inviting.role) }}</span></p>
                                            <p class="text-sm text-gray-700">{{ inviting.message }}</p>
                                        </div>

                                        <div class="flex items-center flex-shrink-0">
                                            <!-- Cancel Team Invitation -->
                                            <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none"
                                                                @click="cancelTeamInvitation(inviting)"
                                                                v-if="permissions.canRemoveTeamMembers">
                                                {{ __('Cancel') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-else-if="permissions.canAddTeamMembers">
                                <!-- Add Team Member -->
                                <form @submit.prevent="addTeamMember">
                                    <div class="pb-4">
                                        <div class="grid grid-cols-6 gap-6">
                                            <div class="col-span-6 sm:col-span-4">
                                                <h3 class="text-lg underline">{{ __('Send Invitation') }}</h3>
                                                <p>{{ __('To invite this user to your current team, please fill in the form below.') }}</p>
                                            </div>

                                            <!-- Role -->
                                            <div class="col-span-6 lg:col-span-4" v-if="availableRoles.length > 0">
                                                <jet-label for="roles" value="Role" />
                                                <jet-input-error :message="addTeamMemberForm.errors.role" class="mt-2" />

                                                <div class="relative z-0 mt-1 border border-gray-200 rounded-lg cursor-pointer">
                                                    <button type="button" class="relative px-4 py-3 inline-flex w-full rounded-lg focus:z-10 focus:outline-none"
                                                                    :class="{'border-t border-gray-200 rounded-t-none': i > 0, 'rounded-b-none': i != Object.keys(availableRoles).length - 1}"
                                                                    @click="addTeamMemberForm.role = role.key"
                                                                    v-for="(role, i) in availableRoles"
                                                                    :key="role.key">
                                                        <div :class="{'opacity-50': addTeamMemberForm.role && addTeamMemberForm.role != role.key}">
                                                            <!-- Role Name -->
                                                            <div class="flex items-center">
                                                                <div class="text-sm text-gray-600" :class="{'font-semibold': addTeamMemberForm.role == role.key}">
                                                                    {{ __(role.name) }}
                                                                </div>

                                                                <svg v-if="addTeamMemberForm.role == role.key" class="ml-2 h-5 w-5 text-green-400" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                            </div>

                                                            <!-- Role Description -->
                                                            <div class="mt-2 text-xs text-gray-600">
                                                                {{ __(role.description) }}
                                                            </div>
                                                        </div>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Message -->
                                            <div class="col-span-6">
                                                <jet-label for="message" value="message" />
                                                <jet-text-area id="message" type="text" class="mt-1 block w-full" v-model="addTeamMemberForm.message" />
                                                <jet-input-error :message="addTeamMemberForm.errors.message" class="mt-2" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-end pt-3 text-right">
                                        <jet-button :class="{ 'opacity-25': addTeamMemberForm.processing }" :disabled="addTeamMemberForm.processing">
                                            {{ __('Invite') }}
                                        </jet-button>
                                    </div>
                                </form>
                            </div>
                            <div v-else>
                                <p>{{ __('You are not authorized to add/invite team members.') }}</p>
                            </div>
                        </div>
                        <div v-else>
                            <p>{{ __('You do not own/belong to any team.') }}</p>
                            <inertia-link :href="route('teams.create')" class="underline text-blue-500 hover:text-purple-700">
                                {{ __('Let\'s create one now!') }}
                            </inertia-link>
                        </div>
                    </template>
                </jet-action-section>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import JetButton from '@/Jetstream/Button'
    import JetFormSection from '@/Jetstream/FormSection'
    import JetInput from '@/Jetstream/Input'
    import JetInputError from '@/Jetstream/InputError'
    import JetLabel from '@/Jetstream/Label'
    import JetSectionBorder from '@/Jetstream/SectionBorder'
    import JetTextArea from '@/Jetstream/TextArea'
    import JetActionSection from '@/Jetstream/ActionSection'
    import Modal from '@/Pages/Modal'

    export default {
        components: {
            JetButton,
            JetFormSection,
            JetInput,
            JetInputError,
            JetLabel,
            JetSectionBorder,
            JetTextArea,
            JetActionSection,
            Modal,
        },

        props: [
            'profile',
            'team',
            'requested',
            'inviting',
            'permissions',
            'availableRoles',
            'member_or_not'
        ],

        data() {
            return {
                openModal: false,

                acceptJoinRequestForm: this.$inertia.form ({
                    join_request: null,
                }),
                addTeamMemberForm: this.$inertia.form({
                    id: this.profile.id,
                    role: null,
                    message: '',
                }),
                lastChat: {
                    isGroupChat: false,
                    id: this.profile.id,
                },
            }
        },

        methods: {
            goToChat() {
                this.$inertia.post(route('chat.store'), {profile_id: this.profile.id}, {});
            },

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

            addTeamMember() {
                this.addTeamMemberForm.post(route('team-members.store', this.team.id), {
                    errorBag: 'addTeamMember',
                    preserveScroll: true,
                    onSuccess: () => this.addTeamMemberForm.reset(),
                });
            },

            cancelTeamInvitation(invitation) {
                this.$inertia.delete(route('team-invitations.destroy', invitation), {
                    preserveScroll: true
                });
            },
        },
    }
</script>
