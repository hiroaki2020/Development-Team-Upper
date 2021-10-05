<template>
    <jet-form-section @submitted="updateProfileInformation">
        <template #title>
            {{ __('Set User Profile') }}
        </template>

        <template #description>
            {{ __('Set your user profile.') }}
            <br>
            <inertia-link :href="route('see-profile.show', user.id)" class="underline text-sm text-gray-600 hover:text-gray-900">
                {{ __('See your user profile.') }}
            </inertia-link>
            <br>
            <span @click="$emit('switch-to-setting-team-profile')" class="text-gray-600 hover:text-gray-900 text-sm underline cursor-pointer">{{ __('Switch to setting team profile.') }}</span>
        </template>

        <template #form>
            <!-- Profile Photo -->
            <div class="col-span-6 sm:col-span-4" v-if="$page.props.jetstream.managesProfilePhotos">
                <!-- Profile Photo File Input -->
                <input type="file" class="hidden"
                            ref="photo"
                            @change="updatePhotoPreview">

                <jet-label for="photo" value="Photo" />

                <!-- Current Profile Photo -->
                <div class="mt-2" v-show="! photoPreview">
                    <img :src="user.profile_photo_url" :alt="user.name" class="rounded-full h-20 w-20 object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div class="mt-2" v-show="photoPreview">
                    <span class="block rounded-full w-20 h-20"
                          :style="'background-size: cover; background-repeat: no-repeat; background-position: center center; background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                <jet-secondary-button class="mt-2 mr-2" type="button" @click.prevent="selectNewPhoto">
                    {{ __('Select A New Photo') }}
                </jet-secondary-button>

                <jet-secondary-button type="button" class="mt-2" @click.prevent="deletePhoto" v-if="user.profile_photo_path">
                    {{ __('Remove Photo') }}
                </jet-secondary-button>

                <jet-input-error :message="form.errors.photo" class="mt-2" />
            </div>

            <!-- Name -->
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="name" value="Name" />
                <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" autocomplete="name" />
                <jet-input-error :message="form.errors.name" class="mt-2" />
            </div>

            <!-- About you -->
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="description" value="About yourself" />
                <jet-text-area id="description" type="text" class="mt-1 block w-full h-32" v-model="form.description" />
                <jet-input-error :message="form.errors.description" class="mt-2" />
            </div>

            <!-- Url -->
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="url" value="URL" />
                <jet-input id="url" type="url" class="mt-1 block w-full" v-model="form.url" />
                <jet-input-error :message="form.errors.url" class="mt-2" />
            </div>

            <!-- Skill -->
            <div class="col-span-6 sm:col-span-4">
                <p class="text-sm text-gray-700">{{ __('Select your skills') }}</p>
                <div v-for="(skill, key) in skillOptions" :key="key" class="flex items-center flex-wrap">
                    <input :id="'skill' + key" type="checkbox" :value="skill.skill" v-model="form.skills" class="block rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <jet-label :for="'skill' + key" :value="skill.skill" class="ml-1" />
                </div>
                <jet-input-error :message="form.errors.skills" class="mt-2" />
            </div>            
        </template>

        <template #actions>
            <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                {{ __('Saved.') }}
            </jet-action-message>

            <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                {{ __('Save') }}
            </jet-button>
        </template>
    </jet-form-section>
</template>

<script>
    import JetButton from '@/Jetstream/Button'
    import JetFormSection from '@/Jetstream/FormSection'
    import JetInput from '@/Jetstream/Input'
    import JetInputError from '@/Jetstream/InputError'
    import JetLabel from '@/Jetstream/Label'
    import JetActionMessage from '@/Jetstream/ActionMessage'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton'
    import JetTextArea from '@/Jetstream/TextArea'

    export default {
        components: {
            JetActionMessage,
            JetButton,
            JetFormSection,
            JetInput,
            JetInputError,
            JetLabel,
            JetSecondaryButton,
            JetTextArea,
        },

        props: ['user', 'skillOptions', 'ownedSkills'],

        emits: [
            'switch-to-setting-team-profile',
        ],

        data() {
            return {
                form: this.$inertia.form({
                    _method: 'PUT',
                    name: this.user.name,
                    photo: null,
                    description: this.user.description,
                    url: this.user.url,
                    skills: this.ownedSkills,
                }),

                photoPreview: null,
            }
        },

        methods: {
            updateProfileInformation() {
                if (this.$refs.photo) {
                    this.form.photo = this.$refs.photo.files[0]
                }

                this.form.post(route('user-profile-information.update'), {
                    errorBag: 'updateProfileInformation',
                    preserveScroll: true
                });
            },

            selectNewPhoto() {
                this.$refs.photo.click();
            },

            updatePhotoPreview() {
                const reader = new FileReader();

                reader.onload = (e) => {
                    this.photoPreview = e.target.result;
                };

                reader.readAsDataURL(this.$refs.photo.files[0]);
            },

            deletePhoto() {
                this.$inertia.delete(route('current-user-photo.destroy'), {
                    preserveScroll: true,
                    onSuccess: () => (this.photoPreview = null),
                });
            },
        },
    }
</script>
