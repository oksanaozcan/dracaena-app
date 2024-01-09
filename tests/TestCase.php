<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\MigrateFreshSeedOnce;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use MigrateFreshSeedOnce;
}
