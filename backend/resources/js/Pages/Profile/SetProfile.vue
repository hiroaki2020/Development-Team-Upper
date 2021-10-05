<template>
    <app-layout>
        <div v-if="$page.props.jetstream.canUpdateProfileInformation" class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <update-profile-information-form @switch-to-setting-team-profile="SwitchToSettingTeamProfile"
                v-if="fromSetup.settingUserProfile"
                :user="$page.props.user"
                :skillOptions="skillOptions"
                :ownedSkills="ownedSkills" />
            <set-team-profile @switch-to-setting-user-profile="SwitchToSettingUserProfile"
                v-else
                :skillOptions="skillOptions"
                :team="team"
                :permissions="permissions"
                :currentRequirements="currentRequirements"
                :currentOptions="currentOptions"
                :wantedPassed="wantedPassed" />
        </div>
    </app-layout>
</template>
    

<script>
    import UpdateProfileInformationForm from './UpdateProfileInformationForm'
    import SetTeamProfile from '@/Pages/TeamProfile/SetTeamProfile'

    import { useRemember } from '@inertiajs/inertia-vue3'

    export default {
        components: {
            UpdateProfileInformationForm,
            SetTeamProfile,
        },
        
        props: [
            'skillOptions',
            'ownedSkills',
            'team',
            'currentRequirements',
            'currentOptions',
            'wantedPassed',
            'permissions',
            ],

        setup() {
            const fromSetup = useRemember({
                settingUserProfile: true,
            });

            return { fromSetup };
        },

        methods: {
            SwitchToSettingTeamProfile() {
                this.fromSetup.settingUserProfile = false;
            },

            SwitchToSettingUserProfile() {
                this.fromSetup.settingUserProfile = true;
            },
        }
    }
</script>
