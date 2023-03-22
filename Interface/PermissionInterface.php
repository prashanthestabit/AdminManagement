<?php

namespace Modules\AdminManagement\Interface;

interface PermissionInterface
{
    public function paginate($perPage);

    public function all();

    public function find($id);

    public function create($request);

    public function update($id, $request);

    public function delete($id);
}
