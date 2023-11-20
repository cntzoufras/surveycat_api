<?php

namespace App\Repositories;

use App\Models\Comment;

use Illuminate\Support\Facades\DB;

class CommentRepository {

    public function index(array $params) {

        try {
            $limit = $params['limit'] ?? 10;
            return DB::transaction(function () use ($limit) {
                return Comment::query()->paginate($limit);
            });
        } catch (\Exception $e) {
            throw new \Exception($e, 500);
        }
    }

    public function resolveModel($comment) {
        if ($comment instanceof Comment) {
            return $comment;
        }
        return Comment::query()->findOrFail($comment);
    }

    public function getIfExist($comment) {
        return Comment::query()->find($comment);
    }

    public function update(Comment $comment, array $params) {
        return DB::transaction(function () use ($params, $comment) {
            $comment->fill($params);
            $comment->save();
            return $comment;
        });
    }

    public function store(array $params): Comment {
        return DB::transaction(function () use ($params) {
            $comment = new Comment();
            $comment->fill($params);
            $comment->save();
            return $comment;
        });
    }

    public function delete(Comment $comment) {
        return DB::transaction(function () use ($comment) {
            $comment->delete();
            return $comment;
        });
    }

}