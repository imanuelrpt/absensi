<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $sessionRole = session()->get('role');

        if ($arguments && ! in_array($sessionRole, $arguments)) {
            $redirectUrl = ($sessionRole === 'admin') ? '/admin' : (($sessionRole === 'guru') ? '/guru' : '/siswa');
            return redirect()->to($redirectUrl)->with('error', 'Akses Ditolak: Anda tidak memiliki izin untuk mengakses halaman ' . $request->getUri()->getPath());
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
