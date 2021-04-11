<div class="d-flex">


    <form wire:submit.prevent="submit" class="w-60 pr-3 pt-4">
        <div id="indicator" class="d-flex justify-content-around mb-4">
            <div
                class="px-2 py-2 border-bottom {{ $step == 0 ? 'border-danger text-danger' : 'border-dark text-dark' }} h5">
                Abstract Detail
            </div>
            <div
                class="px-2 py-2 border-bottom {{ $step == 1 ? 'border-danger text-danger' : 'border-dark text-dark' }} h5">
                First Author
            </div>
            <div
                class="px-2 py-2 border-bottom {{ $step == 2 ? 'border-danger text-danger' : 'border-dark text-dark' }} h5">
                All Authors
            </div>
        </div>
        <div>
            @if($step == 0)
                <h3>{{__('Abstract Detail')}}</h3>
                <div class="form-group">
                    <label for="title">{{ __('Title') }}</label>
                    <input id="title" type="text" class="form-control" wire:model.lazy="title" wire:key="title"
                           placeholder="{{ __('Title') }}">

                    @error('title')<small class="form-text text-danger">{{ __($message) }}</small>@enderror
                </div>
                <div class="form-group">
                    <label for="topic">{{ __('Topic') }}</label>
                    <input id="topic" type="text" class="form-control" wire:model.lazy="topic" wire:key="topic"
                           placeholder="{{ __('Topic') }}">

                    @error('topic')<small class="form-text text-danger">{{ __($message) }}</small>@enderror
                </div>
                <div class="form-group">
                    <label for="body">{{ __('Body') }}</label>
                    <input id="body" type="text" class="form-control" wire:model.lazy="body" wire:key="body"
                           placeholder="{{ __('Body') }}">

                    @error('body')<small class="form-text text-danger">{{ __($message) }}</small>@enderror
                </div>
            @endif

            @if($step ==1)
                <h3>{{__('First Author')}}</h3>
                <div class="form-group">
                    <label for="firstAuthorName">Name</label>
                    <input id="firstAuthorName" type="text" class="form-control" wire:model.lazy="firstAuthorName"
                           placeholder="Name">
                    @error('firstAuthorName')<small class="form-text text-danger">{{ $message }}</small>@enderror
                </div>
                <div class="form-group">
                    <label for="firstAuthorInstitution">Institution</label>
                    <input id="firstAuthorInstitution" type="text" class="form-control"
                           wire:model.lazy="firstAuthorInstitution" placeholder="Institution">
                    @error('firstAuthorInstitution')<small class="form-text text-danger">{{ $message }}</small>@enderror
                </div>
                <div class="form-group">
                    <label for="firstAuthorEmail">Email</label>
                    <input id="firstAuthorEmail" type="email" class="form-control" wire:model.lazy="firstAuthorEmail"
                           placeholder="Email">
                    @error('firstAuthorEmail')<small class="form-text text-danger">{{ $message }}</small>@enderror
                </div>
            @endif

            @if($step==2)
                <h3>{{__('All Authors')}}</h3>
                <button type="button" class="btn btn-info" wire:click="addAuthor">Add
                    author {{ count($authors) }}</button>
                <div class="mt-4">
                    @foreach($authors as $index => $author)
                        <div class="d-flex justify-content-between mb-2" wire:key="{{ 'author' . $index }}">
                            <div class="form-group w-25">
                                <input type="text" class="form-control" wire:model="authors.{{ $index }}.name"
                                       placeholder="Author{{ $loop->index+1 }} Name">

                                @error("authors.($index+1).name")<small
                                    class="form-text text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="form-group w-25">
                                <input type="text" class="form-control"
                                       wire:model="authors.{{ $index }}.affiliation_no"
                                       placeholder="Author{{ $loop->index+1 }} Affiliations">

                                @error('color')<small class="form-text text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="form-group w-25">
                                <input type="text" class="form-control"
                                       wire:model="authors.{{ $index }}.is_presenter"
                                       placeholder="Author{{ $loop->index+1 }} Presenter?">

                                @error('color')<small class="form-text text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="form-group">
                                <input type="button" class="btn btn-danger" value="Delete"
                                       wire:click="removeAuthor({{ $index }})">
                            </div>
                        </div>
                    @endforeach
                </div>

            @endif

            @if($step > 2)
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Thank you for information</h4>
                        <p class="card-text">Welcome to webdevmatics. Happy learning and
                            Subscribe!</p>
                        <a href="{{ route('delegate-papers') }}">Go to home</a>
                    </div>
                </div>
            @endif
        </div>

        <div class="mt-2">
            @if($step> 0 && $step<=2)
                <button type="button" wire:click="decreaseStep" wire:key="back"
                        class="btn btn-secondary mr-3">{{ __('Back') }}</button>
            @endif

            @if($step <= 2)
                <button type="submit" wire:key="next" class="btn btn-success">{{ __('Next') }}</button>
            @endif
        </div>
    </form>
    <div class="w-40 bg-dark px-4 py-4 min-vh-100 text-white">
        <h3 class="text-center mb-4 pb-3 border-bottom border-light text-warning">Abstract Preview</h3>
        <div class="h-75">
            @if($title)
                <h4>{{ $title }}</h4>
            @endif

            @forelse($authors as $author)
                {{ $author['name'] }}<sup>{{ $author['affiliation_no']??'' }}</sup>{{ $loop->last ? '' : ', ' }}
            @empty

            @endforelse
            @if($body)
                <h6 class="mt-3 font-weight-bold">
                    Abstract
                </h6>
                <p>{{ $body }}</p>
            @endif
        </div>
        @if($topic)
            <div class="h-25 border-top border-light pt-3">
                <em class="d-block">Topic: {{ $topic }}</em>
                @if($firstAuthorName && $firstAuthorInstitution && $firstAuthorEmail)
                    <em class="d-block">First
                        author: {{ $firstAuthorName . ', ' . $firstAuthorInstitution . ', ' . $firstAuthorEmail }}</em>
                @endif
            </div>
        @endif
    </div>
</div>
