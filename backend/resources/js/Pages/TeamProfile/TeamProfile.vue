<template>
    <app-layout>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <div>
                <jet-action-section class="mt-10 sm:mt-0">
                    <template #title>
                        {{ __('Team Profile') }}
                    </template>

                    <template #description>
                        {{ __('You can check this team\'s profile.') }}
                    </template>

                    <template #content>
                        <div v-if="$page.props.jetstream.managesProfilePhotos" class="flex-shrink-0">
                            <img @click="openModal=true" class="h-52 w-52 rounded-lg object-cover cursor-pointer" :src="team.team_profile_photo_url" :alt="team.name" />
                            <modal v-if="openModal===true" @close-modal="openModal=false">
                                <img class="object-cover w-auto" style="height: 70vh;" :src="team.team_profile_photo_url" :alt="team.name" />
                            </modal>
                        </div>
                        <div v-if="$page.props.locale === 'en'" class="flex items-center mt-4">
                            <p class="text-2xl">{{ team.name }}</p>
                            <button v-if="$page.props.user && !isMember" type="button" @click="goToChat" class="ml-4 rounded-md flex items-center justify-center focus:outline-none hover:opacity-50">
                                <p class="bg-speech-balloon h-12 w-12 object-cover flex items-center justify-center font-black text-sm">{{ __('Chat') }}</p>
                                <p>&nbsp;{{ __('with') }} {{ team.owner.name }}({{ __('team owner') }})</p>
                            </button>
                            <button v-if="!$page.props.user" type="button" @click="goToChat" class="ml-4 rounded-md flex items-center justify-center focus:outline-none hover:opacity-50">
                                {{ __('Login to') }}&nbsp;
                                <p class="bg-speech-balloon h-12 w-12 object-cover flex items-center justify-center font-black text-sm">{{ __('Chat') }}</p>
                                <p>&nbsp;{{ __('with') }} {{ team.owner.name }}({{ __('team owner') }})</p>
                            </button>
                        </div>
                        <div v-else class="flex items-center mt-4">
                            <p class="text-2xl">{{ team.name }}</p>
                            <button v-if="$page.props.user && !isMember" type="button" @click="goToChat" class="ml-4 rounded-md flex items-center justify-center focus:outline-none hover:opacity-50">
                                <p class="bg-speech-balloon h-12 w-12 object-cover flex items-center justify-center font-black tracking-tighter" style="font-size: 0.5rem;">{{ __('Chat') }}</p>
                                <p>&nbsp;{{ __('with') }} {{ team.owner.name }}({{ __('team owner') }})</p>
                            </button>
                            <button v-if="!$page.props.user" type="button" @click="goToChat" class="ml-4 rounded-md flex items-center justify-center focus:outline-none hover:opacity-50">
                                {{ __('Login to') }}&nbsp;
                                <p class="bg-speech-balloon h-12 w-12 object-cover flex items-center justify-center font-black tracking-tighter" style="font-size: 0.5rem;">{{ __('Chat') }}</p>
                                <p>&nbsp;{{ __('with') }} {{ team.owner.name }}({{ __('team owner') }})</p>
                            </button>
                        </div>
                        <jet-label value="Team Owner" class="mt-4" />
                        <inertia-link :href="route('see-profile.show', team.owner.id)" class="flex items-center mt-1">
                            <img class="w-12 h-12 rounded-full object-cover" :src="team.owner.profile_photo_url" :alt="team.owner.name">
                            <div class="ml-4 leading-tight">
                                <div>{{ team.owner.name }}</div>
                                <div v-if="team.owner.description !== null" class="text-gray-700 text-sm">{{ team.owner.description }}</div>
                            </div>
                        </inertia-link>
                        <div v-if="hasAnyUser" class="mt-4">
                            <jet-label value="Team Member" />
                            <div class="flex items-center" v-for="(user, key) in team.users" :key="key">
                                <inertia-link :href="route('see-profile.show', user.id)" class="flex items-center" :class="{ 'mt-2': key > 0, 'mt-1': key === 0 }">
                                    <img class="w-12 h-12 rounded-full object-cover" :src="user.profile_photo_url" :alt="user.name">
                                    <div class="ml-4 leading-tight">
                                        <div>{{ user.name }}</div>
                                        <div class="text-gray-700 text-sm">{{ user.description }}</div>
                                    </div>
                                </inertia-link>
                            </div>
                        </div>
                        <div v-if="team.description !== null" class="mt-4 flex flex-col justify-center items-start">
                            <h3 class="text-lg underline">{{ __('About this team') }}</h3>
                            <p class="break-words whitespace-pre-wrap">{{ team.description }}</p>
                        </div>
                    </template>
                </jet-action-section>
            </div>
            <div v-if="$page.props.user">
                <jet-section-border />
                <jet-action-section class="mt-10 sm:mt-0">
                    <template #title>
                        {{ __('Team Participation Management ') }}
                    </template>

                    <template #description>
                        {{ __('You can manage your participation in this team.') }}
                    </template>

                    <template #content>
                        <div v-if="isMember">
                            {{ __('You belong to this team.') }}
                        </div>
                        <div v-else-if="invited !== null">
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-lg underline">{{ __('Pending Invitation ') }}</h3>
                                    <p class="">{{ __('You have been invited to this team. You may join the team by accepting the invitation.') }}</p>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex flex-col items-start justify-center">
                                        <p>{{ __('Invitation to join as ') }}<span class="font-bold">{{ __(invited.role) }}</span></p>
                                        <p class="text-sm text-gray-700">{{ invited.message }}</p>
                                    </div>
                                    <div class="flex items-center flex-shrink-0">
                                        <!-- Accept Team Invitation -->
                                        <form method="POST" @submit.prevent="acceptTeamInvitation(invited)">
                                            <button type="submit" class="cursor-pointer ml-6 text-sm text-blue-500 focus:outline-none">
                                                {{ __('Accept') }}
                                            </button>
                                        </form>
                    
                                        <!-- Decline Team Invitation -->
                                        <button @click="declineTeamInvitation(invited)" class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none">
                                            {{ __('Decline') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else-if="team.wanted">
                            <div v-if="requesting !== null" class="space-y-6">
                                <div class="">
                                    <h3 class="text-lg underline">{{ __('Pending Join Request ') }}</h3>
                                    <p class="">{{ __('You are currently waiting for acceptance to this team. Do you want to cancel the request?') }}</p>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex flex-col items-start justify-center">
                                        <p>{{ __('Request to join as') }}&nbsp;<span class="font-bold">{{ __(requesting.role) }}</span></p>
                                        <p class="text-sm text-gray-700">{{ requesting.message }}</p>
                                    </div>

                                    <div class="flex items-center flex-shrink-0">
                                        <!-- Cancel Team Invitation -->
                                        <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none"
                                                            @click="cancelJoinRequest(requesting)">
                                            {{ __('Cancel') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div v-else>
                                <form @submit.prevent="makeJoinRequest">
                                    <div class="pb-4">
                                        <div class="grid grid-cols-6 gap-6">
                                            <div class="col-span-6 sm:col-span-4">
                                                <div class="">
                                                    <h3 class="text-lg underline">{{ __('Make Join Request') }}</h3>
                                                    <p class="">{{ __('This team is looking for new members. To make join request to this team, please fill in the form below.') }}</p>
                                                    <p>{{ __('Note this team has following requirements and options.') }}</p>
                                                    <p v-if="requirements.length === 0 && options.length === 0" class="font-bold">{{ __('This team has no requirements and options.') }}</p>
                                                    <div class="flex flex-col justify-center items-start">
                                                        <div class="flex items-center">
                                                            <div v-for="(requirement,key) in requirements" :key="key" class="flex items-center flex-wrap font-bold">
                                                                <div v-if="key === 0">
                                                                    <span class="">
                                                                        {{ __('Requirements') }}:
                                                                    </span>
                                                                    &nbsp;
                                                                </div>
                                                                <div>
                                                                    {{ requirement }}
                                                                </div>
                                                                <div v-if="requirements.length -1 > key">,&nbsp;</div>
                                                            </div>
                                                        </div>
                                                        <div class="flex items-center">
                                                            <div v-for="(option,key) in options" :key="key" class="flex items-center flex-wrap font-bold">
                                                                <div v-if="key === 0">
                                                                    <span class="">
                                                                        {{ __('Options') }}:
                                                                    </span>
                                                                    &nbsp;
                                                                </div>
                                                                <div>
                                                                    {{ option }}
                                                                </div>
                                                                <div v-if="options.length -1 > key">,&nbsp;</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <p></p>
                                            </div>

                                            <!-- Role -->
                                            <div class="col-span-6 lg:col-span-4" v-if="availableRoles.length > 0">
                                                <jet-label for="roles" value="Role" />
                                                <jet-input-error :message="makeJoinRequestForm.errors.role" class="mt-2" />

                                                <div class="relative z-0 mt-1 border border-gray-200 rounded-lg cursor-pointer">
                                                    <button type="button" class="relative px-4 py-3 inline-flex w-full rounded-lg focus:z-10 focus:outline-none"
                                                                    :class="{'border-t border-gray-200 rounded-t-none': i > 0, 'rounded-b-none': i != Object.keys(availableRoles).length - 1}"
                                                                    @click="makeJoinRequestForm.role = role.key"
                                                                    v-for="(role, i) in availableRoles"
                                                                    :key="role.key">
                                                        <div :class="{'opacity-50': makeJoinRequestForm.role && makeJoinRequestForm.role != role.key}">
                                                            <!-- Role Name -->
                                                            <div class="flex items-center">
                                                                <div class="text-sm text-gray-600" :class="{'font-semibold': makeJoinRequestForm.role == role.key}">
                                                                    {{ __(role.name) }}
                                                                </div>

                                                                <svg v-if="makeJoinRequestForm.role == role.key" class="ml-2 h-5 w-5 text-green-400" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
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
                                                <jet-text-area id="message" type="text" class="mt-1 block w-full" v-model="makeJoinRequestForm.message" />
                                                <jet-input-error :message="makeJoinRequestForm.errors.message" class="mt-2" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-end pt-3 text-right">
                                        <jet-button :class="{ 'opacity-25': makeJoinRequestForm.processing }" :disabled="makeJoinRequestForm.processing">
                                            {{ __('Send request') }}
                                        </jet-button>
                                    </div>
                                </form>
                            </div>    
                        </div>
                        <div v-else>
                            {{ __('This team is not currently looking for new members.') }}
                        </div>
                    </template>
                </jet-action-section>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import JetLabel from '@/Jetstream/Label'
    import JetButton from '@/Jetstream/Button'
    import JetFormSection from '@/Jetstream/FormSection'
    import JetInputError from '@/Jetstream/InputError'
    import JetTextArea from '@/Jetstream/TextArea'
    import JetActionSection from '@/Jetstream/ActionSection'
    import Modal from '@/Pages/Modal'
    import JetSectionBorder from '@/Jetstream/SectionBorder'
    
    export default {
        props: [
            'team',
            'requirements',
            'options',
            'hasAnyUser',
            'isMember',
            'invited',
            'requesting',
            'availableRoles',
        ],

        components: {
            JetLabel,
            JetButton,
            JetFormSection,
            JetInputError,
            JetTextArea,
            JetActionSection,
            Modal,
            JetSectionBorder,
        },    
        
        data() {
            return {
                openModal: false,
                acceptTeamInvitationForm: this.$inertia.form ({
                    invitation: null,
                }),

                makeJoinRequestForm: this.$inertia.form({
                    team_id: this.team.id,
                    role: null,
                    message: '',
                }),  
            }
        },

        methods: {
            goToChat() {
                this.$inertia.post(route('chat.store'), {profile_id: this.team.owner.id}, {});
            },

            makeJoinRequest() {
                this.makeJoinRequestForm.post(route('join-requests.store'), {
                    errorBag: 'makeJoinRequest',
                    preserveScroll: true,
                    onSuccess: () => this.makeJoinRequestForm.reset(),
                });
            },
            
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

            cancelJoinRequest(join_request) {
                this.$inertia.delete(route('join-requests.destroy', join_request), {
                    preserveScroll: true
                });
            },
        },
    }
</script>
