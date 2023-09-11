<div class="mx-auto max-w-screen-sm my-6">
    <div class="bg-white rounded px-8 pt-6 pb-8 mb-4">
        <h2 class="text-xl font-bold mb-4">
            {{__('Send Us Your Feedback')}}
        </h2>

        <form wire:submit.prevent="submit">

            {{$this->form}}

            <button
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline"
                type="submit">
                {{__('Send Feedback')}}
            </button>
        </form>

        @if($showSuccessMessage)
            <div class="alert alert-success mb-4" role="alert">
                <strong class="font-bold">{{__('Thank you!')}}</strong>
                <span class="block sm:inline">{{__('Your feedback has been submitted.')}}</span>
            </div>
        @endif
    </div>
</div>
