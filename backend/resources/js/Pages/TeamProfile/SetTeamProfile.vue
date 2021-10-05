<template>
    <jet-form-section @submitted="updateTeamProfile" v-if="$page.props.hasTeams">
        <template #title>
            {{ __('Set Team Profile') }}
        </template>

        <template #description>
            {{ __('Set your team\'s profile.') }}
            <br>
            <inertia-link :href="route('see-team-profile.show', team.id)" class="underline text-sm text-gray-600 hover:text-gray-900">
                {{ __('See your team\'s profile.') }}
            </inertia-link>
            <br>
            <span @click="$emit('switch-to-setting-user-profile')" class="text-gray-600 hover:text-gray-900 text-sm underline cursor-pointer">{{ __('Switch to setting user profile.') }}</span>
        </template>

        <template #form>
            <!-- Team Profile Photo -->
            <div v-if="permissions.canUpdateTeam" class="col-span-6 sm:col-span-4">
                <!-- Team Profile Photo File Input -->
                <input type="file" class="hidden" ref="photo" @change="updatePhotoPreview">
                <jet-label for="photo" value="Photo" />

                <!-- Current Team Profile Photo -->
                <div class="mt-2" v-show="! photoPreview">
                    <img :src="team.team_profile_photo_url" :alt="team.name" class="rounded-full h-20 w-20 object-cover">
                </div>

                <!-- New Team Profile Photo Preview -->
                <div class="mt-2" v-show="photoPreview">
                    <span class="block rounded-full w-20 h-20"
                        :style="'background-size: cover; background-repeat: no-repeat; background-position: center center; background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                <jet-secondary-button-yellow class="mt-2 mr-2" type="button" @click.prevent="selectNewPhoto">
                    {{ __('Select A New Photo') }}
                </jet-secondary-button-yellow>

                <jet-secondary-button-yellow type="button" class="mt-2" @click.prevent="deletePhoto" v-if="team.team_profile_photo_path">
                    {{ __('Remove Photo') }}
                </jet-secondary-button-yellow>

                <jet-input-error :message="form.errors.photo" class="mt-2" />
            </div>

            <!-- Team Name -->
            <div v-if="permissions.canUpdateTeam" class="col-span-6 sm:col-span-4">
                <jet-label for="name" value="Team name" />

                <jet-input-yellow id="name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.name" />

                <jet-input-error :message="form.errors.name" class="mt-2" />
            </div>

            <!-- About team -->
            <div v-if="permissions.canUpdateTeam" class="col-span-6 sm:col-span-4">
                <jet-label for="description" value="About this team" />
                <jet-text-area-yellow id="description" type="text" class="mt-1 block w-full h-32" v-model="form.description" />
                <jet-input-error :message="form.errors.description" class="mt-2" />
            </div>

            <!-- Wanted -->
            <div v-if="permissions.canUpdateTeam" class="col-span-6 sm:col-span-4 flex items-center flex-wrap">
                <input id="wanted" type="checkbox" value="true" v-model="form.wanted" class="mr-1 block rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                <jet-label for="wanted" value="Team member wanted" />
                <jet-input-error :message="form.errors.wanted" class="mt-2" />
            </div> 

            <!-- Team requirements & options -->
            <div v-if="permissions.canUpdateTeam" class="col-span-6 sm:col-span-4">
                <p class="text-sm text-gray-700">{{ __('Team requirements & options') }}</p>
                <div v-for="(skill, key) in skillOptions" :key="key" class="flex items-center flex-wrap">
                    <p class="w-24 text-sm text-gray-700">{{ skill.skill }}</p>
                    <input :id="'requirement' + key" type="checkbox" :value="skill.skill" v-model="form.requirements" @change="untickOption(skill.skill)" class="block rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                    <jet-label :for="'requirement' + key" value="required" class="mx-1" />
                    <input :id="'option' + key" type="checkbox" :value="skill.skill" v-model="form.options" @change="untickRequirement(skill.skill)" class="block rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                    <jet-label :for="'option' + key" value="optional" class="mx-1" />
                </div>
                <jet-input-error :message="form.errors.requirements" class="mt-2" />
                <jet-input-error :message="form.errors.options" class="mt-2" />
            </div>

            <div v-if="!permissions.canUpdateTeam" class="col-span-6 sm:col-span-4">
                {{ __('You are not authorized to edit team profile.') }}
            </div>
        </template>

        <template #actions v-if="permissions.canUpdateTeam">
            <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                {{ __('Saved.') }}
            </jet-action-message>

            <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                {{ __('Save') }}
            </jet-button>
        </template>
    </jet-form-section>
    <jet-action-section v-else>
        <template #title>
            {{ __('Set Team Profile') }}
        </template>

        <template #description>
            {{ __('You need to own/belong to at least one team to set your team\'s profile.') }}
            <br>
            <span @click="$emit('switch-to-setting-user-profile')" class="text-gray-600 hover:text-gray-900 text-sm underline cursor-pointer">{{ __('Switch to setting user profile.') }}</span>
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
</template>

<script>
    import JetActionMessage from '@/Jetstream/ActionMessage'
    import JetActionSection from '@/Jetstream/ActionSection'
    import JetButton from '@/Jetstream/Button'
    import JetFormSection from '@/Jetstream/FormSection'
    import JetInputYellow from '@/Jetstream/InputYellow'
    import JetInputError from '@/Jetstream/InputError'
    import JetLabel from '@/Jetstream/Label'
    import JetSecondaryButtonYellow from '@/Jetstream/SecondaryButtonYellow'
    import JetTextAreaYellow from '@/Jetstream/TextAreaYellow'

    export default {
        props: [
            'skillOptions',
            'team',
            'permissions',
            'currentRequirements',
            'currentOptions',
            'wantedPassed',
        ],

        emits: [
            'switch-to-setting-user-profile',
        ],

        components: {
            JetActionMessage,
            JetActionSection,
            JetButton,
            JetFormSection,
            JetInputYellow,
            JetInputError,
            JetLabel,
            JetSecondaryButtonYellow,
            JetTextAreaYellow,    
        },    
        
        data() {
            return {
                form: this.$inertia.form({
                    _method: 'PUT',
                    name: this.team.name,
                    photo: null,
                    description: this.team.description,
                    wanted: this.wantedPassed,
                    requirements: this.currentRequirements,
                    options: this.currentOptions,
                }),
                photoPreview: null,
            }
        },

        methods: {
            untickRequirement(skill) {
                if(this.form.requirements.indexOf(skill) !== -1) {
                    this.form.requirements.splice(this.form.requirements.indexOf(skill), 1);
                }
            },

            untickOption(skill) {
                if(this.form.options.indexOf(skill) !== -1) {
                    this.form.options.splice(this.form.options.indexOf(skill), 1);
                }
            },

            updateTeamProfile() {
                if (this.$refs.photo) {
                    this.form.photo = this.$refs.photo.files[0]
                }

                this.form.post(route('teams.update'), {
                    errorBag: 'updateTeamProfileInformation',
                    preserveScroll: true
                });
            },

            updatePhotoPreview() {
                const reader = new FileReader();

                reader.onload = (e) => {
                    this.photoPreview = e.target.result;
                };

                reader.readAsDataURL(this.$refs.photo.files[0]);
            },

            selectNewPhoto() {
                this.$refs.photo.click();
            },

            deletePhoto() {
                this.$inertia.delete(route('current-team-photo.destroy', this.team), {
                    preserveScroll: true,
                    onSuccess: () => (this.photoPreview = null),
                });
            },
        },
    }
</script>
