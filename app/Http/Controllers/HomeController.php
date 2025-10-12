<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SanPhamTietKiem;
use App\Models\VangDauTu;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\GiaVang;

class HomeController extends Controller
{
    public function index()
    {
        // Lấy danh sách sản phẩm đầu tư có trạng thái active (giả sử 1 là active)
        $sanPhamDauTu = SanPhamTietKiem::where('trang_thai', 1)
            ->orderBy('created_at', 'desc')
            ->limit(6) // Giới hạn 6 sản phẩm để hiển thị
            ->get();
        
        // Lấy danh sách giá vàng theo định dạng của getGiaVangDataDirect
        $homNay = date('Y-m-d');
        $homQua = date('Y-m-d', strtotime('-1 day'));

        // Lấy danh sách sản phẩm vàng đang active
        $dsVang = VangDauTu::where('trang_thai', 1)
            ->orderBy('ten_vang')
            ->get(['id', 'ten_vang','ma_vang']);
        $ids = $dsVang->pluck('id')->all();

        // Lấy bản ghi giá vàng hôm nay và hôm qua cho các sản phẩm ở trên
        $todayByVang = GiaVang::query()
            ->where('trang_thai', 1)
            ->whereIn('id_vang', $ids)
            ->whereDate('thoi_gian', $homNay)
            ->orderByDesc('id')
            ->get()
            ->groupBy('id_vang')
            ->map(function ($group) {
                return $group->first();
            });

        $yesterdayByVang = GiaVang::query()
            ->where('trang_thai', 1)
            ->whereIn('id_vang', $ids)
            ->whereDate('thoi_gian', $homQua)
            ->orderByDesc('id')
            ->get()
            ->groupBy('id_vang')
            ->map(function ($group) {
                return $group->first();
            });

        $dataList = [];
        foreach ($dsVang as $vang) {
            $today = $todayByVang->get($vang->id);
            $yesterday = $yesterdayByVang->get($vang->id);

            // Chỉ hiển thị khi có dữ liệu hôm nay hoặc hôm qua
            if (!$today && !$yesterday) {
                continue;
            }

            $dataList[] = [
                'name' => (string) $vang->ma_vang,
                'ma' => (string) $vang->ten_vang,
                'prices' => [
                    'giaMuaHomQua' => $yesterday ? (int) $yesterday->gia_mua : null,
                    'giaBanHomQua' => $yesterday ? (int) $yesterday->gia_ban : null,
                    'giaMuaHomNay' => $today ? (int) $today->gia_mua : null,
                    'giaBanHomNay' => $today ? (int) $today->gia_ban : null,
                ],
            ];
        }
        return view('user.home', compact('sanPhamDauTu', 'dataList'));
    }
    
    /**
     * Lấy dữ liệu giá vàng trực tiếp từ API DOJI
     */
    private function getGiaVangDataDirect()
    {
        $data = [
            [
                "name" => "SJC",
                "prices" => [
                    "giaMuaHomQua" => 132500,
                    "giaBanHomQua" => 134500,
                    "giaMuaHomNay" => 132500,
                    "giaBanHomNay" => 134500,
                ]
            ],
            [
                "name" => "DOJI HN",
                "prices" => [
                    "giaMuaHomQua" => 132500,
                    "giaBanHomQua" => 134500,
                    "giaMuaHomNay" => 132500,
                    "giaBanHomNay" => 134500,
                ]
            ],
            [
                "name" => "DOJI SG",
                "prices" => [
                    "giaMuaHomQua" => 132500,
                    "giaBanHomQua" => 134500,
                    "giaMuaHomNay" => 132500,
                    "giaBanHomNay" => 134500,
                ]
            ],
            [
                "name" => "BTMC SJC",
                "prices" => [
                    "giaMuaHomQua" => 132500,
                    "giaBanHomQua" => 134500,
                    "giaMuaHomNay" => 132500,
                    "giaBanHomNay" => 134500,
                ]
            ],
            [
                "name" => "Phú Qúy SJC",
                "prices" => [
                    "giaMuaHomQua" => 132000,
                    "giaBanHomQua" => 134500,
                    "giaMuaHomNay" => 132000,
                    "giaBanHomNay" => 134500,
                ]
            ],
            [
                "name" => "PNJ TP.HCM",
                "prices" => [
                    "giaMuaHomQua" => 128500,
                    "giaBanHomQua" => 131500,
                    "giaMuaHomNay" => 128500,
                    "giaBanHomNay" => 131500,
                ]
            ],
            [
                "name" => "PNJ Hà Nội",
                "prices" => [
                    "giaMuaHomQua" => 128500,
                    "giaBanHomQua" => 131500,
                    "giaMuaHomNay" => 128500,
                    "giaBanHomNay" => 131500,
                ]
            ]
        ];
        return $data;
    }
    
