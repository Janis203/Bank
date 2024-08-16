<?php

test('create account', function () {
    $response = $this->post('/accounts',[

    ]);

    $response->assertStatus(200);
});
