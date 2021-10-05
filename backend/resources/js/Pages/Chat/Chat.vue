<template>
    <app-layout>
        <div class="grid grid-cols-12" style="height: 93vh;">
            <div class="col-span-3 bg-blue-100 w-full relative h-full">
                <div class="w-full border-b border-black flex items-center justify-center sticky top-0 bg-gray-700 text-gray-300 z-10" style="height: 9vh;">
                    <div class="grid grid-cols-3 h-full w-full">
                        <div @click="sortAll" class="col-span-1 w-full h-full flex justify-center items-center cursor-pointer hover:bg-black hover:text-white" :class="{ 'bg-black': fromSetup.chatSort === 'all', 'text-white': fromSetup.chatSort === 'all' }">
                            {{ __('All') }}
                        </div>
                        <div @click="sortIndividuals" class="col-span-1 w-full h-full flex justify-center items-center cursor-pointer hover:bg-black hover:text-white" :class="{ 'bg-black': fromSetup.chatSort === 'individuals', 'text-white': fromSetup.chatSort === 'individuals' }">
                            {{ __('Individuals') }}
                        </div>
                        <div @click="sortTeams" class="col-span-1 w-full h-full flex justify-center items-center cursor-pointer hover:bg-black hover:text-white" :class="{ 'bg-black': fromSetup.chatSort === 'teams', 'text-white': fromSetup.chatSort === 'teams' }">
                            {{ __('Teams') }}
                        </div>
                    </div>
                </div>
                <div class="w-full overflow-y-scroll flex flex-col items-center justify-start" style="height: 84vh;">
                    <div v-if="fromSetup.chatSort === 'all'" class="h-full w-full">
                        <div v-for="(conversation, key) in fromSetup.chat" :key="key" class="h-12vh w-full border-b border-gray-400" :class="{'bg-blue-200': fromSetup.currentChatId !== conversation.id, 'bg-blue-400': fromSetup.currentChatId === conversation.id}">
                            <div v-if="conversation.team_id !== null" @click="showTeamChat(conversation)" class="h-full w-full cursor-pointer">
                                <div class="grid grid-cols-3 w-full h-full">
                                    <div class="col-span-1 h-full w-full flex items-center justify-center">
                                        <img class="h-14 w-14 block rounded-full object-cover" :src="conversation.team.team_profile_photo_url" :alt="conversation.team.name" />
                                    </div>
                                    <div class="col-span-2 h-full w-full flex flex-col">
                                        <div class="h-1/2 w-full flex justify-between relative">
                                            <div class="self-end overflow-ellipsis overflow-hidden whitespace-nowrap w-full">{{ conversation.team.name }}</div>
                                            <div class="absolute top-0 right-0 text-sm text-gray-700 p-2">{{ conversation.last_message === null ? '' : __(toJST(conversation.last_message.created_at)) }}</div>
                                        </div>
                                        <div class="h-1/2 w-full flex justify-start text-gray-700">
                                            <div class="self-start overflow-ellipsis overflow-hidden whitespace-nowrap w-full">{{ conversation.last_message === null ? '' : conversation.last_message.message }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="h-full w-full" @contextmenu.prevent="showMenuForIndividualChat(conversation, $event)">
                                <div v-for="(eachUser, key) in conversation.users" :key="key" :class="{'h-full': eachUser.id !== $page.props.user.id, 'w-full': eachUser.id !== $page.props.user.id}">
                                    <div v-if="eachUser.id !== $page.props.user.id" @click="showIndividualChat(conversation, eachUser)" class="h-full w-full cursor-pointer">
                                        <div class="grid grid-cols-3 w-full h-full">
                                            <div class="col-span-1 h-full w-full flex items-center justify-center">
                                                <img class="h-14 w-14 block rounded-full object-cover" :src="eachUser.profile_photo_url" :alt="eachUser.name" />
                                            </div>
                                            <div class="col-span-2 h-full w-full flex flex-col">
                                                <div class="h-1/2 w-full flex justify-between relative">
                                                    <div class="self-end overflow-ellipsis overflow-hidden whitespace-nowrap w-full">{{ eachUser.name }}</div>
                                                    <div class="absolute top-0 right-0 text-sm text-gray-700 p-2">{{ conversation.last_message === null ? '' : __(toJST(conversation.last_message.created_at)) }}</div>
                                                </div>
                                                <div class="h-1/2 w-full flex justify-start text-gray-700">
                                                    <div class="self-start overflow-ellipsis overflow-hidden whitespace-nowrap w-full">{{ conversation.last_message === null ? '' : conversation.last_message.message }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="fromSetup.chatSort === 'individuals'" class="h-full w-full">
                        <div v-for="(conversation, key) in fromSetup.chat" :key="key" :class="{ 'h-12vh': conversation.team_id === null, 'w-full': conversation.team_id === null, 'border-b': conversation.team_id === null, 'border-gray-400': conversation.team_id === null, 'bg-blue-200': fromSetup.currentChatId !== conversation.id, 'bg-blue-400': fromSetup.currentChatId === conversation.id}">
                            <div v-if="conversation.team_id === null" @contextmenu.prevent="showMenuForIndividualChat(conversation, $event)" class="h-full w-full">
                                <div v-for="(eachUser, key) in conversation.users" :key="key" :class="{'h-full': eachUser.id !== $page.props.user.id && conversation.team_id === null, 'w-full': eachUser.id !== $page.props.user.id && conversation.team_id === null}">
                                    <div v-if="eachUser.id !== $page.props.user.id" @click="showIndividualChat(conversation, eachUser)" class="h-full w-full cursor-pointer">
                                        <div class="grid grid-cols-3 w-full h-full">
                                            <div class="col-span-1 h-full w-full flex items-center justify-center">
                                                <img class="h-14 w-14 block rounded-full object-cover" :src="eachUser.profile_photo_url" :alt="eachUser.name" />
                                            </div>
                                            <div class="col-span-2 h-full w-full flex flex-col">
                                                <div class="h-1/2 w-full flex justify-between relative">
                                                    <div class="self-end overflow-ellipsis overflow-hidden whitespace-nowrap w-full">{{ eachUser.name }}</div>
                                                    <div class="absolute top-0 right-0 text-sm text-gray-700 p-2">{{ conversation.last_message === null ? '' : __(toJST(conversation.last_message.created_at)) }}</div>
                                                </div>
                                                <div class="h-1/2 w-full flex justify-start text-gray-700">
                                                    <div class="self-start overflow-ellipsis overflow-hidden whitespace-nowrap w-full">{{ conversation.last_message === null ? '' : conversation.last_message.message }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="fromSetup.chatSort === 'teams'" class="h-full w-full">
                        <div v-for="(conversation, key) in fromSetup.chat" :key="key" :class="{ 'h-12vh': conversation.team_id !== null, 'w-full': conversation.team_id !== null, 'border-b': conversation.team_id !== null, 'border-gray-400': conversation.team_id !== null, 'bg-blue-200': fromSetup.currentChatId !== conversation.id, 'bg-blue-400': fromSetup.currentChatId === conversation.id }">
                            <div v-if="conversation.team_id !== null" @click="showTeamChat(conversation)" class="h-full w-full cursor-pointer">
                                <div class="grid grid-cols-3 w-full h-full">
                                    <div class="col-span-1 h-full w-full flex items-center justify-center">
                                        <img class="h-14 w-14 block rounded-full object-cover" :src="conversation.team.team_profile_photo_url" :alt="conversation.team.name" />
                                    </div>
                                    <div class="col-span-2 h-full w-full flex flex-col">
                                        <div class="h-1/2 w-full flex justify-between relative">
                                            <div class="self-end overflow-ellipsis overflow-hidden whitespace-nowrap w-full">{{ conversation.team.name }}</div>
                                            <div class="absolute top-0 right-0 text-sm text-gray-700 p-2">{{ conversation.last_message === null ? '' : __(toJST(conversation.last_message.created_at)) }}</div>
                                        </div>
                                        <div class="h-1/2 w-full flex justify-start text-gray-700">
                                            <div class="self-start overflow-ellipsis overflow-hidden whitespace-nowrap w-full">{{ conversation.last_message === null ? '' : conversation.last_message.message }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-9 w-full h-full border-l border-gray-400 bg-blue-300">
                <div v-if="fromSetup.currentChatId !== ''" class="h-full w-full">
                    <div class="w-full" style="height: 77vh;">
                        <div class="w-full" style="height: 7vh;">
                            <div v-if="fromSetup.isIndividualChat" class="h-full w-full">
                                <inertia-link :href="route('see-profile.show', fromSetup.chatHeader.id)" class="flex items-center h-full pl-4 border-b border-black w-full bg-gray-800 text-white">
                                    <p>{{ fromSetup.chatHeader.name }}</p>
                                </inertia-link>
                            </div>
                            <div v-if="fromSetup.isIndividualChat === false" class="h-full w-full">
                                <inertia-link :href="route('see-team-profile.show', fromSetup.chatHeader.id)" class="flex items-center h-full pl-4 border-b border-black w-full bg-gray-800 text-white">
                                    <p>{{ fromSetup.chatHeader.name }}</p>
                                </inertia-link>
                            </div>
                        </div>
                        <div class="overflow-y-scroll overflow-x-hidden w-full" id="chat-history" style="height: 70vh;">
                            <div v-for="(conversation, key) in fromSetup.chat" :key="key" class="w-full" :class="{ 'hidden': conversation.id !== fromSetup.currentChatId }">
                                <div v-if="conversation.id === fromSetup.currentChatId" class="w-full flex flex-col-reverse" :class="{ 'hidden': conversation.id !== fromSetup.currentChatId }">
                                    <div v-for="(eachMessage, msgKey) in conversation.messages" :key="msgKey" class="w-full">
                                        <div v-if="msgKey === conversation.messages.length - 1 || dateMessageSent(eachMessage.created_at) !== dateMessageSent(conversation.messages[msgKey + 1].created_at)" class="w-full flex items-center justify-center mb-4" :class="{ 'mt-4': msgKey === conversation.messages.length - 1 }">
                                            <div class="rounded-full px-4 text-gray-700 bg-blue-400">{{ __(dateMessageSent(eachMessage.created_at)) }}</div>
                                        </div>
                                        <div v-if="eachMessage.sender_id === $page.props.user.id" class="mb-4 pr-6 w-full">
                                            <div v-if="!eachMessage.is_image" class="flex items-center justify-end w-full">
                                                <div class="flex items-center justify-end w-5/6 relative">
                                                    <div class="rounded-full bg-white p-4 absolute -top-1 -right-3"></div>
                                                    <div class="rounded-full bg-blue-300 p-4 absolute -top-3 -right-4 z-10"></div>
                                                    <div @contextmenu.prevent="showMenuForMessage(eachMessage, $event)" class="py-2 px-4 rounded-3xl bg-white z-20 whitespace-pre-wrap break-words relative">
                                                        {{ eachMessage.message }}
                                                        <div class="absolute -left-11 text-gray-500 bottom-0 text-sm">{{ whenSent(eachMessage.created_at) }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-else class="flex items-center justify-end w-full">
                                                <div class="rounded-lg flex items-start justify-end h-52 w-52 relative">
                                                    <div class="absolute -left-11 text-gray-500 bottom-0 text-sm">
                                                        {{ whenSent(eachMessage.created_at) }}
                                                    </div>
                                                    <img v-if="fromSetup.imageLoading[eachMessage.id] === false" @click.left="openModal(eachMessage.id)" @contextmenu.prevent="showMenuForMessage(eachMessage, $event)" :src="fromSetup.imageUrl['image'+eachMessage.id]" alt="image" :id="'image' + eachMessage.id" @error.once="this.onerror=null;showImageOnError(eachMessage);" class="cursor-pointer object-cover rounded-lg" />
                                                    <img v-else :src="'/images/loading.gif'" alt="loading image" class="object-cover rounded-lg h-52 w-52" />
                                                    <modal v-if="modalImage === eachMessage.id" @close-modal="closeModal">
                                                        <img :src="fromSetup.imageUrl['image'+eachMessage.id]" alt="image" @error="this.onerror=null;this.src=showImageOnError(eachMessage);" />
                                                    </modal>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-else class="w-full">
                                            <div v-for="(eachUser, key) in conversation.users" :key="key" class="w-full">
                                                <div v-if="fromSetup.isIndividualChat" class="w-full">
                                                    <div v-if="eachUser.id === fromSetup.chatHeader.id" class="w-full flex items-start justify-start mb-4">
                                                        <div class="w-5/6 flex items-start justify-start">
                                                            <inertia-link :href="route('see-profile.show', fromSetup.chatHeader.id)" class="mx-4 flex-shrink-0">
                                                                <img class="w-10 h-10 rounded-full" :src="eachUser.profile_photo_url" :alt="eachUser.name" />
                                                            </inertia-link>
                                                            <div v-if="!eachMessage.is_image" class="flex items-center justify-start relative">
                                                                <div class="rounded-full bg-white p-4 absolute -top-1 -left-3"></div>
                                                                <div class="rounded-full bg-blue-300 p-4 absolute -top-3 -left-4 z-10"></div>
                                                                <div class="rounded-3xl py-2 px-4 bg-white z-20 whitespace-pre-wrap break-words relative">
                                                                    {{ eachMessage.message }}
                                                                    <div class="absolute -right-11 text-gray-500 bottom-0 text-sm">{{ whenSent(eachMessage.created_at) }}</div>
                                                                </div>
                                                            </div>
                                                            <div v-else class="relative">
                                                                <div class="absolute -right-11 text-gray-500 bottom-0 text-sm">
                                                                    {{ whenSent(eachMessage.created_at) }}
                                                                </div>
                                                                <img v-if="fromSetup.imageLoading[eachMessage.id] === false" @click="openModal(eachMessage.id)" :src="fromSetup.imageUrl['image'+eachMessage.id]" alt="image" :id="'image' + eachMessage.id" @error.once="this.onerror=null;showImageOnError(eachMessage);" class="rounded-lg cursor-pointer h-52 w-52 object-cover" />
                                                                <img v-else :src="'/images/loading.gif'" alt="loading image" class="rounded-lg h-52 w-52 object-cover" />
                                                                <modal v-if="modalImage === eachMessage.id" @close-modal="closeModal">
                                                                    <img :src="fromSetup.imageUrl['image'+eachMessage.id]" alt="image" @error="this.onerror=null;this.src=showImageOnError(eachMessage);" />
                                                                </modal>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div v-else class="w-full">
                                                    <div v-if="eachMessage.sender_id === eachUser.id" class="w-full flex items-start justify-start mb-4">
                                                        <div class="w-5/6 flex items-start justify-start">
                                                            <inertia-link :href="route('see-profile.show', eachUser.id)" class="mx-4 flex-shrink-0">
                                                                <img class="w-10 h-10 rounded-full" :src="eachUser.profile_photo_url" :alt="eachUser.name" />
                                                            </inertia-link>
                                                            <div class="w-full flex flex-col items-start justify-start">
                                                                <div class="h-5 w-full text-xs flex items-center justify-start z-20">{{ eachUser.name }}</div>
                                                                <div v-if="!eachMessage.is_image" class="flex items-center justify-start relative">
                                                                    <div class="rounded-full bg-white p-4 absolute -top-1 -left-3"></div>
                                                                    <div class="rounded-full bg-blue-300 p-4 absolute -top-3 -left-4 z-10"></div>
                                                                    <div class="rounded-3xl py-2 px-4 bg-white z-20 whitespace-pre-wrap break-words relative">
                                                                        {{ eachMessage.message }}
                                                                        <div class="absolute -right-11 text-gray-500 bottom-0 text-sm">{{ whenSent(eachMessage.created_at) }}</div>
                                                                    </div>
                                                                </div>
                                                                <div v-else class="relative">
                                                                    <div class="absolute -right-11 text-gray-500 bottom-0 text-sm">
                                                                        {{ whenSent(eachMessage.created_at) }}
                                                                    </div>
                                                                    <img v-if="fromSetup.imageLoading[eachMessage.id] === false" @click="openModal(eachMessage.id)" :src="fromSetup.imageUrl['image'+eachMessage.id]" alt="image" :id="'image' + eachMessage.id" @error.once="this.onerror=null;showImageOnError(eachMessage);" class="rounded-lg cursor-pointer h-52 w-52 object-cover" />
                                                                    <img v-else :src="'/images/loading.gif'" alt="loading image" class="rounded-lg h-52 w-52 object-cover" />
                                                                    <modal v-if="modalImage === eachMessage.id" @close-modal="closeModal">
                                                                        <img :src="fromSetup.imageUrl['image'+eachMessage.id]" alt="image" @error="this.onerror=null;this.src=showImageOnError(eachMessage);" />
                                                                    </modal>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-full sticky bottom-0 flex" style="height: 16vh;">
                        <div class="absolute top-0 right-0 h-6 w-6 m-3 z-10">
                            <label for="upload_photo"><img :src="'/images/photo-icon.svg'" alt="photo upload" class="object-contain cursor-pointer" /></label>
                            <input @change="uploadPhoto" accept="image/*" class="hidden" id="upload_photo" type="file" name="upload_photo" @click="setCurrentChatIdToSubmit" />
                        </div>
                        <textarea v-model="fromSetup.tmpMessageStore" @keydown.enter.exact.prevent="sendMessage" @keydown.enter.shift.exact="newLine" @click="setCurrentChatIdToSubmit" class="bg-gray-800 focus:opacity-100 opacity-70 placeholder-white text-white w-full h-full resize-none pr-12 flex-shrink" :placeholder="__('Enter a message')"></textarea>
                    </div>
                </div>
                <div v-else class="h-full w-full flex items-center justify-center">
                    <p>{{ __('Pick someone to talk from the left box. If the box is empty,') }}&nbsp;
                        <inertia-link :href="route('teamup')" class="text-green-700 underline">
                            {{ __('invite someone to talk.') }}
                        </inertia-link>
                    </p>
                </div>
            </div>
        </div>
        <div v-show="showIndividualChatMenu" id="individual-chat-menu-overlay" @click.left.stop.prevent="closeIndividualChatMenu" @click.right.prevent="rightClickOtherPoint" class="z-40 fixed top-0 left-0 w-full h-full bg-transparent cursor-default">
            <ul id="individual-chat-menu" @click.left.stop @click.right.stop.prevent class="fixed z-50 bg-gray-700 text-sm text-white rounded-md p-2 border border-gray-400">
                <li @click.left.stop.prevent="deleteConversation" @click.right.stop.prevent class="hover:bg-blue-400 px-1 rounded">
                    {{ __('Delete this conversation') }}
                </li>
            </ul>
        </div>
        <div v-show="showMessageMenu" id="message-menu-overlay" @click.left.stop.prevent="closeMessageMenu" @click.right.prevent="rightClickOtherPointForMessage" class="z-40 fixed top-0 left-0 w-full h-full bg-transparent cursor-default">
            <ul id="message-menu" @click.left.stop @click.right.stop.prevent class="fixed z-50 bg-gray-700 text-sm text-white rounded-md p-2 border border-gray-400">
                <li @click.left.stop.prevent="deleteMessage" @click.right.stop.prevent class="hover:bg-blue-400 px-1 rounded">
                    {{ __('Delete this message') }}
                </li>
            </ul>
        </div>
    </app-layout>
</template>

<script>
    import Modal from '@/Pages/Modal'

    import { useRemember } from '@inertiajs/inertia-vue3'

    export default {
        components: {
            Modal,
        },

        props: [
            'currentConversation',
            'chatSource',
            'fromProfile',
        ],

        setup() {
            const fromSetup = useRemember({
                chat: null,
                tmpMessage: {},
                chatSort: 'all',
                tmpMessageStore: '',

                currentChatId: '',

                messageForm: {
                    sender_id: null,
                    message: '',
                    is_image: false,
                    currentChatIdToSubmit: '',
                },

                isIndividualChat: null,

                chatHeader: {
                    name: '',
                    id: '',
                },

                imageLoading: {},
                imageUrl: {},
            });
            
            return { fromSetup };
        },

        data() {
            return {
                targetConversation: null,
                targetMessage: null,
                showIndividualChatMenu: false,
                showMessageMenu: false,
                modalImage: null,
                userChannel: null,
                isLoadedFromCache: true,
                isLoadedFromCacheWithNewMessages: false
            }
        },

        methods: {
            deleteConversation() {
                this.showIndividualChatMenu = false;
                axios.post(route('chat.delete'), {
                    conversation_id: this.targetConversation.id,
                }).then(response => {
                    this.fromSetup.chat = response.data.chat;
                    this.targetConversation = null;
                    delete this.fromSetup.tmpMessage['chatId'+response.data.conversation_id];
                    if(this.fromSetup.currentChatId === response.data.conversation_id) {
                        this.fromSetup.currentChatId = '';
                        this.fromSetup.tmpMessageStore = '';
                        this.fromSetup.chatHeader.name = '';
                        this.fromSetup.chatHeader.id = '';
                        this.fromSetup.isIndividualChat = null;
                    }
                });
            },

            deleteMessage() {
                this.showMessageMenu = false;
                axios.post(route('message.delete'), {
                    message_id: this.targetMessage.id,
                }).then(response => {
                    this.fromSetup.chat = response.data;
                }).then(() => {
                   this.targetMessage = null;
                });
            },

            rightClickOtherPoint(event) {
                let promise = new Promise((resolve, reject) => {
                    this.targetConversation = null;
                    this.showIndividualChatMenu = false;
                    resolve();
                });
                
                promise.then(() => {
                    let element = document.elementFromPoint(event.clientX, event.clientY);
                    element.dispatchEvent(new MouseEvent("contextmenu", {
                        bubbles: true,
                        clientX: event.clientX,
                        clientY: event.clientY
                    }));
                });
            },

            rightClickOtherPointForMessage(event) {
                let promise = new Promise((resolve, reject) => {
                    this.targetMessage = null;
                    this.showMessageMenu = false;
                    resolve();
                });
                
                promise.then(() => {
                    let element = document.elementFromPoint(event.clientX, event.clientY);
                    element.dispatchEvent(new MouseEvent("contextmenu", {
                        bubbles: true,
                        clientX: event.clientX,
                        clientY: event.clientY
                    }));
                });
            },

            closeIndividualChatMenu() {
                this.showIndividualChatMenu = false;
            },

            closeMessageMenu() {
                this.showMessageMenu = false;
            },

            showMenuForIndividualChat(conversation, event) {
                this.targetConversation = conversation;
                document.getElementById('individual-chat-menu').style.top = `${event.clientY}px`;
                document.getElementById('individual-chat-menu').style.left = `${event.clientX}px`;
                this.showIndividualChatMenu = true;
            },

            showMenuForMessage(message, event) {
                this.targetMessage = message;
                document.getElementById('message-menu').style.top = `${event.clientY}px`;
                document.getElementById('message-menu').style.left = `${event.clientX}px`;
                this.showMessageMenu = true;
            },

            connect() {
                Echo.private(this.userChannel)
                    .listen('ConversationDeleted', e => {
                        axios.get(route('new-chat.index'))
                        .then(response => {
                            this.fromSetup.chat = response.data;
                            delete this.fromSetup.tmpMessage['chatId'+e.conversation_id];
                            if(this.fromSetup.currentChatId === e.conversation_id) {
                                this.fromSetup.currentChatId = '';
                                this.fromSetup.tmpMessageStore = '';
                                this.fromSetup.chatHeader.name = '';
                                this.fromSetup.chatHeader.id = '';
                                this.fromSetup.isIndividualChat = null;
                            }
                        });
                    })
                    .listen('MessageSent', e => {
                        axios.get(route('message.index'))
                        .then(response => {
                            this.fromSetup.chat = response.data;
                        }).then(() => {
                            if(this.fromSetup.currentChatId === e.message.conversation_id) {
                                document.getElementById('chat-history').scrollTop = document.getElementById('chat-history').scrollHeight;
                            }
                        }).then(() => {
                            if(e.message.is_image) {
                                axios.post(route('image.show'), {
                                    image_path: e.message.image_path,
                                    conversation_id: e.message.conversation_id,
                                }, {
                                    responseType: 'arraybuffer'
                                }).then(innerResponse => {
                                    let blob = new Blob([innerResponse.data], {type: 'image/*'});
                                    let imageURL = window.URL.createObjectURL(blob);
                                    this.fromSetup.imageUrl['image'+e.message.id] = imageURL;
                                    this.fromSetup.imageLoading[e.message.id] = false;
                                });
                            }
                        });
                    })
                    .listen('MessageDeleted', e => {
                        axios.get(route('message.index'))
                        .then(response => {
                            this.fromSetup.chat = response.data;
                        });
                    })
                    .listen('ConversationUserDeleted', e => {
                        axios.get(route('new-chat.index'))
                        .then(response => {
                            this.fromSetup.chat = response.data;
                        });
                    })
                    .listen('ConversationUserCreated', e => {
                        axios.get(route('new-chat.index'))
                        .then(response => {
                            this.fromSetup.chat = response.data;
                        });
                    });
            },

            openModal(messageId) {
                this.modalImage = messageId;
            },

            closeModal() {
                this.modalImage = null;
            },

            setCurrentChatIdToSubmit() {
                this.fromSetup.messageForm.currentChatIdToSubmit = this.fromSetup.currentChatId;
            },

            uploadPhoto(event) {
                if(event.target.files[0] === undefined) {
                    return false;
                };
                let formData = new FormData();
                formData.append('image', event.target.files[0]);
                formData.append('is_image', true);
                formData.append('sender_id', this.fromSetup.messageForm.sender_id);
                formData.append('currentChatId', this.fromSetup.messageForm.currentChatIdToSubmit);
                axios.post(route('image.store'),
                    formData
                ).then(response => {
                    this.fromSetup.chat = response.data.chat;
                    return response;
                }).then(response => {
                    if(this.fromSetup.currentChatId === response.data.message.conversation_id) {
                        document.getElementById('chat-history').scrollTop = document.getElementById('chat-history').scrollHeight;
                    }
                    axios.post(route('image.show'), {
                        image_path: response.data.message.image_path,
                        conversation_id: response.data.message.conversation_id,
                    }, {
                        responseType: 'arraybuffer'
                    }).then(innerResponse => {
                        let blob = new Blob([innerResponse.data], {type: 'image/*'});
                        let imageURL = window.URL.createObjectURL(blob);
                        this.fromSetup.imageUrl['image'+response.data.message.id] = imageURL;
                        this.fromSetup.imageLoading[response.data.message.id] = false;
                    });
                });
            },

            newLine() {
                this.message = `${this.message}\n`;
            },

            sortAll() {
                this.fromSetup.chatSort = 'all';
            },

            sortIndividuals() {
                this.fromSetup.chatSort = 'individuals';
            },

            sortTeams() {
                this.fromSetup.chatSort = 'teams';
            },

            showIndividualChat(conversation, eachUser) {
                const promise = new Promise((resolve, reject) => {
                    this.fromSetup.tmpMessage['chatId'+this.fromSetup.currentChatId] = this.fromSetup.tmpMessageStore;
                    this.fromSetup.isIndividualChat = true;
                    this.fromSetup.chatHeader.name = eachUser.name;
                    this.fromSetup.chatHeader.id = eachUser.id;
                    this.fromSetup.currentChatId = conversation.id;
                    this.fromSetup.tmpMessageStore = this.fromSetup.tmpMessage['chatId'+this.fromSetup.currentChatId] ?? '';
                    resolve();
                });
                promise.then(() => {
                    document.getElementById('chat-history').scrollTop = document.getElementById('chat-history').scrollHeight;
                });
            },

            showTeamChat(conversation) {
                const promise = new Promise((resolve, reject) => {
                    this.fromSetup.tmpMessage['chatId'+this.fromSetup.currentChatId] = this.fromSetup.tmpMessageStore;
                    this.fromSetup.isIndividualChat = false;
                    this.fromSetup.chatHeader.name = conversation.team.name;
                    this.fromSetup.chatHeader.id = conversation.team_id;
                    this.fromSetup.currentChatId = conversation.id;
                    this.fromSetup.tmpMessageStore = this.fromSetup.tmpMessage['chatId'+this.fromSetup.currentChatId] ?? '';
                    resolve();
                });
                promise.then(() => {
                    document.getElementById('chat-history').scrollTop = document.getElementById('chat-history').scrollHeight;
                });
            },

            sendMessage(event) {
                if(event.isComposing || event.keyCode === 229) {
                    return;
                }
                this.fromSetup.messageForm.message = this.fromSetup.tmpMessageStore;
                this.fromSetup.tmpMessageStore = '';
                if(this.fromSetup.messageForm.message.match(/\S/g)) {
                    this.fromSetup.tmpMessage['chatId'+this.fromSetup.messageForm.currentChatIdToSubmit] = this.fromSetup.tmpMessageStore;
                    axios.post(route('message.store'), {
                        messageForm: this.fromSetup.messageForm
                    }).then(response => {
                        this.fromSetup.chat = response.data.chat;
                        return response;
                    }).then(response => {
                        if(this.fromSetup.currentChatId === response.data.message.conversation_id) {
                            document.getElementById('chat-history').scrollTop = document.getElementById('chat-history').scrollHeight;
                        }
                    });
                }
            },

            showImageOnError(message) {
                this.fromSetup.imageLoading[message.id] = true;
                axios.post(route('image.show'), {
                    image_path: message.image_path,
                    conversation_id: message.conversation_id,
                }, {
                    responseType: 'arraybuffer'
                }).then(response => {
                    let blob = new Blob([response.data], {type: 'image/*'});
                    let imageURL = window.URL.createObjectURL(blob);
                    this.fromSetup.imageUrl['image'+message.id] = imageURL;
                    this.fromSetup.imageLoading[message.id] = false;
                }).catch(error => {
                    delete this.fromSetup.imageUrl['image'+message.id];
                    delete this.fromSetup.imageLoading[message.id];
                });
            },
        },

        computed: {
            toJST: function() {
                return function(utc) {
                    let currentTime = new Date().getTime();
                    let messageTime = new Date(utc).getTime();
                    let oneDayTime = 86400000;
                    let oneWeekTime = 604800000;
                    let yesterdayTime = currentTime - oneDayTime;
                    let todayDate = new Date().getDate();
                    let yesterdayDate = new Date(yesterdayTime).getDate();
                    let messageDate = new Date(utc).getDate();
                    let messageDay = new Date(utc).getDay();
                    let day;
                    let thisYear = new Date().getFullYear();
                    let messageYear = new Date(utc).getFullYear();

                    if(currentTime - messageTime < oneDayTime && todayDate === messageDate) {
                        return new Date(utc).toLocaleTimeString({ timeZone: 'Asia/Tokyo' }, { hour: 'numeric', minute: '2-digit'});
                    } else if(yesterdayTime - oneDayTime < messageTime && yesterdayDate === messageDate) {
                        return 'Yesterday';
                    } else if(currentTime - oneWeekTime < messageTime && messageDate !== new Date(currentTime - oneWeekTime).getDate()) {
                        switch(messageDay) {
                            case 0:
                                day = "Sunday";
                                break;
                            case 1:
                                day = "Monday";
                                break;
                            case 2:
                                day = "Tuesday";
                                break;
                            case 3:
                                day = "Wednesday";
                                break;
                            case 4:
                                day = "Thursday";
                                break;
                            case 5:
                                day = "Friday";
                                break;
                            case 6:
                                day = "Saturday";
                                break;
                            default:
                                day = "Error";
                        }
                        return day;
                    } else if(thisYear === messageYear) {
                        return new Date(utc).toLocaleDateString({ timeZone: 'Asia/Tokyo' }, { month: 'numeric', day: '2-digit'});
                    } else {
                        return new Date(utc).toLocaleDateString({ timeZone: 'Asia/Tokyo' }, { year: 'numeric', month: '2-digit', day: '2-digit'});
                    }
                }
            },

            dateMessageSent: function() {
                return function(utc) {
                    let currentTime = new Date().getTime();
                    let messageTime = new Date(utc).getTime();
                    let oneDayTime = 86400000;
                    let oneWeekTime = 604800000;
                    let yesterdayTime = currentTime - oneDayTime;
                    let todayDate = new Date().getDate();
                    let yesterdayDate = new Date(yesterdayTime).getDate();
                    let messageDate = new Date(utc).getDate();
                    let messageDay = new Date(utc).getDay();
                    let day;
                    let thisYear = new Date().getFullYear();
                    let messageYear = new Date(utc).getFullYear();

                    if(currentTime - messageTime < oneDayTime && todayDate === messageDate) {
                        return 'Today';
                    } else if(yesterdayTime - oneDayTime < messageTime && yesterdayDate === messageDate) {
                        return 'Yesterday';
                    } else if(currentTime - oneWeekTime < messageTime && messageDate !== new Date(currentTime - oneWeekTime).getDate()) {
                        switch(messageDay) {
                            case 0:
                                day = "Sunday";
                                break;
                            case 1:
                                day = "Monday";
                                break;
                            case 2:
                                day = "Tuesday";
                                break;
                            case 3:
                                day = "Wednesday";
                                break;
                            case 4:
                                day = "Thursday";
                                break;
                            case 5:
                                day = "Friday";
                                break;
                            case 6:
                                day = "Saturday";
                                break;
                            default:
                                day = "Error";
                        }
                        return day;
                    } else if(thisYear === messageYear) {
                        return new Date(utc).toLocaleDateString({ timeZone: 'Asia/Tokyo' }, { month: 'numeric', day: '2-digit'});
                    } else {
                        return new Date(utc).toLocaleDateString({ timeZone: 'Asia/Tokyo' }, { year: 'numeric', month: '2-digit', day: '2-digit'});
                    }
                }
            },

            whenSent: function() {
                return function(utc) {
                    return new Date(utc).toLocaleTimeString({ timeZone: 'Asia/Tokyo' }, { hour12: false, hour: 'numeric', minute: '2-digit'});
                }
            }
        },

        created() {
            this.fromSetup.messageForm.sender_id = this.$page.props.user.id;
            if(this.fromSetup.chat === null) {
                this.isLoadedFromCache = false;
                this.fromSetup.chat = this.chatSource;
                this.chatSource.forEach(function(conversation) {
                    conversation.messages.forEach(function(message) {
                        if(message.is_image) {
                            axios.post(route('image.show'), {
                                image_path: message.image_path,
                                conversation_id: message.conversation_id,
                            }, {
                                responseType: 'arraybuffer'
                            }).then(response => {
                                let blob = new Blob([response.data], {type: 'image/*'});
                                let imageURL = window.URL.createObjectURL(blob);
                                this.fromSetup.imageUrl['image'+message.id] = imageURL;
                                this.fromSetup.imageLoading[message.id] = false;
                            });
                        }
                    }, this)
                }, this);
                if(this.fromProfile) {
                    this.fromSetup.isIndividualChat = true;
                    this.fromSetup.currentChatId = this.currentConversation.id;
                    let userToTalkTo = this.currentConversation.users.filter(user => user.id !== this.$page.props.user.id);
                    this.fromSetup.chatHeader.name = userToTalkTo[0].name;
                    this.fromSetup.chatHeader.id = userToTalkTo[0].id;
                }
            }
            this.userChannel = `user-channel.${this.$page.props.user.id}`;
            this.connect();
        },

        mounted() {
            if(this.fromSetup.currentChatId !== '') {
                document.getElementById('chat-history').scrollTop = document.getElementById('chat-history').scrollHeight;
            }
            if(this.isLoadedFromCache) {
                axios.get(route('new-chat.index'))
                .then(response => {
                    response.data.forEach(function(conversation) {
                        conversation.messages.forEach(function(message) {
                            if(message.is_image && this.fromSetup.imageUrl['image'+message.id] === undefined) {
                                axios.post(route('image.show'), {
                                    image_path: message.image_path,
                                    conversation_id: message.conversation_id,
                                }, {
                                    responseType: 'arraybuffer'
                                }).then(response => {
                                    let blob = new Blob([response.data], {type: 'image/*'});
                                    let imageURL = window.URL.createObjectURL(blob);
                                    this.fromSetup.imageUrl['image'+message.id] = imageURL;
                                    this.fromSetup.imageLoading[message.id] = false;
                                });
                            }
                        }, this)
                    }, this);
                    if(this.fromSetup.currentChatId !== '') {
                        let currentChatDataFromCache = this.fromSetup.chat.filter(conversation => conversation.id === this.fromSetup.currentChatId, this);
                        this.fromSetup.chat = response.data;
                        let currentChatDataFromServer = this.fromSetup.chat.filter(conversation => conversation.id === this.fromSetup.currentChatId, this);
                        if(currentChatDataFromServer.length > 0) {
                            if((currentChatDataFromServer[0].last_message !== null && currentChatDataFromCache[0].last_message !== null) ? currentChatDataFromServer[0].last_message.id > currentChatDataFromCache[0].last_message.id : false) {
                                this.isLoadedFromCacheWithNewMessages = true;
                            } else if(currentChatDataFromServer[0].last_message !== null && currentChatDataFromCache[0].last_message === null) {
                                this.isLoadedFromCacheWithNewMessages = true;
                            }
                        } else {
                            this.fromSetup.currentChatId = '';
                            this.fromSetup.tmpMessageStore = '';
                            this.fromSetup.chatHeader.name = '';
                            this.fromSetup.chatHeader.id = '';
                            this.fromSetup.isIndividualChat = null;
                        }
                    } else {
                        this.fromSetup.chat = response.data;
                    }
                }).then(() => {
                    if(this.isLoadedFromCacheWithNewMessages) {
                        document.getElementById('chat-history').scrollTop = document.getElementById('chat-history').scrollHeight;
                    }
                });
            }
        },

        beforeUnmount() {
            Echo.leave(this.userChannel);
        }
    }
</script>
