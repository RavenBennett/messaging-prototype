<?php

it('returns a successful response', function () {
    $response = $this->get('/admin');

    $response->assertStatus(200);
});
