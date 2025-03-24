<div class="p-8">
    <div class="flex flex-col">
        <flex:heading size="xl" level="2" class="mb-6">
            Messages Processing
        </flex:heading>
        <flux:separator variant="subtle" />
    </div>
    <ul class="flex flex-col gap-4 p-8 max-w-2xl mx-auto">
        @foreach ($messages as $user)
            <li>
                <flux:heading size="lg" level="3" class="px-3 py-2 text-xl">
                    {{$user->userName}}
                </flux:heading>
                <ul class="flex flex-col gap-2">
                    @foreach($user->messages as $message)
                        <li>
                            <div class="flex flex-row gap-8 py-4 px-5 items-center dark:bg-[#27272a] rounded-lg">
                                <div class="flex-1">
                                    {{$message->formattedTime}}
                                </div>
                                @if(empty($this->onlineUsers))
                                    <flux:button variant="primary" disabled :loading="true">
                                        Complete
                                    </flux:button>
                                @else
                                    <flux:button
                                        variant="primary"
                                        wire:click="CompleteMessage('{{$user->userId}}', '{{$message->uuid}}')"
                                    >
                                        Complete
                                    </flux:button>
                                @endif

                            </div>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>
</div>
