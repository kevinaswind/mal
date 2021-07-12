<?php

namespace App\Http\Livewire;

use App\Affiliation;
use App\Author;
use App\Paper;
use App\Rules\MaxWordsRule;
use Barryvdh\Debugbar\Facade as Debugbar;
use Illuminate\Validation\Rule;
use Livewire\Component;

class PaperCreateForm extends Component
{
    public $title;
    public $topic;
    public $body;
    public $keywords;

    public $firstAuthorName;
    public $firstAuthorInstitution;
    public $firstAuthorEmail;

    public $affiliations = [];
    public $authors = [];

    public $isPresenter = null;
    public $isContact = null;
    public $contactEmail = "";

    public $step;

    public $paper;

    private $stepActions = [
        'submit1',
        'submit2',
        'submit3',
        'submit4',
    ];

    public function mount($paper)
    {
        if ($paper) {
            $this->paper = $paper;

            $this->title = $this->paper->title;
            $this->topic = $this->paper->topic;
            $this->body = $this->paper->body;
            $this->keywords = $this->paper->keywords;
            $this->firstAuthorName = $this->paper->firstAuthor->name;
            $this->firstAuthorInstitution = $this->paper->firstAuthor->institution;
            $this->firstAuthorEmail = $this->paper->firstAuthor->email;

            $this->affiliations = optional($this->paper->affiliations)->toArray();
            $this->authors = optional($this->paper->authors)->toArray();

            $this->resetPresenterContact();
        } else {
            $this->firstAuthorName = auth('delegate')->user()->name;
            $this->firstAuthorEmail = auth('delegate')->user()->email;

            array_push($this->authors, [
                "name" => auth('delegate')->user()->name,
                "affiliation_no" => "",
                "is_presenter" => null,
                "is_contact" => null,
                "contact_email" => "",
            ]);
        }

        $this->step = 0;
    }

    public function decreaseStep()
    {
        $this->step--;
    }

    public function down($arrayName,$index) {
        $a = $this->$arrayName;
        if( count($a)-1 > $index ) {
            $b = array_slice($a,0,$index,true);
            $b[] = $a[$index+1];
            $b[] = $a[$index];
            $b += array_slice($a,$index+2,count($a),true);
            $this->$arrayName = $b;

            if($arrayName == 'authors'){
                $this->resetPresenterContact();
            }

            if($arrayName == 'affiliations'){
                $this->emit('affiliation_changed');
            }
        } else { $this->$arrayName = $a; }
    }

    public function up($arrayName,$index) {
        $a = $this->$arrayName;
        if( $index > 0 and $index < count($a) ) {
            $b = array_slice($a,0,($index-1),true);
            $b[] = $a[$index];
            $b[] = $a[$index-1];
            $b += array_slice($a,($index+1),count($a),true);
            $this->$arrayName = $b;

            if($arrayName == 'authors'){
                $this->resetPresenterContact();
            }

            if($arrayName == 'affiliations'){
                $this->emit('affiliation_changed');
            }
        } else { $this->$arrayName = $a; }
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
            'body' => ['required', new MaxWordsRule(300)],
        ]);

        if ($this->paper) {
            $this->paper = tap($this->paper)->update([
                'title' => $this->title,
                'topic' => $this->topic,
                'body' => $this->body,
                'keywords' => $this->keywords,
            ]);
        } else {
            $this->paper = auth('delegate')->user()->papers()->create([
                'title' => $this->title,
                'topic' => $this->topic,
                'body' => $this->body,
                'keywords' => $this->keywords,
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

    public function addAffiliation(Affiliation $affiliation)
    {
        array_push($this->affiliations, [
            "name" => "",
            "seq" => "",
        ]);
    }

    public function removeAffiliation($index)
    {
//        unset($this->affiliations[$index]);
        array_splice($this->affiliations,$index,1);
    }

    public function submit3()
    {
        $this->validate([
            'affiliations.*.name' => 'required',
        ]);

        $this->paper->affiliations()->forceDelete();

        $seq = 1;

        foreach($this->affiliations as $key => $affiliation){
            $this->affiliations[$key]['seq'] = $seq;
            $seq++;
        }

        $result = $this->paper->affiliations()->createMany($this->affiliations);

        $this->step++;
    }

    public function addAuthor(Author $author)
    {
        array_push($this->authors, [
            "name" => "",
            "affiliation_no" => "",
            "is_presenter" => 0,
            "is_contact" => 0,
            "contact_email" => "",
        ]);
    }

    public function removeAuthor($index)
    {
//        unset($this->authors[$index]);
        array_splice($this->authors,$index,1);
    }

    public function setPresenter($index)
    {
        foreach ($this->authors as &$author)
        {
            $author['is_presenter'] = 0;
        }

        $this->authors[$index]['is_presenter'] = 1;

    }

    public function setContact($index)
    {
        foreach ($this->authors as &$author)
        {
            $author['is_contact'] = 0;
        }

        $this->authors[$index]['is_contact'] = 1;
    }

    public function resetPresenterContact()
    {
        foreach ($this->authors as $key=>$value){
            if($value['is_presenter'] == 1){
                $this->isPresenter = $key;
            }

            if($value['is_contact'] == 1){
                $this->isContact = $key;
            }
        }
    }

    public function submit4()
    {

        $this->validate([
            'authors.*.name' => 'required',
            'authors.*.affiliation_no' => 'required',
            'authors.*.is_presenter' => 'required',
            'authors.*.is_contact' => 'required',
            'authors.*.contact_email' => "required_if:authors.*.is_contact,1",
            'isPresenter' => 'required',
            'isContact' => 'required'
        ],
        [
            'isPresenter.required' => 'Please indicate a presenter',
            'isContact.required' => 'Please indicate a contact',
            'authors.*.contact_email.required_if' => 'Please enter email of contact author',
        ]);

//        foreach ($this->authors as &$author)
//        {
//            $author['affiliation_no'] = implode(',', $author['affiliation_no']);
//        }

        $this->paper->authors()->forceDelete();
        $result = $this->paper->authors()->createMany($this->authors);
        Debugbar::info($result);
        if (sizeof($result) > 0) {
            Debugbar::info('complete');
            $this->paper->update([
                'complete' => true,
            ]);
        } else {
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
