<?php

namespace App\Contracts;

interface ListingRepositoryInterface {

    public function list(array $params = []);

    public function getListingLabel(): string;
}