    /**
     * Format tên hiển thị cho dữ liệu giá vàng
     */
    private function formatDisplayName($key)
    {
        $displayNames = [
            'SJC' => 'SJC',
            'PNJ' => 'PNJ',
            'DOJI' => 'DOJI',
            'BaoTinMinhChau' => 'Bảo Tín Minh Châu',
            'PhuQuySJC' => 'Phú Quý SJC',
            'MiHong' => 'Mi Hồng',
            'ThangLong' => 'Thăng Long',
            'Vang9999' => 'Vàng 9999',
            'Vang24K' => 'Vàng 24K',
            'Vang18K' => 'Vàng 18K',
            'Vang14K' => 'Vàng 14K',
            'Vang10K' => 'Vàng 10K',
            'VangSJC' => 'Vàng SJC',
            'VangPNJ' => 'Vàng PNJ',
            'VangDOJI' => 'Vàng DOJI'
        ];
        
        return $displayNames[$key] ?? ucfirst(str_replace('_', ' ', $key));
    }
    
    /**
     * Dữ liệu fallback khi API không khả dụng
     */
    private function getFallbackData()
    {
        return [
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'raw_data' => null,
            'formatted_data' => [],
            'error' => 'Không thể tải dữ liệu giá vàng. Vui lòng thử lại sau.'
        ];
    }
    
    /**
     * Lấy dataList từ giaVangData
     */
    private function getDataListFromGiaVang($giaVangData)
    {
        if ($giaVangData && isset($giaVangData['DataList']['Data'])) {
            $dataList = $giaVangData['DataList']['Data'];
            
            // Log cấu trúc dữ liệu để debug
            if (!empty($dataList)) {
                Log::info('Sample dataList item structure', ['sample' => $dataList[0] ?? null]);
            }
            
            // Loại bỏ trùng lặp theo loại vàng
            $uniqueByType = $this->removeDuplicateGoldTypes($dataList);

            // Lưu snapshot theo ngày cho hôm nay
            try {
                $this->saveDailySnapshot($uniqueByType);
            } catch (\Exception $e) {
                Log::warning('Failed to save daily gold price snapshot', ['message' => $e->getMessage()]);
            }

            // Biến đổi dataList theo yêu cầu UI: loại vàng, mua hôm qua, mua hôm nay, bán hôm qua, bán hôm nay
            return $this->buildDataListWithYesterdayComparison($uniqueByType);
        }
        return null;
    }
    
    /**
     * Loại bỏ các bản ghi trùng nhau về loại vàng, chỉ giữ lại bản ghi có giá cập nhật gần nhất
     */
    private function removeDuplicateGoldTypes($dataList)
    {
        if (empty($dataList)) {
            return $dataList;
        }
        
        $groupedByType = [];
        
        // Nhóm các bản ghi theo loại vàng
        foreach ($dataList as $item) {
            $goldType = $item['@n_' . $item['@row']] ?? 'Unknown';
            
            if (!isset($groupedByType[$goldType])) {
                $groupedByType[$goldType] = [];
            }
            
            $groupedByType[$goldType][] = $item;
        }
        
        $result = [];
        
        // Với mỗi loại vàng, chọn bản ghi có thời gian cập nhật gần nhất
        foreach ($groupedByType as $goldType => $items) {
            if (count($items) === 1) {
                // Chỉ có 1 bản ghi, giữ nguyên
                $result[] = $items[0];
            } else {
                // Có nhiều bản ghi, chọn bản ghi có thời gian cập nhật gần nhất
                $latestItem = $this->getLatestItemByUpdateTime($items);
                $result[] = $latestItem;
                
                Log::info('Removed duplicate gold type', [
                    'gold_type' => $goldType,
                    'total_items' => count($items),
                    'kept_item' => $latestItem
                ]);
            }
        }
        
        Log::info('Duplicate removal completed', [
            'original_count' => count($dataList),
            'filtered_count' => count($result),
            'removed_count' => count($dataList) - count($result)
        ]);
        
        return $result;
    }

