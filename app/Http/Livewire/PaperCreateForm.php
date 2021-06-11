<?php

namespace App\Http\Livewire;

use App\Author;
use App\Paper;
use Barryvdh\Debugbar\Facade as Debugbar;
use Livewire\Component;

class PaperCreateForm extends Component
{
    public $title;
    public $topic;
    public $body;

    public $firstAuthorName;
    public $firstAuthorInstitution;
    public $firstAuthorEmail;

    public $authors = [];

    public $step;

    public $paper;

    private $stepActions = [
        'submit1',
        'submit2',
        'submit3',
    ];

    public function mount($paper)
    {
        if ($paper) {
            $this->paper = $paper;

            $this->title = $this->paper->title;
            $this->topic = $this->paper->topic;
            $this->body = $this->paper->body;
            $this->firstAuthorName = $this->paper->firstAuthor->name;
            $this->firstAuthorInstitution = $this->paper->firstAuthor->institution;
            $this->firstAuthorEmail = $this->paper->firstAuthor->email;

            $this->authors = $this->paper->authors->toArray();
        } else {
            $this->firstAuthorName = auth('delegate')->user()->name;
            $this->firstAuthorEmail = auth('delegate')->user()->email;
            $this->authors[0]['name'] = auth('delegate')->user()->name;
        }

        $this->step = 0;
    }

    public function decreaseStep()
    {
        $this->step--;
    }

    public function addAuthor(Author $author)
    {
        array_push($this->authors, [
            "name" => "",
            "affiliation_no" => "",
            "is_presenter" => "",
        ]);
    }

    public function removeAuthor($index)
    {
        unset($this->authors[$index]);
    }

    public function submit()
    {
        $action = $this->stepActions[$this->step];

        $this->$action();
    }

    public function submit1()
    {
        $this->validate([
            'title' => 'required|min:4|max:255',
            'topic' => 'required|min:4|max:255',
            'body' => 'required|min:4|max:500',
        ]);

        if ($this->paper) {
            $this->paper = tap($this->paper)->update([
                'title' => $this->title,
                'topic' => $this->topic,
                'body' => $this->body,
            ]);
        } else {
            $this->paper = auth('delegate')->user()->papers()->create([
                'title' => $this->title,
                'topic' => $this->topic,
                'body' => $this->body,
            ]);
        }

        $this->step++;
    }

    public function submit2()
    {
        $this->validate([
            'firstAuthorName' => 'required|min:4|max:255',
            'firstAuthorInstitution' => 'required|min:4|max:255',
            'firstAuthorEmail' => 'required|email',
        ]);

        if ($this->paper->firstAuthor) {
            $this->paper->firstAuthorr = tap($this->paper->firstAuthor)->update([
                'name' => $this->firstAuthorName,
                'institution' => $this->firstAuthorInstitution,
                'email' => $this->firstAuthorEmail,
            ]);
        } else {
            $this->paper->firstAuthor()->create([
                'name' => $this->firstAuthorName,
                'institution' => $this->firstAuthorInstitution,
                'email' => $this->firstAuthorEmail,
            ]);
        }

        $this->step++;
    }

    public function submit3()
    {
        $this->validate([
            'authors.*.name' => 'required',
            'authors.*.affiliation_no' => 'required',
            'authors.*.is_presenter' => 'required',
        ]);

        $this->paper->authors()->delete();
        $result = $this->paper->authors()->createMany($this->authors);
        Debugbar::info($result);
        if(sizeof($result) > 0){
            Debugbar::info('complete');
            $this->paper->update([
                'complete' => true,
            ]);
        }else{
            Debugbar::info('incomplete');
            $this->paper->update([
                'complete' => false,
            ]);
        }

        $this->step++;
    }

    public function render()
    {
        return view('livewire.paper-create-form');
    }
}
