<div class="container mx-auto my-6">
    <div class="bg-white rounded px-8 pt-6 pb-8 mb-4">
        <h2 class="text-xl font-bold mb-4">
            {{__('Send Us Your Feedback')}}
        </h2>

        @if($showSuccessMessage)
            <div class="alert alert-success mb-4" role="alert">
                <strong class="font-bold">Thank you!</strong>
                <span class="block sm:inline">Your feedback has been submitted.</span>
            </div>
        @endif

        <form wire:submit.prevent="submit">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                    {{__('Name')}} <span class="text-gray-500 text-xs">{{__('(Optional)')}}</span>
                </label>
                <input wire:model.debounce="name"
                       class="shadow appearance-none border @error('name') border-red-500 @enderror rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       id="name" type="text" placeholder="{{__('Your Name')}}">
                @error('name')
                <p style="color: red" class="text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="feedback">
                    {{__('Feedback')}}
                </label>
                <textarea wire:model.debounce="feedback"
                          class="shadow appearance-none border @error('feedback') border-red-500 @enderror rounded-lg w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
                          id="feedback" placeholder="{{__('Your Feedback')}}" rows="4"></textarea>
                @error('feedback')
                <p style="color: red" class="text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex items-center gap-2">
                <button
                    wire:loading.attr="disabled"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline"
                    type="submit">
                    {{__('Send Feedback')}}
                </button>
                <span wire:loading wire:target="submit" class="ml-2">
                    <div class="spinner-border w-4 h-4" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </span>
            </div>
        </form>
    </div>
</div>
