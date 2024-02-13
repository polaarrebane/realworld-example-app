<?php

include __DIR__.'/Utils.php';

uses(
    Tests\TestCase::class,
    Illuminate\Foundation\Testing\LazilyRefreshDatabase::class,
)->in('Feature');

uses()->group('User')->in('Feature/User/*');
uses()->group('Article')->in('Feature/Article/*');
uses()->group('Profile')->in('Feature/Profile/*');
