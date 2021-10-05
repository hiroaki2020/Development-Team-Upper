<template>
    <div class="fixed top-0 left-0 w-full z-20" style="height: 7vh;">
        <div v-if="showOpener===true" @click.stop="openGetStartedModal=true" class="cursor-pointer absolute top-full mt-4 right-4 opacity-50 hover:opacity-100 bg-blue-400 text-white rounded-md border border-gray-200 h-28 w-40">
            <div @click.stop="hideGetStartedOpener" class="absolute -top-1 right-1 text-2xl cursor-pointer">×</div>
            <div v-if="currentSlideIndex===0">
                <h1 class="text-yellow-300">{{ __('Get started') }}</h1>
                <p>{{ __('Read insuructions on the 1st slide') }}</p>
            </div>
            <div v-if="currentSlideIndex===1">
                <h1 class="text-yellow-300">{{ __('Step ') }}1:</h1>
                <p>{{ __('Search user ') }}</p>
            </div>
            <div v-if="currentSlideIndex===2">
                <h1 class="text-yellow-300">{{ __('Step ') }}2:</h1>
                <p>{{ __('Search team ') }}</p>
            </div>
            <div v-if="currentSlideIndex===3">
                <h1 class="text-yellow-300">{{ __('Step ') }}3:</h1>
                <p>{{ __('Set your profile') }}</p>
            </div>
            <div v-if="currentSlideIndex===4">
                <h1 class="text-yellow-300">{{ __('Step ') }}4:</h1>
                <p>{{ __('Use chat') }}</p>
            </div>
            <div v-if="currentSlideIndex===5">
                <h1 class="text-yellow-300">{{ __('Step ') }}5:</h1>
                <p>{{ __('Make a join request') }}</p>
            </div>
            <div v-if="currentSlideIndex===6">
                <h1 class="text-yellow-300">{{ __('End') }}</h1>
                <p>{{ __('Start using DevCheers') }}</p>
            </div>
        </div>
        <div id="overlay" v-if="openGetStartedModal===true" @click.stop="openGetStartedModal=false" class="z-40 fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 flex items-center justify-center cursor-default text-black">
            <div id="content" class="z-50 w-1/2 h-2/3 pt-6 bg-app-top-swiper bg-cover flex items-center justify-center relative" @click.stop>
                <div class="absolute top-0 left-0 h-full w-full bg-white opacity-70"></div>
                <div @click.stop="openGetStartedModal=false" class="absolute -top-1 right-1 text-2xl cursor-pointer">×</div>
                <swiper class="w-full h-full"
                    :slides-per-view="1"
                    navigation
                    :pagination="{ clickable: true }"
                    :keyboard="{ enabled: true }"
                    :initialSlide="currentSlideIndex"
                    @slideChange="saveCurrentSlide"
                >
                    <swiper-slide class="px-12">
                        <div class="flex flex-col items-center justify-start h-full relative">
                            <h1 class="xl:text-3xl lg:text-2xl md:text-xl sm:text-lg overflow-y-scroll h-10">{{ __('Get Started') }}</h1>
                            <div class="flex flex-col items-center justify-start overflow-y-scroll absolute top-10 left-0 w-full" style="height: calc(100% - 5rem);">
                                <p class="mt-2">{{ __('Welcome to DevCheers! This Get Started session will walk you through the steps to use main features of DevCheers. Each slide consists of:') }}</p>
                                <ul class="mt-2 list-disc list-inside">
                                    <li>{{ __('Which step you are on(e.g.: \'Step 1: Search User\')') }}</li>
                                    <li>{{ __('ToDos(e.g.: 1. Click "Team Up" in the top navigation bar. 2. Type "DevCheers" into ...)') }}</li>
                                    <li>{{ __('Explanation') }}</li>
                                </ul>
                                <p class="mt-2">{{ __('You may skip reading explanation and go on to the next step. If you got stuck, click the blue box at top right corner and check it. The whole tour won\'t take too long, and in the end you will have solid foundation to use this app. I hope you can build/join a good team with it.') }}</p>
                                <p class="mt-2">{{ __('Click forward arrow on the right hand side or Press rightwards arrow key(') }}&rarr;{{ __(') to go on.') }}</p>
                            </div>
                        </div>
                    </swiper-slide>
                    <swiper-slide class="px-12">
                        <div class="flex flex-col items-center justify-start h-full relative">
                            <h1 class="xl:text-3xl lg:text-2xl md:text-xl sm:text-lg overflow-y-scroll h-10">{{ __('Step ') }}1: {{ __('Search User') }}</h1>
                            <div class="flex flex-col items-center justify-start overflow-y-scroll absolute top-10 left-0 w-full" style="height: calc(100% - 5rem);">
                                <ol class="mt-2 list-decimal list-inside">
                                    <li>{{ __('Click "Team Up" in the top navigation bar.') }}</li>
                                    <li>{{ __('Type "DevCheers" into the input field at top left corner and hit "Search" button.') }}</li>
                                    <li>
                                        {{ __('Click a black box labeled "DevCheers" in the largest white box.') }}<br>
                                        <span class="">{{ __('*Black box is DevCheers\' official account and white ones are users\'.') }}</span>
                                    </li>
                                </ol>
                                <p class="mt-2">{{ __('Explanation: "Team Up" page basically provides features to actually form teams. The features include user and team search as well as join requests and team invitations check. Explanation focuses on user search this time. You can search user by a keyword where the result includes users whose profiles contain the keyword. Note you can filter search results by users\' skills by picking skills in the middle left box. Of course you can directly type the skill name into the input field and search. The largest white box contains search results and by clicking a box of user profile in the box, you can see more specific one at the different page.') }}</p>
                            </div>
                        </div>
                    </swiper-slide>
                    <swiper-slide class="px-12">
                        <div class="flex flex-col items-center justify-start h-full relative">
                            <h1 class="xl:text-3xl lg:text-2xl md:text-xl sm:text-lg overflow-y-scroll h-10">{{ __('Step ') }}2: {{ __('Search Team') }}</h1>
                            <div class="flex flex-col items-center justify-start overflow-y-scroll absolute top-10 left-0 w-full" style="height: calc(100% - 5rem);">
                                <ol class="mt-2 list-decimal list-inside">
                                    <li>{{ __('Click "Back" button in your browser or Click "Team Up" again.') }}</li>
                                    <li>{{ __('Click "Switch to team search" under the top left input field.') }}</li>
                                    <li>{{ __('Type "DevCheers\' Team" into the input field at top left corner and hit "Search" button.') }}</li>
                                    <li>
                                        {{ __('Click a black box labeled "DevCheers\' Team" in the largest white box.') }}<br>
                                        <span class="">{{ __('*Black box is DevCheers\' official team and white ones are users\'.') }}</span>
                                    </li>
                                </ol>
                                <p class="mt-2">{{ __('Explanation: As stated before, on "Team Up" page, you can search team as well. This time keyword search results consist of a list of teams whose profiles contain the keyword. Note the contents in the middle left box changes from the last step and now you can filter by whether teams are looking for new members or not. When you set "wanted" for the params, you can further filter the results by team requirements and options. The largest white box contains search results and by clicking a box of team profile in the box, you can see more specific one at the different page.') }}</p>
                            </div>
                        </div>
                    </swiper-slide>
                    <swiper-slide class="px-12">
                        <div class="flex flex-col items-center justify-start h-full relative">
                            <h1 class="xl:text-3xl lg:text-2xl md:text-xl sm:text-lg overflow-y-scroll h-10">{{ __('Step ') }}3: {{ __('Set Profile ') }}</h1>
                            <div class="flex flex-col items-center justify-start overflow-y-scroll absolute top-10 left-0 w-full" style="height: calc(100% - 5rem);">
                                <p class="text-red-500 text-xl">{{ __('*You need to ') }}
                                    <inertia-link :href="route('register')" class="underline text-blue-500 hover:text-purple-700">
                                        {{ __('register') }}
                                    </inertia-link>
                                    {{ __('and') }}
                                    <inertia-link :href="route('login')" class="underline text-blue-500 hover:text-purple-700">
                                        {{ __('login') }}
                                    </inertia-link>
                                 {{ __('to continue from this step.') }}</p>
                                <ol class="mt-2 list-decimal list-inside">
                                    <li>{{ __('Click "Set Profile" in the top navigation bar.') }}</li>
                                    <li>{{ __('Set your profile by filling empty input fields and hitting "SAVE" button.') }}</li>
                                    <li>{{ __('Click "See your profile" on the left hand side and check your profile.') }}</li>
                                </ol>
                                <p class="mt-2">{{ __('Explanation: As mentioned before, your profile information is used for others to find you in user search. It is highly recommended to set "Select your skills" field because it interests them the most. You may notice the page "See your profile" leads to is the one which actually can be found in user search. Make sure it is ok.') }}</p>
                            </div>
                        </div>
                    </swiper-slide>
                    <swiper-slide class="px-12">
                        <div class="flex flex-col items-center justify-start h-full relative">
                            <h1 class="xl:text-3xl lg:text-2xl md:text-xl sm:text-lg overflow-y-scroll h-10">{{ __('Step ') }}4: {{ __('Use Chat') }}</h1>
                            <div class="flex flex-col items-center justify-start overflow-y-scroll absolute top-10 left-0 w-full" style="height: calc(100% - 5rem);">
                                <ol class="mt-2 list-decimal list-inside">
                                    <li>{{ __('Click "Team Up" and search "DevCheers" in user search and go to the DevCheers\' profile page again.') }}</li>
                                    <li>{{ __('Click "Chat".') }}</li>
                                    <li>{{ __('Click "DevCheers" on the left hand side then type something into the bottom box with "Enter a message" message and hit enter key.') }}</li>
                                    <li>{{ __('Right click speech balloon with message you just sent and click "Delete this message".') }}</li>
                                    <li>{{ __('Right click "DevCheers" on the left hand side and click "Delete this conversation".') }}</li>
                                </ol>
                                <p class="mt-2">
                                    {{ __('Explanation: On "Chat" page, you can have a chat with others and can also negotiate/help team owner negotiate on team participation. As you just did, you can create/delete chatroom and send/delete message. There is no user approval feat to start one to one chat because the primary purpose of chat feat is negotiate on team participation and the approval feat causes delay in negotiation and you may miss a chance. This session does not go through how to use team chat but basically it is the same as the individual chat. A difference is you can not start/delete team chat by yourself. Instead, you are added to team chat when you join a team and you are removed from team chat when you leave team. You can also send an image by clicking the image icon at top right corner of the message input box. You can see a larger image by clicking an image in chat.') }}
                                </p>
                            </div>
                        </div>
                    </swiper-slide>
                    <swiper-slide class="px-12">
                        <div class="flex flex-col items-center justify-start h-full relative">
                            <h1 class="xl:text-3xl lg:text-2xl md:text-xl sm:text-lg overflow-y-scroll h-10">{{ __('Step ') }}5: {{ __('Make A Join Request') }}</h1>
                            <div class="flex flex-col items-center justify-start overflow-y-scroll absolute top-10 left-0 w-full" style="height: calc(100% - 5rem);">
                                <ol class="mt-2 list-decimal list-inside">
                                    <li>{{ __('Click "Team Up" and click "Switch to team search" and search "DevCheers\' team" in team search and go to the "DevCheers\' team" profile page.') }}</li>
                                    <li>{{ __('Fill "Make Join Request" form and click "SEND REQUEST".') }}</li>
                                </ol>
                                <p class="mt-2">
                                    {{ __('Explanation: Join request is the request to join teams. The request you just made is judged by owner of "DevCheers\' team". It is not processed this time because this session is just a practice. You can also cancel your request by clicking "Cancel" in "Pending Join Request" section.') }}
                                </p>
                            </div>
                        </div>
                    </swiper-slide>
                    <swiper-slide class="px-12">
                        <div class="flex flex-col items-center justify-start h-full relative">
                            <h1 class="xl:text-3xl lg:text-2xl md:text-xl sm:text-lg overflow-y-scroll h-10">{{ __('End') }}</h1>
                            <div class="flex flex-col items-center justify-start overflow-y-scroll absolute top-10 left-0 w-full" style="height: calc(100% - 5rem);">
                                <p class="mt-2">{{ __('That\'s it. Although this session does not go through how to do, you can do more things. Such as') }}</p>
                                <ul class="mt-2 list-disc list-inside">
                                    <li>{{ __('Create your own team') }}</li>
                                    <li>{{ __('Invite someone to your team') }}</li>
                                    <li>{{ __('Manage your team') }}</li>
                                </ul>
                                <p class="mt-2">{{ __('For more information, go to ') }}<inertia-link :href="route('documentation')" class="text-blue-500 hover:text-purple-700 underline">{{ __('documentation') }}</inertia-link>{{ __('.') }}</p>
                                <p class="mt-2">
                                    {{ __('Finally and again, I hope you can build/join a good team with DevCheers.') }}
                                </p>
                            </div>
                        </div>
                    </swiper-slide>
                </swiper>
            </div>
        </div>
    </div>
