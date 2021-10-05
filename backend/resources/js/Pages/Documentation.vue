<template>
    <app-layout>
        <div class="w-full grid grid-cols-12 h-full">
            <div class="fixed pt-10 px-10 w-1/4" style="top: 7vh;">
                <div class="bg-white shadow rounded-md overflow-y-scroll w-full p-6" style="height: calc(93vh - 5rem);">
                    <ol class="list-decimal list-inside xl:text-lg lg:text-base">
                        <li><a href="#getStarted">{{ __('Get started') }}</a></li>
                        <li>
                            <a href="#teamUp">{{ __('Team up') }}</a>
                            <ol class="list-decimal list-inside xl:text-base lg:text-sm ml-6 text-gray-700">
                                <li><a href="#teamUpOverview">{{ __('Overview') }}</a></li>
                                <li><a href="#userSearch">{{ __('User search') }}</a></li>
                                <li><a href="#teamSearch">{{ __('Team search') }}</a></li>
                                <li><a href="#seeUserProfileLink">{{ __('See user profile') }}</a></li>
                                <li><a href="#seeTeamProfileLink">{{ __('See team profile') }}</a></li>
                                <li><a href="#checkJoinRequests">{{ __('Check join requests') }}</a></li>
                                <li><a href="#checkTeamInvitations">{{ __('Check team invitations') }}</a></li>
                            </ol>
                        </li>
                        <li>
                            <a href="#seeUserProfile">{{ __('See user profile') }}</a>
                            <ol class="list-decimal list-inside xl:text-base lg:text-sm ml-6 text-gray-700">
                                <li><a href="#seeUserProfileOverview">{{ __('Overview') }}</a></li>
                                <li><a href="#seeUserProfileDetail">{{ __('User profile') }}</a></li>
                                <li><a href="#seeUserProfileToChat">{{ __('Start chat') }}</a></li>
                                <li><a href="#handleJoinRequest">{{ __('Handle join request') }}</a></li>
                                <li><a href="#sendTeamInvitation">{{ __('Send team invitation') }}</a></li>
                            </ol>
                        </li>
                        <li>
                            <a href="#seeTeamProfile">{{ __('See team profile') }}</a>
                            <ol class="list-decimal list-inside xl:text-base lg:text-sm ml-6 text-gray-700">
                                <li><a href="#seeTeamProfileOverview">{{ __('Overview') }}</a></li>
                                <li><a href="#seeTeamProfileDetail">{{ __('Team profile') }}</a></li>
                                <li><a href="#seeTeamProfileToChat">{{ __('Start chat with team owner') }}</a></li>
                                <li><a href="#handleTeamInvitation">{{ __('Handle team invitation') }}</a></li>
                                <li><a href="#makeJoinRequest">{{ __('Make join request') }}</a></li>
                            </ol>
                        </li>
                        <li>
                            <a href="#setProfile">{{ __('Set profile') }}</a>
                            <ol class="list-decimal list-inside xl:text-base lg:text-sm ml-6 text-gray-700">
                                <li><a href="#setProfileOverview">{{ __('Overview') }}</a></li>
                                <li><a href="#setUserProfile">{{ __('Set user profile') }}</a></li>
                                <li><a href="#setTeamProfile">{{ __('Set team profile') }}</a></li>
                            </ol>
                        </li>
                        <li><a href="#createTeam">{{ __('Create team') }}</a></li>
                        <li>
                            <a href="#manageTeam">{{ __('Manage team') }}</a>
                            <ol class="list-decimal list-inside xl:text-base lg:text-sm ml-6 text-gray-700">
                                <li><a href="#manageTeamOverview">{{ __('Overview') }}</a></li>
                                <li><a href="#pendingJoinRequests">{{ __('Pending join requests') }}</a></li>
                                <li><a href="#pendingTeamInvitations">{{ __('Pending team invitations') }}</a></li>
                                <li><a href="#teamMembers">{{ __('Team members') }}</a></li>
                                <li><a href="#deleteTeam">{{ __('Delete team') }}</a></li>
                            </ol>
                        </li>
                        <li>
                            <a href="#chat">{{ __('Chat') }}</a>
                            <ol class="list-decimal list-inside xl:text-base lg:text-sm ml-6 text-gray-700">
                                <li><a href="#chatOverview">{{ __('Overview') }}</a></li>
                                <li><a href="#startIndividualChat">{{ __('Start individual chat') }}</a></li>
                                <li><a href="#deleteIndividualChat">{{ __('Delete individual chat') }}</a></li>
                                <li><a href="#useTeamChat">{{ __('Start/delete team chat') }}</a></li>
                                <li><a href="#sendMessage">{{ __('Send message/image') }}</a></li>
                                <li><a href="#deleteMessage">{{ __('Delete message/image') }}</a></li>
                            </ol>
                        </li>
                        <li><a href="#teamSwitcher">{{ __('Team switcher') }}</a></li>
                        <li>
                            <a href="#account">{{ __('Account') }}</a>
                            <ol class="list-decimal list-inside xl:text-base lg:text-sm ml-6 text-gray-700">
                                <li><a href="#accountOverview">{{ __('Overview') }}</a></li>
                                <li><a href="#updateEmail">{{ __('Update email(login ID)') }}</a></li>
                                <li><a href="#updatePassword">{{ __('Update password') }}</a></li>
                                <li><a href="#twoFactorAuthentication">{{ __('Two factor authentication') }}</a></li>
                                <li><a href="#browserSessions">{{ __('Browser sessions') }}</a></li>
                                <li><a href="#deleteAccount">{{ __('Delete account') }}</a></li>
                            </ol>
                        </li>
                        <li><a href="#languageSwitcher">{{ __('Language switcher') }}</a></li>
                        <li><a href="#login">{{ __('Log in') }}</a></li>
                        <li><a href="#logout">{{ __('Log out') }}</a></li>
                        <li><a href="#register">{{ __('Register') }}</a></li>
                        <li>
                            <a href="#concepts">{{ __('Concepts') }}</a>
                            <ol class="list-decimal list-inside xl:text-base lg:text-sm ml-6 text-gray-700">
                                <li><a href="#conceptsOverview">{{ __('Overview') }}</a></li>
                                <li><a href="#currentTeamConcept">{{ __('Current team') }}</a></li>
                                <li><a href="#teamRoleConcept">{{ __('Team role') }}</a></li>
                                <li><a href="#joinRequestConcept">{{ __('Join request') }}</a></li>
                                <li><a href="#teamInvitationConcept">{{ __('Team invitation') }}</a></li>
                            </ol>
                        </li>
                        <li><a href="#authorInformation">{{ __('Author information') }}</a></li>
                    </ol>
                </div>
            </div>
            <div class="col-span-9 row-span-1 col-start-4 bg-white shadow rounded-md p-10 my-10 mr-10">
                <div id="getStarted" class="mb-4" style="margin-top: -7vh;padding-top: 7vh;">
                    <h3 class="text-2xl font-bold underline">{{ __('Get started') }}</h3>
                    <p class="mt-2">{{ __('If you don\'t know where to start, do "Get started" session. It goes through fundamental part of this app. It is highly recommended to do it before you read this documentation. To start the session, click "DevCheers" at the top left and go to app top page, and click "Get started" at center of the page. You can refer to the information in the modal window after you leave the top page by clicking the small blue window at the top right corner unless you click the cross mark to remove it.') }}</p>
                </div>
                <div id="teamUp" class="mb-2" style="margin-top: -7vh;padding-top: 7vh;">
                    <h3 class="text-2xl font-bold underline">{{ __('Team up') }}</h3>
                </div>
                <div id="teamUpOverview" class="mb-2" style="margin-top: -7vh;padding-top: 7vh;">
                    <h6 class="text-lg font-semibold">{{ __('Overview') }}</h6>
                    <p>{{ __('"Team Up" page basically provides features to actually form teams. The features include user and team search as well as join requests and team invitations check. To accsess the team up page, click "Team Up" at the top navigation bar.') }}</p>
                </div>
                <div id="userSearch" class="mb-2" style="margin-top: -7vh;padding-top: 7vh;">
                    <h6 class="text-lg font-semibold">{{ __('User search') }}</h6>
                    <p>{{ __('To search user, go to "Team up" page and click "switch to user search" at top left if you are on team search page, then search keyword in top left input box. Note the theme color for user search is blue whereas the one for team search is yellow. Search result comes into the largest white box. Search result consists of a list of users whose profiles contain the keyword. Each user\'s brief profile is shown in an inner box. Black one is DevCheers\' official one whereas white one is users\'. Clicking one of them leads you to the corresponding "User Profile" page where you can take actions to team up with the user such as sending team invitation and accepting join request. Note you can filter search results by users\' skills by picking skills in the middle left box. Of course you can directly type the skill name into the input field and search.') }}</p>
                </div>
                <div id="teamSearch" class="mb-2" style="margin-top: -7vh;padding-top: 7vh;">
                    <h6 class="text-lg font-semibold">{{ __('Team search') }}</h6>
                    <p>{{ __('To search team, go to "Team up" page and click "switch to team search" at top left if you are on user search page, then search keyword in top left input box. Note the theme color for user search is blue whereas the one for team search is yellow. Search result comes into the largest white box. Search result consists of a list of teams whose profiles contain the keyword. Each team\'s brief profile is shown in an inner box. Black one is DevCheers\' official one whereas white one is users\'. Clicking one of them leads you to the corresponding "Team Profile" page where you can take actions to join the team such as sending join request and accepting team invitation. Note you can filter search results by whether teams are looking for new members and also can filter by requirements and options to join teams. You can do so by ticking checkboxes in middle left box labeled "filter by conditions". Of course you can directly type the requirement or option skill name into the input field and search.') }}</p>
                </div>
                <div id="seeUserProfileLink" class="mb-2" style="margin-top: -7vh;padding-top: 7vh;">
                    <h6 class="text-lg font-semibold">{{ __('See user profile') }}</h6>
                    <p><span v-if="$page.props.locale === 'en'">See&nbsp;</span><a href="#seeUserProfile" class="underline">"{{ __('See user profile') }}"</a>{{ __('.') }}</p>
                </div>
                <div id="seeTeamProfileLink" class="mb-2" style="margin-top: -7vh;padding-top: 7vh;">
                    <h6 class="text-lg font-semibold">{{ __('See team profile') }}</h6>
                    <p><span v-if="$page.props.locale === 'en'">See&nbsp;</span><a href="#seeTeamProfile" class="underline">"{{ __('See team profile') }}"</a>{{ __('.') }}</p>
                </div>
                <div id="checkJoinRequests" class="mb-2" style="margin-top: -7vh;padding-top: 7vh;">
                    <h6 class="text-lg font-semibold">{{ __('Check join requests') }}</h6>
                    <p>{{ __('To check join requests, click "Team Up" in the top navigation bar and click "Switch to user search" if you are on team search page then click small box at bottom left corner labeled "Check join requests" with the number of pending join requests. Now you are on "check join requests" page and can see a list of join requests sent to join your current team. Each request has details including sender name, which role to join your team as and message. You can accept/decline join requests here as long as you are authorized. Note you can also check join requests on "Manage Team" page whose link is in top navigation bar.') }}</p>
                </div>
                <div id="checkTeamInvitations" class="mb-4" style="margin-top: -7vh;padding-top: 7vh;">
                    <h6 class="text-lg font-semibold">{{ __('Check team invitations') }}</h6>
                    <p>{{ __('To check team invitations, click "Team Up" in top navigation bar and click "Switch to team search" if you are on user search page then click small box at bottom left corner labeled "Check team invitations" with the number of pending team invitations. Now you are on "check team invitations" page and can see a list of team invitations sent to invite you to sender\'s team. Each invitation has details including team name, which role you are invited to join team as and message. You can accept/decline team invitations here. Note you can not check team invitations in "Manage Team" because they are directed to "you" not "your current team".') }}</p>
                </div>
                <div id="seeUserProfile" class="mb-2" style="margin-top: -7vh;padding-top: 7vh;">
                    <h6 class="text-2xl font-bold underline">{{ __('See user profile') }}</h6>
                </div>
                <div id="seeUserProfileOverview" class="mb-2" style="margin-top: -7vh;padding-top: 7vh;">
                    <h6 class="text-lg font-semibold">{{ __('Overview') }}</h6>
                    <p>{{ __('"User profile" page gives you information about user. To see user profile, search user and click a box with user profile. Note you need to log in to use all features on this page, you can only see user profile without login. In the "User Profile" section of this page, you can see user profile and start chat. In the "Team Participation Management" section, you can accept/decline join request from the user and send/cancel team invitation.') }}</p>
                </div>
                <div id="seeUserProfileDetail" class="mb-2" style="margin-top: -7vh;padding-top: 7vh;">
                    <h6 class="text-lg font-semibold">{{ __('User profile') }}</h6>
                    <p>{{ __('In "User Profile" section, you can see user\'s profile photo, name, description, relavant url and skills. You can click profile photo and see larger one. Url field is possibly filled with the user\'s sns or github address. Skills field shows which tool and programming language the user is familiar with. You can set your own profile on "Set Profile" page.') }}</p>
                </div>
                <div id="seeUserProfileToChat" class="mb-2" style="margin-top: -7vh;padding-top: 7vh;">
                    <h6 class="text-lg font-semibold">{{ __('Start chat') }}</h6>
                    <p>{{ __('You can start chat with the user by clicking chat icon. See ') }}<a href="#chat" class="underline">"{{ __('Chat') }}"</a>{{ __(' section on how to use it.') }}</p>
                </div>
                <div id="handleJoinRequest" class="mb-2" style="margin-top: -7vh;padding-top: 7vh;">
                    <h6 class="text-lg font-semibold">{{ __('Handle join request') }}</h6>
                    <p>{{ __('If your current team received a join request from the user whose profile you are checking, you can see "Pending Join Request" as a subsection of "Team Participation Management" section. You can see a role the user wants to join your current team as and message in the section. You can accept/decline the request here.') }}</p>
                </div>
                <div id="sendTeamInvitation" class="mb-4" style="margin-top: -7vh;padding-top: 7vh;">
                    <h6 class="text-lg font-semibold">{{ __('Send team invitation') }}</h6>
                    <p>{{ __('If your current team does not receive a join request from the user whose profile you are checking, you can invite the user to your current team. To send invitations, fill in "Send Invitation" form and click "INVITE". Note you need to own/belong to a team to send invitations. In addition, if you belong to other\'s team, you need to be authorized to add team member.') }}</p>
                </div>
                <div id="seeTeamProfile" class="mb-2" style="margin-top: -7vh;padding-top: 7vh;">
                    <h6 class="text-2xl font-bold underline">{{ __('See team profile') }}</h6>
                </div>
                <div id="seeTeamProfileOverview" class="mb-2" style="margin-top: -7vh;padding-top: 7vh;">
                    <h6 class="text-lg font-semibold">{{ __('Overview') }}</h6>
                    <p>{{ __('"Team Profile" page gives you information about team. To see team profile, search team and click a box with team profile. Note you need to log in to use all features on this page, you can only see team profile without login. In the "Team Profile" section of this page, you can see team profile and start chat with team owner. In the "Team Participation Management" section, you can accept/decline team invitation from the team and send/cancel join request.') }}</p>
                </div>
                <div id="seeTeamProfileDetail" class="mb-2" style="margin-top: -7vh;padding-top: 7vh;">
                    <h6 class="text-lg font-semibold">{{ __('Team profile') }}</h6>
                    <p>{{ __('In "Team Profile" section, you can see team\'s profile photo, name, a list of members, description. You can click profile photo and see larger one. You can set your team\'s profile on "Set Profile" page.') }}</p>
                </div>
                <div id="seeTeamProfileToChat" class="mb-2" style="margin-top: -7vh;padding-top: 7vh;">
                    <h6 class="text-lg font-semibold">{{ __('Start chat with team owner') }}</h6>
                    <p>{{ __('You can start chat with team owner by clicking chat icon. See ') }}<a href="#chat" class="underline">"{{ __('Chat') }}"</a>{{ __(' section on how to use it.') }}</p>
                </div>
                <div id="handleTeamInvitation" class="mb-2" style="margin-top: -7vh;padding-top: 7vh;">
                    <h6 class="text-lg font-semibold">{{ __('Handle team invitation') }}</h6>
                    <p>{{ __('If you received a team invitation from the team whose profile you are checking, you can see "Pending Invitation" as a subsection of "Team Participation Management" section. You can see a role the team wants you to join as and message in the section. You can accept/decline the invitation here.') }}</p>
                </div>
                <div id="makeJoinRequest" class="mb-4" style="margin-top: -7vh;padding-top: 7vh;">
                    <h6 class="text-lg font-semibold">{{ __('Make join request') }}</h6>
                    <p>{{ __('If you do not receive a team invitation from the team whose profile you are checking, you can make a join request to the team. To make a join request, fill in "Make Join Request" form and click "SEND REQUEST".') }}</p>
                </div>
                <div id="setProfile" class="mb-2" style="margin-top: -7vh;padding-top: 7vh;">
                    <h6 class="text-2xl font-bold underline">{{ __('Set profile') }}</h6>
                </div>
                <div id="setProfileOverview" class="mb-2" style="margin-top: -7vh;padding-top: 7vh;">
                    <h6 class="text-lg font-semibold">{{ __('Overview') }}</h6>
                    <p>{{ __('On "Set Profile" page, you can set user and current team profile. To set profile, click "Set Profile" in top navigation bar and access "Set Profile" page then fill in "Set User Profile" or "Set Team Profile" form and click "SAVE".') }}</p>
                </div>
                <div id="setUserProfile" class="mb-2" style="margin-top: -7vh;padding-top: 7vh;">
                    <h6 class="text-lg font-semibold">{{ __('Set user profile') }}</h6>
                    <p>{{ __('To set user profile, go to "Set Profile" page first and then click "Switch to setting user profile." if the page shows "Set Team Profile" form and fill in "Set User Profile" form and hit "SAVE". Note information you provided on this page is visible from other users because it is shown on your user profile page.') }}</p>
                </div>
                <div id="setTeamProfile" class="mb-4" style="margin-top: -7vh;padding-top: 7vh;">
                    <h6 class="text-lg font-semibold">{{ __('Set team profile') }}</h6>
                    <p>{{ __('To set current team profile, go to "Set Profile" page first and then click "Switch to setting team profile." if the page shows "Set User Profile" form and fill in "Set Team Profile" form and hit "SAVE". Note information you provided on this page is visible from other users because it is shown on your current team\'s profile page. In addition, if you do not own your current team, you need to be authorized to edit its profile.') }}</p>
                </div>
                <div id="createTeam" class="mb-4" style="margin-top: -7vh;padding-top: 7vh;">
                    <h3 class="text-2xl font-bold underline">{{ __('Create team') }}</h3>
                    <p class="mt-2">{{ __('You can create a new team on "Create Team" page. To create team, click "Create Team" in top navigation bar and fill in "Team Name" field and hit "CREATE".') }}</p>
                </div>
                <div id="manageTeam" class="mb-2" style="margin-top: -7vh;padding-top: 7vh;">
                    <h6 class="text-2xl font-bold underline">{{ __('Manage team') }}</h6>
                </div>
                <div id="manageTeamOverview" class="mb-2" style="margin-top: -7vh;padding-top: 7vh;">
                    <h6 class="text-lg font-semibold">{{ __('Overview') }}</h6>
                    <p>{{ __('"Manage Team" page enables you to control membership and roles of team members. Note you need to be a team owner or have admin role in the team to add/delete team members. You can also delete team if you are a team owner. To access "Manage Team" page, click "Manage Team" in top navigation bar.') }}</p>
                </div>
                <div id="pendingJoinRequests" class="mb-2" style="margin-top: -7vh;padding-top: 7vh;">
                    <h6 class="text-lg font-semibold">{{ __('Pending join requests') }}</h6>
                    <p>{{ __('In this section, you can see a list of pending requests to join your current team. You can accept/decline each request here. A request contains the name of a user who made the request, role the user wants to join your current team as and message.') }}</p>
                </div>
                <div id="pendingTeamInvitations" class="mb-2" style="margin-top: -7vh;padding-top: 7vh;">
                    <h6 class="text-lg font-semibold">{{ __('Pending team invitations') }}</h6>
                    <p>{{ __('In this section, you can see a list of pending team invitations you sent. You can cancel invitations here. An invitation contains the name of an invited user, role your current team wants the user to join as and message.') }}</p>
                </div>
                <div id="teamMembers" class="mb-2" style="margin-top: -7vh;padding-top: 7vh;">
                    <h6 class="text-lg font-semibold">{{ __('Team members') }}</h6>
                    <p>{{ __('This section lists your current team\'s members. For each member, you can change role of and remove the user.') }}</p>
                </div>
                <div id="deleteTeam" class="mb-4" style="margin-top: -7vh;padding-top: 7vh;">
                    <h6 class="text-lg font-semibold">{{ __('Delete team') }}</h6>
                    <p>{{ __('If you are the current team owner, you can delete your current team.') }}</p>
                </div>
                <div id="chat" class="mb-2" style="margin-top: -7vh;padding-top: 7vh;">
                    <h3 class="text-2xl font-bold underline">{{ __('Chat') }}</h3>
                </div>
                <div id="chatOverview" class="mb-2" style="margin-top: -7vh;padding-top: 7vh;">
                    <h6 class="text-lg font-semibold">{{ __('Overview') }}</h6>
                    <p>{{ __('On "Chat" page, you can have a chat with others and can also negotiate/help team owner negotiate on team participation. You can send/delete messages/images real time.') }}</p>
                </div>
                <div id="startIndividualChat" class="mb-2" style="margin-top: -7vh;padding-top: 7vh;">
                    <h6 class="text-lg font-semibold">{{ __('Start individual chat') }}</h6>
                    <p>{{ __('You can have a chat with another user by individual chat and can also use it to negotiate with another user on team participation. To start an individual chat, go to "User/Team Profile" page and click "chat/chat with team owner". You will be redirected to "Chat" page and start chatting.') }}</p>
                </div>
                <div id="deleteIndividualChat" class="mb-2" style="margin-top: -7vh;padding-top: 7vh;">
                    <h6 class="text-lg font-semibold">{{ __('Delete individual chat') }}</h6>
                    <p>{{ __('To delete an individual chat, from left box which lists chatrooms, just right-click the individual chat you wants to delete and click "Delete this conversation". Note once a chatroom is deleted, all chat history in the chat room is gone and cannot be recovered.') }}</p>
                </div>
                <div id="useTeamChat" class="mb-2" style="margin-top: -7vh;padding-top: 7vh;">
                    <h6 class="text-lg font-semibold">{{ __('Start/delete team chat') }}</h6>
                    <p>{{ __('You can have a chat with team members by team chat and it can also be used to help team owner negotiate with another user on his/her team participation. You can not start/delete team chat by yourself. Instead, you are added to team chat when you join a team and you are removed from team chat when you leave team. Team chat is automatically created when the number of team members exceeds 1 and it is automatically deleted when team owner becomes the only one left in team or when team owner deletes team. You cannot delete team chat on "Chat" page like individual chat. There is no team chat immediately after team is created because owner is the only one in team.') }}</p>
                </div>
                <div id="sendMessage" class="mb-2" style="margin-top: -7vh;padding-top: 7vh;">
                    <h6 class="text-lg font-semibold">{{ __('Send message/image') }}</h6>
                    <p>{{ __('To send a message, click bottom black input box and type a message and hit enter key. To send an image, click top right image icon in the box and choose the image file you would like to send.') }}</p>
                </div>
                <div id="deleteMessage" class="mb-4" style="margin-top: -7vh;padding-top: 7vh;">
                    <h6 class="text-lg font-semibold">{{ __('Delete message/image') }}</h6>
                    <p>{{ __('To delete a message/image, right-click the message/image you would like to delete and click "Delete this message". Note once a message/image is deleted, it cannot be recovered.') }}</p>
                </div>
                <div id="teamSwitcher" class="mb-4" style="margin-top: -7vh;padding-top: 7vh;">
                    <h3 class="text-2xl font-bold underline">{{ __('Team switcher') }}</h3>
                    <p>{{ __('You can switch your current team by team switcher. It is located in top navigation bar. It is labeled with name of your current team. If you do not own/belong to any team, it shows "No team". To switch teams, click the team switcher and pick a team you would like your current team to switch to. Your current team has green tick mark on its left hand side.') }}</p>
                </div>
                <div id="account" class="mb-2" style="margin-top: -7vh;padding-top: 7vh;">
                    <h3 class="text-2xl font-bold underline">{{ __('Account') }}</h3>
                </div>
                <div id="accountOverview" class="mb-2" style="margin-top: -7vh;padding-top: 7vh;">
                    <h6 class="text-lg font-semibold">{{ __('Overview') }}</h6>
                    <p>{{ __('On "Account" page, you can manage your account. You can update email(login ID) and password, enable two factor authentication, log out of other browser sessions and delete account. To access "Account" page, click top right dropdown labeled with your user name and click "Account".') }}</p>
                </div>
                <div id="updateEmail" class="mb-2" style="margin-top: -7vh;padding-top: 7vh;">
                    <h6 class="text-lg font-semibold">{{ __('Update email(login ID)') }}</h6>
                    <p>{{ __('You can update your email(login ID) in this section. To do so, enter new email address into "Email" input field and hit "SAVE".') }}</p>
                </div>
                <div id="updatePassword" class="mb-2" style="margin-top: -7vh;padding-top: 7vh;">
                    <h6 class="text-lg font-semibold">{{ __('Update password') }}</h6>
                    <p>{{ __('You can update your password in this section. To do so, enter your current password into "Current Password" input field and enter your new password into "New Password" input field and "Confirm Password" input field for confirmation and hit "SAVE".') }}</p>
                </div>
                <div id="twoFactorAuthentication" class="mb-2" style="margin-top: -7vh;padding-top: 7vh;">
                    <h6 class="text-lg font-semibold">{{ __('Two factor authentication') }}</h6>
                    <p>{{ __('You can enable two factor authentication in this section. To do so, click "ENABLE" and follow instructions on screen.') }}</p>
                </div>
                <div id="browserSessions" class="mb-2" style="margin-top: -7vh;padding-top: 7vh;">
                    <h6 class="text-lg font-semibold">{{ __('Browser sessions') }}</h6>
                    <p>{{ __('You can log out of other browser sessions for your security. To do so, click "LOG OUT OTHER BROWSER SESSIONS".') }}</p>
                </div>
                <div id="deleteAccount" class="mb-4" style="margin-top: -7vh;padding-top: 7vh;">
                    <h6 class="text-lg font-semibold">{{ __('Delete account') }}</h6>
                    <p>{{ __('You can delete DevCheers account in this section. To do so, click "DELETE ACCOUNT" and enter your password and click "DELETE ACCOUNT". Note you cannot recover your account once you delete it.') }}</p>
                </div>
                <div id="languageSwitcher" class="mb-4" style="margin-top: -7vh;padding-top: 7vh;">
                    <h3 class="text-2xl font-bold underline">{{ __('Language switcher') }}</h3>
                    <p>{{ __('You can switch language between Japanese and English with language switcher. To do so, just click the language you want to read in on the language switcher. It is located in far-right of navigation bar when you log out and under dropdown labeled with your user name which is located far-right of navigation bar when you log in.') }}</p>
                </div>
                <div id="login" class="mb-4" style="margin-top: -7vh;padding-top: 7vh;">
                    <h3 class="text-2xl font-bold underline">{{ __('Log in') }}</h3>
                    <p>{{ __('To login, click "Log In" in the top navigation bar and fill in the form at the center of screen and click "LOG IN". If you forget your password, click "Forgot your password?" and follow instructions on screen.') }}</p>
                </div>
                <div id="logout" class="mb-4" style="margin-top: -7vh;padding-top: 7vh;">
                    <h3 class="text-2xl font-bold underline">{{ __('Log out') }}</h3>
                    <p>{{ __('To log out, click dropdown labeled with your user name which is located in navigation bar and click "Log Out".') }}</p>
                </div>
                <div id="register" class="mb-4" style="margin-top: -7vh;padding-top: 7vh;">
                    <h3 class="text-2xl font-bold underline">{{ __('Register') }}</h3>
                    <p>{{ __('To register, click "Register" in top navigation bar and fill in the form at the center of screen and click "REGISTER". Note "Name" field is visible from others because it is shown in user profile. If you have already registered, click "Already registered?" and log in.') }}</p>
                </div>
                <div id="concepts" class="mb-2" style="margin-top: -7vh;padding-top: 7vh;">
                    <h3 class="text-2xl font-bold underline">{{ __('Concepts') }}</h3>
                </div>
                <div id="conceptsOverview" class="mb-2" style="margin-top: -7vh;padding-top: 7vh;">
                    <h6 class="text-lg font-semibold">{{ __('Overview') }}</h6>
                    <p>{{ __('This section describes ideas and concepts in this app which are difficult to understand.') }}</p>
                </div>
                <div id="currentTeamConcept" class="mb-2" style="margin-top: -7vh;padding-top: 7vh;">
                    <h6 class="text-lg font-semibold">{{ __('Current team') }}</h6>
                    <p>{{ __('Current team is a team you are taking actions on behalf of. For example, if you send a team invitation to a user, you invite the user to your current team and not to other inactive teams you belong to. To check which team your current team is, see label on your team switcher.') }}</p>
                </div>
                <div id="teamRoleConcept" class="mb-2" style="margin-top: -7vh;padding-top: 7vh;">
                    <h6 class="text-lg font-semibold">{{ __('Team role') }}</h6>
                    <p>{{ __('Team role assigns team members rights on which actions they can take. For example, if you are assigned "viewer" team role, you are authorized to join team chat but not authorized to edit team profile.') }}</p>
                </div>
                <div id="joinRequestConcept" class="mb-2" style="margin-top: -7vh;padding-top: 7vh;">
                    <h6 class="text-lg font-semibold">{{ __('Join request') }}</h6>
                    <p>{{ __('Join request is a request made by a user for the user to join a team.') }}</p>
                </div>
                <div id="teamInvitationConcept" class="mb-4" style="margin-top: -7vh;padding-top: 7vh;">
                    <h6 class="text-lg font-semibold">{{ __('Team invitation') }}</h6>
                    <p>{{ __('Team invitation is an invitation made by a team for a user to join the team.') }}</p>
                </div>
                <div id="authorInformation" class="" style="margin-top: -7vh;padding-top: 7vh;">
                    <h3 class="text-2xl font-bold underline">{{ __('Author information') }}</h3>
                    <p>{{ __('Name: Steve') }}<br>{{ __('About myself: junior developer with no working experience as a developer.') }}<br>{{ __('Nationality: Japanese.') }}<br>{{ __('Language: fine with communication in both Japanese and English.') }}<br>Twitter: <a href="https://twitter.com/Steve79425614" class="underline">https://twitter.com/Steve79425614</a></p>
                </div>
            </div>
        </div>
    </app-layout>
</template>