<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'C_admin';
$route['404_override'] = 'errors';
$route['translate_uri_dashes'] = FALSE;

$route['home'] = "C_admin/home";
$route['home/(:any)'] = 'C_admin/home';

$route['login'] = "C_admin_login/index";
$route['login/(:any)'] = 'C_admin_login/index';

$route['daftar'] = "C_admin_login/daftar";
$route['daftar/(:any)'] = 'C_admin_login/daftar';

$route['logout'] = "C_admin_login/logout";
$route['logout/(:any)'] = 'C_admin_login/logout';

$route['kelas'] = "C_kelas";
$route['kelas/(:any)'] = 'C_kelas';

$route['input-kelas'] = "C_kelas/input";
$route['input-kelas/(:any)'] = 'C_kelas/input';

$route['tahun-ajaran'] = "C_tahun_ajaran";
$route['tahun-ajaran/(:any)'] = 'C_tahun_ajaran';

$route['input-tahun'] = "C_tahun_ajaran/create";
$route['input-tahun/(:any)'] = 'C_tahun_ajaran/create';

$route['semester'] = "C_semester";
$route['semester/(:any)'] = 'C_semester';

$route['input-master-semester'] = "C_semester/create";
$route['input-master-semester/(:any)'] = 'C_semester/create';

$route['jabatan'] = "C_jabatan";
$route['jabatan/(:any)'] = 'C_jabatan';

$route['input-jabatan'] = "C_jabatan/create";
$route['input-jabatan/(:any)'] = 'C_jabatan/create';

$route['siswa'] = "C_siswa";
$route['siswa/(:any)'] = 'C_siswa';

$route['input-siswa'] = "C_siswa/create";
$route['input-siswa/(:any)'] = 'C_siswa/create';

$route['2-kelas-siswa'] = "C_kelas_siswa/index";
$route['2-kelas-siswa/(:any)'] = 'C_kelas_siswa/index';

$route['kat-bayaran'] = "C_kat_bayaran";
$route['kat-bayaran/(:any)'] = 'C_kat_bayaran';

$route['input-kat-bayaran'] = "C_kat_bayaran/create";
$route['input-kat-bayaran/(:any)'] = 'C_kat_bayaran/create';

$route['bayaran'] = "C_bayaran";
$route['bayaran/(:any)'] = 'C_bayaran';

$route['input-bayaran'] = "C_bayaran/create";
$route['input-bayaran/(:any)'] = 'C_bayaran/create';

$route['pengurang-bayaran'] = "C_pengurang_bayaran";
$route['pengurang-bayaran/(:any)'] = 'C_pengurang_bayaran';

$route['input-pengurang-bayaran'] = "C_pengurang_bayaran/create";
$route['input-pengurang-bayaran/(:any)'] = 'C_pengurang_bayaran/create';

$route['proyek'] = "C_proyek";
$route['proyek/(:any)'] = 'C_proyek';

$route['input-proyek'] = "C_proyek/create";
$route['input-proyek/(:any)'] = 'C_proyek/create';

$route['karyawan'] = "C_karyawan";
$route['karyawan/(:any)'] = 'C_karyawan';

$route['input-karyawan'] = "C_karyawan/create";
$route['input-karyawan/(:any)'] = 'C_karyawan/create';

$route['kat-uang-masuk'] = "C_kat_uang_masuk";
$route['kat-uang-masuk/(:any)'] = 'C_kat_uang_masuk';

$route['input-kat-uang-masuk'] = "C_kat_uang_masuk/create";
$route['input-kat-uang-masuk/(:any)'] = 'C_kat_uang_masuk/create';

$route['kat-uang-keluar'] = "C_kat_uang_keluar";
$route['kat-uang-keluar/(:any)'] = 'C_kat_uang_keluar';

$route['input-kat-uang-keluar'] = "C_kat_uang_keluar/create";
$route['input-kat-uang-keluar/(:any)'] = 'C_kat_uang_keluar/create';

$route['2-bayaran-kelas'] = "C_bayaran_kelas";
$route['2-bayaran-kelas/(:any)'] = 'C_bayaran_kelas';

$route['2-input-bayaran-kelas'] = "C_bayaran_kelas/create";
$route['2-input-bayaran-kelas/(:any)'] = 'C_bayaran_kelas/create';

