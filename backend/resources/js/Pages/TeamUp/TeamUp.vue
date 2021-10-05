<template>
    <app-layout>
        <div v-if="fromSetup.isUserSearch" class="grid grid-cols-12 gap-6 box-border p-6 rounded-md" style="height: 93vh;">
            <div class="col-span-3 row-span-1 w-full rounded-md shadow bg-white flex flex-col justify-center items-center p-4">
                <div v-if="fromSetup.inputError.userForm" class="text-red-600 text-sm">{{ fromSetup.inputError.userForm['searchUserInput'] }}</div>
                <form @submit.prevent="searchUser" method="GET" class="flex flex-col w-full">
                    <input v-focus v-model="userInput" type="search" name="user" :placeholder="__('Search user')" class="block rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-full">
                    <button :disabled="searching" :class="{ 'opacity-25': searching }" type="submit" class="px-4 py-2 mt-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150 text-center">{{ __('Search') }}</button>
                </form>
                <button @click="switchToTeamSearch" class="underline text-gray-500 hover:text-gray-800 focus:outline-none mt-1">{{ __('Switch to team search') }}</button>
            </div>
            <div class="col-span-9 row-span-6 w-full h-full rounded-md shadow bg-white p-8">
                <div v-if="fromSetup.output.users === null" class="text-4xl flex items-center justify-center h-full w-full text-gray-500">{{ __('Search user in top left input box.') }}</div>
                <div v-else-if="fromSetup.output.users.length === 0" class="text-4xl flex items-center justify-center h-full w-full text-gray-500">{{ __('No results') }}</div>
                <div v-else class="flex flex-col items-center w-full h-full">
                    <div class="flex items-center flex-wrap w-full" style="height: 90%;">
                        <div class="flex items-center w-1/4 h-1/2 p-4" v-for="user in fromSetup.output.users.data" :key="user.id">
                            <inertia-link :href="route('see-profile.show', user.id)" :class="{ 'bg-gray-500': user.name === 'DevCheers', 'hover:bg-gray-700': user.name === 'DevCheers', 'text-white': user.name === 'DevCheers', 'bg-white': user.name !== 'DevCheers', 'hover:bg-blue-200': user.name !== 'DevCheers' }" class="shadow border border-gray-200 rounded-md flex flex-col items-center px-4 pt-4 pb-10 w-full h-full">
                                <div class="flex-shrink-0 flex-grow-0 flex justify-start w-full h-1/4">
                                    <img class="h-full rounded-md object-cover flex-shrink-0 flex-grow-0" :src="user.profile_photo_url" :alt="user.name">
                                </div>
                                <div class="w-full h-3/4 relative">
                                    <div class="text-sm truncate w-full absolute top-0 left-0 pt-1">
                                        {{ user.name }}
                                    </div>
                                    <div class="h-full mt-6 overflow-y-scroll absolute top-0 left-0 w-full">
                                        <div class="whitespace-normal w-full text-sm" :class="{ 'text-gray-200': user.name === 'DevCheers', 'text-gray-700': user.name !== 'DevCheers' }">
                                            {{ user.description }}
                                        </div>
                                        <div class="text-sm w-full">
                                            <div v-for="(userSkill,key) in user.skills" :key="key" class="inline-block text-gray-700">
                                                <span v-if="key === 0"><span class="">{{ __('Skills') }}:</span>&nbsp;</span>
                                                <span>{{ userSkill.skill }}</span>
                                                <span v-if="user.skills.length -1 > key">,&nbsp;</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </inertia-link>
                        </div>
                    </div>
                    <div class="flex items-center" style="height: 10%;">
                        <nav role="navigation" :aria-label="__('Page Navigation')" class="flex items-center justify-between">
                            <div class="flex justify-between flex-1 xl:hidden">
                                <div v-if="fromSetup.output.users.current_page === 1">
                                    <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-300 bg-white border border-gray-300 cursor-default leading-5 rounded-md">
                                        &lt;
                                    </span>
                                </div>
                                <div v-else>
                                    <span @click="goToPage(fromSetup.output.users.prev_page_url)" class="cursor-pointer relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:bg-blue-200 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                                        &lt;
                                    </span>
                                </div>

                                <div v-if="fromSetup.output.users.current_page !== fromSetup.output.users.last_page">
                                    <span @click="goToPage(fromSetup.output.users.next_page_url)" class="cursor-pointer relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:bg-blue-200 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                                        &gt;
                                    </span>
                                </div>
                                <div v-else>                                
                                    <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-300 bg-white border border-gray-300 cursor-default leading-5 rounded-md">
                                        &gt;
                                    </span>
                                </div>
                            </div>

                            <div class="hidden xl:flex xl:flex-col xl:items-center">
                                <div>
                                    <span class="relative z-0 inline-flex shadow-sm rounded-md">
                                        <!-- Previous Page Link -->
                                        <div v-if="fromSetup.output.users.current_page === 1">
                                            <span aria-disabled="true" :aria-label="__('Previous')">
                                                <span class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-300 bg-white border border-gray-300 cursor-default rounded-l-md leading-5" aria-hidden="true">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </span>
                                        </div>
                                        <div v-else>
                                            <span @click="goToPage(fromSetup.output.users.prev_page_url)" rel="prev" class="cursor-pointer relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-l-md leading-5 hover:bg-blue-200 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150" :aria-label="__('Previous')">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        </div>

                                        <!-- Array Of Links -->
                                        <div v-for="(pageLink, key) in fromSetup.output.users.links" :key="key">
                                            <div v-if="pageLink.active">
                                                <span aria-current="page">
                                                    <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium bg-blue-200 text-gray-700 border border-gray-300 cursor-default leading-5">{{ pageLink.label }}</span>
                                                </span>
                                            </div>
                                            <div v-else-if="pageLink.label === '...'">
                                                <span aria-current="page">
                                                    <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium bg-white text-gray-700 border border-gray-300 cursor-default leading-5">{{ pageLink.label }}</span>
                                                </span>
                                            </div>
                                            <div v-else-if="key !== 0 && key !== fromSetup.output.users.links.length - 1">
                                                <span @click="goToPage(pageLink.url)" class="cursor-pointer relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:bg-blue-200 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150" :aria-label="__('Go to page ')+pageLink.label">
                                                    {{ pageLink.label }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Next Page Link -->
                                        <div v-if="fromSetup.output.users.current_page !== fromSetup.output.users.last_page">
                                            <span @click="goToPage(fromSetup.output.users.next_page_url)" rel="next" class="cursor-pointer relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-r-md leading-5 hover:bg-blue-200 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150" :aria-label="__('Next')">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        </div>
                                        <div v-else>
                                            <span aria-disabled="true" :aria-label="__('Next')">
                                                <span class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-300 bg-white border border-gray-300 cursor-default rounded-r-md leading-5" aria-hidden="true">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </span>
                                        </div>
                                    </span>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-700 leading-5">
                                        <span>{{ __('Showing ') }}</span>
                                        <span v-if="$page.props.locale === 'ja'" class="font-medium">{{ fromSetup.output.users.total }}</span>
                                        <span v-if="$page.props.locale === 'ja'">件中</span>
                                        <span class="font-medium">{{ fromSetup.output.users.from }}</span>
                                        <span>{{ __(' to ') }}</span>
                                        <span v-if="$page.props.locale === 'ja'">〜</span>
                                        <span class="font-medium">{{ fromSetup.output.users.to }}</span>
                                        <span>{{ __(' of ') }}</span>
                                        <span v-if="$page.props.locale === 'en'" class="font-medium">{{ fromSetup.output.users.total }}</span>
                                        <span>{{ __(' results') }}</span>
                                    </p>
                                </div>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="col-span-3 row-span-4 w-full overflow-y-scroll rounded-md shadow bg-white p-4">
                <form @submit.prevent="searchUser" method="GET" class="text-center">
                    <p class="text-left">{{ __('Filter by skills') }}</p>
                    <div v-for="(skill, key) in skillOptions" :key="key" class="flex items-center flex-wrap">
                        <div v-if="key === 0" class="block w-full my-2 flex lg:flex-row flex-col items-center">
                            <button :disabled="searching" :class="{ 'opacity-25': searching }" type="submit" class="px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150 text-center">{{ __('Apply') }}</button>
                            <button @click="clearAll" type="button" :disabled="searching" :class="{ 'opacity-25': searching }" class="px-4 py-2 ml-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150 text-center">{{ __('Clear') }}</button>
                        </div>
                        <input :id="'skill' + key" type="checkbox" :value="skill.skill" v-model="fromSetup.userForm.skills" class="mr-1 block rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <label :for="'skill' + key" class="text-gray-700">{{ skill.skill }}</label>
                    </div>
                </form>
            </div>
            <div v-if="$page.props.user" class="col-span-3 row-span-1 rounded-md box-border bg-white shadow flex items-center justify-center">
                <div v-if="$page.props.hasTeams" class="h-full w-full">
                    <inertia-link :href="route('handle-join-requests.index')" v-if="canAddTeamMembers" class="h-full w-full flex flex-col items-center justify-center hover:bg-blue-200 rounded-md p-4">
                        <h3>{{ __('Check join requests') }}</h3>
                        <p>{{ __('Pending requests') }}: <span :class="{ 'text-blue-500': joinRequestsCount !== 0 }" class="font-bold">{{ joinRequestsCount }}</span></p>
                    </inertia-link>
                    <div v-else class="h-full w-full flex items-center justify-center">
                        <p class="h-full w-full p-4">{{ __('You are not authorized to add team members.') }}</p>
                    </div>
                </div>
                <div v-else class="h-full w-full flex flex-col items-center justify-center p-4">
                    <p>{{ __('You don\'t belong to any team yet.') }}</p>
                    <inertia-link :href="route('teams.create')" class="underline text-sm text-blue-500 hover:text-purple-700">
                        {{ __('Let\'s create one now!') }}
                    </inertia-link>
                </div>
            </div>
            <inertia-link v-else :href="route('handle-join-requests.index')" class="col-span-3 row-span-1 rounded-md box-border bg-white flex items-center justify-center p-4 hover:bg-blue-200 shadow">
                <p>{{ __('To check pending join requests,') }}<br>{{ __('please log in.') }}</p>
            </inertia-link>
        </div>
        <div v-else class="grid grid-cols-12 gap-6 p-6 box-border rounded-md" style="height: 93vh;">
            <div class="col-span-3 row-span-1 w-full rounded-md bg-white shadow flex flex-col justify-center items-center p-4">
                <div v-if="fromSetup.inputError.teamForm" class="text-red-600 text-sm">{{ fromSetup.inputError.teamForm['searchTeamInput'] }}</div>
                <form @submit.prevent="searchTeam" method="GET" class="flex flex-col w-full">
                    <input v-focus v-model="teamInput" type="search" name="team" :placeholder="__('Search team')" class="block rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50 w-full">
                    <button :disabled="searching" :class="{ 'opacity-25': searching }" type="submit" class="px-4 py-2 mt-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150 text-center">{{ __('Search') }}</button>
                </form>
                <button @click="switchToUserSearch" class="underline text-gray-500 hover:text-gray-800 focus:outline-none mt-1">{{ __('Switch to user search') }}</button>
            </div>
            <div class="col-span-9 row-span-6 w-full h-full rounded-md shadow bg-white p-8">
                <div v-if="fromSetup.output.teams === null" class="text-4xl flex items-center justify-center h-full w-full text-gray-500">{{ __('Search team in top left input box.') }}</div>
                <div v-else-if="fromSetup.output.teams.length === 0" class="text-4xl flex items-center justify-center h-full w-full text-gray-500">{{ __('No results') }}</div>
                <div v-else class="flex flex-col items-center w-full h-full">
                    <div class="flex items-center flex-wrap w-full" style="height: 90%;">
                        <div class="flex items-center w-1/4 h-1/2 p-4" v-for="team in fromSetup.output.teams.data" :key="team.id">
                            <inertia-link :href="route('see-team-profile.show', team.id)" :class="{ 'bg-gray-500': team.name === 'DevCheers\' Team', 'hover:bg-gray-700': team.name === 'DevCheers\' Team', 'text-white': team.name === 'DevCheers\' Team', 'bg-white': team.name !== 'DevCheers\' Team', 'hover:bg-yellow-200': team.name !== 'DevCheers\' Team' }" class="shadow border border-gray-200 rounded-md flex flex-col items-center px-4 pt-4 pb-10 w-full h-full">
                                <div class="flex-shrink-0 flex-grow-0 flex justify-start w-full h-1/4">
                                    <img class="h-full rounded-md object-cover flex-shrink-0 flex-grow-0" :src="team.team_profile_photo_url" :alt="team.name">
                                </div>
                                <div class="w-full h-3/4 relative text-sm">
                                    <div class="truncate w-full absolute top-0 left-0 pt-1">
                                        {{ team.name }}
                                    </div>
                                    <div class="h-full w-full mt-6 overflow-y-scroll absolute top-0 left-0" :class="{ 'text-gray-200': team.name === 'DevCheers\' Team', 'text-gray-700': team.name !== 'DevCheers\' Team' }">
                                        <div class="whitespace-normal w-full">
                                            {{ team.description }}
                                        </div>
                                        <div v-if="team.wanted" class="w-full">
                                            <div>{{ __('This team is looking for new members.') }}</div>
                                            <div v-for="(teamRequirement,key) in filterOutOptions(team.requirements)" :key="key" class="inline-block">
                                                <span v-if="key === 0">{{ __('Requirements') }}:&nbsp;</span>
                                                <span v-if="teamRequirement.required">{{ teamRequirement.requirement }}</span>
                                                <span v-if="filterOutOptions(team.requirements).length -1 > key">,&nbsp;</span>
                                            </div>
                                            <br>
                                            <div v-for="(teamRequirement,key) in filterOutRequirements(team.requirements)" :key="key" class="inline-block">
                                                <span v-if="key === 0">{{ __('Options') }}:&nbsp;</span>
                                                <span v-if="!teamRequirement.required">{{ teamRequirement.requirement }}</span>
                                                <span v-if="filterOutRequirements(team.requirements).length -1 > key">,&nbsp;</span>
                                            </div>
                                        </div>
                                        <div v-else class="">{{ __('This team is not currently looking for new members.') }}</div>
                                    </div>
                                </div>
                            </inertia-link>
                        </div>
                    </div>
                    <div class="flex items-center" style="height: 10%;">
                        <nav role="navigation" :aria-label="__('Page Navigation')" class="flex items-center justify-between">
                            <div class="flex justify-between flex-1 xl:hidden">
                                <div v-if="fromSetup.output.teams.current_page === 1">
                                    <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-300 bg-white border border-gray-300 cursor-default leading-5 rounded-md">
                                        &lt;
                                    </span>
                                </div>
                                <div v-else>
                                    <span @click="goToPageForTeamSearch(fromSetup.output.teams.prev_page_url)" class="cursor-pointer relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:bg-yellow-200 focus:outline-none focus:ring ring-gray-300 focus:border-yellow-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                                        &lt;
                                    </span>
                                </div>

                                <div v-if="fromSetup.output.teams.current_page !== fromSetup.output.teams.last_page">
                                    <span @click="goToPageForTeamSearch(fromSetup.output.teams.next_page_url)" class="cursor-pointer relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:bg-yellow-200 focus:outline-none focus:ring ring-gray-300 focus:border-yellow-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                                        &gt;
                                    </span>
                                </div>
                                <div v-else>                                
                                    <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-300 bg-white border border-gray-300 cursor-default leading-5 rounded-md">
                                        &gt;
                                    </span>
                                </div>
                            </div>

                            <div class="hidden xl:flex xl:flex-col xl:items-center">
                                <div>
                                    <span class="relative z-0 inline-flex shadow-sm rounded-md">
                                        <!-- Previous Page Link -->
                                        <div v-if="fromSetup.output.teams.current_page === 1">
                                            <span aria-disabled="true" :aria-label="__('Previous')">
                                                <span class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-300 bg-white border border-gray-300 cursor-default rounded-l-md leading-5" aria-hidden="true">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </span>
                                        </div>
                                        <div v-else>
                                            <span @click="goToPageForTeamSearch(fromSetup.output.teams.prev_page_url)" rel="prev" class="cursor-pointer relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-l-md leading-5 hover:bg-yellow-200 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-yellow-300 active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150" :aria-label="__('Previous')">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        </div>

                                        <!-- Array Of Links -->
                                        <div v-for="(pageLink, key) in fromSetup.output.teams.links" :key="key">
                                            <div v-if="pageLink.active">
                                                <span aria-current="page">
                                                    <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium bg-yellow-200 text-gray-700 border border-gray-300 cursor-default leading-5">{{ pageLink.label }}</span>
                                                </span>
                                            </div>
                                            <div v-else-if="pageLink.label === '...'">
                                                <span aria-current="page">
                                                    <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium bg-white text-gray-700 border border-gray-300 cursor-default leading-5">{{ pageLink.label }}</span>
                                                </span>
                                            </div>
                                            <div v-else-if="key !== 0 && key !== fromSetup.output.teams.links.length - 1">
                                                <span @click="goToPageForTeamSearch(pageLink.url)" class="cursor-pointer relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:bg-yellow-200 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-yellow-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150" :aria-label="__('Go to page ')+pageLink.label">
                                                    {{ pageLink.label }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Next Page Link -->
                                        <div v-if="fromSetup.output.teams.current_page !== fromSetup.output.teams.last_page">
                                            <span @click="goToPageForTeamSearch(fromSetup.output.teams.next_page_url)" rel="next" class="cursor-pointer relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-r-md leading-5 hover:bg-yellow-200 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-yellow-300 active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150" :aria-label="__('Next')">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        </div>
                                        <div v-else>
                                            <span aria-disabled="true" :aria-label="__('Next')">
                                                <span class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-300 bg-white border border-gray-300 cursor-default rounded-r-md leading-5" aria-hidden="true">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </span>
                                        </div>
                                    </span>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-700 leading-5">
                                        <span>{{ __('Showing ') }}</span>
                                        <span v-if="$page.props.locale === 'ja'" class="font-medium">{{ fromSetup.output.teams.total }}</span>
                                        <span v-if="$page.props.locale === 'ja'">件中</span>
                                        <span class="font-medium">{{ fromSetup.output.teams.from }}</span>
                                        <span>{{ __(' to ') }}</span>
                                        <span v-if="$page.props.locale === 'ja'">〜</span>
                                        <span class="font-medium">{{ fromSetup.output.teams.to }}</span>
                                        <span>{{ __(' of ') }}</span>
                                        <span v-if="$page.props.locale === 'en'" class="font-medium">{{ fromSetup.output.teams.total }}</span>
                                        <span>{{ __(' results') }}</span>
                                    </p>
                                </div>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="col-span-3 row-span-4 w-full overflow-y-scroll rounded-md bg-white shadow p-4">
                <form @submit.prevent="searchTeam" method="GET" class="flex flex-col">
                    <p class="text-left">{{ __('Filter by conditions') }}</p>
                    <div class="block w-full my-2 flex lg:flex-row flex-col items-center">
                        <button :disabled="searching" :class="{ 'opacity-25': searching }" type="submit" class="px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150 text-center">{{ __('Apply') }}</button>
                        <button @click="clearAllForTeam" type="button" :disabled="searching" :class="{ 'opacity-25': searching }" class="px-4 py-2 ml-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150 text-center">{{ __('Clear') }}</button>
                    </div>
                    <!-- Wanted -->
                    <div class="mt-1">{{ __('Team member wanted status') }}</div>
                    <div class="flex items-center flex-wrap">
                        <p class="w-24 text-gray-700">{{ __('member') }}</p>
                        <input id="wanted" type="checkbox" value=true v-model="fromSetup.teamForm.wanted" @change="tickNotWanted" class="my-1 ml-1 block rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                        <jet-label for="wanted" :value="__('wanted')" class="ml-1" />
                        <input id="notWanted" type="checkbox" value=true v-model="fromSetup.teamForm.notWanted" @change="tickWanted" class="my-1 ml-1 block rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                        <jet-label for="notWanted" :value="__('not wanted')" class="ml-1" />
                    </div>

                    <!-- Team requirements & options -->
                    <div class="">
                        <p :class="{ 'opacity-25': fromSetup.teamForm.wanted === false }" class="mt-2">{{ __('Team requirements & options') }}</p>
                        <div v-for="(skill, key) in skillOptions" :key="key" class="flex items-center flex-wrap">
                            <p :class="{ 'opacity-25': fromSetup.teamForm.wanted === false }" class="w-24 text-gray-700">{{ skill.skill }}</p>
                            <input :id="'requirement' + key" type="checkbox" :value="skill.skill" v-model="fromSetup.teamForm.requirements" @change="untickOption(skill.skill)" :disabled="fromSetup.teamForm.wanted===false" :class="{ 'opacity-25': fromSetup.teamForm.wanted === false }" class="ml-1 block rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                            <jet-label :for="'requirement' + key" value="required" :class="{ 'opacity-25': fromSetup.teamForm.wanted === false }" class="ml-1" />
                            <input :id="'option' + key" type="checkbox" :value="skill.skill" v-model="fromSetup.teamForm.options" @change="untickRequirement(skill.skill)" :disabled="fromSetup.teamForm.wanted===false" :class="{ 'opacity-25': fromSetup.teamForm.wanted === false }" class="ml-1 block rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                            <jet-label :for="'option' + key" value="optional" :class="{ 'opacity-25': fromSetup.teamForm.wanted === false }" class="ml-1" />
                        </div>
                    </div>
                </form>
            </div>
            <inertia-link :href="route('invitations.index')" class="col-span-3 row-span-1 rounded-md box-border bg-white hover:bg-yellow-200 shadow p-4 flex items-center justify-center">
                <div v-if="$page.props.user" class="flex flex-col items-center justify-center">
                    <h3>{{ __('Check team invitations') }}</h3>
                    <p>{{ __('Pending invitations') }}: <span :class="{ 'text-yellow-500': teamInvitationsCount !== 0 }" class="font-bold">{{ teamInvitationsCount }}</span></p>
                </div>
                <div v-else class="flex flex-col items-center justify-center">
                    <p>{{ __('To check pending team invitations,') }}<br>{{ __('please log in.') }}</p>
                </div>
            </inertia-link>
        </div>
    </app-layout>
</template>

<script>
    import JetActionMessage from '@/Jetstream/ActionMessage'
    import JetButton from '@/Jetstream/Button'
    import JetFormSection from '@/Jetstream/FormSection'
    import JetInput from '@/Jetstream/Input'
    import JetInputError from '@/Jetstream/InputError'
    import JetLabel from '@/Jetstream/Label'
    import JetTextArea from '@/Jetstream/TextArea'
    import JetSectionBorder from '@/Jetstream/SectionBorder'
    import JetActionSection from '@/Jetstream/ActionSection'

    import { useRemember } from '@inertiajs/inertia-vue3'

    export default {
        components: {
            JetActionMessage,
            JetButton,
            JetFormSection,
            JetInput,
            JetInputError,
            JetLabel,
            JetTextArea,
            JetActionSection,
            JetSectionBorder,
        },

        props: [
            'joinRequestsCount',
            'teamInvitationsCount',
            'skillOptions',
            'canAddTeamMembers',
        ],

        setup() {
            const fromSetup = useRemember({
                userForm: {
                    searchUserInput: '',
                    skills: [],
                    page: 1,
                },
                teamForm: {
                    searchTeamInput: '',
                    requirements: [],
                    options: [],
                    wanted: true,
                    notWanted: true,
                    page: 1,
                },
                output: {
                    users: null,
                    teams: null,
                },
                inputError: {
                    userForm: '',
                    teamForm: '',
                },
                isUserSearch: true
            });
            
            return { fromSetup };
        },

        data() {
            return {
                searching: false,
            }
        },

        directives: {
            focus: {
                mounted(el) {
                    el.focus()
                }
            }
        },

        methods: {
            untickRequirement(skill) {
                this.searching = true;
                if(this.fromSetup.teamForm.requirements.indexOf(skill) !== -1) {
                    this.fromSetup.teamForm.requirements.splice(this.fromSetup.teamForm.requirements.indexOf(skill), 1);
                }
                this.searching = false;
            },

            untickOption(skill) {
                this.searching = true;
                if(this.fromSetup.teamForm.options.indexOf(skill) !== -1) {
                    this.fromSetup.teamForm.options.splice(this.fromSetup.teamForm.options.indexOf(skill), 1);
                }
                this.searching = false;
            },

            tickWanted() {
                this.searching = true;
                if(this.fromSetup.teamForm.notWanted === false) {
                    this.fromSetup.teamForm.wanted = true;
                }
                this.searching = false;
            },

            tickNotWanted() {
                this.searching = true;
                if(this.fromSetup.teamForm.wanted === false) {
                    this.fromSetup.teamForm.notWanted = true;
                }
                this.searching = false;
            },

            switchToUserSearch() {
                this.fromSetup.isUserSearch = true;
            },

            switchToTeamSearch() {
                this.fromSetup.isUserSearch = false;
            },

            searchUser() {
                if (this.searching) {
                    return false;
                } else {
                    this.searching = true;
                    this.fromSetup.inputError.userForm = '';
                    axios.get(route('search-user.show', this.fromSetup.userForm
                    )).then(response => {
                        this.fromSetup.output.users = response.data;
                    }).catch(error => {
                        this.fromSetup.inputError.userForm = error.response.data.errors;
                    }).then(() => {
                        this.searching = false;
                    });
                }
            },

            goToPage(url) {
                if (this.searching) {
                    return false;
                } else {
                    this.searching = true;
                    this.fromSetup.inputError.userForm = '';
                    axios.get(url).then(response => {
                        this.fromSetup.output.users = response.data;
                    }).catch(error => {
                        this.fromSetup.inputError.userForm = error.response.data.errors;
                    }).then(() => {
                        this.searching = false;
                    });
                }
            },

            searchTeam() {
                if (this.searching) {
                    return false;
                } else {
                    this.searching = true;
                    this.fromSetup.inputError.teamForm = '';
                    axios.get(route('search-team.show', this.fromSetup.teamForm
                    )).then(response => {
                        this.fromSetup.output.teams = response.data;
                    }).catch(error => {
                        this.fromSetup.inputError.teamForm = error.response.data.errors;
                    }).then(() => {
                        this.searching = false;
                    });
                }
            },

            goToPageForTeamSearch(url) {
                if (this.searching) {
                    return false;
                } else {
                    this.searching = true;
                    this.fromSetup.inputError.teamForm = '';
                    axios.get(url).then(response => {
                        this.fromSetup.output.teams = response.data;
                    }).catch(error => {
                        this.fromSetup.inputError.teamForm = error.response.data.errors;
                    }).then(() => {
                        this.searching = false;
                    });
                }
            },

            clearAll() {
                this.fromSetup.userForm.skills = [];
            },

            clearAllForTeam() {
                this.fromSetup.teamForm.wanted = true;
                this.fromSetup.teamForm.notWanted = true;
                this.fromSetup.teamForm.requirements = [];
                this.fromSetup.teamForm.options = [];
            }
        },

        computed: {
            userInput: {
                get() {
                    return this.fromSetup.userForm.searchUserInput;
                },
                set(input) {
                    this.fromSetup.userForm.searchUserInput = input;
                }
            },

            teamInput: {
                get() {
                    return this.fromSetup.teamForm.searchTeamInput;
                },
                set(input) {
                    this.fromSetup.teamForm.searchTeamInput = input;
                }
            },

            filterOutRequirements: function() {
                return function(requirements) {
                    let filtered = requirements.filter(requirement => {
                        return requirement.required === 0;
                    }, this);
                    return filtered;
                }
            },

            filterOutOptions: function() {
                return function(requirements) {
                    let filtered = requirements.filter(requirement => {
                        return requirement.required === 1;
                    }, this);
                    return filtered;
                }
            },
        },
    }
</script>
