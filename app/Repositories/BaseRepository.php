<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

abstract class BaseRepository
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Lấy tất cả
     */
    public function all(array $columns = ['*'], array $relations = [])
    {
        $query = $this->model->newQuery();
        
        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->get($columns);
    }

    /**
     * Phân trang
     */
    public function paginate(
        $perPage = 15,
        array $columns = ['*'],
        array $relations = [],
        $pageName = 'page',
        $page = null
    ) {
        $query = $this->model->newQuery();

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->paginate($perPage, $columns, $pageName, $page);
    }

    /**
     * Lấy theo ID
     */
    public function find($id, array $columns = ['*'], array $relations = [])
    {
        $query = $this->model->newQuery();

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->find($id, $columns);
    }

    /**
     * Tìm hoặc thất bại
     */
    public function findOrFail($id, array $columns = ['*'], array $relations = [])
    {
        $query = $this->model->newQuery();

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->findOrFail($id, $columns);
    }

    /**
     * Tìm theo điều kiện
     */
    public function findBy($key, $value, array $columns = ['*'], array $relations = [])
    {
        $query = $this->model->newQuery();

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->where($key, $value)->first($columns);
    }

    /**
     * Tìm tất cả theo điều kiện
     */
    public function findAllBy($key, $value, array $columns = ['*'], array $relations = [])
    {
        $query = $this->model->newQuery();

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->where($key, $value)->get($columns);
    }

    /**
     * Tạo mới
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Cập nhật
     */
    public function update($id, array $data)
    {
        $model = $this->find($id);

        if ($model) {
            $model->update($data);
        }

        return $model;
    }

    /**
     * Xóa
     */
    public function delete($id)
    {
        $model = $this->find($id);

        if ($model) {
            return $model->delete();
        }

        return false;
    }

    /**
     * Xóa nhiều
     */
    public function deleteMany(array $ids)
    {
        return $this->model->whereIn('id', $ids)->delete();
    }

    /**
     * Lấy query builder
     */
    public function query()
    {
        return $this->model->newQuery();
    }

    /**
     * Kiểm tra tồn tại
     */
    public function exists($key, $value)
    {
        return $this->model->where($key, $value)->exists();
    }

    /**
     * Đếm
     */
    public function count()
    {
        return $this->model->count();
    }
}
