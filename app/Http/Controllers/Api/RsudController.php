<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class RsudController extends Controller
{
    protected $client;
    public function __construct()
    {
        $this->client = new Client();
    }

    public function index()
    {
        try {
            $connected_medfac_ihs =  env('CONNECTED_MEDICAL_FACILITY_IHS');
            $jakarta_medfac =  env('JAKARTA_MEDICAL_FACILITY');
            $ihs_transaction = env('IHS_TRANSACTION');

            $response_of_connected_medfac = $this->client->request('GET', $connected_medfac_ihs)->getBody();
            $response_of_jakarta_medfac = $this->client->request('GET', $jakarta_medfac)->getBody();
            $response_of_ihs_transaction = $this->client->request('GET', $ihs_transaction)->getBody();

            //Memisahkan terlebih dahulu API Daftar Rumah Sakit di Jakarta yang sudah terkoneksi dengan SatuSehat Kemenkes ke dalam collection berdasarkan organisasi_id
            $array2 = collect(json_decode($response_of_jakarta_medfac, true))->keyBy('organisasi_id');

            //Lakukan iterasi pada API Daftar Rumah Sakit Umum Daerah (RSUD) di Jakarta dan merge data dengan $array2
            $result = collect(json_decode($response_of_connected_medfac, true))->map(function ($item) use ($array2) {
                //ambil organisasi_id dari API Daftar Rumah Sakit Umum Daerah (RSUD) di Jakarta
                $organisasi_id = (int) $item['organisasi_id'];
                //Jika organisasi_id sama dengan properties pada $array2 maka merge email
                if ($array2->has($organisasi_id)) {
                    $item['email'] = $array2->get($organisasi_id)['email'];
                    $item['kelas_rs'] = $array2->get($organisasi_id)['kelas_rs'];
                }
                return $item;
            })->filter(function ($item) {
                // Menyaring data yang hanya memiliki email dan kelas_rs yang cocok saja
                return isset($item['email']) && isset($item['kelas_rs']);
            })
                ->toArray();
            return response()->json([
                'status' => true,
                'code' => 200,
                'data' => $result
            ], 200);
        } catch (\Exception $error) {
            response()->json([
                'status' => false,
                'code' => 500,
                'message' => $error
            ], 200);
        }
    }
}