$route['2-bayaran-siswa'] = "C_bayaran_siswa";
$route['2-bayaran-siswa/(:any)'] = 'C_bayaran_siswa';

$route['2-input-bayaran-siswa'] = "C_bayaran_siswa/create";
$route['2-input-bayaran-siswa/(:any)'] = 'C_bayaran_siswa/create';

$route['2-bayaran-pengurang'] = "C_bayaran_pengurang";
$route['2-bayaran-pengurang/(:any)'] = 'C_bayaran_pengurang';

$route['3-bayaran'] = "C_tran_siswa";
$route['3-bayaran/(:any)'] = 'C_tran_siswa';

$route['detail-bayaran'] = "C_tran_siswa/detail";
$route['detail-bayaran/(:any)'] = 'C_tran_siswa/detail';

$route['input-transaksi'] = "C_tran_siswa/input";
$route['input-transaksi/(:any)'] = 'C_tran_siswa/input';
$route['input-transaksi/(:any)/(:any)'] = 'C_tran_siswa/input';

$route['input-semester'] = "C_tran_siswa/input_semester";
$route['input-semester/(:any)'] = 'C_tran_siswa/input_semester';
$route['input-semester/(:any)/(:any)'] = 'C_tran_siswa/input_semester';

$route['input-tahunan'] = "C_tran_siswa/input_tahunan";
$route['input-tahunan/(:any)'] = 'C_tran_siswa/input_tahunan';
$route['input-tahunan/(:any)/(:any)'] = 'C_tran_siswa/input_tahunan';

$route['input-bayaran-tetap'] = "C_tran_siswa/input_tetap";
$route['input-bayaran-tetap/(:any)'] = 'C_tran_siswa/input_tetap';
$route['input-bayaran-tetap/(:any)/(:any)'] = 'C_tran_siswa/input_tetap';


$route['edit-transaksi'] = "C_tran_siswa/update";
$route['edit-transaksi/(:any)'] = 'C_tran_siswa/update';
$route['edit-transaksi/(:any)/(:any)'] = 'C_tran_siswa/update';

$route['edit-semester'] = "C_tran_siswa/update_semester";
$route['edit-semester/(:any)'] = 'C_tran_siswa/update_semester';
$route['edit-semester/(:any)/(:any)'] = 'C_tran_siswa/update_semester';

$route['edit-tahunan'] = "C_tran_siswa/update_tahunan";
$route['edit-tahunan/(:any)'] = 'C_tran_siswa/update_tahunan';
$route['edit-tahunan/(:any)/(:any)'] = 'C_tran_siswa/update_tahunan';

$route['edit-tetap'] = "C_tran_siswa/update_tetap";
$route['edit-tetap/(:any)'] = 'C_tran_siswa/update_tetap';
$route['edit-tetap/(:any)/(:any)'] = 'C_tran_siswa/update_tetap';

$route['3-uang-masuk'] = "C_uang_masuk";
$route['3-uang-masuk/(:any)'] = "C_uang_masuk";

$route['3-input-uang-masuk'] = "C_uang_masuk/create";
$route['3-input-uang-masuk/(:any)'] = "C_uang_masuk/create";

$route['3-uang-keluar'] = "C_uang_keluar";
$route['3-uang-keluar/(:any)'] = "C_uang_keluar";

$route['3-input-uang-keluar'] = "C_uang_keluar/create";
$route['3-input-uang-keluar/(:any)'] = "C_uang_keluar/create";

$route['bank'] = "C_bank";
$route['bank/(:any)'] = "C_bank";

$route['input-bank'] = "C_bank/create";
$route['input-bank/(:any)'] = "C_bank/create";

$route['laporan-keuangan'] = "C_laporan";
$route['laporan-keuangan/(:any)'] = "C_laporan";

$route['akun-setting'] = "C_akun";
$route['akun-setting/(:any)'] = "C_akun";

$route['input-akun'] = "C_akun/create";
$route['input-akun/(:any)'] = "C_akun/create";

$route['profile'] = "C_akun/profile";
$route['profile/(:any)'] = "C_akun/profile";



/*
$route['karyawan'] = "C_karyawan";
$route['karyawan/(:any)'] = 'C_karyawan';

$route['input-karyawan'] = "C_karyawan/create";
$route['input-karyawan/(:any)'] = 'C_karyawan/create';
*/
