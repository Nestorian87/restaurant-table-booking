<div>
    @include('components.layouts.partials.admin-header')

    <livewire:common.chat
        :chatId="$chatId"
        :messages="$messages"
        :pagination="$pagination"
        :page="$page"
        :timezone="$timezone"
        :isAdmin="true"
        :userName="$userName"
        :userSurname="$userSurname"
        :key="'chat-'.$chatId.'-'.count($messages) . $timezone ?? 'initial'"
    />
</div>
