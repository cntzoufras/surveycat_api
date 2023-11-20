<?php

namespace App\Repositories;

use App\Models\Tag;
use Illuminate\Support\Facades\DB;

class TagRepository {

    public function index(array $params) {

        try {
            $limit = $params['limit'] ?? 10;
            return DB::transaction(function () use ($limit) {
                return Tag::query()->paginate($limit);
            });
        } catch (\Exception $e) {
            throw new \Exception($e, 500);
        }
    }

    public function resolveModel($tag) {
        if ($tag instanceof Tag) {
            return $tag;
        }
        return Tag::query()->findOrFail($tag);
    }

    public function getIfExist($tag) {
        return Tag::query()->find($tag);
    }

    public function update(Tag $tag, array $params) {
        return DB::transaction(function () use ($params, $tag) {
            $tag->fill($params);
            $tag->save();
            return $tag;
        });
    }

    public function store(array $params): Tag {
        return DB::transaction(function () use ($params) {
            $tag = new Tag();
            $tag->fill($params);
            $tag->save();
            return $tag;
        });
    }

    public function delete(Tag $tag) {
        return DB::transaction(function () use ($tag) {
            $tag->delete();
            return $tag;
        });
    }

}