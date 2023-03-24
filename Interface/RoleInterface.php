<?php

namespace Modules\AdminManagement\Interface;

interface RoleInterface
{
    public function find($id);

    public function all();

    public function paginate($perPage, $request);

    public function create($request);

    public function update($id, $request);

    public function delete($id);
}
