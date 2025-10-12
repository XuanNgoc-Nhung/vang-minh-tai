<!-- Notification Modal Component -->
<div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" id="notificationModalHeader">
                <h5 class="modal-title" id="notificationModalLabel">
                    <i class="mdi mdi-information" id="notificationModalIcon"></i>
                    <span id="notificationModalTitle">Thông báo</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="notificationModalBody">
                    <!-- Nội dung thông báo sẽ được điền vào đây -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary d-none" id="notificationModalConfirmBtn">Xác nhận</button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Notification Modal Styling */
    #notificationModal .modal-header {
        border-bottom: none;
        padding-bottom: 0;
    }
    
    #notificationModal .modal-header.success {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }
    
    #notificationModal .modal-header.error {
        background: linear-gradient(135deg, #dc3545, #fd7e14);
        color: white;
    }
    
    #notificationModal .modal-header.warning {
        background: linear-gradient(135deg, #ffc107, #fd7e14);
        color: #212529;
    }
    
    #notificationModal .modal-header.info {
        background: linear-gradient(135deg, #17a2b8, #6f42c1);
        color: white;
    }
    
    #notificationModal .modal-content {
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        border-radius: 15px;
    }
    
    #notificationModal .modal-body {
        padding: 2rem;
        text-align: center;
    }
    
    #notificationModal .modal-footer {
        border-top: none;
        padding: 1rem 2rem 2rem;
        justify-content: center;
    }
    
    #notificationModalIcon {
        font-size: 1.5rem;
        margin-right: 0.5rem;
    }
    
    #notificationModalBody {
        font-size: 1.1rem;
        line-height: 1.6;
    }
    
    .notification-animation {
        animation: notificationSlideIn 0.3s ease-out;
    }
    
    @keyframes notificationSlideIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Z-index cao nhất để hiển thị trên tất cả modal khác */
    #notificationModal {
        z-index: 10000 !important;
    }
    
    #notificationModal .modal-dialog {
        z-index: 10001 !important;
    }
    
    #notificationModal .modal-content {
        z-index: 10002 !important;
    }
</style>
