<div>
    <div id="indicator" class="d-flex justify-content-around my-4">
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
            Affiliations
        </div>
        <div
            class="px-2 py-2 border-bottom {{ $step == 3 ? 'border-danger text-danger' : 'border-dark text-dark' }} h5">
            All Authors
        </div>
    </div>
    <div class="d-flex">
        <form wire:submit.prevent="submit" class="w-60 pr-3 pt-4">
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
                        <label for="body" class="col-form-label text-md-right">
                            Abstract Body
                        </label>
                        <div id="counter"></div>
                        <div wire:ignore>
                    <textarea id="body"
                              wire:model="body"
                              wire:key="ckeditor-1"
                              x-ref="ckeditor"
                              x-data
                              x-init="
                              ClassicEditor.create($refs.ckeditor,
                              {
                                toolbar: {
                                    items: [
                                        'undo',
                                        'redo',
                                        '|',
                                        'bold',
                                        'italic',
                                        'bulletedList',
                                        'numberedList',
                                        '|',
                                        'alignment',
                                        'outdent',
                                        'indent',
                                        '|',
                                        'subscript',
                                        'superscript',
                                        'specialCharacters'
                                    ]
                                },
                                language: 'en',
                                image: {
                                    toolbar: [
                                        'imageTextAlternative',
                                        'imageStyle:full',
                                        'imageStyle:side'
                                    ]
                                },
                                licenseKey: '',
                                wordCount: {
                                    container: document.getElementById( 'word-count' ),
                                    displayCharacters: false
                                }
                            })
                            .then( editor => {
                                editor.model.document.on('change:data', () => {
                                   $dispatch('input', editor.getData())
                                })
                            })
                            .catch( error => {
                                console.error( error );
                            } )">{!! $body !!}</textarea>
                        </div>
                        <span id="word-count" wire:ignore></span>
                        @error('body')
                        <span style="font-size: 11px; color: #e3342f">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="keywords">{{ __('Keywords (separated by comma)') }}</label>
                        <input id="keywords" type="text" class="form-control" wire:model.lazy="keywords" wire:key="keywords"
                               placeholder="{{ __('Keywords') }}">

                        @error('keywords')<small class="form-text text-danger">{{ __($message) }}</small>@enderror
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
                        @error('firstAuthorInstitution')<small
                            class="form-text text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="form-group">
                        <label for="firstAuthorEmail">Email</label>
                        <input id="firstAuthorEmail" type="email" class="form-control"
                               wire:model.lazy="firstAuthorEmail"
                               placeholder="Email">
                        @error('firstAuthorEmail')<small class="form-text text-danger">{{ $message }}</small>@enderror
                    </div>
                @endif

                @if($step==2)
                    <h3>{{__('Affiliations')}}</h3>
                    <button type="button" class="btn btn-info" wire:click="addAffiliation">Add
                        affiliation {{ count($affiliations) }}</button>
                    <div class="mt-4">
                        @foreach($affiliations as $index => $affiliation)
                            <div class="d-flex justify-content-between mb-2" wire:key="{{ 'affiliation' . $index }}">
                                <div class="form-group w-75">
                                    <input type="text" class="form-control" wire:model="affiliations.{{ $index }}.name"
                                           placeholder="Affiliation{{ $loop->index+1 }} Name">
                                    @error("affiliations.($index+1).name")<small
                                        class="form-text text-danger">{{ $message }}</small>@enderror
                                </div>
                                <div class="form-group">
                                    <input type="button" class="btn btn-danger" value="Delete"
                                           wire:click="removeAffiliation({{ $index }})">
                                    <input type="button" class="btn btn-info" value="Up"
                                           wire:click="up('affiliations',{{ $index }})">

                                    <input type="button" class="btn btn-warning" value="Down"
                                           wire:click="down('affiliations',{{ $index }})">
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4"
                         x-data="{show:false}"
                         x-init="
                         @this.on('affiliation_changed', () => {
                            show = true;
                            })"
                         x-show.transition.in.duration.1000ms="show">
                        <h5>Affiliations are changed. Please check the affiliation number of each author on the next page.</h5>
                    </div>
                @endif

                @if($step==3)
                    <h3>{{__('All Authors')}}</h3>
                    <button type="button" class="btn btn-info" wire:click="addAuthor">Add
                        author {{ count($authors) }}</button>
                    <div class="mt-4">
                        @foreach($authors as $index => $author)
                            <div class="mb-2" wire:key="{{ 'author' . $index }}">
                                <div class="row">
                                    <div class="col-12 d-flex">
                                        <div class="form-group w-50">
                                            <input type="text" class="form-control" wire:model="authors.{{ $index }}.name"
                                                   placeholder="Author{{ $loop->index+1 }} Name">

                                            @error("authors.$index.name")<small
                                                class="form-text text-danger">{{ $message }}</small>@enderror
                                        </div>
                                        <div class="form-group w-50">
                                            {{--                                            <select class="form-control" multiple--}}
                                            {{--                                                    wire:model="authors.{{ $index }}.affiliation_no"--}}
                                            {{--                                            >--}}
                                            {{--                                                <option value="">Please select</option>--}}
                                            {{--                                                @forelse($affiliations as $affiliation)--}}
                                            {{--                                                    <option--}}
                                            {{--                                                        value="{{ $affiliation['seq'] }}">{{ $affiliation['name'] }}</option>--}}
                                            {{--                                                @empty--}}
                                            {{--                                                    No affiliation--}}
                                            {{--                                                @endforelse--}}
                                            {{--                                            </select>--}}
                                            <input type="text" class="form-control" wire:model="authors.{{ $index }}.affiliation_no" placeholder="Author{{ $loop->index+1 }} affiliations, seperated by comma">

                                            @error("authors.$index.affiliation_no")<small class="form-text text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 d-flex">
                                        <div class="form-group w-25">
                                            <div class="d-flex align-items-center">
                                                <input type="radio" class="form-control" name="isPresenter" checked
                                                       wire:model="isPresenter" value="{{ $index }}" wire:click="setPresenter({{$index}})">presenter?
                                            </div>


                                        </div>
                                        <div class="form-group w-25">
                                            <div class="d-flex align-items-center">
                                                <input type="radio" class="form-control" name="isContact" checked
                                                       wire:model="isContact" value="{{ $index }}" wire:click="setContact({{ $index }})">contact?
                                            </div>
                                        </div>

                                        @if($author['is_contact'] == 1)
                                            <div class="form-group w-50">
                                                <input type="text" class="form-control" wire:model="authors.{{ $index }}.contact_email" placeholder="Contact author's email">

                                                @error("authors.$index.contact_email")<small class="form-text text-danger">{{ $message }}</small>@enderror
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 d-flex">
                                        <div class="form-group">
                                            <input type="button" class="btn btn-danger" value="Delete"
                                                   wire:click="removeAuthor({{ $index }})">

                                            <input type="button" class="btn btn-info" value="Up"
                                                   wire:click="up('authors',{{ $index }})">

                                            <input type="button" class="btn btn-warning" value="Down"
                                                   wire:click="down('authors',{{ $index }})">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if($step > 3)
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
                @error('isPresenter')<small class="form-text text-danger">{{ $message }}</small>@enderror
                @error('isContact')<small class="form-text text-danger">{{ $message }}</small>@enderror
                @if($step> 0 && $step<=3)
                    <button type="button" wire:click="decreaseStep" wire:key="back"
                            class="btn btn-secondary mr-3">{{ __('Back') }}</button>
                @endif

                @if($step <= 3)
                    <button type="submit" wire:key="next" class="btn btn-success">{{ __('Next') }}</button>
                @endif
            </div>
        </form>
        <div class="w-40 bg-dark px-4 py-4 min-vh-100 text-white">
            <h3 class="text-center mb-4 pb-3 border-bottom border-light text-warning">Abstract Preview</h3>
            <div @if(!$body)class="h-75"@endif>
                @if($title)
                    <h4>{{ $title }}</h4>
                    @endif
                    <div class="mb-2">
                    @forelse($authors as $author)
                                    <span @if($author['is_presenter']==1)style="border-bottom: 1px solid #fff"@endif>
                    {{ $author['name'] }}</span>
                        <sup>
                            {{ $author['affiliation_no'] }}
                        </sup>
                        @if($author['is_contact'] == 1)
                                <sup>*</sup>
                            @endif
                        {{ $loop->last ? '' : ', ' }}
                    @empty
                    @endforelse
                    </div>

                    <ol class="pl-3">
                        @forelse($affiliations as $affiliation)
                            <li>{{ $affiliation['name'] }}</li>
                        @empty
                        @endforelse
                    </ol>
                    @foreach($authors as $author)
                        @if($author['is_contact'] == 1)
                    <p>
                        * {{ $author['contact_email'] }}
                    </p>
                        @endif
                    @endforeach
                    @if($body)
                        <h6 class="mt-3 font-weight-bold">
                            Abstract
                        </h6>
                        <p>{!! $body !!}</p>
                        <p>Keywords: {{ $keywords }}</p>
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
</div>



