@extends('user.layouts.app')

@section('title', 'Test Interface')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Test Interface</h4>
                </div>
                <div class="card-body">
                    <form id="testForm">
                        @csrf
                        <div class="mb-3">
                            <label for="jsonInput" class="form-label">Nhập dữ liệu JSON:</label>
                            <div class="input-group">
                                <textarea 
                                    class="form-control" 
                                    id="jsonInput" 
                                    name="json_data" 
                                    rows="10" 
                                    placeholder='Ví dụ: {"name": "John", "age": 30, "email": "john@example.com"}'
                                    required
                                ></textarea>
                                <button type="button" class="btn btn-outline-secondary" id="formatJsonBtn" title="Format JSON">
                                    <i class="bi bi-code-square"></i> Format
                                </button>
                            </div>
                            <div class="form-text">Nhập dữ liệu JSON hợp lệ để test. Nhấn "Format" để làm đẹp JSON</div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-plus-circle"></i> Thêm dữ liệu
                            </button>
                        </div>
                    </form>
                    
                    <!-- Kết quả hiển thị -->
                    <div id="result" class="mt-4" style="display: none;">
                        <div class="alert alert-success">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="mb-0">Kết quả:</h5>
                                <div>
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="copyResultBtn" title="Copy JSON">
                                        <i class="bi bi-clipboard"></i> Copy
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="formatResultBtn" title="Format JSON">
                                        <i class="bi bi-code-square"></i> Format
                                    </button>
                                </div>
                            </div>
                            <pre id="resultContent" class="json-display"></pre>
                        </div>
                    </div>
                    
                    <!-- Thông báo lỗi -->
                    <div id="error" class="mt-4" style="display: none;">
                        <div class="alert alert-danger">
                            <h5>Lỗi:</h5>
                            <div id="errorContent"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.json-display {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    padding: 1rem;
    font-family: 'Courier New', monospace;
    font-size: 0.9rem;
    line-height: 1.4;
    max-height: 400px;
    overflow-y: auto;
    white-space: pre-wrap;
    word-wrap: break-word;
}

.json-key {
    color: #0066cc;
    font-weight: bold;
}

.json-string {
    color: #008000;
}

.json-number {
    color: #ff6600;
}

.json-boolean {
    color: #cc0066;
    font-weight: bold;
}

.json-null {
    color: #999999;
    font-style: italic;
}

.input-group .btn {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}

.toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1055;
}
</style>

<script>
// Format JSON function
function formatJSON(jsonString, indent = 2) {
    try {
        const obj = JSON.parse(jsonString);
        return JSON.stringify(obj, null, indent);
    } catch (error) {
        throw new Error('JSON không hợp lệ: ' + error.message);
    }
}

// Syntax highlighting for JSON
function highlightJSON(jsonString) {
    return jsonString
        .replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
            let cls = 'json-number';
            if (/^"/.test(match)) {
                if (/:$/.test(match)) {
                    cls = 'json-key';
                } else {
                    cls = 'json-string';
                }
            } else if (/true|false/.test(match)) {
                cls = 'json-boolean';
            } else if (/null/.test(match)) {
                cls = 'json-null';
            }
            return '<span class="' + cls + '">' + match + '</span>';
        });
}

// Copy to clipboard function
async function copyToClipboard(text) {
    try {
        await navigator.clipboard.writeText(text);
        showToast('Đã copy vào clipboard!', 'success');
    } catch (err) {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        showToast('Đã copy vào clipboard!', 'success');
    }
}

// Show toast notification
function showToast(message, type = 'info') {
    const toastContainer = document.querySelector('.toast-container') || createToastContainer();
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : 'info'} border-0`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    toastContainer.appendChild(toast);
    
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    // Remove toast element after it's hidden
    toast.addEventListener('hidden.bs.toast', () => {
        toast.remove();
    });
}

// Create toast container if it doesn't exist
function createToastContainer() {
    const container = document.createElement('div');
    container.className = 'toast-container';
    document.body.appendChild(container);
    return container;
}

// Format JSON input button
document.getElementById('formatJsonBtn').addEventListener('click', function() {
    const jsonInput = document.getElementById('jsonInput');
    try {
        const formatted = formatJSON(jsonInput.value);
        jsonInput.value = formatted;
        showToast('JSON đã được format!', 'success');
    } catch (error) {
        showToast(error.message, 'danger');
    }
});

// Format result button
document.getElementById('formatResultBtn').addEventListener('click', function() {
    const resultContent = document.getElementById('resultContent');
    try {
        const currentText = resultContent.textContent;
        const formatted = formatJSON(currentText);
        resultContent.innerHTML = highlightJSON(formatted);
        showToast('JSON đã được format!', 'success');
    } catch (error) {
        showToast(error.message, 'danger');
    }
});

// Copy result button
document.getElementById('copyResultBtn').addEventListener('click', function() {
    const resultContent = document.getElementById('resultContent');
    const textToCopy = resultContent.textContent || resultContent.innerText;
    copyToClipboard(textToCopy);
});

// Main form submission
document.getElementById('testForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const jsonInput = document.getElementById('jsonInput').value;
    const resultDiv = document.getElementById('result');
    const errorDiv = document.getElementById('error');
    const resultContent = document.getElementById('resultContent');
    const errorContent = document.getElementById('errorContent');
    
    // Ẩn các thông báo trước đó
    resultDiv.style.display = 'none';
    errorDiv.style.display = 'none';
    
    // Validate JSON
    try {
        const jsonData = JSON.parse(jsonInput);
        
        // Gửi dữ liệu đến server
        fetch('{{ route("test.add-data") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                data: jsonData
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const formattedJSON = JSON.stringify(data, null, 2);
                resultContent.innerHTML = highlightJSON(formattedJSON);
                resultDiv.style.display = 'block';
            } else {
                errorContent.textContent = data.message || 'Có lỗi xảy ra';
                errorDiv.style.display = 'block';
            }
        })
        .catch(error => {
            errorContent.textContent = 'Lỗi kết nối: ' + error.message;
            errorDiv.style.display = 'block';
        });
        
    } catch (error) {
        errorContent.textContent = 'JSON không hợp lệ: ' + error.message;
        errorDiv.style.display = 'block';
    }
});
</script>
@endsection
