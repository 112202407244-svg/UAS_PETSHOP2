<?php

class OngkirController extends Controller
{
    public function provinsi(): void 
    {
        $response = $this->callApi('GET', '/destination/province');
        $this->json($response);
    }

    public function cari(): void 
    {
        $keyword = $_POST['keyword'] ?? $_GET['keyword'] ?? '';

        if (empty($keyword)) {
            $this->json(['status' => 'error', 'message' => 'kata kunci pencarian wajib diisi.'], 400);
            return;
        }

        $response = $this->callApi('GET', '/destination/domestic-destination', [
            'search' => $keyword,
            'limit' => 10,
            'offset' => 0,
        ]);

        $this->json($response);
    }

    public function cek(): void 
    {
        $destination = $_POST['destination'] ?? '';
        $weight = $_POST['weight'] ?? 1000;
        $courier = $_POST['courier'] ?? 'jne:sicepat:jnt';

        if (empty($destination)) {
            $this->json(['status' => 'error', 'message' => 'Kota tujuan wajib diisi.'],400);
            return;
        }

        $params = [
            'origin' => RAJAONGKIR_ORIGIN,
            'destination' => $destination,
            'weight' => (int) $weight,
            'courier' => $courier,
            'price' => 'lowest',
        ];

        $response = $this->callApi('POST', '/calculate/domestic-cost', $params);
        $this->json($response);
    }

    private function callApi(string $method, string $endpoint, array $params = []): array
    {
        $url = RAJAONGKIR_BASE_URL . $endpoint;
        $ch = curl_init();

        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        } else {
            if (!empty($params)) {
                $url .= '?' . http_build_query($params);
            }
        }

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => [
                'key: ' . RAJAONGKIR_KEY,
                'Content-Type: application/x-www-form-urlencoded',
            ],
        ]);

        $result = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return['status' => 'error', 'message' => 'cURL error: ' . $error];
        }

        return json_decode($result, true) ?? ['status' => 'error', 'message' => 'Response tidak valid.'];
    }
}