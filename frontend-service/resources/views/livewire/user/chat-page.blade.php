<div>
    @include('components.layouts.partials.user-header')

    <livewire:common.chat
        :chatId="$chatId"
        :messages="$messages"
        :pagination="$pagination"
        :page="$page"
        :timezone="$timezone"
        :isAdmin="false"
        :key="'chat-'.$chatId.'-'.count($messages) ?? 'initial'"
    />
</div>