</template>

<script>
    import SwiperCore, { Navigation, Pagination, Keyboard } from 'swiper'
    import { Swiper, SwiperSlide } from 'swiper/vue'

    import 'swiper/swiper-bundle.css'

    import { useRemember } from '@inertiajs/inertia-vue3'
    
    SwiperCore.use([Navigation, Pagination, Keyboard]);

    export default {
        components: {
            Swiper,
            SwiperSlide,
        },

        setup() {
            const fromSetup = useRemember({
                isLoadedFromCache: false
            });
            
            return { fromSetup };
        },

        data() {
            return {
                openGetStartedModal: false,
                showOpener: this.$page.props.showOpenerFromServer ?? false,
                currentSlideIndex: this.$page.props.currentSlideIndexFromServer ?? 0,
                saveCurrentSlideTimer: false
            }
        },

        methods: {
            hideGetStartedOpener() {
                this.showOpener = false;
                axios.post(route('show-opener'), {
                    _method: 'PUT',
                    show_opener: this.showOpener
                });
            },

            saveCurrentSlide(swiper) {
                this.currentSlideIndex = swiper.activeIndex;
                if(this.saveCurrentSlideTimer) {
                    clearTimeout(this.saveCurrentSlideTimer);
                }
                this.saveCurrentSlideTimer = setTimeout(this.saveCurrentSlideToBackend, 2000);
            },

            saveCurrentSlideToBackend() {
                this.saveCurrentSlideTimer = false;
                axios.post(route('current-slide'), {
                    _method: 'PUT',
                    current_slide_index: this.currentSlideIndex
                });
            }
        },

        created() {
            if(this.fromSetup.isLoadedFromCache) {
                this.showOpener = false;
                axios.get(route('get-started-modal-session-data')
                ).then(response => {
                    this.currentSlideIndex = response.data.currentSlideIndex;
                    this.showOpener = response.data.showOpener;
                });
            }
            this.fromSetup.isLoadedFromCache = true;
        },
    }
</script>
