<!-- Giá vàng DOJI -->
@if($dataList && count($dataList) > 0)
<section id="gia-vang" class="py-5 bg-light">
    <div class="container">
        <style>
            /* Sticky action column inside the horizontal scroller */
            #gia-vang .sticky-col { position: sticky; right: 0; z-index: 12; background: #fffaf0; box-shadow: -6px 0 6px -6px rgba(0,0,0,.15); }
            #gia-vang thead .sticky-col { z-index: 13; }
            #gia-vang .w-action { width: 1%; white-space: nowrap; }

            /* Sticky first two columns on the left */
            #gia-vang .sticky-col-left { position: sticky; left: 0; z-index: 12; background: #fffaf0; }
            #gia-vang .sticky-col-left-2 { position: sticky; left: 60px; z-index: 12; background: #fffaf0; box-shadow: 6px 0 6px -6px rgba(0,0,0,.15); }
            #gia-vang thead .sticky-col-left, #gia-vang thead .sticky-col-left-2 { z-index: 13; }
            #gia-vang .w-index { min-width: 60px; }
            #gia-vang .w-name { width: 1%; white-space: nowrap; }
            /* Ivory background for all table cells */
            #gia-vang table thead th,
            #gia-vang table tbody th,
            #gia-vang table tbody td { background-color: #fffaf0 !important; }

            /* Make table text thinner */
            #gia-vang table thead th,
            #gia-vang table tbody th,
            #gia-vang table tbody td { font-weight: 400 !important; }
            #gia-vang table .fw-bold,
            #gia-vang table .fw-semibold,
            #gia-vang table h6 { font-weight: 400 !important; }

            /* Borders for all table cells */
            #gia-vang table { border-collapse: separate; border-spacing: 0; }
            #gia-vang table thead th,
            #gia-vang table tbody th,
            #gia-vang table tbody td { border: 1px solid #dee2e6; }

            /* Ensure sticky columns show bordering edges clearly */
            #gia-vang .sticky-col-left,
            #gia-vang .sticky-col-left-2 { border-right: 1px solid #dee2e6; }
            #gia-vang .sticky-col { border-left: 1px solid #dee2e6; }
            /* Chart layout: fill card body */
            #gia-vang .chart-body { display: flex; flex-direction: column; }
            #gia-vang .chart-wrapper { flex: 1 1 auto; min-height: 180px; }
            #gia-vang #goldPriceChart { width: 100% !important; height: 100% !important; }
        </style>
        <div class="text-center mb-4">
            <span class="text-primary fw-bold text-uppercase small">Thị trường</span>
            <h2 class="mt-2">Giá vàng hôm nay</h2>
            <p class="text-muted">Cập nhật nhật gần đây nhất - {{ now()->format('d/m/Y H:i') }}</p>
        </div>
		<div class="row g-4">
			<div class="col-12 col-lg-7">
				<div class="card shadow-sm border-0">
					<div class="card-body p-0">
                        <div class="table-responsive" style="overflow-x:auto;-webkit-overflow-scrolling:touch">
                            <table class="table table-bordered mb-0 text-nowrap" style="min-width: 720px;">
                                <thead class="bg-transparent">
                                    <tr>
                                        <th class="text-center sticky-col-left w-index" rowspan="2">#</th>
                                        <th class="text-center sticky-col-left-2 w-name" rowspan="2">Loại vàng</th>
                                        <th class="text-center" colspan="2">Hôm qua <br> ({{ now()->subDay()->format('d/m/Y') }})</th>
                                        <th class="text-center" colspan="2">Hôm nay <br> ({{ now()->format('d/m/Y') }})</th>
                                        <th class="text-center sticky-col w-action" rowspan="2">Hành động</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">Giá mua</th>
                                        <th class="text-center">Giá bán</th>
                                        <th class="text-center">Giá mua</th>
                                        <th class="text-center">Giá bán</th>
                                    </tr>
                                </thead>
                                <tbody id="goldTypeBody">
                                    @foreach($dataList as $index => $item)
                                    <tr class="gold-type-row" data-ma-vang="{{ $item['name'] ?? '' }}" data-index="{{ $index }}" style="cursor:pointer">
                                        <td class="text-center fw-semibold text-dark sticky-col-left w-index">
                                            {{ $index + 1 }} 
                                        </td>
                                        <td class="text-left fw-semibold text-primary sticky-col-left-2 w-name">
                                            {{ $item['name'] ?? 'N/A' }}
                                        </td>
                                        <td class="text-center">
                                            @if(isset($item['prices']['giaMuaHomQua']) && is_numeric($item['prices']['giaMuaHomQua']))
                                                <span class="fw-semibold">{{ number_format($item['prices']['giaMuaHomQua'], 0, ',', '.') }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if(isset($item['prices']['giaBanHomQua']) && is_numeric($item['prices']['giaBanHomQua']))
                                                <span class="fw-semibold">{{ number_format($item['prices']['giaBanHomQua'], 0, ',', '.') }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if(isset($item['prices']['giaMuaHomNay']) && is_numeric($item['prices']['giaMuaHomNay']))
                                                <span class="h6 text-success fw-bold">{{ number_format($item['prices']['giaMuaHomNay'], 0, ',', '.') }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if(isset($item['prices']['giaBanHomNay']) && is_numeric($item['prices']['giaBanHomNay']))
                                                <span class="h6 text-danger fw-bold">{{ number_format($item['prices']['giaBanHomNay'], 0, ',', '.') }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center sticky-col w-action">
                                            <a href="{{ route('dashboard.chi-tiet-dau-tu', $item['ma'] ?? '') }}" class="btn btn-success btn-sm fw-semibold">
                                                <i class="bi bi-cart-plus me-1"></i>Giao dịch
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
						</div>
					</div>
				</div>
			</div>
            <div class="col-12 col-lg-5">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body chart-body">
                        <h5 class="card-title mb-3">Biểu đồ giá theo thời gian</h5>
                        <div class="chart-wrapper">
                            <canvas id="goldPriceChart" aria-label="Biểu đồ giá vàng theo thời gian" role="img"></canvas>
                        </div>
                        <p class="text-muted small mt-2 mb-0">Mặc định hiển thị loại vàng đầu tiên. Nhấn vào từng dòng để chuyển.</p>
					</div>
				</div>
			</div>
		</div>

        <div class="text-center mt-2 d-md-none text-muted small">
            <i class="bi bi-arrow-left-right me-1"></i> Vuốt ngang để xem đầy đủ bảng
        </div>

        <div class="text-center mt-4">
            <small class="text-muted">
                <i class="bi bi-info-circle me-1"></i>
                Dữ liệu hôm qua được lưu theo ngày, có thể trống nếu mới lần đầu tải.
            </small>
        </div>
    </div>
</section>
@endif

@if(isset($dataList) && is_array($dataList) && count($dataList) > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function() {
	var ctx = document.getElementById('goldPriceChart');
	if (!ctx || typeof Chart === 'undefined') return;

	// Khởi tạo rỗng; sẽ render sau khi gọi API
	var chart = new Chart(ctx, { type: 'line', data: { labels: [], datasets: [] }, options: { maintainAspectRatio: false } });

	var rows = document.querySelectorAll('#goldTypeBody .gold-type-row');
	if (!rows.length) {
		// Không có hàng nào -> hiển thị chart rỗng với thông điệp
		chart.destroy();
		chart = new Chart(ctx, {
			type: 'line',
			data: { labels: [], datasets: [] },
			options: { maintainAspectRatio: false, plugins: { title: { display: true, text: 'Chưa có dữ liệu giá vàng' } } }
		});
		return;
	}
	var defaultMaVang = rows[0].getAttribute('data-ma-vang');
	var apiUrl = '{{ route('api.gia-vang-history') }}';

	function renderLine(labels, mua, ban, title) {
		chart.destroy();
		chart = new Chart(ctx, {
			type: 'line',
			data: {
				labels: labels,
				datasets: [
					{
						label: 'Mua vào',
						data: mua,
						borderColor: 'rgb(220, 53, 69)',
						backgroundColor: 'rgba(220,53,69,0.15)',
						tension: 0.3,
						pointRadius: 3,
						fill: true
					},
					{
						label: 'Bán ra',
						data: ban,
						borderColor: 'rgb(25, 135, 84)',
						backgroundColor: 'rgba(25,135,84,0.15)',
						tension: 0.3,
						pointRadius: 3,
						fill: true
					}
				]
			},
			options: {
				maintainAspectRatio: false,
				plugins: {
					legend: { position: 'top' },
					title: { display: true, text: title }
				},
				scales: {
					y: { beginAtZero: false, grid: { color: 'rgba(0,0,0,0.05)' } },
					x: { grid: { display: false } }
				}
			}
		});
	}

	function loadAndRender(maVang) {
		fetch(apiUrl + '?ma_vang=' + encodeURIComponent(maVang) + '&days=30', { headers: { 'Accept': 'application/json' } })
			.then(function(r){ return r.json(); })
			.then(function(d){
				if (d && d.success && Array.isArray(d.labels) && d.labels.length) {
					renderLine(d.labels, d.gia_mua, d.gia_ban, 'Biểu đồ: ' + d.ma_vang);
				} else {
					chart.destroy();
					chart = new Chart(ctx, { type: 'line', data: { labels: [], datasets: [] }, options: { maintainAspectRatio: false, plugins: { title: { display: true, text: 'Không có dữ liệu lịch sử' } } } });
				}
			})
			.catch(function(){
				chart.destroy();
				chart = new Chart(ctx, { type: 'line', data: { labels: [], datasets: [] }, options: { maintainAspectRatio: false, plugins: { title: { display: true, text: 'Lỗi tải dữ liệu' } } } });
			});
	}

	rows.forEach(function(row){
		row.addEventListener('click', function(){
			rows.forEach(function(r){ r.classList.remove('table-active'); });
			row.classList.add('table-active');
			loadAndRender(row.getAttribute('data-ma-vang'));
		});
	});

	// Mặc định chọn hàng đầu tiên
	rows[0].classList.add('table-active');
	loadAndRender(defaultMaVang);
})();
</script>
@endif


<script>
(function() {
	// Đồng bộ chiều cao tối đa của biểu đồ với card bảng giá (cột trái)
	function syncChartMaxHeight() {
		var tableCard = document.querySelector('#gia-vang .col-12.col-lg-7 .card');
		var chartCardBody = document.querySelector('#gia-vang .chart-body');
		if (!tableCard || !chartCardBody) return;
		var maxH = tableCard.getBoundingClientRect().height;
		chartCardBody.style.maxHeight = maxH + 'px';
	}
	window.addEventListener('load', syncChartMaxHeight);
	window.addEventListener('resize', function(){
		// debounce nhẹ
		clearTimeout(window.__syncChartTimer);
		window.__syncChartTimer = setTimeout(syncChartMaxHeight, 150);
	});
})();
</script>
