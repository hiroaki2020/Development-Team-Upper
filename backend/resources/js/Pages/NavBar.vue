<template>
    <div>
        <div class="relative top-0 left-0 w-full" style="height: 7vh;"></div>
        <jet-banner class="z-30" />
        <div :class="{ 'pl-6': $page.props.user, 'px-6': !$page.props.user }" class="fixed top-0 left-0 w-screen bg-white flex items-center z-30 shadow" style="height: 7vh;">
            <div class="flex justify-between w-full h-full">
                <!-- Hamburger -->
                <div class="-mr-2 flex items-center xl:hidden">
                    <button @click="showingNavigationDropdown = ! showingNavigationDropdown" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': showingNavigationDropdown, 'inline-flex': ! showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <!-- Responsive Navigation Menu -->
                <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}" class="xl:hidden bg-white rounded-md shadow fixed left-6 w-60 overflow-y-scroll" style="max-height: 85vh;top: 8vh;">
                    <div class="py-1 space-y-1">
                        <jet-responsive-nav-link :href="route('home')" :active="route().current('home')">
                            {{ __('Home') }}
                        </jet-responsive-nav-link>
                        <jet-responsive-nav-link :href="route('teamup')" :active="route().current('teamup') || route().current('see-profile.show') || route().current('see-team-profile.show') || route().current('handle-join-requests.index') || route().current('invitations.index')">
                            {{ __('Team Up') }}
                        </jet-responsive-nav-link>
                        <jet-responsive-nav-link :href="route('yourprofile')" :active="route().current('yourprofile')">
                            {{ __('Set Profile') }}
                        </jet-responsive-nav-link>
                        <jet-responsive-nav-link :href="route('teams.create')" :active="route().current('teams.create')">
                            {{ __('Create Team') }}
                        </jet-responsive-nav-link>
                        <jet-responsive-nav-link :href="$page.props.user ? $page.props.hasTeams ? route('teams.show', $page.props.user.current_team) : route('no-team.index') : route('teams.index')" :active="route().current('teams.show')">
                            {{ __('Manage Team') }}
                        </jet-responsive-nav-link>
                        <jet-responsive-nav-link :href="route('chat.index')" :active="route().current('chat.index')">
                            {{ __('Chat') }}
                        </jet-responsive-nav-link>
                        <jet-responsive-nav-link :href="route('documentation')" :active="route().current('documentation')">
                            {{ __('Documentation') }}
                        </jet-responsive-nav-link>
                    </div>

                    <!-- Responsive Settings Options for Authenticated User -->
                    <div v-if="$page.props.user" class="py-1 border-t border-gray-200">
                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('Manage Account') }}
                        </div>

                        <div class="flex items-center px-4 mt-3">
                            <div v-if="$page.props.jetstream.managesProfilePhotos" class="flex-shrink-0 mr-3" >
                                <img class="h-10 w-10 rounded-full object-cover" :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name" />
                            </div>
                            <div>
                                <div class="font-medium text-base text-gray-800 w-40">{{ $page.props.user.name }}</div>
                                <div class="font-medium text-sm text-gray-500 w-40 break-words">{{ $page.props.email }}</div>
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <jet-responsive-nav-link :href="route('account.show')" :active="route().current('account.show')">
                                {{ __('Account') }}
                            </jet-responsive-nav-link>

                            <jet-responsive-nav-link :href="route('api-tokens.index')" :active="route().current('api-tokens.index')" v-if="$page.props.jetstream.hasApiFeatures">
                                {{ __('API Tokens') }}
                            </jet-responsive-nav-link>

                            <!-- Authentication -->
                            <form method="POST" @submit.prevent="logout">
                                <jet-responsive-nav-link as="button">
                                    {{ __('Log Out') }}
                                </jet-responsive-nav-link>
                            </form>

                            <!-- Team Management -->
                            <template v-if="$page.props.jetstream.hasTeamFeatures">
                                <div class="border-t border-gray-200"></div>

                                <!-- Team Switcher -->
                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    {{ __('Switch Teams') }}
                                </div>

                                <div v-for="team in $page.props.user.all_teams" :key="team.id">
                                    <form @submit.prevent="switchToTeam(team)">
                                        <jet-responsive-nav-link as="button">
                                            <div class="flex items-center">
                                                <svg v-if="team.id == $page.props.user.current_team_id" class="flex-shrink-0 mr-2 h-5 w-5 text-green-400" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                <div>{{ team.name }}</div>
                                            </div>
                                        </jet-responsive-nav-link>
                                    </form>
                                </div>
                            </template>
                        </div>
                    </div>
                    <div v-else>
                        <div class="py-1 border-t border-gray-200">
                            <jet-responsive-nav-link :href="route('login')">
                                {{ __('Log In') }}
                            </jet-responsive-nav-link>
                            <jet-responsive-nav-link :href="route('register')">
                                {{ __('Register') }}
                            </jet-responsive-nav-link>
                        </div>
                    </div>
                    <div>
                        <div class="py-2 pl-4 text-xs border-t border-gray-200 text-gray-400">
                            {{ __('Language') }}
                        </div>
                        <div class="py-1">
                            <jet-dropdown-link :href="route('language', {lang: selectable_language})">
                                <div class="flex items-center">
                                    <svg v-if="selectable_language === 'en'" class="mr-2 h-5 w-5 text-green-400" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    日本語
                                </div>
                            </jet-dropdown-link>

                            <jet-dropdown-link :href="route('language', {lang: selectable_language})">
                                <div class="flex items-center">
                                    <svg v-if="selectable_language === 'ja'" class="mr-2 h-5 w-5 text-green-400" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    English
                                </div>
                            </jet-dropdown-link>
                        </div>
                    </div>
                </div>
                <div class="flex justify-start items-center h-full xl:block hidden">
                    <!-- Logo -->        
                    <jet-nav-link class="text-lg mr-1.5 h-full" :href="route('home')" :active="route().current('home')">
                        {{ __('DevCheers') }}
                    </jet-nav-link>
                    <jet-nav-link class="text-lg mx-1.5 h-full" :href="route('teamup')" :active="route().current('teamup') || route().current('see-profile.show') || route().current('see-team-profile.show') || route().current('handle-join-requests.index') || route().current('invitations.index')">
                        {{ __('Team Up') }}
                    </jet-nav-link>
                    <jet-nav-link class="text-lg mx-1.5 h-full" :href="route('yourprofile')" :active="route().current('yourprofile')">
                        {{ __('Set Profile') }}
                    </jet-nav-link>
                    <jet-nav-link class="text-lg mx-1.5 h-full" :href="route('teams.create')" :active="route().current('teams.create')">
                        {{ __('Create Team') }}
                    </jet-nav-link>
                    <jet-nav-link class="text-lg mx-1.5 h-full" :href="$page.props.user ? $page.props.hasTeams ? route('teams.show', $page.props.user.current_team) : route('no-team.index') : route('teams.index')" :active="route().current('teams.show')">
                        {{ __('Manage Team') }}
                    </jet-nav-link>
                    <jet-nav-link class="text-lg mx-1.5 h-full" :href="route('chat.index')" :active="route().current('chat.index')">
                        {{ __('Chat') }}
                    </jet-nav-link>
                    <jet-nav-link class="text-lg mx-1.5 h-full" :href="route('documentation')" :active="route().current('documentation')">
                        {{ __('Documentation') }}
                    </jet-nav-link>
                </div>
                <div class="flex justify-end items-center">
                    <nav v-if="$page.props.user">
                        <!-- Primary Navigation Menu -->
                        <div class="max-w-7xl mx-auto hidden xl:block">
                            <div class="flex items-center h-16">
                                <!-- User and Teams Dropdowns -->
                                <div class="hidden sm:flex sm:items-center">
                                    <div class="relative">
                                        <!-- Teams Dropdown -->
                                        <jet-dropdown align="right" width="60" v-if="$page.props.jetstream.hasTeamFeatures">
                                            <template #trigger>
                                                <span class="inline-flex rounded-md">
                                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                                        <span class="truncate" style="max-width: 10rem;">{{  $page.props.hasTeams ? $page.props.user.current_team.name : __('No team') }}</span>

                                                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </span>
                                            </template>

                                            <template #content>
                                                <div class="w-60">
                                                    <!-- Team Management -->
                                                    <template v-if="$page.props.jetstream.hasTeamFeatures">
                                                        <!-- Team Switcher -->
                                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                                            {{ __('Switch Teams') }}
                                                        </div>

                                                        <div v-for="team in $page.props.user.all_teams" :key="team.id">
                                                            <form @submit.prevent="switchToTeam(team)">
                                                                <jet-dropdown-link as="button">
                                                                    <div class="flex items-center">
                                                                        <svg v-if="team.id == $page.props.user.current_team_id" class="flex-shrink-0 mr-2 h-5 w-5 text-green-400" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                                        <div>{{ team.name }}</div>
                                                                    </div>
                                                                </jet-dropdown-link>
                                                            </form>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                        </jet-dropdown>
                                    </div>

                                    <!-- User Dropdown -->
                                    <div class="relative mr-3">
                                        <jet-dropdown align="right" width="48">
                                            <template #trigger>
                                                <span class="inline-flex rounded-md">
                                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                                        <span class="truncate" style="max-width: 10rem;">{{ $page.props.user.name }}</span>

                                                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </span>
                                            </template>

                                            <template #content>
                                                <!-- Account Management -->
                                                <div class="block px-4 py-2 text-xs text-gray-400">
                                                    {{ __('Manage Account') }}
                                                </div>

                                                <jet-dropdown-link :href="route('account.show')">
                                                    {{ __('Account') }}
                                                </jet-dropdown-link>

                                                <jet-dropdown-link :href="route('api-tokens.index')" v-if="$page.props.jetstream.hasApiFeatures">
                                                    {{ __('API Tokens') }}
                                                </jet-dropdown-link>

                                                <div class="border-t border-gray-100"></div>

                                                <!-- Language Selector -->
                                                <div class="block px-4 py-2 text-xs text-gray-400">
                                                    {{ __('Language') }}
                                                </div>

                                                <jet-dropdown-link :href="route('language', {lang: selectable_language})">
                                                    <div class="flex items-center">
                                                        <svg v-if="selectable_language === 'en'" class="mr-2 h-5 w-5 text-green-400" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        日本語
                                                    </div>
                                                </jet-dropdown-link>

                                                <jet-dropdown-link :href="route('language', {lang: selectable_language})">
                                                    <div class="flex items-center">
                                                        <svg v-if="selectable_language === 'ja'" class="mr-2 h-5 w-5 text-green-400" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        English
                                                    </div>
                                                </jet-dropdown-link>

                                                <div class="border-t border-gray-100"></div>

                                                <!-- Authentication -->
                                                <form @submit.prevent="logout">
                                                    <jet-dropdown-link as="button">
                                                        {{ __('Log Out') }}
                                                    </jet-dropdown-link>
                                                </form>
                                            </template>
                                        </jet-dropdown>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </nav>
                    <template v-else class="flex items-center">
                        <inertia-link :href="route('login')" class="text-gray-500 hover:text-gray-700 underline xl:block hidden">
                            {{ __('Log In') }}
                        </inertia-link>
                        <inertia-link :href="route('register')" class="mx-4 text-gray-500 hover:text-gray-700 underline xl:block hidden">
                            {{ __('Register') }}
                        </inertia-link>
                        <language-selector class="flex-shrink-0 xl:flex hidden" />
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import JetApplicationMark from '@/Jetstream/ApplicationMark'
    import JetBanner from '@/Jetstream/Banner'
    import JetDropdown from '@/Jetstream/Dropdown'
    import JetDropdownLink from '@/Jetstream/DropdownLink'
    import JetNavLink from '@/Jetstream/NavLink'
    import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink'
    import LanguageSelector from '@/Pages/LanguageSelector'

    export default {
        components: {
            JetApplicationMark,
            JetBanner,
            JetDropdown,
            JetDropdownLink,
            JetNavLink,
            JetResponsiveNavLink,
            LanguageSelector,
        },

        data() {
            return {
                showingNavigationDropdown: false,
            }
        },

        methods: {
            switchToTeam(team) {
                this.$inertia.put(route('current-team.update'), {
                    'team_id': team.id
                }, {
                    preserveState: false
                })
            },

            logout() {
                this.$inertia.post(route('logout'),{},{
                    onSuccess: () => { this.showingNavigationDropdown = false; }
                });
            },
        },

        computed: {
            selectable_language() {
                if(this.$page.props.locale == 'en') {
                    return 'ja';
                }
                return 'en';
            },
        }
    }
</script>
