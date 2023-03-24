<?php

namespace Modules\AdminManagement\Interface;

interface AuthInterface
{
    public function paginate($perPage, $request);

    public function create(array $data);

    public function update($where, $data);

    public function find($id);

    public function delete($id);

    public function savePasswordReset($email, $token);

    public function getPasswordReset(array $conditions);

    public function deletePasswordReset(array $conditions);

    public function sendMail($token, $request, $subject);

    public function authUpdate($data);
}