    /**
     * Lưu snapshot giá vàng theo ngày vào storage (để lấy dữ liệu hôm qua)
     */
    private function saveDailySnapshot(array $dataList): void
    {
        $directory = storage_path('app/private/gold_prices');
        if (!is_dir($directory)) {
            @mkdir($directory, 0775, true);
        }

        $filePath = $directory . '/' . date('Y-m-d') . '.json';

        // Chuyển dữ liệu về map theo loại vàng để tra cứu nhanh
        $snapshot = [];
        foreach ($dataList as $item) {
            $rowKey = $item['@row'] ?? null;
            $goldTypeName = $rowKey ? ($item['@n_' . $rowKey] ?? null) : null;
            if (!$goldTypeName) {
                continue;
            }
            $buy = $rowKey ? ($item['@pb_' . $rowKey] ?? null) : null;
            $sell = $rowKey ? ($item['@ps_' . $rowKey] ?? null) : null;
            $snapshot[$goldTypeName] = [
                'buy' => is_numeric($buy) ? (int)$buy : null,
                'sell' => is_numeric($sell) ? (int)$sell : null,
            ];
        }

        @file_put_contents($filePath, json_encode($snapshot, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }

    /**
     * Tải snapshot theo ngày (Y-m-d). Trả về mảng [loại_vàng => ['buy'=>..., 'sell'=>...]]
     */
    private function loadSnapshot(string $date)
    {
        $filePath = storage_path('app/private/gold_prices/' . $date . '.json');
        if (!file_exists($filePath)) {
            return null;
        }
        try {
            $content = file_get_contents($filePath);
            if ($content === false) {
                return null;
            }
            $data = json_decode($content, true);
            return is_array($data) ? $data : null;
        } catch (\Throwable $e) {
            Log::warning('Failed to load snapshot', ['date' => $date, 'error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Xây dựng mảng dataList mới gồm: loại_vàng, mua_hqua, mua_hnay, bán_hqua, bán_hnay
     */
    private function buildDataListWithYesterdayComparison(array $todayUniqueList): array
    {
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $yesterdaySnapshot = $this->loadSnapshot($yesterday) ?? [];

        $result = [];
        foreach ($todayUniqueList as $item) {
            $rowKey = $item['@row'] ?? null;
            $goldTypeName = $rowKey ? ($item['@n_' . $rowKey] ?? 'Unknown') : 'Unknown';
            $buyToday = $rowKey ? ($item['@pb_' . $rowKey] ?? null) : null;
            $sellToday = $rowKey ? ($item['@ps_' . $rowKey] ?? null) : null;

            $ySnap = $yesterdaySnapshot[$goldTypeName] ?? null;
            $buyYesterday = $ySnap['buy'] ?? null;
            $sellYesterday = $ySnap['sell'] ?? null;

            $result[] = [
                'loai_vang' => $goldTypeName,
                'mua_hqua' => is_numeric($buyYesterday) ? (int)$buyYesterday : null,
                'mua_hnay' => is_numeric($buyToday) ? (int)$buyToday : null,
                'ban_hqua' => is_numeric($sellYesterday) ? (int)$sellYesterday : null,
                'ban_hnay' => is_numeric($sellToday) ? (int)$sellToday : null,
            ];
        }

        return $result;
    }
    
    /**
     * Tìm bản ghi có thời gian cập nhật gần nhất trong danh sách
     */
    private function getLatestItemByUpdateTime($items)
    {
        $latestItem = $items[0];
        $latestTime = $this->extractUpdateTime($items[0]);
        
        foreach ($items as $item) {
            $itemTime = $this->extractUpdateTime($item);
            
            if ($itemTime && (!$latestTime || $itemTime > $latestTime)) {
                $latestTime = $itemTime;
                $latestItem = $item;
            }
        }
        
        return $latestItem;
    }
    
    /**
     * Trích xuất thời gian cập nhật từ item
     */
    private function extractUpdateTime($item)
    {
        // Thử các trường có thể chứa thời gian cập nhật
        $timeFields = [
            '@d_' . $item['@row'], // Trường ghi chú có thể chứa thời gian
            'updated_at',
            'timestamp',
            'time'
        ];
        
        foreach ($timeFields as $field) {
            if (isset($item[$field]) && !empty($item[$field])) {
                $timeValue = $item[$field];
                
                // Thử parse thời gian
                $timestamp = strtotime($timeValue);
                if ($timestamp !== false) {
                    return $timestamp;
                }
            }
        }
        
        // Nếu không tìm thấy thời gian, trả về null
        return null;
    }
    
    /**
     * Làm mới dữ liệu giá vàng
     */
    public function refreshGiaVang()
    {
        $giaVangData = $this->getGiaVangDataDirect();
        
        // Lấy ra dataList từ giaVangData (đã được loại bỏ trùng lặp)
        $dataList = $this->getDataListFromGiaVang($giaVangData);
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $giaVangData,
                'dataList' => $dataList,
                'message' => 'Dữ liệu giá vàng đã được cập nhật và loại bỏ trùng lặp'
            ]);
        }
        
        return redirect()->back()->with('success', 'Dữ liệu giá vàng đã được cập nhật và loại bỏ trùng lặp');
    }
    
    public function dashboard()
    {
        return view('user.dashboard');
    }
    
    /**
     * API endpoint để lấy dataList từ giaVangData (đã loại bỏ trùng lặp)
     */
    public function getGiaVangDataList()
    {
        $giaVangData = $this->getGiaVangDataDirect();
        $dataList = $this->getDataListFromGiaVang($giaVangData);
        
        return response()->json([
            'success' => true,
            'dataList' => $dataList,
            'count' => $dataList ? count($dataList) : 0,
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'note' => 'Dữ liệu đã được loại bỏ trùng lặp theo loại vàng'
        ]);
    }
    
    /**
     * API: Lịch sử giá vàng theo mã vàng trong khoảng N ngày gần nhất
     */
    public function getGiaVangHistory(Request $request)
    {
        $maVang = (string) $request->query('ma_vang', '');
        $days = (int) $request->query('days', 30);
        $to = (string) $request->query('to', '');
        
        if ($maVang === '') {
            return response()->json(['success' => false, 'message' => 'Thiếu tham số ma_vang'], 422);
        }
        if ($days <= 0) { $days = 30; }
        
        $toDate = $to !== '' ? date('Y-m-d', strtotime($to)) : date('Y-m-d');
        $fromDate = date('Y-m-d', strtotime('-' . $days . ' days', strtotime($toDate)));
        
        $vang = VangDauTu::where('ma_vang', $maVang)->first();
        if (!$vang) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy mã vàng'], 404);
        }
        
        $records = GiaVang::query()
            ->where('trang_thai', 1)
            ->where('id_vang', $vang->id)
            ->whereDate('thoi_gian', '>=', $fromDate)
            ->whereDate('thoi_gian', '<=', $toDate)
            ->orderBy('thoi_gian', 'asc')
            ->get(['gia_mua','gia_ban','thoi_gian']);
        
        $labels = [];
        $mua = [];
        $ban = [];
        foreach ($records as $r) {
            $labels[] = date('d/m', strtotime($r->thoi_gian));
            $mua[] = (int) $r->gia_mua;
            $ban[] = (int) $r->gia_ban;
        }
        
        return response()->json([
            'success' => true,
            'ma_vang' => $maVang,
            'ten_vang' => $vang->ten_vang,
            'from' => $fromDate,
            'to' => $toDate,
            'labels' => $labels,
            'gia_mua' => $mua,
            'gia_ban' => $ban,
            'count' => count($labels),
        ]);
    }
    public function test()
    {
        return view('user.test');
    }
    
    /**
     * Chuyển đổi dữ liệu từ dạng object thành mảng với cấu trúc mới
     */
    private function convertGoldDataToArray($duLieu, $thoiGian)
    {
        $result = [];
        
        // Kiểm tra dữ liệu đầu vào
        if (!is_array($duLieu) || empty($duLieu)) {
            return $result;
        }
        
        // Chuyển đổi thời gian từ dd/mm/yyyy sang yyyy-mm-dd
        $thoiGianFormatted = null;
        if (!empty($thoiGian)) {
            // Tách ngày, tháng, năm từ định dạng dd/mm/yyyy
            $dateParts = explode('/', $thoiGian);
            if (count($dateParts) === 3) {
                $day = $dateParts[0];
                $month = $dateParts[1];
                $year = $dateParts[2];
                $thoiGianFormatted = $year . '-' . $month . '-' . $day;
            }
        }
        
        // Nếu không thể chuyển đổi thời gian, sử dụng thời gian hiện tại
        if (!$thoiGianFormatted) {
            $thoiGianFormatted = now()->format('Y-m-d');
        }
        
        // Duyệt qua từng key (tên vàng) trong duLieu
        foreach ($duLieu as $vang => $giaData) {
            // Kiểm tra cấu trúc dữ liệu giá
            if (!is_array($giaData) || !isset($giaData['gia_mua']) || !isset($giaData['gia_ban'])) {
                continue;
            }
            
            // Lấy id từ bảng vangDauTu dựa trên ma_vang khớp với $vang
            $vangDauTu = VangDauTu::where('ma_vang', $vang)->first();
            $idVang = $vangDauTu ? $vangDauTu->id : null;
            
            // Chuyển đổi giá từ string sang int (loại bỏ dấu phẩy)
            $giaMua = is_string($giaData['gia_mua']) ? 
                (int) str_replace(',', '', $giaData['gia_mua']) : 
                (int) $giaData['gia_mua'];
                
            $giaBan = is_string($giaData['gia_ban']) ? 
                (int) str_replace(',', '', $giaData['gia_ban']) : 
                (int) $giaData['gia_ban'];
            
            // Tạo mảng mới với cấu trúc yêu cầu
            $result[] = [
                'thoi_gian' => $thoiGianFormatted, // Thời gian đã được chuyển đổi sang yyyy-mm-dd
                'vang' => $vang,
                'gia_mua' => $giaMua*100,
                'gia_ban' => $giaBan*100,
                'id_vang' => $idVang
            ];
        }
        
        return $result;
    }

    /**
     * Kiểm tra và thêm dữ liệu vào bảng gia_vang
     */
    private function checkAndInsertGiaVang($convertedData)
    {
        $insertedCount = 0;
        $skippedCount = 0;
        $errors = [];
        
        if (!is_array($convertedData) || empty($convertedData)) {
            return [
                'inserted_count' => 0,
                'skipped_count' => 0,
                'errors' => ['Dữ liệu convertedData không hợp lệ hoặc rỗng']
            ];
        }
        
        foreach ($convertedData as $index => $data) {
            try {
                // Kiểm tra dữ liệu bắt buộc
                if (!isset($data['id_vang']) || !isset($data['thoi_gian']) || 
                    !isset($data['gia_mua']) || !isset($data['gia_ban'])) {
                    $errors[] = "Dữ liệu tại index {$index} thiếu thông tin bắt buộc";
                    continue;
                }
                
                // Kiểm tra xem đã tồn tại bản ghi với cùng thoi_gian và id_vang chưa
                $existingRecord = GiaVang::where('thoi_gian', $data['thoi_gian'])
                    ->where('id_vang', $data['id_vang'])
                    ->first();
                
                if ($existingRecord) {
                    $skippedCount++;
                    Log::info("Bản ghi đã tồn tại", [
                        'thoi_gian' => $data['thoi_gian'],
                        'id_vang' => $data['id_vang'],
                        'existing_id' => $existingRecord->id
                    ]);
                    continue;
                }
                
                // Thêm bản ghi mới
                $giaVang = GiaVang::create([
                    'id_vang' => $data['id_vang'],
                    'thoi_gian' => $data['thoi_gian'],
                    'gia_mua' => $data['gia_mua'],
                    'gia_ban' => $data['gia_ban'],
                    'trang_thai' => 1
                ]);
                
                $insertedCount++;
                Log::info("Đã thêm bản ghi mới", [
                    'gia_vang_id' => $giaVang->id,
                    'thoi_gian' => $data['thoi_gian'],
                    'id_vang' => $data['id_vang'],
                    'gia_mua' => $data['gia_mua'],
                    'gia_ban' => $data['gia_ban']
                ]);
                
            } catch (\Exception $e) {
                $errors[] = "Lỗi tại index {$index}: " . $e->getMessage();
                Log::error("Lỗi khi thêm bản ghi gia_vang", [
                    'index' => $index,
                    'data' => $data,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        return [
            'inserted_count' => $insertedCount,
            'skipped_count' => $skippedCount,
            'errors' => $errors
        ];
    }

    /**
     * Xử lý dữ liệu JSON từ form test
     */
    public function addTestData(Request $request)
    {
        try {
            // Lấy dữ liệu JSON từ request
            $jsonData = $request->input('data');
            $insertResult = null;
            
            // Log dữ liệu được gửi
            Log::info('Test Data Received', [
                'timestamp' => now()->format('Y-m-d H:i:s'),
                'user_ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'data' => $jsonData,
                'data_type' => gettype($jsonData),
                'data_size' => is_string($jsonData) ? strlen($jsonData) : (is_array($jsonData) ? count($jsonData) : 'unknown')
            ]);
            
            // Log chi tiết dữ liệu
            if (is_array($jsonData)) {
                Log::info('Test Data Details', [
                    'keys' => array_keys($jsonData),
                    'values' => $jsonData,
                    'json_string' => json_encode($jsonData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
                ]);
                
                // Chuyển đổi dữ liệu nếu có cấu trúc duLieu và thoiGian
                $convertedData = null;
                if (isset($jsonData['duLieu']) && isset($jsonData['thoiGian'])) {
                    $convertedData = $this->convertGoldDataToArray($jsonData['duLieu'], $jsonData['thoiGian']);
                    
                    Log::info('Converted Data', [
                        'original_structure' => $jsonData,
                        'converted_array' => $convertedData,
                        'converted_count' => count($convertedData)
                    ]);
                    
                    // Kiểm tra và thêm dữ liệu vào bảng gia_vang
                    $insertResult = $this->checkAndInsertGiaVang($convertedData);
                    
                    Log::info('GiaVang Insert Result', [
                        'inserted_count' => $insertResult['inserted_count'],
                        'skipped_count' => $insertResult['skipped_count'],
                        'errors' => $insertResult['errors']
                    ]);
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Dữ liệu đã được nhận và xử lý thành công',
                'received_data' => $jsonData,
                'converted_data' => $convertedData ?? null,
                'gia_vang_result' => $insertResult ?? null,
                'timestamp' => now()->format('Y-m-d H:i:s'),
                'log_location' => 'storage/logs/laravel.log'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Test Data Processing Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xử lý dữ liệu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API endpoint để thêm dữ liệu giá vàng
     * Thay thế cho route /test/add-data
     */
    public function addTestDataApi(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'data' => 'required|array',
                'data.duLieu' => 'required|array',
                'data.thoiGian' => 'required|string'
            ]);

            // Lấy dữ liệu JSON từ request
            $jsonData = $request->input('data');
            $insertResult = null;
            
            // Log dữ liệu được gửi
            Log::info('API Test Data Received', [
                'timestamp' => now()->format('Y-m-d H:i:s'),
                'user_ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'data' => $jsonData,
                'data_type' => gettype($jsonData),
                'data_size' => is_string($jsonData) ? strlen($jsonData) : (is_array($jsonData) ? count($jsonData) : 'unknown')
            ]);
            
            // Log chi tiết dữ liệu
            if (is_array($jsonData)) {
                Log::info('API Test Data Details', [
                    'keys' => array_keys($jsonData),
                    'values' => $jsonData,
                    'json_string' => json_encode($jsonData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
                ]);
                
                // Chuyển đổi dữ liệu nếu có cấu trúc duLieu và thoiGian
                $convertedData = null;
                if (isset($jsonData['duLieu']) && isset($jsonData['thoiGian'])) {
                    $convertedData = $this->convertGoldDataToArray($jsonData['duLieu'], $jsonData['thoiGian']);
                    
                    Log::info('API Converted Data', [
                        'original_structure' => $jsonData,
                        'converted_array' => $convertedData,
                        'converted_count' => count($convertedData)
                    ]);
                    
                    // Kiểm tra và thêm dữ liệu vào bảng gia_vang
                    $insertResult = $this->checkAndInsertGiaVang($convertedData);
                    
                    Log::info('API GiaVang Insert Result', [
                        'inserted_count' => $insertResult['inserted_count'],
                        'skipped_count' => $insertResult['skipped_count'],
                        'errors' => $insertResult['errors']
                    ]);
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Dữ liệu đã được nhận và xử lý thành công qua API',
                'data' => [
                    'received_data' => $jsonData,
                    'converted_data' => $convertedData ?? null,
                    'gia_vang_result' => $insertResult ?? null,
                    'timestamp' => now()->format('Y-m-d H:i:s'),
                    'log_location' => 'storage/logs/laravel.log'
                ]
            ], 200);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('API Validation Error', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('API Test Data Processing Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xử lý dữ liệu: ' . $e->getMessage()
            ], 500);
        }
    }
}
