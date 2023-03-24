<?php

namespace Modules\AdminManagement\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Modules\AdminManagement\Entities\PasswordReset;
use Modules\AdminManagement\Interface\AuthInterface;

/* Class AuthRepository.
 * This class is responsible for handling database operations related to authentication.
 */
class AuthRepository implements AuthInterface
{
    /**
     * Get All User pagination
     *
     * @param  int  $perPage
     * @return \App\Models\User
     */
    public function paginate($perPage, $request)
    {
        return User::orderBy('id', 'DESC')
            ->when($request->has('table_search'), function ($query) use ($request) {
                return $query->where('name', 'like', '%'.$request->input('table_search').'%')
                            ->orWhere('email', 'like', '%'.$request->input('table_search').'%');
            })
            ->paginate($perPage);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

   /**
    * Update user with the given data.
    *
    * @param  array  $data
    * @param  array  $where
    * @return \App\Models\User
    */
   public function update($where, $data)
   {
       return User::where($where)->update($data);
   }

   /**
    * Find user by id.
    *
    * @param  int  $id
    * @return \App\Models\User
    */
   public function find($id)
   {
       return User::find($id);
   }

   /**
    * Delete User By id
    *
    * @param  int  $id
    * @return \App\Models\User
    */
   public function delete($id)
   {
       return User::find($id)->delete();
   }

    public function savePasswordReset($email, $token)
    {
        return  PasswordReset::insert([
            'email' => $email,
            'token' => $token,
            'created_at' => now(),
        ]);
    }

    public function getPasswordReset(array $conditions)
    {
        return PasswordReset::where($conditions)->first();
    }

    public function deletePasswordReset(array $conditions)
    {
        return PasswordReset::where($conditions)->delete();
    }

    public function sendMail($token, $request, $subject)
    {
        Mail::send('adminmanagement::email.forgetPassword', ['token' => $token], function ($m)
        use ($request, $subject) {
            $m->to($request->email);
            $m->subject($subject);
        });

        return true;
    }

   /**
    * Update auth user with the given data.
    *
    * @param  array  $data
    * @return \App\Models\User
    */
   public function authUpdate($data)
   {
       return auth()->user()->update($data);
   }
}
