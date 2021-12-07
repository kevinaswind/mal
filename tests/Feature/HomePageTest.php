<?php
it('can render homepage', function () {
    $this
        ->get('/')
        ->assertSuccessful()
        ->assertSee('Welcome');
});

it('', function () {

});
